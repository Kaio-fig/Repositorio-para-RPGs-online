<?php
// meus_personagens.php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once '../conection/db_connect.php'; 

$user_id = $_SESSION['user_id'];
$exclusao_sucesso = false;

// --- LÓGICA DE EXCLUSÃO (Agora suporta T20 e OP) ---
if (isset($_GET['excluir']) && isset($_GET['sistema'])) {
    $personagem_id = intval($_GET['excluir']);
    $sistema = $_GET['sistema'];

    $tabela = "";
    $stmt_check = null;
    $stmt_delete = null;

    if ($sistema == 'op') {
        $stmt_check = $conn->prepare("SELECT user_id FROM personagens_op WHERE id = ?");
        $stmt_check->bind_param("i", $personagem_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $personagem = $result_check->fetch_assoc();

        if ($personagem && $personagem['user_id'] == $user_id) {
            $stmt_delete = $conn->prepare("DELETE FROM personagens_op WHERE id = ?");
            $stmt_delete->bind_param("i", $personagem_id);
            $stmt_delete->execute();
            $exclusao_sucesso = true;
        }
        if ($stmt_check) $stmt_check->close();
        if ($stmt_delete) $stmt_delete->close();

    } else if ($sistema == 't20') {
        $stmt_check = $conn->prepare("SELECT user_id FROM personagens_t20 WHERE id = ?");
        $stmt_check->bind_param("i", $personagem_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $personagem = $result_check->fetch_assoc();

        if ($personagem && $personagem['user_id'] == $user_id) {
            $stmt_delete = $conn->prepare("DELETE FROM personagens_t20 WHERE id = ?");
            $stmt_delete->bind_param("i", $personagem_id);
            $stmt_delete->execute();
            $exclusao_sucesso = true;
        }
        if ($stmt_check) $stmt_check->close();
        if ($stmt_delete) $stmt_delete->close();
    }

    if ($exclusao_sucesso) {
        header('Location: meus_personagens.php?excluido=1');
        exit;
    }
}

// --- BUSCA E UNIFICAÇÃO DOS PERSONAGENS ---
$todos_personagens = array();

// 1. Buscar Personagens de Ordem Paranormal
$stmt_op = $conn->prepare("SELECT id, nome, imagem, nex FROM personagens_op WHERE user_id = ?");
$stmt_op->bind_param("i", $user_id);
$stmt_op->execute();
$result_op = $stmt_op->get_result();
$personagens_op = $result_op->fetch_all(MYSQLI_ASSOC);
$stmt_op->close();

foreach ($personagens_op as $p) {
    $todos_personagens[] = array(
        'id' => $p['id'],
        'nome' => $p['nome'],
        'imagem' => $p['imagem'], // Imagem local
        'sistema' => 'Ordem Paranormal',
        'nivel_label' => 'NEX',
        'nivel_valor' => $p['nex'] . '%',
        'link_editar' => '../templates/ficha_op.php?personagem_id=' . $p['id'],
        'link_excluir' => 'meus_personagens.php?excluir=' . $p['id'] . '&sistema=op'
    );
}

// 2. Buscar Personagens de Tormenta 20
// *** CORREÇÃO AQUI: Trocado 'imagem_url' por 'imagem' ***
$stmt_t20 = $conn->prepare("SELECT id, nome, imagem, nivel FROM personagens_t20 WHERE user_id = ?");
$stmt_t20->bind_param("i", $user_id);
$stmt_t20->execute();
$result_t20 = $stmt_t20->get_result();
$personagens_t20 = $result_t20->fetch_all(MYSQLI_ASSOC);
$stmt_t20->close();

foreach ($personagens_t20 as $p) {
    $todos_personagens[] = array(
        'id' => $p['id'],
        'nome' => $p['nome'],
        'imagem' => $p['imagem'], // <-- Corrigido (era imagem_url)
        'sistema' => 'Tormenta 20',
        'nivel_label' => 'Nível',
        'nivel_valor' => $p['nivel'],
        'link_editar' => '../templates/ficha_t20.php?id=' . $p['id'],
        'link_excluir' => 'meus_personagens.php?excluir=' . $p['id'] . '&sistema=t20'
    );
}

// 3. Ordenar a lista unificada por nome (case-insensitive)
usort($todos_personagens, function($a, $b) {
    return strcasecmp($a['nome'], $b['nome']);
});

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Personagens - Arca do Aventureiro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos CSS (sem alterações, igual à versão anterior) */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        :root {
            --primary-color: #6a1b9a;
            --secondary-color: #9c27b0;
            --dark-color: #2c2c2c;
            --light-color: #f5f5f5;
            --success-color: #4caf50;
            --danger-color: #f44336;
            
            /* Cores dos Sistemas */
            --op-color: #6a1b9a; /* Roxo (OP) */
            --t20-color: #8a0303; /* Vermelho (T20) */
        }

        body {
            background-color: #f9f9f9;
            color: var(--dark-color);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            padding: 20px 0;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 2.5rem;
            color: var(--dark-color); 
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #7b1fa2;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--dark-color);
            border: 2px solid #ccc;
        }

        .btn-secondary:hover {
            background-color: var(--light-color);
            border-color: #aaa;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
            transform: translateY(-2px);
        }

        .characters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .character-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            border-left: 5px solid var(--primary-color); /* Padrão (OP) */
        }
        
        .character-card.sistema-t20 {
            border-left-color: var(--t20-color);
        }
        .character-card.sistema-op {
            border-left-color: var(--op-color);
        }


        .character-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .character-image {
            height: 200px;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .character-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .character-image i {
            font-size: 4rem;
            color: #9e9e9e;
        }
        
        .character-image i.fa-dragon {
             color: var(--t20-color);
             opacity: 0.7;
        }
        .character-image i.fa-user-secret {
             color: var(--op-color);
             opacity: 0.7;
        }


        .character-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .character-info h3 {
            color: var(--dark-color); 
            margin-bottom: 10px;
            font-size: 1.4rem;
        }
        
        .character-card.sistema-t20 h3 {
             color: var(--t20-color);
        }
        .character-card.sistema-op h3 {
             color: var(--op-color);
        }


        .character-details {
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .character-details p {
            margin-bottom: 5px;
            color: #555;
        }

        .character-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        
        .character-card.sistema-t20 .btn-primary {
             background-color: var(--t20-color);
        }
        .character-card.sistema-t20 .btn-primary:hover {
             background-color: #6d0202;
        }
        .character-card.sistema-op .btn-primary {
             background-color: var(--op-color);
        }
        .character-card.sistema-op .btn-primary:hover {
             background-color: #5a1281;
        }


        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border-color: #d6e9c6;
        }

        /* --- ESTILOS PARA O MODAL --- */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            width: 90%;
            max-width: 600px;
            text-align: center;
        }

        .modal-header {
            margin-bottom: 20px;
        }

        .modal-header h2 {
            color: var(--dark-color);
        }

        .systems-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .system-card {
            background: var(--light-color);
            padding: 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .system-card:hover {
            transform: translateY(-3px);
        }

        .system-card.op-card:hover {
             border-color: var(--op-color);
        }
        .system-card.t20-card:hover {
             border-color: var(--t20-color);
        }

        .system-card h3 {
            margin-bottom: 10px;
        }
        
        .system-card.op-card h3 {
             color: var(--op-color);
        }
        .system-card.t20-card h3 {
             color: var(--t20-color);
        }

        .system-card p {
            color: #555;
            font-size: 0.9em;
        }

        .close-modal {
            background: var(--danger-color);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .characters-grid {
                grid-template-columns: 1fr;
            }

            .character-actions {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-scroll"></i> Meus Personagens</h1>
            <p>Gerencie todos os seus aventureiros</p>
        </header>

        <?php if (isset($_GET['excluido']) && $_GET['excluido'] == 1): ?>
            <div class="alert alert-success">Personagem excluído com sucesso!</div>
        <?php endif; ?>
        <?php if (isset($_GET['criado']) && $_GET['criado'] == 1): ?>
            <div class="alert alert-success">Personagem criado com sucesso!</div>
        <?php endif; ?>
        <?php if (isset($_GET['status']) && !empty($_GET['status'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['status']); ?></div>
        <?php endif; ?>


        <div style="text-align: center; margin-bottom: 30px;">
            <button class="btn btn-primary" onclick="abrirModalSistemas()">
                <i class="fas fa-plus"></i> Criar Novo Personagem
            </button>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
            </a>
        </div>

        <?php if (count($todos_personagens) > 0): ?>
            <div class="characters-grid">
                <?php foreach ($todos_personagens as $personagem): ?>
                    
                    <?php
                    // Define a classe CSS e o ícone com base no sistema
                    $card_class = '';
                    $icon_class = 'fas fa-question';
                    if ($personagem['sistema'] == 'Ordem Paranormal') {
                        $card_class = 'sistema-op';
                        $icon_class = 'fas fa-user-secret';
                    } else if ($personagem['sistema'] == 'Tormenta 20') {
                        $card_class = 'sistema-t20';
                        $icon_class = 'fas fa-dragon';
                    }
                    
                    // Lógica da Imagem (Trata URL externa e upload local)
                    $img_src = '';
                    if (!empty($personagem['imagem'])) {
                        if (filter_var($personagem['imagem'], FILTER_VALIDATE_URL)) {
                            // 1. É uma URL (T20)
                            $img_src = $personagem['imagem'];
                        } else if ($personagem['imagem'] != 'default.jpg' && file_exists("../uploads/" . $personagem['imagem'])) {
                            // 2. É um arquivo local (OP)
                            $img_src = "../uploads/" . htmlspecialchars($personagem['imagem']);
                        }
                    }
                    ?>

                    <div class="character-card <?php echo $card_class; ?>">
                        <div class="character-image">
                            <?php if (!empty($img_src)): ?>
                                <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($personagem['nome']); ?>">
                            <?php else: ?>
                                <i class="<?php echo $icon_class; ?>"></i>
                            <?php endif; ?>
                        </div>
                        <div class="character-info">
                            <h3><?php echo htmlspecialchars($personagem['nome']); ?></h3>
                            <div class="character-details">
                                <p><strong>Sistema:</strong> <?php echo $personagem['sistema']; ?></p>
                                <p><strong><?php echo $personagem['nivel_label']; ?>:</strong> <?php echo $personagem['nivel_valor']; ?></p>
                            </div>
                            <div class="character-actions">
                                <a href="<?php echo $personagem['link_editar']; ?>" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Ver / Editar
                                </a>
                                <a href="<?php echo $personagem['link_excluir']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este personagem?')">
                                    <i class="fas fa-trash"></i> Excluir
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <i class="fas fa-folder-open" style="font-size: 4rem; color: #9e9e9e; margin-bottom: 20px;"></i>
                <h3>Nenhum personagem encontrado</h3>
                <p>Você ainda não criou nenhum aventureiro. Clique no botão acima para começar sua jornada!</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal" id="sistema-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Escolha o Sistema de RPG</h2>
                <p>Selecione o universo para seu novo personagem</p>
            </div>

            <div class="systems-grid">
                <div class="system-card op-card" onclick="criarPersonagem('Ordem Paranormal')">
                    <h3><i class="fas fa-book-dead"></i> Ordem Paranormal</h3>
                    <p>Enfrente o oculto em um mundo de investigação e horror.</p>
                </div>

                <div class="system-card t20-card" onclick="criarPersonagem('Tormenta 20')">
                    <h3><i class="fas fa-dragon"></i> Tormenta 20</h3>
                    <p>Viva grandes aventuras em um cenário de fantasia épica.</p>
                </div>
            </div>

            <button class="btn close-modal" onclick="fecharModalSistemas()">Cancelar</button>
        </div>
    </div>

    <script>
        const modal = document.getElementById('sistema-modal');

        function abrirModalSistemas() {
            modal.style.display = 'flex';
        }

        function fecharModalSistemas() {
            modal.style.display = 'none';
        }

        function criarPersonagem(sistema) {
            // Usa id=0 para sinalizar uma "nova ficha"
            // (Seu script de salvar já trata id=0 como INSERT)
            if (sistema === 'Ordem Paranormal') {
                window.location.href = `../templates/ficha_op.php?personagem_id=0`;
            } else if (sistema === 'Tormenta 20') {
                // O seu script t20 parece usar 'id' no GET
                window.location.href = `../templates/ficha_t20.php?id=0`;
            }
        }

        // Fecha o modal se o usuário clicar fora do conteúdo
        window.onclick = function(event) {
            if (event.target === modal) {
                fecharModalSistemas();
            }
        }
    </script>
</body>

</html>

