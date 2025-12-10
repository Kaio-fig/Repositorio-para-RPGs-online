<?php
require_once 'db_connect.php';
require_once 'item_functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $personagem_id = $_POST['personagem_id'] ?? 0;
    $item_id = $_POST['item_id'] ?? 0;
    $tipo_item = $_POST['tipo_item'] ?? '';
    $modificacoes = json_decode($_POST['modificacoes'] ?? '[]', true);
    $categoria_final = $_POST['categoria_final'] ?? 0;
    
    if ($personagem_id > 0 && $item_id > 0 && !empty($tipo_item)) {
        $sucesso = adicionarItemPersonagem($personagem_id, $item_id, $tipo_item, $modificacoes);
        
        if ($sucesso) {
            echo json_encode(['success' => true, 'message' => 'Item adicionado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao adicionar item']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?>