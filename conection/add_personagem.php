<?php
session_start();
include __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $raca = $_POST['raca'];
    $classe = $_POST['classe'];
    $usuario = $_SESSION['usuario'];
    
    $stmt = $conn->prepare("INSERT INTO personagens (usuario, nome, raca, classe) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $usuario, $nome, $raca, $classe);
    
    if ($stmt->execute()) {
        header("Location: ../dashboard.php");
    } else {
        echo "Erro ao cadastrar personagem";
    }
    exit();
}
?>