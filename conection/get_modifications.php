<?php
require_once 'db_connect.php';
require_once 'item_functions.php';

header('Content-Type: application/json');

if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
    
    // Mapear tipo para o formato do banco
    $tiposMap = [
        'weapon' => 'arma',
        'armor' => 'protecao',
        'paranormal' => 'paranormal',
        'general' => 'geral'
    ];
    
    $tipoBanco = $tiposMap[$tipo] ?? $tipo;
    $modificacoes = getModificacoes($tipoBanco);
    
    echo json_encode($modificacoes);
} else {
    echo json_encode([]);
}
?>