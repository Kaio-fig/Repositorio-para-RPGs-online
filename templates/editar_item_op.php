<?php
// editar_item_op.php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login/login.php"); // Ajuste o caminho se necessário
    exit();
}

require_once '../conection/db_connect.php'; // Ajuste o caminho se necessário
$user_id = $_SESSION['user_id'];
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$page_title = ($item_id > 0) ? "Editar Item" : "Criar Novo Item";

// -- LÓGICA DE SALVAR (INSERT/UPDATE) --
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = intval($_POST['id']);
    
    // Dados principais
    $nome = $conn->real_escape_string($_POST['nome']);
    $tipo_item_id = intval($_POST['tipo_item_id']);
    $categoria = intval($_POST['categoria']);
    $espacos = intval($_POST['espacos']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    
    // Dados condicionais (podem ser NULL)
    $dano = !empty($_POST['dano']) ? $conn->real_escape_string($_POST['dano']) : NULL;
    $critico = !empty($_POST['critico']) ? $conn->real_escape_string($_POST['critico']) : NULL;
    $alcance = !empty($_POST['alcance']) ? $conn->real_escape_string($_POST['alcance']) : NULL;
    $tipo_dano = !empty($_POST['tipo_dano']) ? $conn->real_escape_string($_POST['tipo_dano']) : NULL;
    $defesa_bonus = !empty($_POST['defesa_bonus']) ? intval($_POST['defesa_bonus']) : NULL;

    // Limpa os campos que não pertencem ao tipo de item selecionado
    if ($tipo_item_id != 1) { // Se não for Arma
        $dano = NULL;
        $critico = NULL;
        $alcance = NULL;
        $tipo_dano = NULL;
    }
    if ($tipo_item_id != 2) { // Se não for Proteção
        $defesa_bonus = NULL;
    }

    if ($item_id > 0) {
        // UPDATE (Atualiza um item existente)
        // A checagem de user_id aqui é vital para segurança
        $stmt = $conn->prepare("UPDATE itens_op SET 
            nome = ?, tipo_item_id = ?, categoria = ?, espacos = ?, descricao = ?, 
            dano = ?, critico = ?, alcance = ?, tipo_dano = ?, defesa_bonus = ? 
            WHERE id = ? AND user_id = ?");
        $stmt->bind_param("siiisssssiii", 
            $nome, $tipo_item_id, $categoria, $espacos, $descricao, 
            $dano, $critico, $alcance, $tipo_dano, $defesa_bonus, 
            $item_id, $user_id);
            
    } else {
        // INSERT (Cria um novo item)
        // O user_id é inserido para marcar este item como homebrew
        $stmt = $conn->prepare("INSERT INTO itens_op 
            (user_id, nome, tipo_item_id, categoria, espacos, descricao, dano, critico, alcance, tipo_dano, defesa_bonus) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isiiisssssi", 
            $user_id, $nome, $tipo_item_id, $categoria, $espacos, $descricao, 
            $dano, $critico, $alcance, $tipo_dano, $defesa_bonus);
    }
    
    $stmt->execute();
    $stmt->close();
    
    header("Location: meus_itens.php?salvo=1");
    exit();
}

// -- LÓGICA DE CARREGAR (READ) --
// Inicializa um item em branco para o modo "Criar"
$item = array(
    'id' => 0,
    'nome' => '',
    'tipo_item_id' => 3, // Padrão 'Item Geral'
    'categoria' => 0,
    'espacos' => 1,
    'descricao' => '',
    'dano' => '',
    'critico' => '',
    'alcance' => '',
    'tipo_dano' => '',
    'defesa_bonus' => 0
);

if ($item_id > 0) {
    // Se estiver editando, busca o item no banco
    // A checagem user_id garante que o usuário só possa editar seus próprios itens
    $stmt = $conn->prepare("SELECT * FROM itens_op WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $item_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
    } else {
        // Se o item não for encontrado ou não pertencer ao usuário, redireciona
        header("Location: meus_itens.php?erro=item_invalido");
        exit();
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Ordem Paranormal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos (inspirados em meus_personagens.php) */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        :root {
            --op-color: #6a1b9a;
            --dark-color: #2c2c2c;
            --light-color: #f5f5f5;
            --danger-color: #f44336;
            --borda: #dee2e6;
        }
        body { background-color: #f9f9f9; color: var(--dark-color); line-height: 1.6; padding: 20px; }
        .container { width: 90%; max-width: 800px; margin: 0 auto; }
        
        header { text-align: center; padding: 20px 0; margin-bottom: 30px; }
        header h1 { font-size: 2.5rem; color: var(--op-color); margin-bottom: 10px; }
        
        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
        }
        
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid var(--borda);
            font-size: 1rem;
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn-group {
            grid-column: 1 / -1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            border-top: 1px solid var(--borda);
            padding-top: 20px;
        }

        .btn {
            display: inline-block; padding: 10px 20px; border-radius: 5px;
            font-weight: 600; transition: all 0.3s ease; text-decoration: none;
            border: none; cursor: pointer; font-size: 1rem;
        }
        .btn-primary { background-color: var(--op-color); color: white; }
        .btn-primary:hover { background-color: #5a1281; }
        .btn-secondary { background-color: transparent; color: var(--dark-color); border: 2px solid #ccc; }
        .btn-secondary:hover { background-color: var(--light-color); border-color: #aaa; }
        
        /* Div para campos condicionais */
        .conditional-fields {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            border-top: 1px dashed var(--borda);
            border-bottom: 1px dashed var(--borda);
            padding: 20px 0;
            margin-top: 10px;
        }
        .conditional-fields h4 {
            grid-column: 1 / -1;
            margin-bottom: 0;
            color: var(--op-color);
        }

    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-clipboard-list"></i> <?php echo $page_title; ?></h1>
            <p>Sistema: Ordem Paranormal</p>
        </header>

        <div class="form-container">
            <form action="editar_item_op.php" method="POST">
                <!-- ID Oculto -->
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                
                <div class="form-grid">
                    <!-- Nome -->
                    <div class="form-group full-width">
                        <label for="nome">Nome do Item</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($item['nome']); ?>" required>
                    </div>

                    <!-- Tipo de Item -->
                    <div class="form-group">
                        <label for="tipo_item_id">Tipo de Item</label>
                        <select id="tipo_item_id" name="tipo_item_id" required>
                            <option value="3" <?php echo ($item['tipo_item_id'] == 3) ? 'selected' : ''; ?>>Item Geral</option>
                            <option value="1" <?php echo ($item['tipo_item_id'] == 1) ? 'selected' : ''; ?>>Arma</option>
                            <option value="2" <?php echo ($item['tipo_item_id'] == 2) ? 'selected' : ''; ?>>Proteção</option>
                            <option value="4" <?php echo ($item['tipo_item_id'] == 4) ? 'selected' : ''; ?>>Item Amaldiçoado</option>
                        </select>
                    </div>

                    <!-- Categoria -->
                    <div class="form-group">
                        <label for="categoria">Categoria (Ex: 0, 1, 2)</label>
                        <input type="number" id="categoria" name="categoria" value="<?php echo $item['categoria']; ?>" min="0" max="4" required>
                    </div>

                    <!-- Espaços -->
                    <div class="form-group">
                        <label for="espacos">Espaços</label>
                        <input type="number" id="espacos" name="espacos" value="<?php echo $item['espacos']; ?>" min="0" required>
                    </div>

                    <!-- (Espaço vazio no grid) -->
                    <div class="form-group"></div>


                    <!-- CAMPOS CONDICIONAIS DE ARMA -->
                    <div id="campos-arma" class="conditional-fields" style="display: none;">
                        <h4><i class="fas fa-gavel"></i> Atributos de Arma</h4>
                        
                        <div class="form-group">
                            <label for="dano">Dano (Ex: 1d12)</label>
                            <input type="text" id="dano" name="dano" value="<?php echo htmlspecialchars($item['dano']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="critico">Crítico (Ex: 19/x3)</label>
                            <input type="text" id="critico" name="critico" value="<?php echo htmlspecialchars($item['critico']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="alcance">Alcance (Ex: Curto)</label>
                            <input type="text" id="alcance" name="alcance" value="<?php echo htmlspecialchars($item['alcance']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="tipo_dano">Tipo de Dano (Ex: B, C, I, P)</label>
                            <input type="text" id="tipo_dano" name="tipo_dano" value="<?php echo htmlspecialchars($item['tipo_dano']); ?>">
                        </div>
                    </div>

                    <!-- CAMPOS CONDICIONAIS DE PROTEÇÃO -->
                    <div id="campos-protecao" class="conditional-fields" style="display: none;">
                        <h4><i class="fas fa-shield-alt"></i> Atributos de Proteção</h4>
                        
                        <div class="form-group">
                            <label for="defesa_bonus">Bônus na Defesa</label>
                            <input type="number" id="defesa_bonus" name="defesa_bonus" value="<?php echo $item['defesa_bonus']; ?>" min="0">
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="form-group full-width">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($item['descricao']); ?></textarea>
                    </div>
                    
                    <!-- Botões -->
                    <div class="btn-group">
                        <a href="meus_itens.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoSelect = document.getElementById('tipo_item_id');
            const camposArma = document.getElementById('campos-arma');
            const camposProtecao = document.getElementById('campos-protecao');

            function toggleCamposVisiveis() {
                const tipoSelecionado = tipoSelect.value;
                
                // (PHP 5.4 não suporta operador ternário complexo de forma confiável no JS? Melhor usar if/else)
                
                // Mostra/Esconde campos de Arma
                if (tipoSelecionado == 1) {
                    camposArma.style.display = 'grid';
                } else {
                    camposArma.style.display = 'none';
                }
                
                // Mostra/Esconde campos de Proteção
                if (tipoSelecionado == 2) {
                    camposProtecao.style.display = 'grid';
                } else {
                    camposProtecao.style.display = 'none';
                }
            }

            // Adiciona o listener
            tipoSelect.addEventListener('change', toggleCamposVisiveis);

            // Executa uma vez no carregamento da página para definir o estado inicial
            toggleCamposVisiveis();
        });
    </script>
</body>
</html>
