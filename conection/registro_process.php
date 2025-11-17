<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario = $_POST["nome_usuario"];
    $email = $_POST["email"];
    $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);


    $sql = "INSERT INTO usuarios (nome_usuario, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome_usuario, $email, $senha_hash);

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
