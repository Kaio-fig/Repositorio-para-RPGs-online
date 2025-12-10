<?php
// Iniciar sessão e verificar login
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Conexão com o banco de dados
require_once '../conection/db_connect.php';

// Processar exclusão de mundo
if (isset($_GET['excluir'])) {
    $mundo_id = intval($_GET['excluir']);
    $user_id = $_SESSION['user_id'];

    // Verifica se o mundo pertence ao usuário antes de deletar E pega o nome da imagem
    $stmt_check = $conn->prepare("SELECT id, imagem_mundo FROM mundos WHERE id = ? AND user_id = ?");
    $stmt_check->bind_param("ii", $mundo_id, $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $mundo = $result_check->fetch_assoc();
        
        // Deleta o registro do banco
        $stmt_delete = $conn->prepare("DELETE FROM mundos WHERE id = ?");
        $stmt_delete->bind_param("i", $mundo_id);
        $stmt_delete->execute();
        
        // Deleta a imagem associada (se não for a padrão)
        if ($mundo['imagem_mundo'] != 'default_mundo.jpg' && file_exists("../uploads/" . $mundo['imagem_mundo'])) {
            unlink("../uploads/" . $mundo['imagem_mundo']);
        }
        
        header('Location: meus_mundos.php?excluido=1');
        exit;
    }
}

// Buscar todos os mundos do usuário logado
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM mundos WHERE user_id = ? ORDER BY nome ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$mundos = $result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Mundos - Arca do Aventureiro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilo consistente com as outras páginas "Meus..." */
        :root {
            --primary-color: #6a1b9a;
            --secondary-color: #9c27b0;
            --dark-color: #2c2c2c;
            --light-color: #f5f5f5;
            --danger-color: #f44336;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f9f9f9; color: var(--dark-color); padding: 20px; }
        .container { width: 90%; max-width: 1200px; margin: 0 auto; }
        header { text-align: center; padding: 20px 0; margin-bottom: 30px; }
        h1 { font-size: 2.5rem; color: var(--primary-color); }
        .btn {
            display: inline-block; padding: 10px 20px; border-radius: 5px;
            font-weight: 600; transition: all 0.3s ease;
            margin: 5px; text-decoration: none; color: white;
            border: none; cursor: pointer;
        }
        .btn i { margin-right: 8px; }
        .btn-primary { background-color: var(--primary-color); }
        .btn-primary:hover { background-color: #7b1fa2; transform: translateY(-2px); }
        .btn-secondary { background-color: #6c757d; }
        .btn-secondary:hover { background-color: #5a6268; transform: translateY(-2px); }
        .btn-danger { background-color: var(--danger-color); color: white; }
        
        .mundos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        .mundo-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .mundo-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12); }
        
        .mundo-imagem {
            height: 180px;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 4rem;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            overflow: hidden; /* Adicionado */
        }
        .mundo-imagem img { width: 100%; height: 100%; object-fit: cover; }

        .mundo-info {
            padding: 20px;
            flex-grow: 1;
        }
        .mundo-info h3 { color: var(--primary-color); margin-bottom: 5px; font-size: 1.5rem; }
        .mundo-info .sistema {
            font-size: 0.9rem;
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 15px;
        }
        .mundo-info .descricao { 
            font-size: 1rem; 
            color: #333; 
            line-height: 1.6;
            /* Limita a sinopse para não quebrar o card */
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 4; /* Mostra no máximo 4 linhas */
            -webkit-box-orient: vertical;
        }

        .mundo-footer {
            padding: 15px 20px;
            background-color: #f9f9f9;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-globe-americas"></i> Meus Mundos</h1>
            <p>Gerencie seus universos e cenários de campanha</p>
        </header>

        <?php if (isset($_GET['excluido']) || isset($_GET['salvo'])): ?>
            <div class="alert alert-success">
                <?php 
                    if (isset($_GET['excluido'])) echo "Mundo excluído com sucesso!";
                    if (isset($_GET['salvo'])) echo "Mundo salvo com sucesso!";
                ?>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-bottom: 30px;">
            <!-- *** CORREÇÃO AQUI *** -->
            <!-- Link corrigido de 'novo_mundo.php' para 'ficha_mundo.php?id=0' -->
            <a href="novo_mundo.php?id=0" class="btn btn-primary"><i class="fas fa-plus"></i> Criar Novo Mundo</a>
            <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar ao Dashboard</a>
        </div>

        <?php if (count($mundos) > 0): ?>
            <div class="mundos-grid">
                <?php foreach ($mundos as $mundo): ?>
                    <div class="mundo-card">
                        <div class="mundo-imagem">
                            <?php if ($mundo['imagem_mundo'] && $mundo['imagem_mundo'] != 'default_mundo.jpg' && file_exists("../uploads/" . $mundo['imagem_mundo'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($mundo['imagem_mundo']); ?>" alt="Imagem do Mundo">
                            <?php else: ?>
                                <i class="fas fa-globe-americas"></i> <!-- Ícone atualizado -->
                            <?php endif; ?>
                        </div>
                        <div class="mundo-info">
                            <h3><?php echo htmlspecialchars($mundo['nome']); ?></h3>
                            <p class="sistema"><?php echo htmlspecialchars($mundo['sistema_jogo']); ?></p>
                            
                            <!-- *** CORREÇÃO AQUI *** -->
                            <!-- Decodifica o JSON da 'descricao' para exibir um resumo -->
                            <div class="descricao">
                                <?php
                                    $descricao_texto = "Este mundo ainda não tem seções.";
                                    if (!empty($mundo['descricao'])) {
                                        $blocos = json_decode($mundo['descricao'], true);
                                        if (is_array($blocos) && count($blocos) > 0) {
                                            $descricao_texto = "<strong>Contém " . count($blocos) . " seções.</strong><br>";
                                            if (!empty($blocos[0]['texto'])) {
                                                $descricao_texto .= htmlspecialchars(strip_tags($blocos[0]['texto']));
                                            } else {
                                                $descricao_texto .= "<i>(Primeira seção sem texto)</i>";
                                            }
                                        } else if ($mundo['descricao'][0] != '[') {
                                            // Fallback para texto antigo que não é JSON
                                            $descricao_texto = htmlspecialchars($mundo['descricao']);
                                        }
                                    }
                                    echo $descricao_texto;
                                ?>
                            </div>

                        </div>
                        <div class="mundo-footer">
                            <a href="novo_mundo.php?id=<?php echo $mundo['id']; ?>" class="btn btn-secondary" style="color: #2c2c2c;">Editar</a>
                            <a href="meus_mundos.php?excluir=<?php echo $mundo['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este mundo?')">Excluir</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; background: white; border-radius: 10px;">
                <h3>Nenhum mundo encontrado</h3>
                <p>Você ainda não criou nenhum mundo. Clique no botão "Criar Novo Mundo" para começar sua primeira saga!</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
