<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
// Autenticação
// if (!isset($_SESSION['user_id'])) { header("Location: ../login.php"); exit(); }
require_once '../conection/db_connect.php';

// --- LÓGICA PARA CARREGAR OU CRIAR UM PERSONAGEM PARA EXIBIÇÃO ---
$personagem = null;
$is_new = true;

if (isset($_GET['personagem_id'])) {
    $id = intval($_GET['personagem_id']);
    $user_id_placeholder = 1; // Substitua por $_SESSION['user_id']

    $stmt = $conn->prepare("SELECT * FROM personagens_op WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id_placeholder);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $personagem = $result->fetch_assoc();
        $is_new = false;
    }
}

if ($is_new) {
    $personagem = [
        'id' => null,
        'nome' => 'Novo Personagem',
        'imagem' => 'default.jpg',
        'nex' => 5,
        'classe_id' => 1,
        'origem_id' => 1,
        'patente' => 'Recruta',
        'forca' => 1,
        'agilidade' => 1,
        'intelecto' => 1,
        'vigor' => 1,
        'presenca' => 1
    ];
}

// --- DADOS DO UNIVERSO (BUSCANDO DO BANCO DE DADOS) ---
// Classes (podem vir do banco também, mas por serem poucas, mantemos aqui)
// --- DADOS DO UNIVERSO (BUSCANDO DO BANCO DE DADOS) ---
// Busca todas as classes com seus dados de cálculo
// --- DADOS DO UNIVERSO (BUSCANDO DO BANCO DE DADOS) ---
// Busca Classes
$classes = [];
$sql_classes = "SELECT id, nome, pv_inicial, pv_por_nivel, pe_inicial, pe_por_nivel, san_inicial, san_por_nivel FROM classes";
$resultado_classes = $conn->query($sql_classes);
if ($resultado_classes) {
    while ($linha = $resultado_classes->fetch_assoc()) {
        $classes[$linha['id']] = $linha;
    }
}

// Busca Origens
$origens = [];
$sql_origens = "SELECT id, nome, poder_nome, poder_desc FROM origens ORDER BY nome ASC";
$resultado_origens = $conn->query($sql_origens);
if ($resultado_origens) {
    while ($linha = $resultado_origens->fetch_assoc()) {
        $origens[$linha['id']] = $linha;
    }
}

// Busca TODAS as Trilhas
$trilhas = [];
$sql_trilhas = "SELECT id, classe_id, nome FROM trilhas ORDER BY nome ASC";
$resultado_trilhas = $conn->query($sql_trilhas);
if ($resultado_trilhas) {
    while ($linha = $resultado_trilhas->fetch_assoc()) {
        $trilhas[] = $linha;
    }
}

// Busca TODOS os Poderes de Trilha
$poderes_trilha = [];
$sql_poderes = "SELECT id, trilha_id, nex_requerido, nome, descricao FROM poderes_trilha";
$resultado_poderes = $conn->query($sql_poderes);
if ($resultado_poderes) {
    while ($linha = $resultado_poderes->fetch_assoc()) {
        $poderes_trilha[] = $linha;
    }
}

// Habilidades Iniciais que cada classe ganha em NEX 5%
$poderes_iniciais_classe = [
    // Combatente (ID 1)
    1 => [
        ['nome' => 'Ataque Especial', 'descricao' => 'Quando faz um ataque, você pode gastar 2 PE para receber +5 no teste de ataque ou na rolagem de dano. O bônus aumenta conforme o NEX.']
    ],
    // Especialista (ID 2)
    2 => [
        ['nome' => 'Eclético', 'descricao' => 'Quando faz um teste de uma perícia, você pode gastar 2 PE para receber os benefícios de ser treinado nela.'],
        ['nome' => 'Perito', 'descricao' => 'Escolha duas perícias (exceto Luta e Pontaria). Ao fazer um teste de uma delas, pode gastar 2 PE para somar +1d6 no resultado. O bônus aumenta com o NEX.']
    ],
    // Ocultista (ID 3)
    3 => [
        ['nome' => 'Escolhido pelo Outro Lado', 'descricao' => 'Você foi marcado pelo Outro Lado e pode lançar rituais de 1º círculo. Você aprende a lançar rituais de círculos maiores conforme avança de NEX.']
    ]
];

// Busca TODOS os Poderes Gerais de Classe do banco de dados
$poderes_de_classe = [];
$sql_poderes_classe = "SELECT id, classe_id, nex_requerido, nome, descricao AS `desc` FROM poderes_classe";
$resultado_poderes_classe = $conn->query($sql_poderes_classe);
if ($resultado_poderes_classe) {
    while ($linha = $resultado_poderes_classe->fetch_assoc()) {
        $poderes_de_classe[] = $linha;
    }
}

// Busca Poderes Paranormais
$poderes_paranormais = [];
$sql_poderes_paranormais = "SELECT * FROM poderes_paranormais";
$resultado_poderes_paranormais = $conn->query($sql_poderes_paranormais);
if ($resultado_poderes_paranormais) {
    while ($linha = $resultado_poderes_paranormais->fetch_assoc()) {
        $poderes_paranormais[] = $linha;
    }
}

$poderes_salvos = ['classe' => [], 'paranormal' => []];
if (!$is_new) {
    $sql_poderes_salvos = "SELECT poder_id, tipo_poder FROM personagens_op_poderes WHERE personagem_id = ?";
    $stmt_ps = $conn->prepare($sql_poderes_salvos);
    $stmt_ps->bind_param("i", $id);
    $stmt_ps->execute();
    $res_ps = $stmt_ps->get_result();
    while ($linha = $res_ps->fetch_assoc()) {
        // Garante que a chave exista antes de adicionar
        if (isset($poderes_salvos[$linha['tipo_poder']])) {
            $poderes_salvos[$linha['tipo_poder']][] = intval($linha['poder_id']);
        }
    }
}

// Perícias agrupadas
$pericias_agrupadas = [
    'Agilidade' => [['id' => 1, 'nome' => 'Acrobacia'], ['id' => 7, 'nome' => 'Crime', 'so_treinado' => true], ['id' => 11, 'nome' => 'Furtividade'], ['id' => 12, 'nome' => 'Iniciativa'], ['id' => 20, 'nome' => 'Pilotagem', 'so_treinado' => true], ['id' => 21, 'nome' => 'Pontaria'], ['id' => 23, 'nome' => 'Reflexos']],
    'Força' => [['id' => 4, 'nome' => 'Atletismo'], ['id' => 16, 'nome' => 'Luta']],
    'Inteligência' => [['id' => 5, 'nome' => 'Atualidades'], ['id' => 6, 'nome' => 'Ciências', 'so_treinado' => true], ['id' => 14, 'nome' => 'Intuição'], ['id' => 15, 'nome' => 'Investigação'], ['id' => 17, 'nome' => 'Medicina', 'so_treinado' => true], ['id' => 18, 'nome' => 'Ocultismo', 'so_treinado' => true], ['id' => 22, 'nome' => 'Profissão', 'so_treinado' => true], ['id' => 25, 'nome' => 'Sobrevivência'], ['id' => 26, 'nome' => 'Tática', 'so_treinado' => true], ['id' => 27, 'nome' => 'Tecnologia', 'so_treinado' => true]],
    'Presença' => [['id' => 2, 'nome' => 'Adestramento', 'so_treinado' => true], ['id' => 3, 'nome' => 'Artes', 'so_treinado' => true], ['id' => 8, 'nome' => 'Diplomacia'], ['id' => 9, 'nome' => 'Enganação'], ['id' => 13, 'nome' => 'Intimidação'], ['id' => 19, 'nome' => 'Percepção'], ['id' => 24, 'nome' => 'Religião', 'so_treinado' => true], ['id' => 28, 'nome' => 'Vontade', 'so_treinado' => true]],
    'Vigor' => [['id' => 10, 'nome' => 'Fortitude']]
];

//capacidade de carga por patente
$patentes = [
    'Recruta' => ['I' => 2, 'II' => 0, 'III' => 0, 'IV' => 0],
    'Operador' => ['I' => 3, 'II' => 1, 'III' => 0, 'IV' => 0],
    'Agente Especial' => ['I' => 3, 'II' => 2, 'III' => 1, 'IV' => 0],
    'Oficial de Operações' => ['I' => 3, 'II' => 3, 'III' => 2, 'IV' => 1],
    'Agente de Elite' => ['I' => 3, 'II' => 3, 'III' => 3, 'IV' => 2]
];

$todos_itens_op = [];
$sql_todos_itens = "SELECT id, nome, tipo_item_id, categoria, espacos, descricao FROM itens_op ORDER BY nome ASC";
$resultado_todos_itens = $conn->query($sql_todos_itens);
if ($resultado_todos_itens) {
    while ($linha = $resultado_todos_itens->fetch_assoc()) {
        $todos_itens_op[] = $linha;
    }
}

// *** NOVO: Carregar o inventário do personagem ***
$inventario_personagem = [];
// Garante que só buscamos o inventário se o personagem já existir no banco
if (!$is_new) {
    $sql_inventario = "SELECT i.id, i.nome, i.tipo_item_id, i.categoria, i.espacos, i.defesa_bonus, inv.quantidade 
                       FROM inventario_op inv
                       JOIN itens_op i ON inv.item_id = i.id
                       WHERE inv.personagem_id = ?";
    $stmt_inv = $conn->prepare($sql_inventario);

    // CORREÇÃO: Usando a variável $id, que contém o ID do personagem carregado.
    $stmt_inv->bind_param("i", $id);

    $stmt_inv->execute();
    $resultado_inventario = $stmt_inv->get_result();
    if ($resultado_inventario) {
        while ($linha = $resultado_inventario->fetch_assoc()) {
            $inventario_personagem[] = $linha;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Personagem - Ordem Paranormal</title>
    <link rel="stylesheet" href="../static/ficha_op.css">
</head>

<body>
    <div class="container">
        <form id="ficha-form" method="POST" action="../conection/save_character.php" enctype="multipart/form-data">
            <input type="hidden" name="personagem_id" value="<?= $personagem['id'] ?>">
            <!-- ABA IMAGEM E INFORMATIVOS -->
            <div class="ficha-grid">
                <div class="coluna-esquerda">
                    <div class="bloco-personagem">
                        <input type="file" name="imagem_personagem" id="input-imagem" accept="image/png, image/jpeg, image/gif" style="display: none;">
                        <div class="personagem-imagem" id="container-imagem">
                            <img src="../uploads/<?= htmlspecialchars($personagem['imagem']) ?>" alt="Imagem do Personagem" id="preview-imagem">
                        </div>
                        <button type="button" id="btn-importar-imagem" class="btn-acao" style="margin-bottom: 15px;">Importar Imagem</button>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($personagem['nome']) ?>">
                    </div>
                    <div class="bloco-status">
                        <div class="status-box"><label>❤️ VIDA</label>
                            <div><span id="vida-display" class="valor">--</span></div>
                        </div>
                        <div class="status-box"><label>🧠 SANIDADE</label>
                            <div><span id="sanidade-display" class="valor">--</span></div>
                        </div>
                        <div class="status-box"><label>🔥 ESFORÇO</label>
                            <div><span id="pe-display" class="valor">--</span></div>
                        </div>
                        <div class="status-box"><label>🛡️ DEFESA</label>
                            <div><span id="defesa-display" class="valor">--</span></div>
                        </div>
                    </div>
                </div>

                <!-- ABA ATRIBUTOS E PERICIAS -->
                <div class="coluna-direita">
                    <nav class="abas-nav">
                        <button type="button" class="tab-button active" data-tab="tab-atributos">Atributos & Perícias</button>
                        <button type="button" class="tab-button" data-tab="tab-poderes">Poderes & Rituais</button>
                        <button type="button" class="tab-button" data-tab="tab-equipamento">Equipamento</button>
                    </nav>

                    <div id="tab-atributos" class="tab-content active">
                        <h2>Atributos</h2>
                        <div class="atributos-grid">
                            <?php foreach (['forca', 'agilidade', 'intelecto', 'vigor', 'presenca'] as $attr): ?>
                                <div class="atributo-box">
                                    <label><?= strtoupper($attr) ?></label>
                                    <input type="number" class="atributo-input" id="<?= $attr ?>" name="<?= $attr ?>" value="<?= $personagem[$attr] ?>" min="0" max="5">
                                </div>
                            <?php endforeach; ?>
                            <div class="atributo-box">
                                <label>NEX</label>
                                <select class="atributo-input" id="nex" name="nex">
                                    <?php
                                    for ($i = 5; $i <= 95; $i += 5) {
                                        $selected = ($i == $personagem['nex']) ? 'selected' : '';
                                        echo "<option value=\"$i\" $selected>$i%</option>";
                                    }
                                    $selected_99 = ($personagem['nex'] == 99) ? 'selected' : '';
                                    echo "<option value=\"99\" $selected_99>99%</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                        <h2>Perícias</h2>
                        <div class="pericias-container">
                            <?php foreach ($pericias_agrupadas as $atributo => $lista_pericias): ?>
                                <div class="pericia-coluna">
                                    <h3><?= strtoupper($atributo) ?></h3>
                                    <?php foreach ($lista_pericias as $p): ?>
                                        <div class="pericia-item" data-atributo-base="<?= strtolower($atributo) ?>">
                                            <div class="pericia-nome"><?= $p['nome'] ?><?= (isset($p['so_treinado']) && $p['so_treinado']) ? '<span>*</span>' : '' ?></div>
                                            <div class="pericia-input-wrapper"><input type="number" class="pericia-input" id="pericia_<?= $p['id'] ?>" value="0"></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- ABA PODERES E RITUAIS -->
                    <div id="tab-poderes" class="tab-content">

                        <div class="info-poderes">
                            <div>
                                <label for="classe-select">Classe</label>
                                <select id="classe-select" name="classe_id">
                                    <?php foreach ($classes as $id => $classe): ?>
                                        <option value="<?= $id ?>" <?= ($personagem['classe_id'] == $id) ? 'selected' : '' ?>>
                                            <?= $classe['nome'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="origem-select">Origem</label>
                                <select id="origem-select" name="origem_id">
                                    <option value="0">Selecione...</option>
                                    <?php foreach ($origens as $id => $origem): ?>
                                        <option value="<?= $id ?>" <?= ($personagem['origem_id'] == $id) ? 'selected' : '' ?>>
                                            <?= $origem['nome'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="trilha-select">Trilha</label>
                                <select id="trilha-select" name="trilha_id" disabled>
                                    <option value="0">Apenas em NEX 10%+</option>

                                    <?php foreach ($trilhas as $trilha): ?>
                                        <option value="<?= $trilha['id'] ?>" <?= (isset($personagem['trilha_id']) && $personagem['trilha_id'] == $trilha['id']) ? 'selected' : '' ?>>
                                            <?= $trilha['nome'] ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                            <div id="dt-rituais-container" style="display: none;">
                                DT Rituais: <span id="dt-rituais-span"></span>
                            </div>
                        </div>

                        <h2>Poderes e Habilidades</h2>

                        <div class="lista-poderes">
                            <div class="poder-item">
                                <h4>Poder de Origem</h4>
                                <p id="poder-origem-display">Selecione uma origem para ver o poder correspondente.</p>
                            </div>
                        </div>

                        <button type="button" class="btn-acao" id="btn-adicionar-poder" style="margin-top: 20px;">Adicionar Poder de Classe</button>


                    </div>

                    <!-- ABA EQUIPAMENTO -->

                    <div id="tab-equipamento" class="tab-content">
                        <div class="info-equipamento">
                            <div class="patente-container">
                                <label for="patente-select">Patente</label>
                                <select id="patente-select" name="patente">
                                    <?php foreach (array_keys($patentes) as $patente) : ?>
                                        <option value="<?= $patente ?>"><?= $patente ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="espacos-container">
                                <!-- Mudamos para mostrar "usado / total" -->
                                <span>Carga</span>
                                <strong id="espacos-usados-display">0</strong> / <strong id="espacos-total-display">--</strong>
                            </div>
                            <div class="limites-categoria">
                                <span>Limite de Itens por Categoria</span>
                                <div class="limites-grid">
                                    <div class="limite-box">I<strong id="limite-cat-i"></strong></div>
                                    <div class="limite-box">II<strong id="limite-cat-ii"></strong></div>
                                    <div class="limite-box">III<strong id="limite-cat-iii"></strong></div>
                                    <div class="limite-box">IV<strong id="limite-cat-iv"></strong></div>
                                </div>
                            </div>
                        </div>

                        <h2>Inventário</h2>
                        <!-- A lista agora tem um ID e será preenchida pelo JS -->
                        <div class="lista-itens" id="lista-itens-personagem">
                            <div class="item-row header">
                                <div class="item-nome">Item</div>
                                <div class="item-cat">Cat.</div>
                                <div class="item-esp">Esp.</div>
                                <div class="item-acoes">Ações</div>
                            </div>
                            <!-- A lista de itens do personagem será inserida aqui dinamicamente -->
                        </div>
                        <!-- O botão agora abre o novo modal -->
                        <button type="button" class="btn-acao" id="btn-abrir-modal-item" style="margin-top: 20px;">Adicionar Item</button>
                    </div>

                    <!-- RODAPE -->
                    <div class="botoes-rodape">
                        <a href="meus_personagens.php" class="btn-acao btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar para Meus Personagens
                        </a>
                        <button type="submit" class="btn-acao">
                            <i class="fas fa-save"></i> Salvar Personagem
                        </button>
                    </div>
        </form>
    </div>

    <!-- MODAIS -->

    <div id="modal-poderes-classe" class="modal-overlay">
        <div class="modal-content">
            <h2>Adicionar Habilidade de Classe</h2>
            <div id="lista-poderes-modal-content" class="lista-modal"></div>
            <button type="button" class="btn-acao" onclick="fecharModalPoderes()" style="margin-top: 20px;">Fechar</button>
        </div>
    </div>

    <div id="modal-transcender" class="modal-overlay">
        <div class="modal-content modal-transcender-content">
            <h2>Transcender</h2>
            <p>Você abre mão do seu próximo aumento de Sanidade para obter um poder paranormal.</p>
            <div id="lista-poderes-transcender" class="lista-modal-grid">
            </div>

            <button type="button" class="btn-acao" onclick="fecharModalTranscender()" style="margin-top: 20px;">Fechar</button>
        </div>
    </div>

    <div id="modal-adicionar-item" class="modal-overlay">
        <div class="modal-content modal-itens">
            <h2>Adicionar Item ao Inventário</h2>
            <div class="modal-filtros">
                <input type="text" id="filtro-item-nome" placeholder="Buscar por nome...">
                <div class="modal-botoes-filtro">
                    <button type="button" class="filtro-tipo-item active" data-tipo-id="0">Todos</button>
                    <button type="button" class="filtro-tipo-item" data-tipo-id="1">Armas</button>
                    <button type="button" class="filtro-tipo-item" data-tipo-id="2">Proteções</button>
                    <button type="button" class="filtro-tipo-item" data-tipo-id="3">Geral</button>
                    <button type="button" class="filtro-tipo-item" data-tipo-id="4">Paranormal</button>
                </div>
            </div>
            <div id="lista-itens-modal" class="lista-modal">
                <!-- A lista de todos os itens do jogo será inserida aqui pelo JavaScript -->
            </div>
            <button type="button" class="btn-acao" onclick="fecharModalAdicionarItem()" style="margin-top: 20px;">Fechar</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- DADOS DO PHP PARA O JS ---
            const classesData = <?= json_encode(isset($classes) ? $classes : []) ?>;
            const origens = <?= json_encode(isset($origens) ? $origens : []) ?>;
            const todasAsTrilhas = <?= json_encode(isset($trilhas) ? $trilhas : []) ?>;
            const todosOsPoderesDeTrilha = <?= json_encode(isset($poderes_trilha) ? $poderes_trilha : []) ?>;
            const poderesIniciaisClasse = <?= json_encode(isset($poderes_iniciais_classe) ? $poderes_iniciais_classe : []) ?>;
            const todosOsPoderesDeClasse = <?= json_encode(isset($poderes_de_classe) ? $poderes_de_classe : []) ?>;
            const todosOsPoderesParanormais = <?= json_encode(isset($poderes_paranormais) ? $poderes_paranormais : []) ?>;
            const patentesData = <?= json_encode(isset($patentes) ? $patentes : []) ?>;
            const todosOsItensOP = <?= json_encode(isset($todos_itens_op) ? $todos_itens_op : []) ?>;
            const inventarioInicialPersonagem = <?= json_encode(isset($inventario_personagem) ? $inventario_personagem : []) ?>;


            // --- ELEMENTOS GLOBAIS ---
            const form = document.getElementById('ficha-form');
            if (!form) return;
            const inputsParaMonitorar = form.querySelectorAll('input.atributo-input, select.atributo-input, #classe-select, #origem-select, #trilha-select, #patente-select');
            const modalPoderesClasse = document.getElementById('modal-poderes-classe');
            const modalTranscender = document.getElementById('modal-transcender');
            const modalAdicionarItem = document.getElementById('modal-adicionar-item');
            let transcendCount = 0;
            var poderesParanormaisSelecionados = [];
            let classeIdAnterior = parseInt(document.getElementById('classe-select').value) || 0;
            let inventarioAtual = [...inventarioInicialPersonagem];

            // --- LÓGICA DE UPLOAD E ABAS ---
            const btnImportar = document.getElementById('btn-importar-imagem');
            const inputImagem = document.getElementById('input-imagem');
            const previewImagem = document.getElementById('preview-imagem');
            const containerImagem = document.getElementById('container-imagem');
            if (btnImportar && inputImagem) btnImportar.addEventListener('click', () => inputImagem.click());
            if (containerImagem && inputImagem) containerImagem.addEventListener('click', () => inputImagem.click());
            if (inputImagem) {
                inputImagem.addEventListener('change', event => {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            if (previewImagem) previewImagem.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            if (tabButtons && tabContents) {
                tabButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        tabButtons.forEach(btn => btn.classList.remove('active'));
                        tabContents.forEach(content => content.classList.remove('active'));
                        button.classList.add('active');
                        const targetContent = document.getElementById(button.dataset.tab);
                        if (targetContent) targetContent.classList.add('active');
                    });
                });
            }

            // --- FUNÇÕES DE ATUALIZAÇÃO DA INTERFACE ---

            function atualizarPoderOrigem() {
                const origemSelect = document.getElementById('origem-select');
                const displayContainer = document.getElementById('poder-origem-display');
                const displayTitle = displayContainer ? displayContainer.parentElement.querySelector('h4') : null;
                if (!origemSelect || !displayTitle) return;

                const origemId = origemSelect.value;
                const poderOrigemInfo = origens[origemId];

                if (poderOrigemInfo) {
                    displayTitle.textContent = `Poder de Origem (${poderOrigemInfo.nome})`;
                    displayContainer.textContent = poderOrigemInfo.poder_desc;
                } else {
                    displayTitle.textContent = 'Poder de Origem';
                    displayContainer.textContent = 'Selecione uma origem para ver seu poder.';
                }
            }

            function atualizarTrilhasDisponiveis() {
                const classeId = parseInt(document.getElementById('classe-select').value) || 0;
                const nex = parseInt(document.getElementById('nex').value) || 0;
                const trilhaSelect = document.getElementById('trilha-select');
                if (!trilhaSelect) return;

                const trilhaSelecionadaAnteriormente = trilhaSelect.value;

                trilhaSelect.innerHTML = '';

                if (classeId > 0 && nex >= 10) {
                    trilhaSelect.add(new Option('Nenhuma Trilha', '0'));

                    const trilhasDisponiveis = todasAsTrilhas.filter(trilha => trilha.classe_id == classeId);

                    trilhasDisponiveis.forEach(trilha => {
                        trilhaSelect.add(new Option(trilha.nome, trilha.id));
                    });

                    trilhaSelect.value = trilhaSelecionadaAnteriormente;
                    trilhaSelect.disabled = false;
                } else {
                    trilhaSelect.add(new Option('Apenas em NEX 10%+', '0'));
                    trilhaSelect.disabled = true;
                }
            }

            function atualizarPoderesDaTrilha() {
                const nex = parseInt(document.getElementById('nex').value) || 0;
                const trilhaId = parseInt(document.getElementById('trilha-select').value) || 0;
                const listaPoderesFicha = document.querySelector('#tab-poderes .lista-poderes');
                if (!listaPoderesFicha) return;

                listaPoderesFicha.querySelectorAll('.poder-trilha-item').forEach(item => item.remove());

                if (trilhaId > 0) {
                    const poderesGanhos = todosOsPoderesDeTrilha.filter(poder => {
                        return poder.trilha_id == trilhaId && poder.nex_requerido <= nex;
                    });
                    poderesGanhos.forEach(poder => {
                        const poderDiv = document.createElement('div');
                        poderDiv.className = 'poder-item poder-trilha-item';
                        poderDiv.innerHTML = `<h4>${poder.nome} (NEX ${poder.nex_requerido}%)</h4><p>${poder.descricao}</p>`;
                        listaPoderesFicha.appendChild(poderDiv);
                    });
                }
            }

            function atualizarPoderesIniciaisDeClasse() {
                const classeId = parseInt(document.getElementById('classe-select').value) || 0;
                const listaPoderesFicha = document.querySelector('#tab-poderes .lista-poderes');
                if (!listaPoderesFicha) return;

                if (classeId !== classeIdAnterior) {
                    const listaPoderesFicha = document.querySelector('#tab-poderes .lista-poderes');
                    if (listaPoderesFicha) {
                        listaPoderesFicha.querySelectorAll('.poder-classe-adicionado, .poder-paranormal-item').forEach(item => item.remove());
                    }
                    transcendCount = 0;
                    poderesParanormaisSelecionados = []; // Limpa os poderes paranormais selecionados
                    classeIdAnterior = classeId;
                }

                // Limpa quaisquer poderes iniciais de classe exibidos anteriormente
                listaPoderesFicha.querySelectorAll('.poder-inicial-item').forEach(item => item.remove());

                // Pega os poderes da classe selecionada
                const poderesIniciais = poderesIniciaisClasse[classeId];

                if (poderesIniciais) {
                    // Adiciona cada poder inicial à lista na ficha
                    poderesIniciais.forEach(poder => {
                        const poderDiv = document.createElement('div');
                        poderDiv.className = 'poder-item poder-inicial-item'; // Nova classe para identificação
                        poderDiv.innerHTML = `<h4>${poder.nome} (Habilidade de Classe)</h4><p>${poder.descricao}</p>`;

                        // Adiciona logo após o poder de origem
                        const poderOrigemEl = listaPoderesFicha.querySelector('.poder-item');
                        if (poderOrigemEl) {
                            poderOrigemEl.insertAdjacentElement('afterend', poderDiv);
                        } else {
                            listaPoderesFicha.appendChild(poderDiv);
                        }
                    });
                }
            }

            function limparPoderesDeClasseAdicionados() {
                const listaPoderesFicha = document.querySelector('#tab-poderes .lista-poderes');
                if (listaPoderesFicha) {
                    listaPoderesFicha.querySelectorAll('.poder-classe-adicionado').forEach(item => item.remove());
                }
            }
            // --- LÓGICA DE MODAIS ---
            // --- FUNÇÕES DE CONTROLE DO MODAL (TORNADAS GLOBAIS) ---
            function abrirModalPoderesDeClasse() {
                if (!modalPoderesClasse) return;
                const nex = parseInt(document.getElementById('nex').value) || 0;
                const classeId = parseInt(document.getElementById('classe-select').value) || 0;
                const listaModal = document.getElementById('lista-poderes-modal-content');
                listaModal.innerHTML = '';

                const poderesDisponiveis = todosOsPoderesDeClasse.filter(poder => {
                    return (poder.nex_requerido <= nex) && (poder.classe_id === null || poder.classe_id == classeId);
                });

                poderesDisponiveis.forEach(poder => {
                    const poderDiv = document.createElement('div');
                    let botaoHTML;
                    if (poder.nome === 'Transcender') {
                        poderDiv.className = 'poder-item-modal poder-transcender-opcao';
                        botaoHTML = `<button type="button" onclick="abrirModalTranscender()">Escolher</button>`;
                    } else {
                        poderDiv.className = 'poder-item-modal';
                        botaoHTML = `<button type="button" onclick="adicionarPoderDeClasse(${poder.id})">Adicionar</button>`;
                    }
                    poderDiv.innerHTML = `<div><h4>${poder.nome}</h4><p>${poder.desc}</p></div>${botaoHTML}`;
                    listaModal.appendChild(poderDiv);
                });
                modalPoderesClasse.style.display = 'flex';
            }

            window.abrirModalTranscender = function() {
                if (!modalTranscender) return;
                const listaTranscender = document.getElementById('lista-poderes-transcender');
                listaTranscender.innerHTML = '';
                todosOsPoderesParanormais.forEach(poder => {
                    const poderCard = document.createElement('div');
                    poderCard.className = `poder-paranormal-card ${poder.elemento}`;
                    poderCard.onclick = () => selecionarPoderParanormal(poder.id);
                    poderCard.innerHTML = `<h4>${poder.nome}</h4><p>${poder.descricao}</p>`;
                    listaTranscender.appendChild(poderCard);
                });
                fecharModalPoderes();
                modalTranscender.style.display = 'flex';
            }

            window.selecionarPoderParanormal = function(poderId) {
                const poderInfo = todosOsPoderesParanormais.find(p => p.id == poderId);
                if (!poderInfo) return;
                const listaPoderesFicha = document.querySelector('#tab-poderes .lista-poderes');
                const poderDiv = document.createElement('div');
                poderDiv.className = 'poder-item poder-paranormal-item';
                poderDiv.innerHTML = `<h4>${poderInfo.nome} (Paranormal)</h4><p>${poderInfo.descricao}</p>`;
                listaPoderesFicha.appendChild(poderDiv);
                poderesParanormaisSelecionados.push(parseInt(poderId));

                transcendCount++;
                fecharModalTranscender();
                calcularTudo(); // Recalcula tudo para aplicar a penalidade E OS NOVOS BÔNUS
            }

            window.adicionarPoderDeClasse = function(poderId) {
                const poderInfo = todosOsPoderesDeClasse.find(p => p.id == poderId);
                if (!poderInfo) return;
                const listaPoderesFicha = document.querySelector('#tab-poderes .lista-poderes');
                const poderDiv = document.createElement('div');
                poderDiv.className = 'poder-item poder-classe-adicionado'; // Classe para limpeza
                poderDiv.innerHTML = `<h4>${poderInfo.nome} (Classe)</h4><p>${poderInfo.desc}</p>`;
                listaPoderesFicha.appendChild(poderDiv);
                fecharModalPoderes();
            }

            window.fecharModalPoderes = () => {
                if (modalPoderesClasse) modalPoderesClasse.style.display = 'none';
            }

            window.fecharModalTranscender = () => {
                if (modalTranscender) modalTranscender.style.display = 'none';
            }

            function atualizarDisplayInventario() {
                const containerInventario = document.getElementById('lista-itens-personagem');
                if (!containerInventario) return;

                // Limpa o conteúdo atual, mantendo o cabeçalho
                while (containerInventario.children.length > 1) {
                    containerInventario.removeChild(containerInventario.lastChild);
                }

                let espacosUsados = 0;
                inventarioAtual.forEach((item, index) => {
                    const itemRow = document.createElement('div');
                    itemRow.className = 'item-row';
                    // Adiciona um botão de remover que chama a função com o índice do item
                    itemRow.innerHTML = `
            <div class="item-nome">${item.nome}</div>
            <div class="item-cat">${item.categoria}</div>
            <div class="item-esp">${item.espacos}</div>
            <div class="item-acoes">
                <button type="button" class="btn-remover-item" onclick="removerItemDoInventario(${index})">X</button>
            </div>
        `;
                    containerInventario.appendChild(itemRow);
                    espacosUsados += parseInt(item.espacos) || 0;
                });

                // Atualiza o display de espaços usados
                document.getElementById('espacos-usados-display').textContent = espacosUsados;
            }

            window.adicionarItemAoInventario = (itemId) => {
                const itemInfo = todosOsItensOP.find(i => i.id == itemId);
                if (itemInfo) {
                    inventarioAtual.push(itemInfo);
                    atualizarDisplayInventario();
                    calcularTudo(); // Recalcula tudo para atualizar carga e bônus
                }
            };

            window.removerItemDoInventario = (index) => {
                inventarioAtual.splice(index, 1); // Remove o item do array
                atualizarDisplayInventario();
                calcularTudo();
            };

            function popularModalItens() {
                const containerModal = document.getElementById('lista-itens-modal');
                if (!containerModal) return;

                const termoBusca = document.getElementById('filtro-item-nome').value.toLowerCase();
                const tipoIdFiltro = document.querySelector('.filtro-tipo-item.active').dataset.tipoId;

                containerModal.innerHTML = ''; // Limpa a lista

                const itensFiltrados = todosOsItensOP.filter(item => {
                    const nomeMatch = item.nome.toLowerCase().includes(termoBusca);
                    const tipoMatch = (tipoIdFiltro == "0" || item.tipo_item_id == tipoIdFiltro);
                    return nomeMatch && tipoMatch;
                });

                itensFiltrados.forEach(item => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'item-modal';
                    itemDiv.innerHTML = `
            <div class="item-modal-info">
                <h4>${item.nome} (Cat ${item.categoria}, ${item.espacos} esp)</h4>
                ${item.descricao ? `<p>${item.descricao}</p>` : ''}
            </div>
            <button type="button" class="btn-adicionar" onclick="adicionarItemAoInventario(${item.id})">Adicionar</button>
        `;
                    containerModal.appendChild(itemDiv);
                });
            }

            // Funções globais para abrir e fechar o modal de itens
            window.abrirModalAdicionarItem = () => {
                if (modalAdicionarItem) {
                    popularModalItens();
                    modalAdicionarItem.style.display = 'flex';
                }
            };

            window.fecharModalAdicionarItem = () => {
                if (modalAdicionarItem) modalAdicionarItem.style.display = 'none';
            };

            // --- FUNÇÃO MASTER DE CÁLCULO ----
            function calcularTudo() {

                const nex = parseInt(document.getElementById('nex').value) || 0;
                const classeId = parseInt(document.getElementById('classe-select').value) || 0;
                const origemId = parseInt(document.getElementById('origem-select').value) || 0;
                const trilhaId = parseInt(document.getElementById('trilha-select').value) || 0;
                const patenteSelecionada = document.getElementById('patente-select').value;

                const atributos = {};
                ['forca', 'agilidade', 'intelecto', 'vigor', 'presenca'].forEach(attr => {
                    atributos[attr] = parseInt(document.getElementById(attr).value) || 0;
                });

                const classeAtual = classesData[classeId];

                if (!classeAtual) {
                    document.getElementById('vida-display').textContent = '--';
                    document.getElementById('pe-display').textContent = '--';
                    document.getElementById('sanidade-display').textContent = '--';
                    return;
                }

                const niveis = Math.floor(nex / 5);
                const niveisAposPrimeiro = niveis > 1 ? niveis - 1 : 0;

                // --- CÁLCULO DOS STATUS ---

                // CÁLCULO DE VIDA
                // Fórmula: PV Inicial + (Vigor x Nível) + (PV por Nível x (Níveis acima do 1º))
                let vidaMax = parseInt(classeAtual.pv_inicial) + (atributos.vigor * niveis) + (parseInt(classeAtual.pv_por_nivel) * niveisAposPrimeiro);
                if (origemId == 9) { // Desgarrado
                    vidaMax += niveis;
                }
                if (trilhaId === 5) { // Tropa de Choque (Casca Grossa)
                    vidaMax += niveis;
                }
                if (poderesParanormaisSelecionados.includes(3)) { // poder sangue de ferro
                    vidaMax += (niveis * 2);
                }

                // CÁLCULO DE PE
                // Fórmula: PE Inicial + Presença + (PE por Nível x (Níveis acima do 1º))
                let peMax = parseInt(classeAtual.pe_inicial) + (atributos.presenca * niveis) + (parseInt(classeAtual.pe_por_nivel) * niveisAposPrimeiro);
                if (poderesParanormaisSelecionados.includes(13)) { // poder potencial aprimorado
                    peMax += niveis;
                }

                // CÁLCULO DE SANIDADE COM PENALIDADE DE TRANSCENDER
                let sanidadeMax = parseInt(classeAtual.san_inicial) + (parseInt(classeAtual.san_por_nivel) * (niveisAposPrimeiro - transcendCount));
                if (origemId == 24) {
                    sanidadeMax += niveis;
                }

                document.getElementById('sanidade-display').textContent = sanidadeMax;

                // CÁLCULO DE DEFESA
                let defesaTotal = 10 + atributos.agilidade;
                if (origemId == 16) defesaTotal += 2; // Policial
                // Soma a defesa de todas as proteções e escudos no inventário
                inventarioAtual.forEach(item => {
                    if (item.tipo_item_id == 2) { // ID 2 = Proteção
                        defesaTotal += parseInt(item.defesa_bonus) || 0;
                    }
                });

                // CÁLCULOS DE INVENTÁRIO
                // Verifica se o personagem tem o poder de trilha "Inventário Otimizado" (ID 37)
                const temInventarioOtimizado = todosOsPoderesDeTrilha.some(p => p.trilha_id == trilhaId && p.nex_requerido <= nex && p.id == 37);
                const temMochilaMilitar = inventarioAtual.some(item => item.nome === 'Mochila militar');

                let espacosTotal;
                if (temInventarioOtimizado) {
                    espacosTotal = (atributos.forca + atributos.intelecto) * 5;
                } else {
                    espacosTotal = (atributos.forca == 0) ? 2 : (5 * atributos.forca);
                }
                if (temMochilaMilitar) {
                    espacosTotal += 2; // Bônus da mochila
                }

                document.getElementById('espacos-total-display').textContent = espacosTotal;
                const limites = patentesData[patenteSelecionada];
                if (limites) {
                    document.getElementById('limite-cat-i').textContent = limites['I'] || '—';
                    document.getElementById('limite-cat-ii').textContent = limites['II'] || '—';
                    document.getElementById('limite-cat-iii').textContent = limites['III'] || '—';
                    document.getElementById('limite-cat-iv').textContent = limites['IV'] || '—';
                }

                // Atualiza os displays na tela
                document.getElementById('vida-display').textContent = vidaMax;
                document.getElementById('pe-display').textContent = peMax;
                document.getElementById('sanidade-display').textContent = sanidadeMax;
                document.getElementById('defesa-display').textContent = defesaTotal;

                // Lógica da DT de Rituais (para Ocultista)
                const dtRituaisContainer = document.getElementById('dt-rituais-container');
                if (dtRituaisContainer) {
                    if (classeId === 3) { // Se for Ocultista
                        dtRituaisContainer.style.display = 'block';

                        // Calcula a DT base
                        let dtRituais = 10 + atributos.presenca + Math.floor(nex / 5);

                        // Bônus da Trilha Graduado (Rituais Eficientes)
                        if (trilhaId === 13 && nex >= 65) {
                            dtRituais += 5;
                        }

                        document.getElementById('dt-rituais-span').textContent = dtRituais;
                    } else {
                        dtRituaisContainer.style.display = 'none';
                    }
                }

                // Chama as funções de atualização da UI
                atualizarPoderOrigem();
                atualizarTrilhasDisponiveis();
                atualizarPoderesDaTrilha();
                atualizarPoderesIniciaisDeClasse();
            }

            // --- EVENT LISTENERS E INICIALIZAÇÃO ---

            inputsParaMonitorar.forEach(input => input.addEventListener('change', calcularTudo));

            document.getElementById('classe-select')?.addEventListener('change', () => {
                limparPoderesDeClasseAdicionados();
                calcularTudo();
            });

            document.getElementById('btn-adicionar-poder')?.addEventListener('click', abrirModalPoderesDeClasse);

            // Novos Listeners para o Modal de Itens
            document.getElementById('btn-abrir-modal-item')?.addEventListener('click', abrirModalAdicionarItem);
            document.getElementById('filtro-item-nome')?.addEventListener('input', popularModalItens);
            document.querySelectorAll('.filtro-tipo-item').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelector('.filtro-tipo-item.active').classList.remove('active');
                    e.target.classList.add('active');
                    popularModalItens();
                });
            });

            // Inicialização da Ficha
            atualizarDisplayInventario(); // Popula o inventário inicial
            calcularTudo(); // Calcula todos os status iniciais
        });
    </script>
</body>

</html>