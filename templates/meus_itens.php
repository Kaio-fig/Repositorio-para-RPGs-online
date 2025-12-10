<?php
// meus_itens.php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /login.php");
    exit();
}

require_once '../conection/db_connect.php'; 

$user_id = $_SESSION['user_id'];
$exclusao_sucesso = false;

// --- LÓGICA DE EXCLUSÃO ---
// (Assume que suas tabelas de itens têm uma coluna 'user_id' para segurança)
if (isset($_GET['excluir']) && isset($_GET['sistema'])) {
    $item_id = intval($_GET['excluir']);
    $sistema = $_GET['sistema'];
    $tabela = "";

    if ($sistema == 'op') {
        $tabela = 'itens_op';
    } else if ($sistema == 't20') {
        $tabela = 't20_itens';
    }

    if (!empty($tabela)) {
        // Verifica se o item pertence ao usuário antes de deletar
        $stmt_check = $conn->prepare("SELECT user_id FROM $tabela WHERE id = ?");
        $stmt_check->bind_param("i", $item_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $item = $result_check->fetch_assoc();

        if ($item && $item['user_id'] == $user_id) {
            $stmt_delete = $conn->prepare("DELETE FROM $tabela WHERE id = ?");
            $stmt_delete->bind_param("i", $item_id);
            $stmt_delete->execute();
            $exclusao_sucesso = true;
            $stmt_delete->close();
        }
        $stmt_check->close();
    }

    if ($exclusao_sucesso) {
        header('Location: meus_itens.php?excluido=1');
        exit;
    }
}

// --- BUSCA E UNIFICAÇÃO DOS ITENS ---
$todos_itens_homebrew = array();

// 1. Buscar Itens de Ordem Paranormal
// (Assumindo que itens_op tem 'user_id', 'nome', 'descricao', 'categoria')
$stmt_op = $conn->prepare("SELECT id, nome, descricao, categoria FROM itens_op WHERE user_id = ?");
$stmt_op->bind_param("i", $user_id);
$stmt_op->execute();
$result_op = $stmt_op->get_result();
$itens_op = $result_op->fetch_all(MYSQLI_ASSOC);
$stmt_op->close();

foreach ($itens_op as $item) {
    $todos_itens_homebrew[] = array(
        'id' => $item['id'],
        'nome' => $item['nome'],
        'descricao' => $item['descricao'],
        'tipo' => $item['categoria'], // ex: 'Utensílio'
        'sistema' => 'Ordem Paranormal',
        'link_editar' => 'editar_item_op.php?id=' . $item['id'],
        'link_excluir' => 'meus_itens.php?excluir=' . $item['id'] . '&sistema=op'
    );
}

// 2. Buscar Itens de Tormenta 20
// (Assumindo que t20_itens tem 'user_id', 'nome', 'descricao', 'tipo')
$stmt_t20 = $conn->prepare("SELECT id, nome, descricao, tipo FROM t20_itens WHERE user_id = ?");
$stmt_t20->bind_param("i", $user_id);
$stmt_t20->execute();
$result_t20 = $stmt_t20->get_result();
$itens_t20 = $result_t20->fetch_all(MYSQLI_ASSOC);
$stmt_t20->close();

foreach ($itens_t20 as $item) {
    $todos_itens_homebrew[] = array(
        'id' => $item['id'],
        'nome' => $item['nome'],
        'descricao' => $item['descricao'],
        'tipo' => $item['tipo'], // ex: 'Arma', 'Armadura'
        'sistema' => 'Tormenta 20',
        'link_editar' => 'editar_item_t20.php?id=' . $item['id'],
        'link_excluir' => 'meus_itens.php?excluir=' . $item['id'] . '&sistema=t20'
    );
}

// 3. Ordenar a lista unificada por nome
usort($todos_itens_homebrew, function($a, $b) {
    return strcasecmp($a['nome'], $b['nome']);
});

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Itens Homebrew - Arca do Aventureiro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS copiado e adaptado de meus_personagens.php */
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
            --op-color: #6a1b9a;
            --t20-color: #8a0303;
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
        
        /* Grid de Itens */
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .item-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            border-left: 5px solid var(--primary-color);
        }
        .item-card.sistema-t20 { border-left-color: var(--t20-color); }
        .item-card.sistema-op { border-left-color: var(--op-color); }

        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .item-icon {
            text-align: center;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .item-icon i {
            font-size: 3rem;
        }
        .item-card.sistema-t20 .item-icon i { color: var(--t20-color); }
        .item-card.sistema-op .item-icon i { color: var(--op-color); }


        .item-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .item-info h3 {
            margin-bottom: 10px;
            font-size: 1.4rem;
        }
        .item-card.sistema-t20 .item-info h3 { color: var(--t20-color); }
        .item-card.sistema-op .item-info h3 { color: var(--op-color); }

        .item-details {
            margin-bottom: 15px;
            flex-grow: 1;
        }
        .item-details p {
            margin-bottom: 5px;
            color: #555;
            font-size: 0.9em;
        }
        .item-details strong {
            color: #333;
        }

        .item-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        
        .item-card.sistema-t20 .btn-primary { background-color: var(--t20-color); }
        .item-card.sistema-t20 .btn-primary:hover { background-color: #6d0202; }
        .item-card.sistema-op .btn-primary { background-color: var(--op-color); }
        .item-card.sistema-op .btn-primary:hover { background-color: #5a1281; }

        /* Alertas e Modais (Idênticos a meus_personagens.php) */
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
        .modal {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.7); z-index: 1000; align-items: center; justify-content: center;
        }
        .modal-content {
            background: white; border-radius: 10px; padding: 30px;
            width: 90%; max-width: 600px; text-align: center;
        }
        .modal-header { margin-bottom: 20px; }
        .modal-header h2 { color: var(--dark-color); }
        .systems-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px; margin: 30px 0;
        }
        .system-card {
            background: var(--light-color); padding: 20px; border-radius: 8px;
            cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent;
        }
        .system-card:hover { transform: translateY(-3px); }
        .system-card.op-card:hover { border-color: var(--op-color); }
        .system-card.t20-card:hover { border-color: var(--t20-color); }
        .system-card h3 { margin-bottom: 10px; }
        .system-card.op-card h3 { color: var(--op-color); }
        .system-card.t20-card h3 { color: var(--t20-color); }
        .system-card p { color: #555; font-size: 0.9em; }
        .close-modal {
            background: var(--danger-color); color: white; padding: 8px 15px;
            border-radius: 5px; cursor: pointer; margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-flask"></i> Meus Itens Homebrew</h1>
            <p>Gerencie seus itens personalizados</p>
        </header>

        <?php if (isset($_GET['excluido']) && $_GET['excluido'] == 1): ?>
            <div class="alert alert-success">Item excluído com sucesso!</div>
        <?php endif; ?>
        <?php if (isset($_GET['salvo']) && $_GET['salvo'] == 1): ?>
            <div class="alert alert-success">Item salvo com sucesso!</div>
        <?php endif; ?>

        <div style="text-align: center; margin-bottom: 30px;">
            <button class="btn btn-primary" onclick="abrirModalSistemas()">
                <i class="fas fa-plus"></i> Criar Novo Item
            </button>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
            </a>
        </div>

        <?php if (count($todos_itens_homebrew) > 0): ?>
            <div class="items-grid">
                <?php foreach ($todos_itens_homebrew as $item): ?>
                    
                    <?php
                    $card_class = ($item['sistema'] == 'Tormenta 20') ? 'sistema-t20' : 'sistema-op';
                    $icon_class = ($item['sistema'] == 'Tormenta 20') ? 'fa-scroll' : 'fa-clipboard-list'; // Ícones diferentes
                    ?>

                    <div class="item-card <?php echo $card_class; ?>">
                        <div class="item-icon">
                            <i class="fas <?php echo $icon_class; ?>"></i>
                        </div>
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                            <div class="item-details">
                                <p><strong>Sistema:</strong> <?php echo $item['sistema']; ?></p>
                                <p><strong>Tipo:</strong> <?php echo htmlspecialchars($item['tipo']); ?></p>
                                <p><?php echo htmlspecialchars($item['descricao']); ?></p>
                            </div>
                            <div class="item-actions">
                                <a href="<?php echo $item['link_editar']; ?>" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="<?php echo $item['link_excluir']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este item permanentemente?')">
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
                <h3>Nenhum item homebrew encontrado</h3>
                <p>Você ainda não criou nenhum item. Clique no botão acima para começar!</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal de Seleção de Sistema (copiado de meus_personagens.php) -->
    <div class="modal" id="sistema-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Escolha o Sistema de RPG</h2>
                <p>Para qual sistema é este novo item?</p>
            </div>

            <div class="systems-grid">
                <div class="system-card op-card" onclick="criarItem('Ordem Paranormal')">
                    <h3><i class="fas fa-book-dead"></i> Ordem Paranormal</h3>
                    <p>Itens, Vestimentas, Utensílios...</p>
                </div>

                <div class="system-card t20-card" onclick="criarItem('Tormenta 20')">
                    <h3><i class="fas fa-dragon"></i> Tormenta 20</h3>
                    <p>Armas, Armaduras, Itens Gerais...</p>
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

        function criarItem(sistema) {
            // Redireciona para a página de edição com id=0 (sinal de "novo")
            if (sistema === 'Ordem Paranormal') {
                window.location.href = `editar_item_op.php?id=0`;
            } else if (sistema === 'Tormenta 20') {
                window.location.href = `editar_item_t20.php?id=0`;
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
