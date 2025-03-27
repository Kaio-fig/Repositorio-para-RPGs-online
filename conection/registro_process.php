<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario = $_POST["nome_usuario"];
    $email = $_POST["email"];
    $senha = md5($_POST['senha']);


    $sql = "INSERT INTO usuarios (nome_usuario, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome_usuario, $email, $senha);

    if ($stmt->execute()) {
        echo "Registro realizado com sucesso!";
        header("Location: ../templates/dashboard.php");
    } else {
        echo "Erro ao registrar: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
