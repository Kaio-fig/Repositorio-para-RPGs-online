<?php
require_once 'db_connect.php';

header('Content-Type: application/json');

if (isset($_GET['personagem_id'])) {
    $personagem_id = intval($_GET['personagem_id']);
    
    // Buscar equipamentos do personagem
    $stmt = $conn->prepare("
        SELECT pi.*, 
               COALESCE(a.nome, p.nome, g.nome, par.nome) as nome,
               COALESCE(a.dano, '') as dano,
               COALESCE(a.crit, '') as crit,
               COALESCE(p.defesa, '') as defesa,
               COALESCE(g.bonus, '') as bonus,
               COALESCE(par.efeito, '') as efeito,
               COALESCE(a.espaco, p.espaco, g.espaco, par.espaco) as espaco
        FROM Op_personagem_itens pi
        LEFT JOIN Op_armas a ON pi.tipo_item = 'arma' AND pi.item_id = a.id
        LEFT JOIN Op_protecoes p ON pi.tipo_item = 'protecao' AND pi.item_id = p.id
        LEFT JOIN Op_gerais g ON pi.tipo_item = 'geral' AND pi.item_id = g.id
        LEFT JOIN Op_paranormal par ON pi.tipo_item = 'paranormal' AND pi.item_id = par.id
        WHERE pi.personagem_id = ?
    ");
    $stmt->bind_param("i", $personagem_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $equipment = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($equipment);
} else {
    echo json_encode([]);
}
?>