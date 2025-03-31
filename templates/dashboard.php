<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../templates/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Arca do Aventureiro</title>
    <link rel="stylesheet" href="../static/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Arca do Aventureiro</h2>
            <p>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario']) ?></p>
            
            <nav>
                <ul>
                    <li><a href="#" class="active" data-target="personagens"><i class="fas fa-users"></i> Personagens</a></li>
                    <li><a href="#" data-target="mundos"><i class="fas fa-globe"></i> Mundos</a></li>
                    <li><a href="#" data-target="itens"><i class="fas fa-gem"></i> Itens</a></li>
                    <li><a href="#" data-target="historias"><i class="fas fa-book"></i> Histórias</a></li>
                    <li><a href="../conection/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                </ul>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Seção de Personagens -->
            <section id="personagens" class="content-section active">
                <div class="card">
                    <h3><i class="fas fa-users"></i> Personagens</h3>
                    <button class="btn btn-primary" onclick="showForm('personagem')">
                        <i class="fas fa-plus"></i> Novo Personagem
                    </button>
                    
                    <!-- Formulário de Personagem (inicialmente oculto) -->
                    <form id="form-personagem" class="hidden" action="../conection/add_personagem.php" method="POST">
                        <div class="form-group">
                            <label>Nome:</label>
                            <input type="text" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label>Raça:</label>
                            <input type="text" name="raca">
                        </div>
                        <div class="form-group">
                            <label>Classe:</label>
                            <input type="text" name="classe">
                        </div>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </form>
                    
                    <!-- Lista de Personagens -->
                    <div class="card-grid" id="lista-personagens">
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM personagens WHERE usuario = ?");
                        $stmt->bind_param("s", $_SESSION['usuario']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        while($row = $result->fetch_assoc()):
                        ?>
                        <div class="card">
                            <h4><?= htmlspecialchars($row['nome']) ?></h4>
                            <p>Raça: <?= htmlspecialchars($row['raca']) ?></p>
                            <p>Classe: <?= htmlspecialchars($row['classe']) ?></p>
                            <div class="actions">
                                <a href="editar_personagem.php?id=<?= $row['id'] ?>" class="btn btn-secondary">Editar</a>
                                <a href="../conection/delete_personagem.php?id=<?= $row['id'] ?>" class="btn btn-danger">Excluir</a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
            
            <!-- Seção de Mundos (estrutura similar) -->
            <section id="mundos" class="content-section">
                <!-- Conteúdo similar ao de personagens -->
            </section>
            
            <!-- Seção de Itens (estrutura similar) -->
            <section id="itens" class="content-section">
                <!-- Conteúdo similar -->
            </section>
            
            <!-- Seção de Histórias (estrutura similar) -->
            <section id="historias" class="content-section">
                <!-- Conteúdo similar -->
            </section>
        </div>
    </div>

    <script>
        // Alternar entre seções
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active de todos
                document.querySelectorAll('.sidebar a').forEach(el => el.classList.remove('active'));
                document.querySelectorAll('.content-section').forEach(el => el.classList.remove('active'));
                
                // Adiciona active no selecionado
                this.classList.add('active');
                document.getElementById(this.dataset.target).classList.add('active');
            });
        });
        
        // Mostrar/ocultar formulários
        function showForm(tipo) {
            document.getElementById(`form-${tipo}`).classList.toggle('hidden');
        }
    </script>
</body>
</html>