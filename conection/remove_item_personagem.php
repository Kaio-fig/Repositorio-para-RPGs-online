<?php
require_once 'db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'] ?? 0;
    $tipo_item = $_POST['tipo_item'] ?? '';
    
    if ($item_id > 0 && !empty($tipo_item)) {
        $stmt = $conn->prepare("DELETE FROM Op_personagem_itens WHERE id = ?");
        $stmt->bind_param("i", $item_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Item removido com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao remover item']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?>