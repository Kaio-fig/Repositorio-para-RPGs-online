<?php
// login_process.php
include 'db_connect.php';

// Iniciar a sessão no início do script
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha_digitada = $_POST["senha"]; // A senha pura que o usuário digitou

    $sql = "SELECT id, nome_usuario, senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    // Verificamos se o usuário existe
    if ($stmt->num_rows > 0) {
        // O usuário existe, agora buscamos o hash da senha armazenada no banco
        $stmt->bind_result($id, $nome_usuario, $senha_hash_armazenada);
        $stmt->fetch();

        // Usamos password_verify() para comparar a senha digitada com o hash
        if (password_verify($senha_digitada, $senha_hash_armazenada)) {
            
            // A senha está correta!
            // Configurar todas as variáveis de sessão necessárias
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $nome_usuario;
            $_SESSION['user_email'] = $email;
            $_SESSION['logged_in'] = true;
            
            // Redirecionar para o dashboard
            header("Location: ../templates/dashboard.php");
            exit();

        } else {
            // Senha incorreta.
            // Por segurança, usamos um erro genérico.
            header("Location: ../templates/login.php?erro=invalido");
            exit();
        }
    } else {
        // Usuário não encontrado.
        // Usamos o mesmo erro genérico para não vazar informação.
        header("Location: ../templates/login.php?erro=invalido");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // Se não for POST, redirecionar para login
    header("Location: ../templates/login.php");
    exit();
}
?>