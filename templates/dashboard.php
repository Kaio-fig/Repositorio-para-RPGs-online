<?php
// dashboard.php
// Iniciar sessão e verificar login
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../templates/login.php');
    exit;
}

// Incluir conexão com banco
require_once '../conection/db_connect.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Arca do Aventureiro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../static/dashboard.css">
</head>
<body>
    <main>
        <header>
            <div class="container">
                <nav>
                    <a href="../index.html" class="logo">Arca do Aventureiro</a>
                    <ul class="nav-links">
                        <li><a href="../index.html">Início</a></li>
                        <li><a href="meus_personagens.php">Personagens</a></li>
                        <li><a href="meus_mundos.php">Mundos</a></li>
                        <li><a href="meus_itens.php">Itens</a></li>
                        <li><a href="minhas_historias.php">Histórias</a></li>
                        <li><a href="../conection/logout.php" class="btn btn-secondary">Sair</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <section class="dashboard-content">
            <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
            
            <div class="dashboard-grid">
                <a href="meus_personagens.php" class="dashboard-card">
                    <h3>Personagens</h3>
                    <p>Gerencie seus personagens</p>
                </a>
                
                <a href="meus_mundos.php" class="dashboard-card">
                    <h3>Mundos</h3>
                    <p>Desenvolva novos mundos</p>
                </a>
                
                <a href="meus_itens.php" class="dashboard-card">
                    <h3>Itens</h3>
                    <p>Explore itens e equipamentos</p>
                </a>

                <a href="minhas_historias.php" class="dashboard-card">
                    <h3>Historias</h3>
                    <p>Escreva todas suas ideias</p>
                </a>
            </div>
        </section>
    </main>
</body>
</html>