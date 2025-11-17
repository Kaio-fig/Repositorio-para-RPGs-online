<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../templates/login.php");
    exit();
}
require_once '../conection/db_connect.php';


// --- LÓGICA PARA CARREGAR OU CRIAR UM PERSONAGEM PARA EXIBIÇÃO ---
$personagem = null;
$is_new = true;
$id = 0; // Inicializa o ID
$user_id = $_SESSION['user_id'];

if (isset($_GET['personagem_id'])) {
    $id = intval($_GET['personagem_id']);

    if ($id > 0) {
        $stmt = $conn->prepare("SELECT * FROM personagens_op WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $personagem = $result->fetch_assoc();
            $is_new = false;
        }
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
        'trilha_id' => null,
        'patente' => 'Recruta',
        'forca' => 1,
        'agilidade' => 1,
        'intelecto' => 1,
        'vigor' => 1,
        'presenca' => 1
    ];
}

// --- DADOS DO UNIVERSO (BUSCANDO DO BANCO DE DADOS) ---
// Classes
$classes = [];
$sql_classes = "SELECT id, nome, pv_inicial, pv_por_nivel, pe_inicial, pe_por_nivel, san_inicial, san_por_nivel FROM classes";
$resultado_classes = $conn->query($sql_classes);
if ($resultado_classes) {
    while ($linha = $resultado_classes->fetch_assoc()) {
        $classes[$linha['id']] = $linha;
    }
}

// Origens
$origens = [];
$sql_origens = "SELECT id, nome, poder_nome, poder_desc FROM origens ORDER BY nome ASC";
$resultado_origens = $conn->query($sql_origens);
if ($resultado_origens) {
    while ($linha = $resultado_origens->fetch_assoc()) {
        $origens[$linha['id']] = $linha;
    }
}

// Trilhas
$trilhas = [];
$sql_trilhas = "SELECT id, classe_id, nome FROM trilhas ORDER BY nome ASC";
$resultado_trilhas = $conn->query($sql_trilhas);
if ($resultado_trilhas) {
    while ($linha = $resultado_trilhas->fetch_assoc()) {
        $trilhas[] = $linha;
    }
}

// Poderes de Trilha
$poderes_trilha = [];
$sql_poderes = "SELECT id, trilha_id, nex_requerido, nome, descricao FROM poderes_trilha";
$resultado_poderes = $conn->query($sql_poderes);
if ($resultado_poderes) {
    while ($linha = $resultado_poderes->fetch_assoc()) {
        $poderes_trilha[] = $linha;
    }
}

// Habilidades Iniciais de Classe
$poderes_iniciais_classe = [
    1 => [['nome' => 'Ataque Especial', 'descricao' => 'Quando faz um ataque, você pode gastar 2 PE para receber +5 no teste de ataque ou na rolagem de dano. O bônus aumenta conforme o NEX.']],
    2 => [['nome' => 'Eclético', 'descricao' => 'Quando faz um teste de uma perícia, você pode gastar 2 PE para receber os benefícios de ser treinado nela.'], ['nome' => 'Perito', 'descricao' => 'Escolha duas perícias (exceto Luta e Pontaria). Ao fazer um teste de uma delas, pode gastar 2 PE para somar +1d6 no resultado. O bônus aumenta com o NEX.']],
    3 => [['nome' => 'Escolhido pelo Outro Lado', 'descricao' => 'Você foi marcado pelo Outro Lado e pode lançar rituais de 1º círculo. Você aprende a lançar rituais de círculos maiores conforme avança de NEX.']]
];

// Poderes Gerais de Classe
$poderes_de_classe = [];
$sql_poderes_classe = "SELECT id, classe_id, nex_requerido, nome, descricao AS `desc` FROM poderes_classe";
$resultado_poderes_classe = $conn->query($sql_poderes_classe);
if ($resultado_poderes_classe) {
    while ($linha = $resultado_poderes_classe->fetch_assoc()) {
        $poderes_de_classe[] = $linha;
    }
}

// Poderes Paranormais
$poderes_paranormais = [];
$sql_poderes_paranormais = "SELECT * FROM poderes_paranormais";
$resultado_poderes_paranormais = $conn->query($sql_poderes_paranormais);
if ($resultado_poderes_paranormais) {
    while ($linha = $resultado_poderes_paranormais->fetch_assoc()) {
        $poderes_paranormais[] = $linha;
    }
}

// Rituais
$todos_os_rituais = [];
$sql_rituais = "SELECT * FROM rituais_op ORDER BY circulo, nome";
$resultado_rituais = $conn->query($sql_rituais);
if ($resultado_rituais) {
    while ($linha = $resultado_rituais->fetch_assoc()) {
        $todos_os_rituais[] = $linha;
    }
}

// Poderes Salvos
$poderes_salvos = ['classe' => [], 'paranormal' => []];
if (!$is_new) {
    $sql_poderes_salvos = "SELECT poder_id, tipo_poder FROM personagens_op_poderes WHERE personagem_id = ?";
    $stmt_ps = $conn->prepare($sql_poderes_salvos);
    $stmt_ps->bind_param("i", $id);
    $stmt_ps->execute();
    $res_ps = $stmt_ps->get_result();
    while ($linha = $res_ps->fetch_assoc()) {
        if (isset($poderes_salvos[$linha['tipo_poder']])) {
            $poderes_salvos[$linha['tipo_poder']][] = intval($linha['poder_id']);
        }
    }
    $stmt_ps->close();
}

// Rituais Salvos
$rituais_salvos = [];
if (!$is_new) {
    $sql_rituais_salvos = "SELECT ritual_id FROM personagens_op_rituais WHERE personagem_id = ?";
    $stmt_rs = $conn->prepare($sql_rituais_salvos);
    $stmt_rs->bind_param("i", $id);
    $stmt_rs->execute();
    $res_rs = $stmt_rs->get_result();
    while ($linha = $res_rs->fetch_assoc()) {
        $rituais_salvos[] = intval($linha['ritual_id']);
    }
    $stmt_rs->close();
}


// Perícias
$pericias_agrupadas = [
    'Agilidade' => [['id' => 1, 'nome' => 'Acrobacia'], ['id' => 7, 'nome' => 'Crime', 'so_treinado' => true], ['id' => 11, 'nome' => 'Furtividade'], ['id' => 12, 'nome' => 'Iniciativa'], ['id' => 20, 'nome' => 'Pilotagem', 'so_treinado' => true], ['id' => 21, 'nome' => 'Pontaria'], ['id' => 23, 'nome' => 'Reflexos']],
    'Força' => [['id' => 4, 'nome' => 'Atletismo'], ['id' => 16, 'nome' => 'Luta']],
    'Inteligência' => [['id' => 5, 'nome' => 'Atualidades'], ['id' => 6, 'nome' => 'Ciências', 'so_treinado' => true], ['id' => 14, 'nome' => 'Intuição'], ['id' => 15, 'nome' => 'Investigação'], ['id' => 17, 'nome' => 'Medicina', 'so_treinado' => true], ['id' => 18, 'nome' => 'Ocultismo', 'so_treinado' => true], ['id' => 22, 'nome' => 'Profissão', 'so_treinado' => true], ['id' => 25, 'nome' => 'Sobrevivência'], ['id' => 26, 'nome' => 'Tática', 'so_treinado' => true], ['id' => 27, 'nome' => 'Tecnologia', 'so_treinado' => true]],
    'Presença' => [['id' => 2, 'nome' => 'Adestramento', 'so_treinado' => true], ['id' => 3, 'nome' => 'Artes', 'so_treinado' => true], ['id' => 8, 'nome' => 'Diplomacia'], ['id' => 9, 'nome' => 'Enganação'], ['id' => 13, 'nome' => 'Intimidação'], ['id' => 19, 'nome' => 'Percepção'], ['id' => 24, 'nome' => 'Religião', 'so_treinado' => true], ['id' => 28, 'nome' => 'Vontade', 'so_treinado' => true]],
    'Vigor' => [['id' => 10, 'nome' => 'Fortitude']]
];

//Patentes
$patentes = [
    'Recruta' => ['I' => 2, 'II' => 0, 'III' => 0, 'IV' => 0],
    'Operador' => ['I' => 3, 'II' => 1, 'III' => 0, 'IV' => 0],
    'Agente Especial' => ['I' => 3, 'II' => 2, 'III' => 1, 'IV' => 0],
    'Oficial de Operações' => ['I' => 3, 'II' => 3, 'III' => 2, 'IV' => 1],
    'Agente de Elite' => ['I' => 3, 'II' => 3, 'III' => 3, 'IV' => 2]
];

// Itens
$todos_itens_op = [];
$sql_todos_itens = "SELECT id, nome, tipo_item_id, categoria, espacos, descricao, defesa_bonus FROM itens_op WHERE user_id IS NULL OR user_id = ?";
$stmt_itens = $conn->prepare($sql_todos_itens);
$stmt_itens->bind_param("i", $user_id);
$stmt_itens->execute();
$resultado_todos_itens = $stmt_itens->get_result();
if ($resultado_todos_itens) {
    while ($linha = $resultado_todos_itens->fetch_assoc()) {
        $todos_itens_op[] = $linha;
    }
}
$stmt_itens->close();


// Inventário
$inventario_personagem = [];
if (!$is_new) {
    $sql_inventario = "SELECT i.id, i.nome, i.tipo_item_id, i.categoria, i.espacos, i.defesa_bonus, inv.quantidade 
                       FROM inventario_op inv
                       JOIN itens_op i ON inv.item_id = i.id
                       WHERE inv.personagem_id = ?";
    $stmt_inv = $conn->prepare($sql_inventario);
    $stmt_inv->bind_param("i", $id);
    $stmt_inv->execute();
    $resultado_inventario = $stmt_inv->get_result();
    if ($resultado_inventario) {
        while ($linha = $resultado_inventario->fetch_assoc()) {
            $inventario_personagem[] = $linha;
        }
    }
    $stmt_inv->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Personagem - Ordem Paranormal</title>
    <link rel="stylesheet" href="../static/ficha_op.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <form id="ficha-form" method="POST" action="../conection/save_character.php" enctype="multipart/form-data">
            <input type="hidden" name="personagem_id" value="<?= $personagem['id'] ?>">

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

                <div class="coluna-direita">
                    <nav class="abas-nav">
                        <button type="button" class="tab-button active" data-tab="tab-atributos">Atributos & Perícias</button>
                        <button type="button" class="tab-button" data-tab="tab-poderes">Poderes</button>
                        <button type="button" class="tab-button" data-tab="tab-rituais" id="tab-btn-rituais" style="display: none;">Rituais</button>
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

                    <!-- ABA PODERES -->
                    <div id="tab-poderes" class="tab-content">

                        <div class="info-poderes">
                            <div>
                                <label for="classe-select">Classe</label>
                                <select id="classe-select" name="classe_id">
                                    <?php foreach ($classes as $id_classe => $classe): ?>
                                        <option value="<?= $id_classe ?>" <?= ($personagem['classe_id'] == $id_classe) ? 'selected' : '' ?>>
                                            <?= $classe['nome'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="origem-select">Origem</label>
                                <select id="origem-select" name="origem_id">
                                    <option value="0">Selecione...</option>
                                    <?php foreach ($origens as $id_origem => $origem): ?>
                                        <option value="<?= $id_origem ?>" <?= ($personagem['origem_id'] == $id_origem) ? 'selected' : '' ?>>
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
                                        <option value="<?= $trilha['id'] ?>" data-classe-id="<?= $trilha['classe_id'] ?>" <?= (isset($personagem['trilha_id']) && $personagem['trilha_id'] == $trilha['id']) ? 'selected' : '' ?>>
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

                        <!-- Estrutura Organizada -->
                        <div id="poder-origem-container" class="poderes-secao">
                            <h3>Poder de Origem</h3>
                            <div class="poder-item">
                                <h4 id="poder-origem-titulo">Poder de Origem</h4>
                                <p id="poder-origem-display">Selecione uma origem para ver o poder correspondente.</p>
                            </div>
                        </div>

                        <div id="poderes-iniciais-container" class="poderes-secao">
                            <h3>Habilidades de Classe</h3>
                        </div>

                        <div id="poderes-trilha-container" class="poderes-secao">
                            <h3>Habilidades de Trilha</h3>
                        </div>

                        <div id="poderes-classe-container" class="poderes-secao">
                            <h3>Poderes de Classe</h3>
                            <p>Poderes que você escolheu ao avançar de NEX.</p>
                            <button type="button" class="btn-acao" id="btn-adicionar-poder" style="margin-top: 15px;">Adicionar Poder de Classe</button>
                        </div>

                        <div id="poderes-paranormais-container" class="poderes-secao">
                            <h3>Poderes Paranormais</h3>
                            <p>Poderes adquiridos ao Transcender.</p>
                        </div>

                    </div>
                    <!-- FIM DA ABA PODERES -->

                    <!-- ABA RITUAIS -->
                    <div id="tab-rituais" class="tab-content">
                        <h2>Rituais</h2>

                        <div class="ritual-limites-display">
                            <div class="limite-box">
                                <span>Total de Rituais</span>
                                <strong id="rituais-conhecidos">0/0</strong>
                            </div>
                            <div class="limite-box">
                                <span>Círculo Máx.</span>
                                <strong id="rituais-circulo-max">1º</strong>
                            </div>
                            <div class="limite-box">
                                <span>DT Rituais</span>
                                <strong id="rituais-dt-display">--</strong>
                            </div>
                        </div>

                        <div id="rituais-container" class="poderes-secao">
                            <!-- JS vai inserir os rituais aprendidos aqui, organizados por círculo -->
                        </div>

                        <button type="button" class="btn-acao" id="btn-abrir-modal-ritual" style="margin-top: 15px;">Aprender Ritual</button>
                    </div>
                    <!-- FIM DA ABA RITUAIS -->


                    <!-- ABA EQUIPAMENTO -->
                    <div id="tab-equipamento" class="tab-content">
                        <div class="info-equipamento">
                            <div class="patente-container">
                                <label for="patente-select">Patente</label>
                                <select id="patente-select" name="patente">
                                    <?php foreach (array_keys($patentes) as $patente) : ?>
                                        <option value="<?= $patente ?>" <?= ($personagem['patente'] == $patente) ? 'selected' : '' ?>>
                                            <?= $patente ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="espacos-container">
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
                        <div class="lista-itens" id="lista-itens-personagem">
                            <div class="item-row header">
                                <div class="item-nome">Item</div>
                                <div class="item-cat">Cat.</div>
                                <div class="item-esp">Esp.</div>
                                <div class="item-acoes">Ações</div>
                            </div>
                        </div>
                        <button type="button" class="btn-acao" id="btn-abrir-modal-item" style="margin-top: 20px;">Adicionar Item</button>
                    </div>

                    <!-- RODAPE -->
                    <div class="botoes-rodape">
                        <a href="meus_personagens.php" class="btn-acao btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn-acao">
                            <i class="fas fa-save"></i> Salvar Personagem
                        </button>
                    </div>
                </div> <!-- Fim da coluna-direita -->
            </div> <!-- Fim da ficha-grid -->
        </form> <!-- Fim do form -->
    </div> <!-- Fim do container -->

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
            </div>
            <button type="button" class="btn-acao" onclick="fecharModalAdicionarItem()" style="margin-top: 20px;">Fechar</button>
        </div>
    </div>

    <div id="modal-adicionar-ritual" class="modal-overlay">
        <div class="modal-content modal-itens">
            <h2>Aprender Ritual</h2>
            <div class="modal-filtros">
                <input type="text" id="filtro-ritual-nome" placeholder="Buscar por nome...">
                <div class="modal-botoes-filtro" id="filtro-ritual-circulo">
                    <!-- JS vai popular -->
                </div>
            </div>
            <div id="lista-rituais-modal" class="lista-modal">
                <!-- JS vai popular -->
            </div>
            <button type="button" class="btn-acao" onclick="fecharModalAdicionarRitual()" style="margin-top: 20px;">Fechar</button>
        </div>
    </div>


    <!-- *** SCRIPT CORRIGIDO E UNIFICADO *** -->
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
            const poderesSalvos = <?= json_encode(isset($poderes_salvos) ? $poderes_salvos : ['classe' => [], 'paranormal' => []]) ?>;
            const todosOsRituais = <?= json_encode(isset($todos_os_rituais) ? $todos_os_rituais : []) ?>;
            const rituaisSalvos = <?= json_encode(isset($rituais_salvos) ? $rituais_salvos : []) ?>;


            // --- ELEMENTOS GLOBAIS ---
            const form = document.getElementById('ficha-form');
            if (!form) return;
            const inputsParaMonitorar = form.querySelectorAll('input.atributo-input, select.atributo-input, #classe-select, #origem-select, #trilha-select, #patente-select');
            const modalPoderesClasse = document.getElementById('modal-poderes-classe');
            const modalTranscender = document.getElementById('modal-transcender');
            const modalAdicionarItem = document.getElementById('modal-adicionar-item');
            const modalAdicionarRitual = document.getElementById('modal-adicionar-ritual');
            const tabBtnRituais = document.getElementById('tab-btn-rituais');
            const dtRituaisContainer = document.getElementById('dt-rituais-container');
            const rituaisContainer = document.getElementById('rituais-container');

            // --- ARRAYS DE ESTADO GLOBAL ---
            let transcendCount = 0;
            let poderesParanormaisSelecionados = [...(poderesSalvos.paranormal || [])];
            let poderesDeClasseSelecionados = [...(poderesSalvos.classe || [])];
            let classeIdAnterior = parseInt(document.getElementById('classe-select').value) || 0;
            let inventarioAtual = [...inventarioInicialPersonagem];
            let rituaisSelecionados = [...rituaisSalvos];

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
                const displayTitle = document.getElementById('poder-origem-titulo');
                if (!origemSelect || !displayContainer || !displayTitle) return;

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

                    const trilhaValida = trilhasDisponiveis.some(t => t.id == trilhaSelecionadaAnteriormente);
                    if (trilhaValida) {
                        trilhaSelect.value = trilhaSelecionadaAnteriormente;
                    } else {
                        trilhaSelect.value = '0';
                    }

                    trilhaSelect.disabled = false;
                } else {
                    trilhaSelect.add(new Option('Apenas em NEX 10%+', '0'));
                    trilhaSelect.disabled = true;
                }
            }


            function atualizarPoderesDaTrilha() {
                const nex = parseInt(document.getElementById('nex').value) || 0;
                const trilhaId = parseInt(document.getElementById('trilha-select').value) || 0;
                const listaPoderesFicha = document.getElementById('poderes-trilha-container');
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
                const listaPoderesFicha = document.getElementById('poderes-iniciais-container');
                if (!listaPoderesFicha) return;

                listaPoderesFicha.querySelectorAll('.poder-inicial-item').forEach(item => item.remove());

                if (classeId !== classeIdAnterior) {
                    document.getElementById('poderes-classe-container').querySelectorAll('.poder-classe-adicionado').forEach(item => item.remove());
                    document.getElementById('poderes-paranormais-container').querySelectorAll('.poder-paranormal-item').forEach(item => item.remove());
                    document.getElementById('rituais-container').querySelectorAll('.ritual-card').forEach(item => item.remove());

                    transcendCount = 0;
                    poderesParanormaisSelecionados = [];
                    poderesDeClasseSelecionados = [];
                    rituaisSelecionados = [];
                    classeIdAnterior = classeId;
                }

                const poderesIniciais = poderesIniciaisClasse[classeId];
                if (poderesIniciais) {
                    poderesIniciais.forEach(poder => {
                        const poderDiv = document.createElement('div');
                        poderDiv.className = 'poder-item poder-inicial-item';
                        poderDiv.innerHTML = `<h4>${poder.nome} (Habilidade de Classe)</h4><p>${poder.descricao}</p>`;
                        listaPoderesFicha.appendChild(poderDiv);
                    });
                }
            }

            function renderizarPoderesSalvos() {
                const listaClasse = document.getElementById('poderes-classe-container');
                const listaParanormal = document.getElementById('poderes-paranormais-container');
                if (!listaClasse || !listaParanormal) return;

                let countTranscender = 0;

                poderesDeClasseSelecionados.forEach(poderId => {
                    const poderInfo = todosOsPoderesDeClasse.find(p => p.id == poderId);
                    if (poderInfo) {
                        if (poderInfo.nome === 'Transcender') {
                            countTranscender++;
                        }
                        const poderDiv = document.createElement('div');
                        poderDiv.className = 'poder-item poder-classe-adicionado';
                        poderDiv.innerHTML = `
                            <button type="button" class="btn-remover-poder" onclick="removerPoderDeClasse(${poderId}, this)">X</button>
                            <h4>${poderInfo.nome} (Classe)</h4>
                            <p>${poderInfo.desc}</p>`;
                        listaClasse.appendChild(poderDiv);
                    }
                });

                poderesParanormaisSelecionados.forEach(poderId => {
                    const poderInfo = todosOsPoderesParanormais.find(p => p.id == poderId);
                    if (poderInfo) {
                        const poderDiv = document.createElement('div');
                        poderDiv.className = 'poder-item poder-paranormal-item';
                        poderDiv.innerHTML = `
                            <button type="button" class="btn-remover-poder" onclick="removerPoderParanormal(${poderId}, this)">X</button>
                            <h4>${poderInfo.nome} (Paranormal)</h4>
                            <p>${poderInfo.descricao}</p>`;
                        listaParanormal.appendChild(poderDiv);
                    }
                });

                transcendCount = poderesParanormaisSelecionados.length;
            }

            // --- FUNÇÕES DE ADICIONAR/REMOVER PODERES ---

            window.abrirModalPoderesDeClasse = function() {
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
                    const jaAdicionado = poderesDeClasseSelecionados.includes(parseInt(poder.id));

                    if (poder.nome === 'Transcender') {
                        poderDiv.className = 'poder-item-modal poder-transcender-opcao';
                        botaoHTML = `<button type="button" onclick="abrirModalTranscender()">Escolher</button>`;
                    } else {
                        poderDiv.className = 'poder-item-modal';
                        botaoHTML = `<button type="button" onclick="adicionarPoderDeClasse(${poder.id})" ${jaAdicionado ? 'disabled' : ''}>
                                        ${jaAdicionado ? 'Adicionado' : 'Adicionar'}
                                    </button>`;
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
                    const jaAdicionado = poderesParanormaisSelecionados.includes(parseInt(poder.id));
                    const poderCard = document.createElement('div');
                    poderCard.className = `poder-paranormal-card ${poder.elemento} ${jaAdicionado ? 'disabled' : ''}`;
                    if (!jaAdicionado) {
                        poderCard.onclick = () => selecionarPoderParanormal(poder.id);
                    }
                    poderCard.innerHTML = `<h4>${poder.nome}</h4><p>${poder.descricao}</p> ${jaAdicionado ? '<span>(Adicionado)</span>' : ''}`;
                    listaTranscender.appendChild(poderCard);
                });
                fecharModalPoderes();
                modalTranscender.style.display = 'flex';
            }

            window.selecionarPoderParanormal = function(poderId) {
                const poderInfo = todosOsPoderesParanormais.find(p => p.id == poderId);
                if (!poderInfo || poderesParanormaisSelecionados.includes(poderId)) return;

                const listaPoderesFicha = document.getElementById('poderes-paranormais-container');
                const poderDiv = document.createElement('div');
                poderDiv.className = 'poder-item poder-paranormal-item';
                poderDiv.innerHTML = `
                    <button type="button" class="btn-remover-poder" onclick="removerPoderParanormal(${poderId}, this)">X</button>
                    <h4>${poderInfo.nome} (Paranormal)</h4>
                    <p>${poderInfo.descricao}</p>`;
                listaPoderesFicha.appendChild(poderDiv);

                poderesParanormaisSelecionados.push(parseInt(poderId));
                transcendCount++;

                const poderClasseTranscender = todosOsPoderesDeClasse.find(p => p.nome === 'Transcender');
                if (poderClasseTranscender) {
                    adicionarPoderDeClasse(poderClasseTranscender.id, true); // true = silencioso
                }

                fecharModalTranscender();
                calcularTudo();
            }

            window.adicionarPoderDeClasse = function(poderId, silencioso = false) {
                const poderInfo = todosOsPoderesDeClasse.find(p => p.id == poderId);
                if (!poderInfo) return;

                const poderIdNumerico = parseInt(poderId);

                if (poderInfo.nome !== 'Transcender' && poderesDeClasseSelecionados.includes(poderIdNumerico)) {
                    if (!silencioso) fecharModalPoderes();
                    return;
                }

                const listaPoderesFicha = document.getElementById('poderes-classe-container');

                // Adiciona o ID ao array
                // Trata o Transcender de forma especial: só adiciona o poder de classe na primeira vez
                if (poderInfo.nome === 'Transcender') {
                    if (transcendCount === 1) { // Só adiciona o poder de classe "Transcender" na primeira vez
                        poderesDeClasseSelecionados.push(poderIdNumerico);
                    } else if (transcendCount > 1) {
                        // Já tem, não faz nada
                    } else {
                        // Adicionando manualmente (sem poder paranormal)
                        poderesDeClasseSelecionados.push(poderIdNumerico);
                    }
                } else {
                    poderesDeClasseSelecionados.push(poderIdNumerico);
                }

                // Renderiza na tela
                const poderDiv = document.createElement('div');
                poderDiv.className = 'poder-item poder-classe-adicionado';
                poderDiv.innerHTML = `
                    <button type="button" class="btn-remover-poder" onclick="removerPoderDeClasse(${poderIdNumerico}, this)">X</button>
                    <h4>${poderInfo.nome} (Classe)</h4>
                    <p>${poderInfo.desc}</p>`;
                listaPoderesFicha.appendChild(poderDiv);

                if (!silencioso) {
                    fecharModalPoderes();
                }
                calcularTudo();
            }

            window.removerPoderDeClasse = function(poderId, botao) {
                const index = poderesDeClasseSelecionados.indexOf(parseInt(poderId));
                if (index > -1) {
                    const poderInfo = todosOsPoderesDeClasse.find(p => p.id == poderId);
                    if (poderInfo && poderInfo.nome === 'Transcender') {
                        poderesParanormaisSelecionados = [];
                        document.querySelectorAll('.poder-paranormal-item').forEach(el => el.remove());
                        transcendCount = 0;
                        poderesDeClasseSelecionados.splice(index, 1);
                    } else {
                        poderesDeClasseSelecionados.splice(index, 1);
                    }
                }
                botao.parentElement.remove();
                calcularTudo();
            }

            window.removerPoderParanormal = function(poderId, botao) {
                const index = poderesParanormaisSelecionados.indexOf(parseInt(poderId));
                if (index > -1) {
                    poderesParanormaisSelecionados.splice(index, 1);
                    transcendCount--;

                    if (poderesParanormaisSelecionados.length === 0) {
                        const poderClasseTranscender = todosOsPoderesDeClasse.find(p => p.nome === 'Transcender');
                        const indexTranscender = poderesDeClasseSelecionados.indexOf(parseInt(poderClasseTranscender.id));
                        if (indexTranscender > -1) {
                            poderesDeClasseSelecionados.splice(indexTranscender, 1);
                            document.querySelectorAll('.poder-classe-adicionado').forEach(el => {
                                if (el.querySelector('h4').innerText.includes('Transcender')) {
                                    el.remove();
                                }
                            });
                        }
                    }
                }
                botao.parentElement.remove();
                calcularTudo();
            }

            window.fecharModalPoderes = () => {
                if (modalPoderesClasse) modalPoderesClasse.style.display = 'none';
            }

            window.fecharModalTranscender = () => {
                if (modalTranscender) modalTranscender.style.display = 'none';
            }

            // --- LÓGICA DE INVENTÁRIO ---

            function atualizarDisplayInventario() {
                const containerInventario = document.getElementById('lista-itens-personagem');
                if (!containerInventario) return;

                while (containerInventario.children.length > 1) {
                    containerInventario.removeChild(containerInventario.lastChild);
                }

                let espacosUsados = 0;
                inventarioAtual.forEach((item, index) => {
                    const itemRow = document.createElement('div');
                    itemRow.className = 'item-row';
                    itemRow.innerHTML = `
                        <div class="item-nome">${item.nome}</div>
                        <div class="item-cat">${item.categoria}</div>
                        <div class="item-esp">${item.espacos}</div>
                        <div class="item-acoes">
                            <button type="button" class="btn-remover-item" onclick="removerItemDoInventario(${index})">X</button>
                        </div>`;
                    containerInventario.appendChild(itemRow);
                    espacosUsados += parseInt(item.espacos) || 0;
                });
                document.getElementById('espacos-usados-display').textContent = espacosUsados;
            }

            window.adicionarItemAoInventario = (itemId) => {
                const itemInfoCompleto = todosOsItensOP.find(i => i.id == itemId);
                if (itemInfoCompleto) {
                    inventarioAtual.push(JSON.parse(JSON.stringify(itemInfoCompleto)));
                    calcularTudo();
                    atualizarDisplayInventario()
                    fecharModalAdicionarItem();
                } else {
                    console.error("Item não encontrado:", itemId);
                }
            };

            window.removerItemDoInventario = (index) => {
                inventarioAtual.splice(index, 1);
                atualizarDisplayInventario();
                calcularTudo();
            };

            function popularModalItens() {
                const containerModal = document.getElementById('lista-itens-modal');
                if (!containerModal) return;
                const termoBusca = document.getElementById('filtro-item-nome').value.toLowerCase();
                const tipoIdFiltro = document.querySelector('.filtro-tipo-item.active').dataset.tipoId;
                containerModal.innerHTML = '';
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
                        <button type="button" class="btn-adicionar" onclick="adicionarItemAoInventario(${item.id})">Adicionar</button>`;
                    containerModal.appendChild(itemDiv);
                });
            }

            window.abrirModalAdicionarItem = () => {
                if (modalAdicionarItem) {
                    popularModalItens();
                    modalAdicionarItem.style.display = 'flex';
                }
            };
            window.fecharModalAdicionarItem = () => {
                if (modalAdicionarItem) modalAdicionarItem.style.display = 'none';
            };

            // --- *** INÍCIO: NOVAS FUNÇÕES DE RITUAIS *** ---

            // *** NOVO: Renderiza rituais salvos ao carregar ***
            function renderizarRituaisSalvos() {
                if (!rituaisContainer) return;
                rituaisContainer.innerHTML = ''; // Limpa

                let rituaisPorCirculo = {
                    1: [],
                    2: [],
                    3: [],
                    4: []
                };

                rituaisSelecionados.forEach(ritualId => {
                    const ritualInfo = todosOsRituais.find(r => r.id == ritualId);
                    if (ritualInfo) {
                        if (!rituaisPorCirculo[ritualInfo.circulo]) {
                            rituaisPorCirculo[ritualInfo.circulo] = [];
                        }
                        rituaisPorCirculo[ritualInfo.circulo].push(ritualInfo);
                    }
                });

                // Ordena os círculos
                Object.keys(rituaisPorCirculo).sort().forEach(circulo => {
                    if (rituaisPorCirculo[circulo].length > 0) {
                        const h3 = document.createElement('h3');
                        h3.textContent = `Círculo ${circulo}`;
                        rituaisContainer.appendChild(h3);

                        rituaisPorCirculo[circulo].forEach(ritualInfo => {
                            const ritualDiv = document.createElement('div');
                            // *** CORREÇÃO AQUI: Adiciona a classe do elemento ***
                            ritualDiv.className = 'poder-item ritual-card ' + (ritualInfo.elemento || 'Nenhum');
                            ritualDiv.innerHTML = `
                                <button type="button" class="btn-remover-poder" onclick="removerRitual(${ritualInfo.id}, this)">X</button>
                                <h4>${ritualInfo.nome} (${ritualInfo.elemento})</h4>
                                <p><strong>Execução:</strong> ${ritualInfo.execucao} | <strong>Alcance:</strong> ${ritualInfo.alcance}</p>
                                <p>${ritualInfo.descricao}</p>`;
                            rituaisContainer.appendChild(ritualDiv);
                        });
                    }
                });
            }

            // *** NOVO: Abre o modal de Rituais ***
            window.abrirModalAdicionarRitual = function() {
                if (!modalAdicionarRitual) return;

                const nex = parseInt(document.getElementById('nex').value) || 0;
                const {
                    maxCirculo
                } = calcularLimitesRituais(nex);

                const filtroContainer = document.getElementById('filtro-ritual-circulo');
                filtroContainer.innerHTML = '<button type="button" class="filtro-tipo-item active" data-circulo="0">Todos</button>';
                for (let i = 1; i <= maxCirculo; i++) {
                    filtroContainer.innerHTML += `<button type="button" class="filtro-tipo-item" data-circulo="${i}">${i}º Círculo</button>`;
                }

                filtroContainer.querySelectorAll('.filtro-tipo-item').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        filtroContainer.querySelector('.filtro-tipo-item.active').classList.remove('active');
                        e.target.classList.add('active');
                        popularModalRituais();
                    });
                });

                popularModalRituais();
                modalAdicionarRitual.style.display = 'flex';
            }

            // *** NOVO: Popula o modal de Rituais ***
            function popularModalRituais() {
                const containerModal = document.getElementById('lista-rituais-modal');
                if (!containerModal) return;

                const termoBusca = document.getElementById('filtro-ritual-nome').value.toLowerCase();
                const circuloFiltro = document.querySelector('#filtro-ritual-circulo .filtro-tipo-item.active').dataset.circulo;
                const nex = parseInt(document.getElementById('nex').value) || 0;
                const {
                    totalPermitido,
                    maxCirculo
                } = calcularLimitesRituais(nex);

                const podeAprenderMais = rituaisSelecionados.length < totalPermitido;

                containerModal.innerHTML = '';

                const rituaisFiltrados = todosOsRituais.filter(ritual => {
                    const nomeMatch = ritual.nome.toLowerCase().includes(termoBusca);
                    const circuloMatch = (circuloFiltro == "0" || ritual.circulo == circuloFiltro);
                    const circuloPermitido = ritual.circulo <= maxCirculo;
                    return nomeMatch && circuloMatch && circuloPermitido;
                });

                rituaisFiltrados.forEach(ritual => {
                    const jaAdicionado = rituaisSelecionados.includes(parseInt(ritual.id));
                    const ritualDiv = document.createElement('div');
                    ritualDiv.className = 'poder-item-modal'; // Reusa estilo
                    ritualDiv.innerHTML = `
                        <div>
                            <h4>${ritual.nome} (${ritual.circulo}º Círculo - ${ritual.elemento})</h4>
                            <p>${ritual.descricao.substring(0, 100)}...</p>
                        </div>
                        <button type="button" class="btn-adicionar" 
                                onclick="adicionarRitual(${ritual.id})" 
                                ${jaAdicionado || !podeAprenderMais ? 'disabled' : ''}>
                            ${jaAdicionado ? 'Aprendido' : (podeAprenderMais ? 'Aprender' : 'Limite Atingido')}
                        </button>`;
                    containerModal.appendChild(ritualDiv);
                });
            }

            // *** NOVO: Adiciona um Ritual ***
            window.adicionarRitual = function(ritualId) {
                const ritualIdNum = parseInt(ritualId);
                if (!rituaisSelecionados.includes(ritualIdNum)) {
                    rituaisSelecionados.push(ritualIdNum);
                    renderizarRituaisSalvos();
                    calcularTudo();
                    popularModalRituais();
                }
            }

            // *** NOVO: Remove um Ritual ***
            window.removerRitual = function(ritualId, botao) {
                const index = rituaisSelecionados.indexOf(parseInt(ritualId));
                if (index > -1) {
                    rituaisSelecionados.splice(index, 1);
                }
                botao.parentElement.remove();
                calcularTudo();
            }

            // *** NOVO: Fecha o modal de Rituais ***
            window.fecharModalAdicionarRitual = () => {
                if (modalAdicionarRitual) modalAdicionarRitual.style.display = 'none';
            }

            // *** NOVO: Calcula limites de rituais ***
            function calcularLimitesRituais(nex) {
                const nivel = Math.floor(nex / 5);
                const totalPermitido = 2 + nivel; // Regra: 3 no nível 1 (3 = 2+1), +1 por nível

                let maxCirculo = 1;
                if (nex >= 85) maxCirculo = 4;
                else if (nex >= 55) maxCirculo = 3;
                else if (nex >= 20) maxCirculo = 2;

                return {
                    totalPermitido,
                    maxCirculo
                };
            }

            // --- *** FIM: NOVAS FUNÇÕES DE RITUAIS *** ---


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

                // CÁLCULO DE VIDA
                let vidaMax = parseInt(classeAtual.pv_inicial) + (atributos.vigor * niveis) + (parseInt(classeAtual.pv_por_nivel) * niveisAposPrimeiro);
                if (origemId == 9) {
                    vidaMax += niveis;
                } // Desgarrado
                if (parseInt(trilhaId) === 5) {
                    vidaMax += niveis;
                } // Tropa de Choque (Casca Grossa)
                if (poderesParanormaisSelecionados.includes(3)) {
                    vidaMax += (niveis * 2);
                } // Sangue de Ferro

                // CÁLCULO DE PE
                let peMax = parseInt(classeAtual.pe_inicial) + (atributos.presenca * niveis) + (parseInt(classeAtual.pe_por_nivel) * niveisAposPrimeiro);
                if (poderesParanormaisSelecionados.includes(13)) {
                    peMax += niveis;
                } // Potencial Aprimorado

                // CÁLCULO DE SANIDADE
                let sanidadeMax = parseInt(classeAtual.san_inicial) + (parseInt(classeAtual.san_por_nivel) * (niveisAposPrimeiro - transcendCount));
                if (origemId == 24) {
                    sanidadeMax += niveis;
                } // Vítima

                //CÁLCULO DE DEFESA
                let defesaTotal = 10 + atributos.agilidade;
                if (origemId == 16) {
                    defesaTotal += 2;
                } // Policial
                if (poderesDeClasseSelecionados.includes(13)) {
                    defesaTotal += 2;
                } // Reflexos Defensivos

                if (typeof inventarioAtual !== 'undefined' && Array.isArray(inventarioAtual)) {
                    inventarioAtual.forEach((item) => {
                        if (item && item.tipo_item_id == 2 && item.hasOwnProperty('defesa_bonus') && item.defesa_bonus !== null && item.defesa_bonus !== '') {
                            const bonusDef = parseInt(item.defesa_bonus);
                            if (!isNaN(bonusDef)) {
                                defesaTotal += bonusDef;
                            }
                        }
                    });
                }

                // CÁLCULOS DE INVENTÁRIO
                const temInventarioOtimizado = todosOsPoderesDeTrilha.some(p => p.trilha_id == trilhaId && p.nex_requerido <= nex && p.id == 37);
                const temMochilaMilitar = inventarioAtual.some(item => item.id == 63); // ID 63 = Mochila Militar
                let espacosTotal;
                if (temInventarioOtimizado) {
                    espacosTotal = (atributos.forca + atributos.intelecto) * 5;
                } else {
                    espacosTotal = (atributos.forca == 0) ? 2 : (5 * atributos.forca);
                }
                if (temMochilaMilitar) {
                    espacosTotal += 2;
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

                // *** ATUALIZAÇÃO: Lógica de Rituais (DT e Visibilidade) ***
                if (classeId === 3) { // Se for Ocultista (ID 3)
                    dtRituaisContainer.style.display = 'block';
                    tabBtnRituais.style.display = 'inline-block';

                    let dtRituais = 10 + atributos.presenca + Math.floor(nex / 5);
                    if (parseInt(trilhaId) === 13 && nex >= 65) { // Graduado 65%
                        dtRituais += 5;
                    }
                    document.getElementById('dt-rituais-span').textContent = dtRituais;
                    document.getElementById('rituais-dt-display').textContent = dtRituais;

                    // Atualiza contadores de Rituais
                    const {
                        totalPermitido,
                        maxCirculo
                    } = calcularLimitesRituais(nex);
                    document.getElementById('rituais-conhecidos').textContent = `${rituaisSelecionados.length}/${totalPermitido}`;
                    document.getElementById('rituais-circulo-max').textContent = `${maxCirculo}º`;

                } else {
                    dtRituaisContainer.style.display = 'none';
                    tabBtnRituais.style.display = 'none';
                    if (tabBtnRituais.classList.contains('active')) {
                        tabButtons.forEach(btn => btn.classList.remove('active'));
                        tabContents.forEach(content => content.classList.remove('active'));
                        document.querySelector('.tab-button[data-tab="tab-atributos"]').classList.add('active');
                        document.getElementById('tab-atributos').classList.add('active');
                    }
                }

                // ATUALIZAÇÕES DA UI
                atualizarPoderOrigem();
                atualizarTrilhasDisponiveis();
                atualizarPoderesDaTrilha();
                atualizarPoderesIniciaisDeClasse();
            }


            // --- *** LÓGICA DE ENVIO DO FORMULÁRIO (ATUALIZADA) *** ---
            form.addEventListener('submit', function(e) {

                form.querySelectorAll('input[name="poderes_classe[]"], input[name="poderes_paranormais[]"], input[name="inventario_ids[]"], input[name="rituais_ids[]"]').forEach(input => input.remove());

                if (typeof poderesDeClasseSelecionados !== 'undefined' && Array.isArray(poderesDeClasseSelecionados)) {
                    poderesDeClasseSelecionados.forEach(poderId => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'poderes_classe[]';
                        input.value = poderId;
                        form.appendChild(input);
                    });
                }

                if (typeof poderesParanormaisSelecionados !== 'undefined' && Array.isArray(poderesParanormaisSelecionados)) {
                    poderesParanormaisSelecionados.forEach(poderId => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'poderes_paranormais[]';
                        input.value = poderId;
                        form.appendChild(input);
                    });
                }

                if (typeof inventarioAtual !== 'undefined' && Array.isArray(inventarioAtual)) {
                    inventarioAtual.forEach(item => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'inventario_ids[]';
                        input.value = item.id;
                        form.appendChild(input);
                    });
                }

                // *** NOVO: Adiciona os Rituais ***
                if (typeof rituaisSelecionados !== 'undefined' && Array.isArray(rituaisSelecionados)) {
                    rituaisSelecionados.forEach(ritualId => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'rituais_ids[]';
                        input.value = ritualId;
                        form.appendChild(input);
                    });
                }

                return true;
            });


            // --- EVENT LISTENERS E INICIALIZAÇÃO ---
            inputsParaMonitorar.forEach(input => input.addEventListener('change', calcularTudo));

            document.getElementById('classe-select')?.addEventListener('change', () => {
                calcularTudo();
            });

            document.getElementById('btn-adicionar-poder')?.addEventListener('click', abrirModalPoderesDeClasse);

            // Listeners de Itens
            document.getElementById('btn-abrir-modal-item')?.addEventListener('click', abrirModalAdicionarItem);
            document.getElementById('filtro-item-nome')?.addEventListener('input', popularModalItens);
            document.querySelectorAll('.filtro-tipo-item').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelector('.filtro-tipo-item.active').classList.remove('active');
                    e.target.classList.add('active');
                    popularModalItens();
                });
            });

            document.getElementById('btn-abrir-modal-ritual')?.addEventListener('click', abrirModalAdicionarRitual);
            document.getElementById('filtro-ritual-nome')?.addEventListener('input', popularModalRituais);

            // --- INICIALIZAÇÃO DA FICHA ---
            atualizarDisplayInventario();
            renderizarPoderesSalvos();
            renderizarRituaisSalvos(); // *** NOVO ***
            calcularTudo();

        }); // <-- FIM DO DOMCONTENTLOADED
    </script>
</body>

</html>