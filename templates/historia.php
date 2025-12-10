<?php
// ficha_historia.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conection/db_connect.php';
$user_id = $_SESSION['user_id'];
$historia_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$page_title = ($historia_id > 0) ? "Editar História" : "Criar Nova História";

$blocos_iniciais = array(); // Para o JavaScript

// --- LÓGICA DE SALVAR (INSERT/UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $historia_id = intval($_POST['id']);
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $sistema_jogo = $conn->real_escape_string($_POST['sistema_jogo']);
    $imagem_antiga = $conn->real_escape_string($_POST['imagem_antiga']);
    $imagem_final = $imagem_antiga;

    // --- LÓGICA DOS BLOCOS DE CONTEÚDO ---
    $blocos_json = '[]'; // Padrão
    if (isset($_POST['bloco_titulo']) && isset($_POST['bloco_texto'])) {
        $titulos = $_POST['bloco_titulo'];
        $textos = $_POST['bloco_texto'];
        $blocos_array = array();
        
        // (PHP 5.4 não suporta array_map com null, então usamos um loop)
        for ($i = 0; $i < count($titulos); $i++) {
            if (isset($titulos[$i]) && isset($textos[$i])) {
                 $blocos_array[] = array(
                    'titulo' => $titulos[$i],
                    'texto' => $textos[$i]
                );
            }
        }
        // json_encode existe no PHP 5.4
        $blocos_json = json_encode($blocos_array);
    }
    // A variável $blocos_json agora contém a 'sinopse'
    // -------------------------------------

    // --- LÓGICA DE UPLOAD DE IMAGEM ---
    if (isset($_FILES['imagem_historia']) && $_FILES['imagem_historia']['error'] == 0) {
        $target_dir = "../uploads/"; 
        $extensao = strtolower(pathinfo($_FILES["imagem_historia"]["name"], PATHINFO_EXTENSION));
        $novo_nome = "historia_" . $user_id . "_" . uniqid() . "." . $extensao;
        $target_file = $target_dir . $novo_nome;
        
        if (getimagesize($_FILES["imagem_historia"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["imagem_historia"]["tmp_name"], $target_file)) {
                $imagem_final = $novo_nome;
                if ($imagem_antiga != 'default_historia.jpg' && file_exists($target_dir . $imagem_antiga)) {
                    unlink($target_dir . $imagem_antiga);
                }
            }
        }
    }

    if ($historia_id > 0) {
        // UPDATE (Substitui 'sinopse' por $blocos_json)
        $stmt = $conn->prepare("UPDATE historias SET titulo = ?, sistema_jogo = ?, sinopse = ?, imagem_historia = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssssii", $titulo, $sistema_jogo, $blocos_json, $imagem_final, $historia_id, $user_id);
    } else {
        // INSERT (Substitui 'sinopse' por $blocos_json)
        $stmt = $conn->prepare("INSERT INTO historias (user_id, titulo, sistema_jogo, sinopse, imagem_historia) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $titulo, $sistema_jogo, $blocos_json, $imagem_final);
    }
    
    $stmt->execute();
    $stmt->close();
    
    header("Location: minhas_historias.php?salvo=1");
    exit();
}

// -- LÓGICA DE CARREGAR (READ) --
$historia = array(
    'id' => 0,
    'titulo' => '',
    'sistema_jogo' => 'Ordem Paranormal',
    'sinopse' => '[]', // Padrão agora é um JSON de array vazio
    'imagem_historia' => 'default_historia.jpg'
);

if ($historia_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM historias WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $historia_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $historia = $result->fetch_assoc();
    } else {
        header("Location: minhas_historias.php?erro=historia_invalida");
        exit();
    }
    $stmt->close();
}

// Prepara os blocos para o JavaScript
// json_decode existe no PHP 5.4
$blocos_iniciais = json_decode($historia['sinopse'], true);
if (is_null($blocos_iniciais) || !is_array($blocos_iniciais)) {
    $blocos_iniciais = array();
}


$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Arca do Aventureiro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        :root {
            --primary-color: #6a1b9a;
            --dark-color: #2c2c2c;
            --light-color: #f5f5f5;
            --danger-color: #f44336;
            --borda: #dee2e6;
        }
        body { background-color: #f9f9f9; color: var(--dark-color); line-height: 1.6; padding: 20px; }
        .container { width: 90%; max-width: 800px; margin: 0 auto; }
        
        header { text-align: center; padding: 20px 0; margin-bottom: 30px; }
        header h1 { font-size: 2.5rem; color: var(--primary-color); margin-bottom: 10px; }
        
        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
        }
        
        .form-group input[type="text"],
        .form-group input[type="file"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid var(--borda);
            font-size: 1rem;
        }
        
        .imagem-preview {
            margin-top: 10px; max-width: 200px; height: 120px;
            border: 1px dashed var(--borda); border-radius: 5px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden; background-color: #f1f1f1;
        }
        .imagem-preview img { width: 100%; height: 100%; object-fit: cover; }
        .imagem-preview i { font-size: 2rem; color: #aaa; }

        .btn-group {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 20px; border-top: 1px solid var(--borda); padding-top: 20px;
        }
        .btn {
            display: inline-block; padding: 10px 20px; border-radius: 5px;
            font-weight: 600; transition: all 0.3s ease; text-decoration: none;
            border: none; cursor: pointer; font-size: 1rem;
        }
        .btn-primary { background-color: var(--primary-color); color: white; }
        .btn-primary:hover { background-color: #5a1281; }
        .btn-secondary { background-color: transparent; color: var(--dark-color); border: 2px solid #ccc; }
        .btn-secondary:hover { background-color: var(--light-color); border-color: #aaa; }
        
        /* --- NOVOS ESTILOS PARA BLOCOS DINÂMICOS --- */
        #blocos-container {
            border-top: 1px solid var(--borda);
            padding-top: 20px;
        }
        .bloco-item {
            background: #fdfdfd;
            border: 1px solid var(--borda);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
        }
        .bloco-item label {
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: bold;
        }
        .bloco-item input[type="text"] {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .bloco-item textarea {
            min-height: 150px;
            resize: vertical;
        }
        .btn-remover-bloco {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--danger-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            line-height: 28px;
            text-align: center;
        }
        .btn-remover-bloco:hover {
            background: #c02a1d;
        }
        .btn-add-bloco {
            background-color: #28a745;
            color: white;
            width: 100%;
            padding: 12px;
            font-size: 1rem;
        }
        .btn-add-bloco:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-feather-alt"></i> <?php echo $page_title; ?></h1>
        </header>

        <div class="form-container">
            <form action="historia.php" method="POST" enctype="multipart/form-data">
                
                <input type="hidden" name="id" value="<?php echo $historia['id']; ?>">
                <input type="hidden" name="imagem_antiga" value="<?php echo htmlspecialchars($historia['imagem_historia']); ?>">
                
                <!-- Campos Principais -->
                <div class="form-group">
                    <label for="titulo">Título da História</label>
                    <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($historia['titulo']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="sistema_jogo">Sistema de Jogo</label>
                    <select id="sistema_jogo" name="sistema_jogo" required>
                        <option value="Ordem Paranormal" <?php echo ($historia['sistema_jogo'] == 'Ordem Paranormal') ? 'selected' : ''; ?>>Ordem Paranormal</option>
                        <option value="Tormenta 20" <?php echo ($historia['sistema_jogo'] == 'Tormenta 20') ? 'selected' : ''; ?>>Tormenta 20</option>
                        <option value="Outro" <?php echo ($historia['sistema_jogo'] == 'Outro') ? 'selected' : ''; ?>>Outro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="imagem_historia">Imagem da História (Opcional)</label>
                    <input type="file" id="imagem_historia" name="imagem_historia" accept="image/png, image/jpeg, image/gif">
                    <div class="imagem-preview" id="preview-container">
                        <?php if ($historia['imagem_historia'] && $historia['imagem_historia'] != 'default_historia.jpg' && file_exists("../uploads/" . $historia['imagem_historia'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($historia['imagem_historia']); ?>" alt="Preview da Imagem">
                        <?php else: ?>
                            <i class="fas fa-image"></i>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Container para Blocos Dinâmicos -->
                <div id="blocos-container">
                    <!-- Os blocos de conteúdo serão inseridos aqui pelo JavaScript -->
                </div>

                <!-- Botão de Adicionar Bloco -->
                <div class="form-group">
                    <button type="button" class="btn btn-add-bloco" id="btn-add-bloco">
                        <i class="fas fa-plus"></i> Adicionar Seção de Texto
                    </button>
                </div>

                <!-- Botões de Salvar/Voltar -->
                <div class="btn-group">
                    <a href="minhas_historias.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar História</button>
                </div>
                
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('blocos-container');
            const btnAdd = document.getElementById('btn-add-bloco');
            
            // Pega os dados iniciais do PHP
            // Cuidado com a saída do json_encode em PHP 5.4, mas para arrays simples deve funcionar.
            const blocosIniciais = <?php echo json_encode($blocos_iniciais); ?>;

            // Função para adicionar um novo bloco (seja do load ou novo)
            function adicionarBloco(titulo, texto) {
                if (titulo === undefined) { titulo = ''; }
                if (texto === undefined) { texto = ''; }

                const novoBloco = document.createElement('div');
                novoBloco.className = 'bloco-item';
                
                novoBloco.innerHTML = 
                    '<button type="button" class="btn-remover-bloco" title="Remover Seção">X</button>' +
                    '<div class="form-group">' +
                        '<label>Título da Seção</label>' +
                        '<input type="text" name="bloco_titulo[]" placeholder="Capítulo 1, NPCs, etc..." value="' + htmlEntities(titulo) + '">' +
                    '</div>' +
                    '<div class="form-group">' +
                        '<label>Texto</label>' +
                        '<textarea name="bloco_texto[]" placeholder="Escreva sua história aqui...">' + htmlEntities(texto) + '</textarea>' +
                    '</div>';
                
                container.appendChild(novoBloco);
                
                // Adiciona o listener no botão de remover que acabamos de criar
                novoBloco.querySelector('.btn-remover-bloco').addEventListener('click', function() {
                    removerBloco(this);
                });
            }
            
            // Função para remover um bloco
            function removerBloco(botao) {
                // O botão está dentro do div .bloco-item, então pegamos o 'pai'
                botao.parentElement.remove();
            }
            
            // Função para escapar HTML (segurança básica para 'value' e 'textarea')
            function htmlEntities(str) {
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            // Listener para o botão "Adicionar Seção"
            btnAdd.addEventListener('click', function() {
                adicionarBloco('', ''); // Adiciona um bloco em branco
            });
            
            // Carrega os blocos iniciais salvos no banco
            if (blocosIniciais.length > 0) {
                for (var i = 0; i < blocosIniciais.length; i++) {
                    adicionarBloco(blocosIniciais[i].titulo, blocosIniciais[i].texto);
                }
            } else {
                // Se for uma história nova, começa com um bloco em branco
                if (<?php echo $historia_id; ?> == 0) {
                     adicionarBloco('Início', '');
                }
            }

            // Script de preview da imagem (mesmo de antes)
            document.getElementById('imagem_historia').addEventListener('change', function(event) {
                const previewContainer = document.getElementById('preview-container');
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.innerHTML = '<img src="' + e.target.result + '" alt="Preview da Imagem">';
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.innerHTML = '<i class="fas fa-image"></i>';
                }
            });
        });
    </script>
</body>
</html>

