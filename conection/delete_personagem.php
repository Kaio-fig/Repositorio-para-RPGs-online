<?php
session_start();
include __DIR__ . '/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario = $_SESSION['usuario'];
    
    // Verifica se o personagem pertence ao usuário
    $stmt = $conn->prepare("DELETE FROM personagens WHERE id = ? AND usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
}

header("Location: ../dashboard.php");
exit();
?>