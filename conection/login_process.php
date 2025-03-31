<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = md5($_POST["senha"]); // Criptografar a senha para comparar

    $sql = "SELECT id, nome_usuario, senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nome_usuario, $senha_armazenada);
        $stmt->fetch();

        if ($senha === $senha_armazenada) { // Comparação direta com MD5
            session_start();
            $_SESSION["usuario"] = $nome_usuario;
            echo "Login bem-sucedido!";
            header("Location: ../templates/dashboard.php");
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