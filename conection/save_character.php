<?php
// Inicia a sessão para podermos verificar o login do usuário
session_start();

// 1. CHECAGENS DE SEGURANÇA E CONEXÃO
// ======================================================
if (!isset($_SESSION['user_id'])) {
    // Para testes, vamos definir um user_id padrão. Remova ou comente esta linha em produção.
    $_SESSION['user_id'] = 1;
    // die("Acesso negado. Você precisa estar logado para salvar um personagem.");
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método de requisição inválido.");
}
require_once 'db_connect.php';

// 2. COLETA E SANITIZAÇÃO DOS DADOS DO FORMULÁRIO
// ======================================================
$personagem_id = isset($_POST['personagem_id']) && !empty($_POST['personagem_id']) ? intval($_POST['personagem_id']) : null;
$user_id = $_SESSION['user_id'];

$nome = isset($_POST['nome']) ? strip_tags($_POST['nome']) : 'Sem Nome';
$nex = isset($_POST['nex']) ? intval($_POST['nex']) : 5;
$classe_id = isset($_POST['classe_id']) ? intval($_POST['classe_id']) : null;
$origem_id = isset($_POST['origem_id']) ? intval($_POST['origem_id']) : null;
$trilha_id = isset($_POST['trilha_id']) && intval($_POST['trilha_id']) > 0 ? intval($_POST['trilha_id']) : null;
$patente = isset($_POST['patente']) ? strip_tags($_POST['patente']) : 'Recruta';

$forca = isset($_POST['forca']) ? intval($_POST['forca']) : 1;
$agilidade = isset($_POST['agilidade']) ? intval($_POST['agilidade']) : 1;
$intelecto = isset($_POST['intelecto']) ? intval($_POST['intelecto']) : 1;
$vigor = isset($_POST['vigor']) ? intval($_POST['vigor']) : 1;
$presenca = isset($_POST['presenca']) ? intval($_POST['presenca']) : 1;

$inventario_ids = isset($_POST['inventario']) ? $_POST['inventario'] : [];

// 3. LÓGICA DE UPLOAD DA IMAGEM
// ======================================================
$nome_imagem_final = 'default.jpg';
if ($personagem_id) {
    $stmt_old_img = $conn->prepare("SELECT imagem FROM personagens_op WHERE id = ? AND user_id = ?");
    $stmt_old_img->bind_param("ii", $personagem_id, $user_id);
    $stmt_old_img->execute();
    $result = $stmt_old_img->get_result();
    if ($result->num_rows > 0) {
        $nome_imagem_final = $result->fetch_assoc()['imagem'];
    }
    $stmt_old_img->close();
}

if (isset($_FILES['imagem_personagem']) && $_FILES['imagem_personagem']['error'] === 0) {
    $upload_dir = '../uploads/';
    $arquivo_temporario = $_FILES['imagem_personagem']['tmp_name'];
    $extensao = strtolower(pathinfo($_FILES['imagem_personagem']['name'], PATHINFO_EXTENSION));
    $novo_nome_imagem = uniqid('char_', true) . '.' . $extensao;
    $caminho_final = $upload_dir . $novo_nome_imagem;

    if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

    if (move_uploaded_file($arquivo_temporario, $caminho_final)) {
        if ($nome_imagem_final && $nome_imagem_final !== 'default.jpg' && file_exists($upload_dir . $nome_imagem_final)) {
            unlink($upload_dir . $nome_imagem_final);
        }
        $nome_imagem_final = $novo_nome_imagem;
    }
}

// Desliga o autocommit para iniciar a transação
$conn->autocommit(FALSE);
$tudo_ok = true; 

// 4. OPERAÇÃO NO BANCO DE DADOS (INSERT ou UPDATE)
// ======================================================
if ($personagem_id) {
    // ATUALIZAR PERSONAGEM EXISTENTE
    $sql = "UPDATE personagens_op SET nome=?, nex=?, classe_id=?, origem_id=?, trilha_id=?, patente=?, forca=?, agilidade=?, intelecto=?, vigor=?, presenca=?, imagem=? WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    // CORREÇÃO FINAL DA STRING DE TIPOS (14 caracteres para 14 parâmetros)
    $stmt->bind_param("siiiisiiiiisii", $nome, $nex, $classe_id, $origem_id, $trilha_id, $patente, $forca, $agilidade, $intelecto, $vigor, $presenca, $nome_imagem_final, $personagem_id, $user_id);
} else {
    // CRIAR NOVO PERSONAGEM
    $sql = "INSERT INTO personagens_op (user_id, nome, nex, classe_id, origem_id, trilha_id, patente, forca, agilidade, intelecto, vigor, presenca, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isiiiisiiiiis", $user_id, $nome, $nex, $classe_id, $origem_id, $trilha_id, $patente, $forca, $agilidade, $intelecto, $vigor, $presenca, $nome_imagem_final);
}

if (!$stmt->execute()) {
    $tudo_ok = false;
    echo "Erro ao salvar dados do personagem: " . $stmt->error;
}

if (!$personagem_id) {
    $personagem_id = $conn->insert_id;
}
$stmt->close();

// 5. ATUALIZAR O INVENTÁRIO
// ======================================================
if ($tudo_ok) {
    $stmt_delete_inv = $conn->prepare("DELETE FROM inventario_op WHERE personagem_id = ?");
    $stmt_delete_inv->bind_param("i", $personagem_id);
    if (!$stmt_delete_inv->execute()) {
        $tudo_ok = false;
        echo "Erro ao limpar inventário antigo: " . $stmt_delete_inv->error;
    }
    $stmt_delete_inv->close();
}

if ($tudo_ok && !empty($inventario_ids)) {
    $sql_insert_item = "INSERT INTO inventario_op (personagem_id, item_id, quantidade) VALUES (?, ?, 1)";
    $stmt_insert_item = $conn->prepare($sql_insert_item);
    foreach ($inventario_ids as $item_id) {
        $item_id_int = intval($item_id);
        $stmt_insert_item->bind_param("ii", $personagem_id, $item_id_int);
        if (!$stmt_insert_item->execute()) {
            $tudo_ok = false;
            echo "Erro ao inserir novo item no inventário: " . $stmt_insert_item->error;
            break; 
        }
    }
    $stmt_insert_item->close();
}

// --- FINALIZAÇÃO DA TRANSAÇÃO ---
if ($tudo_ok) {
    $conn->commit();
    $conn->autocommit(TRUE);
    $conn->close();
    header("Location: ../templates/ficha_op.php?personagem_id=" . $personagem_id . "&status=salvo");
    exit();
} else {
    $conn->rollback();
    $conn->autocommit(TRUE);
    $conn->close();
    exit();
}
?>

