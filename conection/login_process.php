<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT id, nome_usuario, senha_hash FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nome_usuario, $senha_hash);
        $stmt->fetch();

        if (password_verify($senha, $senha_hash)) {
            session_start();
            $_SESSION["usuario"] = $nome_usuario;
            echo "Login bem-sucedido!";
            // Redirecionar para a página principal
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }

    $stmt->close();
    $conn->close();
}
?>
