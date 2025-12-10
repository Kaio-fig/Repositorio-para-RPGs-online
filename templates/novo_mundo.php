<?php
// ficha_mundo.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conection/db_connect.php';
$user_id = $_SESSION['user_id'];
$mundo_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$page_title = ($mundo_id > 0) ? "Editar Mundo" : "Criar Novo Mundo";

// --- LÓGICA DE SALVAR (INSERT/UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mundo_id = intval($_POST['id']);
    $nome = $conn->real_escape_string($_POST['nome']);
    $sistema_jogo = $conn->real_escape_string($_POST['sistema_jogo']);
    $imagem_antiga = $conn->real_escape_string($_POST['imagem_antiga']);
    $imagem_final = $imagem_antiga;

    // *** NOVA LÓGICA DE SALVAMENTO ***
    $descricao_json = $_POST['descricao'];

    // (Lógica de Upload de Imagem - igual ao editor de história)
    if (isset($_FILES['imagem_mundo']) && $_FILES['imagem_mundo']['error'] == 0) {
        $target_dir = "../uploads/";
        $extensao = strtolower(pathinfo($_FILES["imagem_mundo"]["name"], PATHINFO_EXTENSION));
        $novo_nome = "mundo_" . $user_id . "_" . uniqid() . "." . $extensao;
        $target_file = $target_dir . $novo_nome;

        if (getimagesize($_FILES["imagem_mundo"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["imagem_mundo"]["tmp_name"], $target_file)) {
                $imagem_final = $novo_nome;
                if ($imagem_antiga != 'default_mundo.jpg' && file_exists($target_dir . $imagem_antiga)) {
                    unlink($target_dir . $imagem_antiga);
                }
            }
        }
    }

    if ($mundo_id > 0) {
        // UPDATE
        $stmt = $conn->prepare("UPDATE mundos SET nome = ?, sistema_jogo = ?, descricao = ?, imagem_mundo = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssssii", $nome, $sistema_jogo, $descricao_json, $imagem_final, $mundo_id, $user_id);
    } else {
        // INSERT
        $stmt = $conn->prepare("INSERT INTO mundos (user_id, nome, sistema_jogo, descricao, imagem_mundo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $nome, $sistema_jogo, $descricao_json, $imagem_final);
    }

    $stmt->execute();
    $stmt->close();

    header("Location: meus_mundos.php?salvo=1");
    exit();
}

// -- LÓGICA DE CARREGAR (READ) --
$mundo = array(
    'id' => 0,
    'nome' => 'Novo Mundo',
    'sistema_jogo' => 'Outro',
    'descricao' => '{"nodes": [], "edges": []}', // Padrão JSON do Mapa Mental
    'imagem_mundo' => 'default_mundo.jpg'
);

if ($mundo_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM mundos WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $mundo_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mundo = $result->fetch_assoc();
    } else {
        header("Location: meus_mundos.php?erro=mundo_invalido");
        exit();
    }
    $stmt->close();
}

// *** CARREGAR DADOS PARA IMPORTAÇÃO ***
// 1. Personagens (OP e T20)
$stmt_chars_op = $conn->prepare("SELECT id, nome FROM personagens_op WHERE user_id = ?");
$stmt_chars_op->bind_param("i", $user_id);
$stmt_chars_op->execute();
$result_chars_op = $stmt_chars_op->get_result();
$personagens_op = $result_chars_op->fetch_all(MYSQLI_ASSOC);
$stmt_chars_op->close();

$stmt_chars_t20 = $conn->prepare("SELECT id, nome FROM personagens_t20 WHERE user_id = ?");
$stmt_chars_t20->bind_param("i", $user_id);
$stmt_chars_t20->execute();
$result_chars_t20 = $stmt_chars_t20->get_result();
$personagens_t20 = $result_chars_t20->fetch_all(MYSQLI_ASSOC);
$stmt_chars_t20->close();

// 2. Itens Homebrew (OP e T20)
$stmt_items_op = $conn->prepare("SELECT id, nome FROM itens_op WHERE user_id = ?");
$stmt_items_op->bind_param("i", $user_id);
$stmt_items_op->execute();
$result_items_op = $stmt_items_op->get_result();
$itens_op = $result_items_op->fetch_all(MYSQLI_ASSOC);
$stmt_items_op->close();

$stmt_items_t20 = $conn->prepare("SELECT id, nome FROM t20_itens WHERE user_id = ?");
$stmt_items_t20->bind_param("i", $user_id);
$stmt_items_t20->execute();
$result_items_t20 = $stmt_items_t20->get_result();
$itens_t20 = $result_items_t20->fetch_all(MYSQLI_ASSOC);
$stmt_items_t20->close();

// 3. Histórias
$stmt_hist = $conn->prepare("SELECT id, titulo AS nome FROM historias WHERE user_id = ?");
$stmt_hist->bind_param("i", $user_id);
$stmt_hist->execute();
$result_hist = $stmt_hist->get_result();
$historias = $result_hist->fetch_all(MYSQLI_ASSOC);
$stmt_hist->close();


$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Arca do Aventureiro</title>
    <!-- CSS da Biblioteca Vis.js (para o mapa) -->
    <link href="https://unpkg.com/vis-network/styles/vis-network.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #6a1b9a;
            --t20-color: #8a0303;
            --dark-color: #2c2c2c;
            --light-color: #f5f5f5;
            --borda: #dee2e6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            /* Evita scroll na página inteira */
            background-color: #f0f2f5;
        }

        /* --- CORREÇÃO PRINCIPAL AQUI --- */
        #app-container {
            display: grid;
            grid-template-columns: 300px 1fr;
            /* Sidebar e Conteúdo */
            /* Header tem 60px, o resto tem (Altura da Tela - 60px) */
            grid-template-rows: 60px calc(100vh - 60px);
            height: 100vh;
        }

        /* --- HEADER --- */
        #app-header {
            grid-column: 1 / -1;
            grid-row: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            background: white;
            border-bottom: 1px solid var(--borda);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            z-index: 10;
        }

        #app-header h1 {
            font-size: 1.5rem;
            color: var(--primary-color);
            white-space: nowrap;
            /* Impede que o título quebre */
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #app-header .btn-group {
            display: flex;
            gap: 10px;
        }

        /* Estilo para os inputs no header (do seu screenshot) */
        #form-salvar {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        #form-salvar input[type="text"],
        #form-salvar select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid var(--borda);
            font-size: 0.9rem;
        }


        /* --- SIDEBAR --- */
        #app-sidebar {
            grid-column: 1;
            grid-row: 2;
            background: #ffffff;
            border-right: 1px solid var(--borda);
            padding: 20px;
            overflow-y: auto;
            /* <-- A barra de scroll */
            height: 100%;
            /* Garante que o scroll funcione dentro da altura calculada */
            z-index: 5;
        }

        #app-sidebar h3 {
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 5px;
            margin-bottom: 15px;
            color: var(--dark-color);
            font-size: 1.1rem;
        }

        .sidebar-section {
            margin-bottom: 25px;
        }

        .sidebar-section label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .sidebar-section select,
        .sidebar-section input[type="text"],
        .sidebar-section input[type="file"] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid var(--borda);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .btn-add-node {
            background-color: #28a745;
            color: white;
            width: 100%;
            padding: 10px;
        }

        .btn-add-node:hover {
            background-color: #218838;
        }

        /* --- MAPA --- */
        #map-container {
            grid-column: 2;
            grid-row: 2;
            position: relative;
            /* Pai para o vis-network */
            height: 100%;
            /* Garante que o mapa preencha a altura calculada */
            overflow: hidden;
            /* Impede o mapa de vazar */
        }

        #mindmap {
            width: 100%;
            height: 100%;
            background-color: #f0f2f5;
        }

        /* --- GERAL BTN --- */
        .btn {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn i {
            margin-right: 5px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #5a1281;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* --- MODAL DE EDIÇÃO --- */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 8px;
            padding: 25px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal-content h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .modal-content .form-group {
            margin-bottom: 15px;
        }

        .modal-content label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .modal-content input[type="text"],
        .modal-content textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid var(--borda);
            font-size: 1rem;
        }

        .modal-content textarea {
            min-height: 100px;
            resize: vertical;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>

    <div id="app-container">

        <!-- CABEÇALHO COM BOTÕES DE SALVAR -->
        <header id="app-header">
            <h1><i class="fas fa-globe-americas"></i> Editor de Mundo: <?php echo htmlspecialchars($mundo['nome']); ?></h1>

            <!-- Formulário de Salvamento -->
            <form id="form-salvar" action="ficha_mundo.php" method="POST" enctype="multipart/form-data" style="display: flex; gap: 10px;">
                <!-- Dados principais do mundo -->
                <input type="hidden" name="id" value="<?php echo $mundo['id']; ?>">
                <input type="hidden" name="imagem_antiga" value="<?php echo htmlspecialchars($mundo['imagem_mundo']); ?>">
                <!-- O JSON do mapa mental será inserido aqui pelo JS -->
                <input type="hidden" id="descricao-json" name="descricao">

                <!-- Campos de Título e Sistema (no header para edição rápida) -->
                <input type="text" name="nome" value="<?php echo htmlspecialchars($mundo['nome']); ?>" style="padding: 8px; border-radius: 5px; border: 1px solid var(--borda);">
                <select name="sistema_jogo" style="padding: 8px; border-radius: 5px; border: 1px solid var(--borda);">
                    <option value="Outro" <?php echo ($mundo['sistema_jogo'] == 'Outro') ? 'selected' : ''; ?>>Outro</option>
                    <option value="Ordem Paranormal" <?php echo ($mundo['sistema_jogo'] == 'Ordem Paranormal') ? 'selected' : ''; ?>>Ordem Paranormal</option>
                    <option value="Tormenta 20" <?php echo ($mundo['sistema_jogo'] == 'Tormenta 20') ? 'selected' : ''; ?>>Tormenta 20</option>
                </select>

                <div class="btn-group">
                    <a href="meus_mundos.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Mundo</button>
                </div>
            </form>
        </header>

        <!-- SIDEBAR DE FERRAMENTAS E IMPORTAÇÃO -->
        <aside id="app-sidebar">
            <div class="sidebar-section">
                <h3><i class="fas fa-tools"></i> Ferramentas</h3>
                <button type="button" class="btn btn-primary btn-add-node" id="btn-add-local"><i class="fas fa-map-marker-alt"></i> Adicionar Local</button>
                <br><br>
                <button type="button" class="btn btn-primary btn-add-node" id="btn-add-npc"><i class="fas fa-user-ninja"></i> Adicionar NPC</button>
                <br><br>
                <button type="button" class="btn" id="btn-toggle-edges" style="width: 100%; background-color: #ffc107;">
                    <i class="fas fa-project-diagram"></i> Modo Conexão
                </button>
            </div>

            <div class="sidebar-section">
                <label for="imagem_mundo">Imagem Principal do Mundo</label>
                <input type="file" id="imagem_mundo" name="imagem_mundo" form="form-salvar" accept="image/*">
            </div>

            <div class="sidebar-section">
                <h3><i class="fas fa-download"></i> Importar Entradas</h3>

                <label>Personagens (OP)</label>
                <select id="import-char-op">
                    <option value="">Selecione...</option>
                    <?php foreach ($personagens_op as $p): ?>
                        <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-secondary btn-add-node" id="btn-import-char-op" style="width: 100%;"><i class="fas fa-book-dead"></i> Importar</button>
            </div>

            <div class="sidebar-section">
                <label>Personagens (T20)</label>
                <select id="import-char-t20">
                    <option value="">Selecione...</option>
                    <?php foreach ($personagens_t20 as $p): ?>
                        <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-secondary btn-add-node" id="btn-import-char-t20" style="width: 100%; background-color: var(--t20-color);"><i class="fas fa-dragon"></i> Importar</button>
            </div>

            <div class="sidebar-section">
                <label>Itens Homebrew (OP)</label>
                <select id="import-item-op">
                    <option value="">Selecione...</option>
                    <?php foreach ($itens_op as $i): ?>
                        <option value="<?php echo $i['id']; ?>"><?php echo htmlspecialchars($i['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-secondary btn-add-node" id="btn-import-item-op" style="width: 100%;"><i class="fas fa-book-dead"></i> Importar</button>
            </div>

            <div class="sidebar-section">
                <label>Itens Homebrew (T20)</label>
                <select id="import-item-t20">
                    <option value="">Selecione...</option>
                    <?php foreach ($itens_t20 as $i): ?>
                        <option value="<?php echo $i['id']; ?>"><?php echo htmlspecialchars($i['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-secondary btn-add-node" id="btn-import-item-t20" style="width: 100%; background-color: var(--t20-color);"><i class="fas fa-dragon"></i> Importar</button>
            </div>

            <div class="sidebar-section">
                <label>Histórias</label>
                <select id="import-historia">
                    <option value="">Selecione...</option>
                    <?php foreach ($historias as $h): ?>
                        <option value="<?php echo $h['id']; ?>"><?php echo htmlspecialchars($h['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-secondary btn-add-node" id="btn-import-historia" style="width: 100%;"><i class="fas fa-feather-alt"></i> Importar</button>
            </div>

        </aside>

        <!-- CONTAINER DO MAPA MENTAL -->
        <main id="map-container">
            <div id="mindmap"></div>
        </main>
    </div>

    <!-- MODAL PARA EDITAR NÓS E CONEXÕES -->
    <div class="modal-overlay" id="modal-editor">
        <div class="modal-content">
            <h2 id="modal-titulo">Editar Nó</h2>
            <form id="form-modal">
                <input type="hidden" id="modal-id">
                <input type="hidden" id="modal-tipo"> <!-- 'node' ou 'edge' -->

                <div class="form-group">
                    <label for="modal-label">Rótulo / Título</label>
                    <input type="text" id="modal-label" required>
                </div>
                <div class="form-group" id="group-modal-texto">
                    <label for="modal-texto">Descrição / Texto</label>
                    <textarea id="modal-texto"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-danger" id="btn-modal-excluir">Excluir</button>
                    <button type="button" class="btn btn-secondary" id="btn-modal-cancelar">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>


    <!-- JS da Biblioteca Vis.js -->
    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. CARREGAR DADOS DO PHP ---
            const dadosImport = {
                chars_op: <?php echo json_encode($personagens_op); ?>,
                chars_t20: <?php echo json_encode($personagens_t20); ?>,
                itens_op: <?php echo json_encode($itens_op); ?>,
                itens_t20: <?php echo json_encode($itens_t20); ?>,
                historias: <?php echo json_encode($historias); ?>
            };

            let dadosIniciaisMapa;
            try {
                let rawJson = <?php echo json_encode($mundo['descricao']); ?>;
                dadosIniciaisMapa = JSON.parse(rawJson);
                if (!dadosIniciaisMapa || typeof dadosIniciaisMapa.nodes === 'undefined') {
                    throw new Error("JSON inválido ou texto antigo");
                }
            } catch (e) {
                dadosIniciaisMapa = {
                    nodes: [],
                    edges: []
                };
                let textoAntigo = <?php echo json_encode($mundo['descricao']); ?>;
                if (textoAntigo && textoAntigo[0] !== '{' && textoAntigo[0] !== '[') {
                    dadosIniciaisMapa.nodes.push({
                        id: 'legacy_content',
                        label: 'Descrição Antiga',
                        title: textoAntigo,
                        shape: 'box'
                    });
                }
            }

            // *** NOVA CORREÇÃO 1/2 ***
            // Verifica se os nós já têm posições X/Y salvas.
            let physicsInitiallyEnabled = true; // Ligar física (padrão para mapas novos)
            if (dadosIniciaisMapa.nodes && dadosIniciaisMapa.nodes.length > 0 && dadosIniciaisMapa.nodes[0].x !== undefined) {
                physicsInitiallyEnabled = false; // Desliga a física se as posições já existem
            }

            // --- 2. INICIALIZAR O MAPA (VIS.JS) ---
            const nodes = new vis.DataSet(dadosIniciaisMapa.nodes || []);
            const edges = new vis.DataSet(dadosIniciaisMapa.edges || []);

            const container = document.getElementById('mindmap');
            const data = {
                nodes: nodes,
                edges: edges
            };
            const options = {
                interaction: {
                    navigationButtons: true,
                    keyboard: true,
                    dragNodes: true,
                    zoomView: true,
                    dragView: true
                },
                manipulation: {
                    enabled: false,
                    addEdge: function(edgeData, callback) {
                        edgeData.label = '...';
                        callback(edgeData);
                        abrirModalEdicao('edge', edgeData.id, 'Nova Conexão', '...');
                    }
                },
                nodes: {
                    shape: 'box',
                    borderWidth: 2,
                    font: {
                        size: 14,
                        color: '#333'
                    },
                    color: {
                        border: '#aaa',
                        background: '#fff',
                        highlight: {
                            border: 'var(--primary-color)',
                            background: '#f0e6f7'
                        },
                        hover: {
                            border: 'var(--primary-color)',
                            background: '#f9f6fb'
                        }
                    },
                    shapeProperties: {
                        borderRadius: 4
                    }
                },
                edges: {
                    width: 2,
                    arrows: 'to',
                    color: {
                        color: '#848484',
                        highlight: 'var(--primary-color)',
                        hover: '#b199be'
                    },
                    font: {
                        align: 'top',
                        background: 'white',
                        color: 'black'
                    },
                    smooth: true
                },
                physics: {
                    // *** NOVA CORREÇÃO 2/2 ***
                    // Usa a variável que checa se o mapa já tem posições salvas.
                    enabled: physicsInitiallyEnabled
                }
            };

            const network = new vis.Network(container, data, options);

            // *** CORREÇÃO DO BUG DE SCROLL ***
            // Esta função agora SÓ vai rodar em mapas NOVOS (quando physicsInitiallyEnabled = true)
            network.on("stabilized", function() {
                network.setOptions({
                    physics: false
                });
            });


            // --- 3. REFERÊNCIAS DO DOM (Modais e Botões) ---
            const modal = document.getElementById('modal-editor');
            const modalTitulo = document.getElementById('modal-titulo');
            const modalForm = document.getElementById('form-modal');
            const modalId = document.getElementById('modal-id');
            const modalTipo = document.getElementById('modal-tipo');
            const modalLabel = document.getElementById('modal-label');
            const modalTexto = document.getElementById('modal-texto');
            const groupModalTexto = document.getElementById('group-modal-texto');

            // --- 4. FUNÇÕES DO MODAL DE EDIÇÃO ---

            function abrirModalEdicao(tipo, id, label, texto) {
                modalId.value = id;
                modalTipo.value = tipo;

                if (tipo === 'node') {
                    modalTitulo.innerText = 'Editar Nó';
                    groupModalTexto.style.display = 'block';
                    modalLabel.value = label || '';
                    modalTexto.value = texto || '';
                } else { // 'edge'
                    modalTitulo.innerText = 'Editar Conexão';
                    groupModalTexto.style.display = 'block';
                    modalLabel.value = "Conexão";
                    modalTexto.value = label || '';
                }
                modal.style.display = 'flex';
                (tipo === 'node' ? modalLabel : modalTexto).focus();
            }

            function fecharModalEdicao() {
                modal.style.display = 'none';
            }

            // Salvar (Modal)
            modalForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const id = modalId.value;
                const tipo = modalTipo.value;
                const label = modalLabel.value;
                const texto = modalTexto.value;

                try {
                    if (tipo === 'node') {
                        nodes.update({
                            id: id,
                            label: label,
                            title: texto
                        });
                    } else { // 'edge'
                        edges.update({
                            id: id,
                            label: texto
                        });
                    }
                } catch (err) {
                    console.error("Erro ao atualizar nó/conexão:", err);
                }
                fecharModalEdicao();
            });

            // Cancelar (Modal)
            document.getElementById('btn-modal-cancelar').addEventListener('click', fecharModalEdicao);

            // Excluir (Modal)
            document.getElementById('btn-modal-excluir').addEventListener('click', function() {
                if (confirm('Tem certeza que deseja excluir este item?')) {
                    const id = modalId.value;
                    const tipo = modalTipo.value;
                    try {
                        if (tipo === 'node') {
                            nodes.remove({
                                id: id
                            });
                        } else {
                            edges.remove({
                                id: id
                            });
                        }
                    } catch (err) {
                        console.error("Erro ao excluir:", err);
                    }
                    fecharModalEdicao();
                }
            });

            // --- 5. EVENT HANDLERS DO MAPA (VIS.JS) ---

            // Evento de clique (para editar)
            network.on("click", function(params) {
                if (params.nodes.length > 0) {
                    const nodeId = params.nodes[0];
                    const nodeData = nodes.get(nodeId);
                    abrirModalEdicao('node', nodeId, nodeData.label, nodeData.title);
                } else if (params.edges.length > 0) {
                    const edgeId = params.edges[0];
                    const edgeData = edges.get(edgeId);
                    abrirModalEdicao('edge', edgeId, edgeData.label, edgeData.label);
                }
            });

            // --- 6. EVENT HANDLERS DA SIDEBAR (Adicionar e Importar) ---

            function adicionarNovoNo(label, tipo, dadosImportados) {
                const novoId = new Date().getTime().toString();
                const novoNo = {
                    id: novoId,
                    label: label,
                    title: '',
                    shape: 'box',
                    color: {
                        border: '#aaa',
                        background: '#fff'
                    },
                    data: dadosImportados || {
                        tipo: tipo
                    }
                };

                if (tipo === 'local') {
                    novoNo.shape = 'icon';
                    novoNo.icon = {
                        face: 'FontAwesome',
                        code: '\uf3c5',
                        color: '#c0392b'
                    }; // fa-map-marker-alt
                } else if (tipo === 'npc') {
                    novoNo.shape = 'icon';
                    novoNo.icon = {
                        face: 'FontAwesome',
                        code: '\uf508',
                        color: '#2980b9'
                    }; // fa-user-ninja
                } else if (tipo === 'char_op') {
                    novoNo.color = {
                        border: 'var(--primary-color)',
                        background: '#f0e6f7'
                    };
                    novoNo.shape = 'icon';
                    novoNo.icon = {
                        face: 'FontAwesome',
                        code: '\uf6e8',
                        color: 'var(--primary-color)'
                    }; // fa-book-dead
                } else if (tipo === 'char_t20') {
                    novoNo.color = {
                        border: 'var(--t20-color)',
                        background: '#f7e6e6'
                    };
                    novoNo.shape = 'icon';
                    novoNo.icon = {
                        face: 'FontAwesome',
                        code: '\uf6d3',
                        color: 'var(--t20-color)'
                    }; // fa-dragon
                } else if (tipo === 'item_op' || tipo === 'item_t20' || tipo === 'historia') {
                    novoNo.color = {
                        border: '#6c757d',
                        background: '#f8f9fa'
                    };
                    novoNo.shape = 'icon';
                    novoNo.icon = {
                        face: 'FontAwesome',
                        code: '\uf14b',
                        color: '#6c757d'
                    }; // fa-file-alt
                }

                nodes.add(novoNo);
                network.focus(novoId, {
                    scale: 1.0,
                    animation: true
                });
                abrirModalEdicao('node', novoId, label, '');
            }

            document.getElementById('btn-add-local').addEventListener('click', function() {
                adicionarNovoNo('Novo Local', 'local', null);
            });
            document.getElementById('btn-add-npc').addEventListener('click', function() {
                adicionarNovoNo('Novo NPC', 'npc', null);
            });

            function setupImportButton(btnId, selectId, dataArray, tipo) {
                document.getElementById(btnId).addEventListener('click', function() {
                    const select = document.getElementById(selectId);
                    const id = select.value;
                    if (!id) {
                        alert('Por favor, selecione um item para importar.');
                        return;
                    }
                    const item = dataArray.find(function(p) {
                        return p.id == id;
                    });
                    if (item) {
                        adicionarNovoNo(item.nome, tipo, item);
                    }
                });
            }

            setupImportButton('btn-import-char-op', 'import-char-op', dadosImport.chars_op, 'char_op');
            setupImportButton('btn-import-char-t20', 'import-char-t20', dadosImport.chars_t20, 'char_t20');
            setupImportButton('btn-import-item-op', 'import-item-op', dadosImport.itens_op, 'item_op');
            setupImportButton('btn-import-item-t20', 'import-item-t20', dadosImport.itens_t20, 'item_t20');
            setupImportButton('btn-import-historia', 'import-historia', dadosImport.historias, 'historia');

            // Botão Modo Conexão
            document.getElementById('btn-toggle-edges').addEventListener('click', function(e) {
                const modoAtivo = network.manipulation.enabled;
                if (modoAtivo) {
                    network.disableEditMode();
                    e.target.innerText = 'Modo Conexão';
                    e.target.style.backgroundColor = '#ffc107';
                } else {
                    network.enableEditMode();
                    network.addEdgeMode();
                    e.target.innerText = 'Sair do Modo Conexão';
                    e.target.style.backgroundColor = '#e0a800';
                }
            });

            // --- 7. LÓGICA DE SALVAMENTO (Formulário Principal) ---
            const formSalvar = document.getElementById('form-salvar');
            const hiddenDescricaoInput = document.getElementById('descricao-json');

            formSalvar.addEventListener('submit', function(e) {
                // Se a física estava desligada (mapa existente), não precisa salvar posições
                if (physicsInitiallyEnabled) {
                    network.setOptions({
                        physics: false
                    });
                }
                // Salva as posições de qualquer maneira, caso o usuário tenha movido
                network.storePositions();

                const nodesArray = nodes.get({
                    fields: ['id', 'label', 'title', 'shape', 'icon', 'color', 'data', 'x', 'y']
                });
                const edgesArray = edges.get({
                    fields: ['id', 'from', 'to', 'label', 'arrows']
                });

                const dataToSave = {
                    nodes: nodesArray,
                    edges: edgesArray
                };

                hiddenDescricaoInput.value = JSON.stringify(dataToSave);

                return true;
            });

        });
    </script>
</body>

</html>