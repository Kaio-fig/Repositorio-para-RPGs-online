<?php
session_start();
require_once 'db_connect.php';

// Garantir retorno sempre em JSON
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
    exit;
}

// Verificar login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$personagem_id = isset($_POST['personagem_id']) ? intval($_POST['personagem_id']) : 0;

// ----- Campos esperados (mantive os anteriores e adicionei os novos) ----- //
$nome      = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$nex       = isset($_POST['nex']) ? intval($_POST['nex']) : 0;
$vida      = isset($_POST['vida']) ? intval($_POST['vida']) : 0;
$pe        = isset($_POST['pe']) ? intval($_POST['pe']) : 0;
$san       = isset($_POST['san']) ? intval($_POST['san']) : 0;
$forca     = isset($_POST['forca']) ? intval($_POST['forca']) : 0;
$agilidade = isset($_POST['agilidade']) ? intval($_POST['agilidade']) : 0;
$intelecto = isset($_POST['intelecto']) ? intval($_POST['intelecto']) : 0;
$vigor     = isset($_POST['vigor']) ? intval($_POST['vigor']) : 0;
$presenca  = isset($_POST['presenca']) ? intval($_POST['presenca']) : 0;

// Novos campos solicitados
$sistema = isset($_POST['sistema']) ? trim($_POST['sistema']) : '';
$nivel   = isset($_POST['nivel']) ? intval($_POST['nivel']) : 1;
$origem  = isset($_POST['origem']) ? trim($_POST['origem']) : '';
$classe  = isset($_POST['classe']) ? trim($_POST['classe']) : '';
$defesa  = isset($_POST['defesa']) ? intval($_POST['defesa']) : 0;

// Perícias: aceita JSON string ou array via POST
$pericias = [];
if (isset($_POST['pericias'])) {
    if (is_array($_POST['pericias'])) {
        $pericias = $_POST['pericias'];
    } else {
        $decoded = json_decode($_POST['pericias'], true);
        if (is_array($decoded)) $pericias = $decoded;
    }
}

// Habilidades / Rituais / Equipamento: aceitar array ou JSON -> salvar como JSON string
function read_json_list($key) {
    if (!isset($_POST[$key])) return [];
    if (is_array($_POST[$key])) return $_POST[$key];
    $d = json_decode($_POST[$key], true);
    return is_array($d) ? $d : [];
}

$habilidades_arr = read_json_list('habilidades');
$rituais_arr     = read_json_list('rituais');
$equipamento_arr = read_json_list('equipamento');

$habilidades_json = json_encode($habilidades_arr, JSON_UNESCAPED_UNICODE);
$rituais_json     = json_encode($rituais_arr, JSON_UNESCAPED_UNICODE);
$equipamento_json = json_encode($equipamento_arr, JSON_UNESCAPED_UNICODE);

// ----------------- Upload de imagem (mantive sua lógica) ----------------- //
$imagem = 'default.jpg';
if ($personagem_id > 0) {
    $stmt = $conn->prepare("SELECT imagem FROM personagens WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $personagem_id, $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $imagem = $row['imagem'] ?? 'default.jpg';
    }
    $stmt->close();
}

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));

    // Extensões permitidas
    $permitidas = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($extensao, $permitidas)) {
        echo json_encode(['success' => false, 'message' => 'Formato de imagem inválido.']);
        exit;
    }

    // Garante que a pasta de uploads exista
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $novoNome = uniqid('img_') . '.' . $extensao;
    $destino = $uploadDir . $novoNome;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
        // Apagar imagem antiga se não for default
        if ($personagem_id > 0 && $imagem !== 'default.jpg') {
            $antiga = $uploadDir . $imagem;
            if (file_exists($antiga)) unlink($antiga);
        }
        $imagem = $novoNome;
    } else {
        echo json_encode(['success' => false, 'message' => 'Falha ao salvar a imagem.']);
        exit;
    }
}

// ----------------- Monta dados para INSERT/UPDATE dinâmico ----------------- //
$to_save = [
    'nome'       => $nome,
    'vida'       => $vida,
    'pe'         => $pe,
    'san'        => $san,
    'forca'      => $forca,
    'agilidade'  => $agilidade,
    'intelecto'  => $intelecto,
    'vigor'      => $vigor,
    'presenca'   => $presenca,
    'imagem'     => $imagem,
    // novos
    'sistema'    => $sistema,
    'nivel'      => $nivel,
    'origem'     => $origem,
    'classe'     => $classe,
    'defesa'     => $defesa,
    'habilidades'=> $habilidades_json,
    'rituais'    => $rituais_json,
    'equipamento'=> $equipamento_json
];

// Se for INSERT, precisamos incluir user_id no começo
if ($personagem_id > 0) {
    // UPDATE dinâmico
    $set_parts = [];
    foreach ($to_save as $col => $v) $set_parts[] = "$col = ?";
    $sql = "UPDATE personagens SET " . implode(", ", $set_parts) . " WHERE id = ? AND user_id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Erro na preparação da query: ' . $conn->error]);
        exit;
    }

    // Params
    $params = array_values($to_save);
    $params[] = $personagem_id;
    $params[] = $user_id;

} else {
    // INSERT dinâmico
    $to_insert = array_merge(['user_id' => $user_id], $to_save);
    $cols = implode(", ", array_keys($to_insert));
    $placeholders = implode(", ", array_fill(0, count($to_insert), '?'));
    $sql = "INSERT INTO personagens ($cols) VALUES ($placeholders)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Erro na preparação da query: ' . $conn->error]);
        exit;
    }

    $params = array_values($to_insert);
}

// Monta string de tipos dinamicamente ('i' para int, 's' para string)
$types = '';
foreach ($params as $p) {
    $types .= (is_int($p) ? 'i' : 's');
}

// bind_param exige referências
$bind_names = [];
$bind_names[] = $types;
for ($i = 0; $i < count($params); $i++) {
    // garantir que valores numéricos estejam como int
    if ($types[$i] === 'i') {
        $params[$i] = intval($params[$i]);
    } else {
        $params[$i] = (string)$params[$i];
    }
    $bind_names[] = &$params[$i];
}

// Executa bind e query
call_user_func_array([$stmt, 'bind_param'], $bind_names);
$ok = $stmt->execute();
if (!$ok) {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar personagem: ' . $stmt->error]);
    exit;
}

if ($personagem_id === 0) {
    $personagem_id = $conn->insert_id;
}
$stmt->close();

// ----------------- Salva perícias (tabela separada) ----------------- //
if (!empty($pericias) && is_array($pericias)) {
    foreach ($pericias as $nome_pericia => $valor) {
        $valor = intval($valor);
        $stmt = $conn->prepare("INSERT INTO pericias (personagem_id, nome, valor) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE valor = VALUES(valor)");
        if ($stmt) {
            $stmt->bind_param("isi", $personagem_id, $nome_pericia, $valor);
            $stmt->execute();
            $stmt->close();
        }
    }
}

echo json_encode([
    'success' => true,
    'message' => 'Personagem salvo com sucesso!',
    'personagem_id' => $personagem_id
]);
exit;
