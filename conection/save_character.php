<?php
session_start();
require_once 'db_connect.php';

// Garantir retorno sempre em JSON
header('Content-Type: application/json');

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

// Dados básicos
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

$pericias = [];
if (isset($_POST['pericias'])) {
    $decoded = json_decode($_POST['pericias'], true);
    if (is_array($decoded)) {
        $pericias = $decoded;
    }
}

// Upload de imagem
$imagem = 'default.jpg';
if ($personagem_id > 0) {
    // Buscar imagem atual do personagem
    $stmt = $conn->prepare("SELECT imagem FROM personagens WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $personagem_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $imagem = $row['imagem'];
    }
}

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));

    // Extensões permitidas
    $permitidas = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($extensao, $permitidas)) {
        echo json_encode(['success' => false, 'message' => 'Formato de imagem inválido.']);
        exit;
    }

    $novoNome = uniqid('img_') . '.' . $extensao;
    $destino = __DIR__ . '/../uploads/' . $novoNome;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
        // Apagar imagem antiga se não for default
        if ($personagem_id > 0 && $imagem !== 'default.jpg') {
            $antiga = __DIR__ . '/../uploads/' . $imagem;
            if (file_exists($antiga)) {
                unlink($antiga);
            }
        }
        $imagem = $novoNome;
    } else {
        echo json_encode(['success' => false, 'message' => 'Falha ao salvar a imagem.']);
        exit;
    }
}

// Insert ou Update
if ($personagem_id > 0) {
    // UPDATE
    $stmt = $conn->prepare("UPDATE personagens 
        SET nome=?, nex=?, vida=?, pe=?, san=?, forca=?, agilidade=?, intelecto=?, vigor=?, presenca=?, imagem=? 
        WHERE id=? AND user_id=?");
    $stmt->bind_param(
        "siiiiiiiisiii",
        $nome, $nex, $vida, $pe, $san, $forca, $agilidade, $intelecto, $vigor, $presenca, $imagem,
        $personagem_id, $user_id
    );
    $ok = $stmt->execute();
    if (!$ok) {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar personagem.']);
        exit;
    }

} else {
    // INSERT
    $stmt = $conn->prepare("INSERT INTO personagens 
        (user_id, nome, nex, vida, pe, san, forca, agilidade, intelecto, vigor, presenca, imagem) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "isiiiiiiiiss",
        $user_id, $nome, $nex, $vida, $pe, $san, $forca, $agilidade, $intelecto, $vigor, $presenca, $imagem
    );
    $ok = $stmt->execute();
    if (!$ok) {
        echo json_encode(['success' => false, 'message' => 'Erro ao criar personagem.']);
        exit;
    }
    $personagem_id = $stmt->insert_id;
}

// Salvar perícias (pode ser em tabela separada, ajusta conforme seu schema)
if (!empty($pericias)) {
    foreach ($pericias as $nome_pericia => $valor) {
        $valor = intval($valor);
        $stmt = $conn->prepare("INSERT INTO pericias (personagem_id, nome, valor) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE valor = VALUES(valor)");
        $stmt->bind_param("isi", $personagem_id, $nome_pericia, $valor);
        $stmt->execute();
    }
}

echo json_encode([
    'success' => true,
    'message' => 'Personagem salvo com sucesso!',
    'personagem_id' => $personagem_id
]);
