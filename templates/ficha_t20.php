<?php
// ficha_t20.php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login/login.php"); // Ajuste o caminho se necessário
    exit();
}

require_once '../conection/db_connect.php'; // Ajuste o caminho se necessário
$user_id = $_SESSION['user_id'];

// Pega o ID da URL. Se for 0, é um personagem novo.
$personagem_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$personagem_t20 = null;
$poderes_personagem_t20 = array();
$inventario_personagem_t20 = array();

if ($personagem_id > 0) {
    // --- MODO DE EDIÇÃO (CARREGAR DADOS) ---

    // 1. Carregar dados principais
    $stmt = $conn->prepare("SELECT * FROM personagens_t20 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $personagem_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $personagem_t20 = $result->fetch_assoc();
    } else {
        // Não achou ou não pertence ao usuário
        echo "Personagem não encontrado ou não autorizado.";
        exit();
    }
    $stmt->close();

    // 2. Carregar poderes escolhidos
    $stmt_poderes = $conn->prepare("SELECT poder_id, tipo_poder FROM personagem_t20_poderes WHERE personagem_id = ?");
    $stmt_poderes->bind_param("i", $personagem_id);
    $stmt_poderes->execute();
    $result_poderes = $stmt_poderes->get_result();
    $poderes_personagem_t20 = $result_poderes->fetch_all(MYSQLI_ASSOC);
    $stmt_poderes->close();

    // 3. Carregar inventário (precisa do JOIN para ter os dados do item)
    $stmt_inv = $conn->prepare(
        "SELECT inv.item_id as id, inv.quantidade, inv.equipado, item.nome, item.espacos, item.bonus_carga, item.tipo, item.bonus_defesa, item.penalidade_armadura 
         FROM personagem_t20_inventario AS inv 
         JOIN t20_itens AS item ON inv.item_id = item.id 
         WHERE inv.personagem_id = ?"
    );
    $stmt_inv->bind_param("i", $personagem_id);
    $stmt_inv->execute();
    $result_inv = $stmt_inv->get_result();
    $inventario_personagem_t20 = $result_inv->fetch_all(MYSQLI_ASSOC);
    $stmt_inv->close();
} else {
    // --- MODO DE CRIAÇÃO (DADOS EM BRANCO) ---
    // (Este é o array que corrige o erro de 'tibares' e outros)
    $personagem_t20 = array(
        'id' => 0, // Importante para o save_character
        'user_id' => $user_id,
        'nome' => 'Novo Personagem T20',
        'nivel' => 1,
        'raca_id' => null,
        'origem_id' => null,
        'classe_id' => null,
        'divindade_id' => null,
        'imagem' => 'default_t20.jpg',
        'pv_max' => 0,
        'pv_atual' => 0,
        'pm_max' => 0,
        'pm_atual' => 0,
        'tibares' => 0,
        'carga_maxima' => 0,
        'forca' => 0,
        'destreza' => 0,
        'constituicao' => 0,
        'inteligencia' => 0,
        'sabedoria' => 0,
        'carisma' => 0,
        // Adiciona valores padrão para todas as perícias (para evitar erros de 'Undefined index')
        'treino_acrobacia' => 0,
        'outros_acrobacia' => 0,
        'treino_adestramento' => 0,
        'outros_adestramento' => 0,
        'treino_atletismo' => 0,
        'outros_atletismo' => 0,
        'treino_atuacao' => 0,
        'outros_atuacao' => 0,
        'treino_cavalgar' => 0,
        'outros_cavalgar' => 0,
        'treino_conhecimento' => 0,
        'outros_conhecimento' => 0,
        'treino_cura' => 0,
        'outros_cura' => 0,
        'treino_diplomacia' => 0,
        'outros_diplomacia' => 0,
        'treino_enganacao' => 0,
        'outros_enganacao' => 0,
        'treino_fortitude' => 0,
        'outros_fortitude' => 0,
        'treino_furtividade' => 0,
        'outros_furtividade' => 0,
        'treino_guerra' => 0,
        'outros_guerra' => 0,
        'treino_iniciativa' => 0,
        'outros_iniciativa' => 0,
        'treino_intimidacao' => 0,
        'outros_intimidacao' => 0,
        'treino_intuicao' => 0,
        'outros_intuicao' => 0,
        'treino_investigacao' => 0,
        'outros_investigacao' => 0,
        'treino_jogatina' => 0,
        'outros_jogatina' => 0,
        'treino_ladinagem' => 0,
        'outros_ladinagem' => 0,
        'treino_luta' => 0,
        'outros_luta' => 0,
        'treino_misticismo' => 0,
        'outros_misticismo' => 0,
        'treino_nobreza' => 0,
        'outros_nobreza' => 0,
        'treino_oficio' => 0,
        'outros_oficio' => 0,
        'treino_percepcao' => 0,
        'outros_percepcao' => 0,
        'treino_pilotagem' => 0,
        'outros_pilotagem' => 0,
        'treino_pontaria' => 0,
        'outros_pontaria' => 0,
        'treino_reflexos' => 0,
        'outros_reflexos' => 0,
        'treino_religiao' => 0,
        'outros_religiao' => 0,
        'treino_sobrevivencia' => 0,
        'outros_sobrevivencia' => 0,
        'treino_vontade' => 0,
        'outros_vontade' => 0
    );

    $poderes_personagem_t20 = array();
    $inventario_personagem_t20 = array();
}

// --- CARREGAR DADOS GERAIS DO JOGO (Para Modais e Selects) ---

function fetch_all_assoc_by_id($result)
{
    $data = array();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[$row['id']] = $row;
        }
    }
    return $data;
}

// Racas
$result_racas = $conn->query("SELECT * FROM t20_racas");
$racas_t20 = fetch_all_assoc_by_id($result_racas);

// Classes
$result_classes = $conn->query("SELECT * FROM t20_classes");
$classes_t20 = fetch_all_assoc_by_id($result_classes);

// Origens
$result_origens = $conn->query("SELECT * FROM t20_origens");
$origens_t20 = fetch_all_assoc_by_id($result_origens);

// Divindades
$result_divindades = $conn->query("SELECT * FROM t20_divindades");
$divindades_t20 = fetch_all_assoc_by_id($result_divindades);

// Poderes Divinos (e links)
$result_poderes_divinos = $conn->query("SELECT * FROM t20_poderes_divinos");
$poderes_divinos_t20 = fetch_all_assoc_by_id($result_poderes_divinos);
$result_divindade_links = $conn->query("SELECT * FROM t20_divindade_poderes");
$divindade_poderes_links = $result_divindade_links ? $result_divindade_links->fetch_all(MYSQLI_ASSOC) : array();

// Poderes e Habilidades de Classe
$result_hab_auto = $conn->query("SELECT * FROM t20_habilidades_classe_auto");
$habilidades_classe_auto_t20 = $result_hab_auto ? $result_hab_auto->fetch_all(MYSQLI_ASSOC) : array();
$result_poderes_classe = $conn->query("SELECT * FROM t20_poderes_classe");
$poderes_classe_t20 = $result_poderes_classe ? $result_poderes_classe->fetch_all(MYSQLI_ASSOC) : array();

// Poderes Gerais
$result_poderes_gerais = $conn->query("SELECT * FROM t20_poderes_gerais");
$poderes_gerais_t20 = $result_poderes_gerais ? $result_poderes_gerais->fetch_all(MYSQLI_ASSOC) : array();

// Itens (Todos os itens, incluindo homebrew (user_id IS NULL OR user_id = $user_id))
// Carrega tanto os itens oficiais (user_id NULL) quanto os homebrew do usuário
$stmt_itens = $conn->prepare("SELECT * FROM t20_itens WHERE user_id IS NULL OR user_id = ?");
$stmt_itens->bind_param("i", $user_id);
$stmt_itens->execute();
$result_itens = $stmt_itens->get_result();
$itens_t20 = fetch_all_assoc_by_id($result_itens);
$stmt_itens->close();

$conn->close();

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/ficha_t20.css"> <!-- Certifique-se que o CSS existe -->
    <title>Ficha T20 - <?= htmlspecialchars($personagem_t20['nome']) ?></title>
</head>

<body>

    <div class="ficha-container">
        <form id="ficha-t20-form" method="POST" action="../conection/save_character_t20.php" enctype="multipart/form-data">
            <input type="hidden" name="personagem_id" value="<?= htmlspecialchars($personagem_t20['id']) ?>">

            <!-- CABEÇALHO COM INFORMAÇÕES BÁSICAS -->
            <header class="cabecalho-grid">
                <div class="info-item">
                    <label for="nome">Nome do Personagem</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($personagem_t20['nome']) ?>">
                </div>
                <div class="info-item">
                    <label for="nivel">Nível</label>
                    <input type="number" id="nivel" name="nivel" value="<?= htmlspecialchars($personagem_t20['nivel']) ?>" min="1" max="20">
                </div>
                <div class="info-item">
                    <label for="raca_id">Raça</label>
                    <select id="raca_id" name="raca_id">
                        <option value="">Selecione...</option>
                        <?php foreach ($racas_t20 as $id_raca => $raca): ?>
                            <option value="<?= $id_raca ?>" <?= ($personagem_t20['raca_id'] == $id_raca) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($raca['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- Área para mostrar ajustes de atributos da raça -->
                    <div id="ajustes-raciais-info" class="info-racial"></div>
                </div>
                <div class="info-item">
                    <label for="classe_id">Classe</label>
                    <select id="classe_id" name="classe_id">
                        <option value="">Selecione...</option>
                        <?php foreach ($classes_t20 as $id_classe => $classe): ?>
                            <option value="<?= $id_classe ?>" <?= ($personagem_t20['classe_id'] == $id_classe) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($classe['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="info-item">
                    <label for="origem_id">Origem</label>
                    <select id="origem_id" name="origem_id">
                        <option value="">Selecione...</option>

                        <?php
                        // --- DEBUG FORÇADO ---
                        echo "<!-- DEBUG: Verificando \$origens_t20 ANTES do loop -->";
                        if (isset($origens_t20) && is_array($origens_t20) && count($origens_t20) > 0) {
                            echo "<!-- \$origens_t20 existe e tem " . count($origens_t20) . " itens. -->";
                            // Tenta imprimir o nome da primeira origem como teste
                            $first_key = key($origens_t20); // Pega a chave do primeiro elemento
                            if (isset($origens_t20[$first_key]['nome'])) {
                                echo "<!-- Nome da primeira origem: " . htmlspecialchars($origens_t20[$first_key]['nome']) . " -->";
                            } else {
                                echo "<!-- ERRO: Primeiro item não tem a chave 'nome'. -->";
                            }
                        } else {
                            echo "<!-- ERRO: \$origens_t20 NÃO existe, NÃO é array ou está VAZIA aqui! -->";
                            // Tenta forçar a impressão, mesmo que vazia
                            echo "<!-- Conteúdo (dump): " . print_r($origens_t20, true) . " -->";
                        }
                        echo "<!-- FIM DEBUG -->";
                        // --- FIM DEBUG FORÇADO ---
                        ?>

                        <?php foreach ($origens_t20 as $id_origem => $origem): ?>
                            <?php if (is_array($origem) && isset($origem['nome'])): ?>
                                <option value="<?= $id_origem ?>" <?= (isset($personagem_t20['origem_id']) && $personagem_t20['origem_id'] == $id_origem) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($origem['nome']) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="info-item">
                    <label for="divindade_id">Divindade</label>
                    <select id="divindade_id" name="divindade_id">
                        <option value="">(Nenhuma)</option> <!-- Opção para não ter divindade -->
                        <?php foreach ($divindades_t20 as $id_divindade => $divindade): ?>
                            <option value="<?= $id_divindade ?>" <?= (isset($personagem_t20['divindade_id']) && $personagem_t20['divindade_id'] == $id_divindade) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($divindade['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </header>

            <div class="conteudo-grid">
                <!-- COLUNA LATERAL (IMAGEM, STATUS) -->
                <aside class="coluna-lateral">
                    <div class="bloco bloco-personagem">
                        <input type="file" name="imagem_personagem" id="input-imagem-t20" accept="image/png, image/jpeg, image/gif" style="display: none;">
                        <div class="personagem-imagem" id="container-imagem-t20">
                            <img src="../uploads/<?= htmlspecialchars($personagem_t20['imagem']) ?>" alt="Imagem Personagem" id="preview-imagem-t20">
                        </div>
                        <button type="button" id="btn-importar-imagem-t20" class="btn">Importar Imagem</button>
                    </div>
                    <div class="bloco status-grid">
                        <div class="status-box">
                            <label>❤️ PV</label>
                            <div class="valor"><span id="pv_atual"><?= htmlspecialchars($personagem_t20['pv_atual']) ?></span> / <span id="pv_max"><?= htmlspecialchars($personagem_t20['pv_max']) ?></span></div>
                        </div>
                        <div class="status-box">
                            <label>💧 PM</label>
                            <div class="valor"><span id="pm_atual"><?= htmlspecialchars($personagem_t20['pm_atual']) ?></span> / <span id="pm_max"><?= htmlspecialchars($personagem_t20['pm_max']) ?></span></div>
                        </div>
                        <div class="status-box">
                            <label>🛡️ Defesa</label>
                            <div class="valor" id="defesa_total">--</div>
                        </div>
                    </div>
                </aside>

                <!-- COLUNA PRINCIPAL (ABAS) -->
                <main class="coluna-principal">
                    <nav class="abas-nav">
                        <button type="button" class="tab-button active" data-tab="tab-atributos">Atributos & Perícias</button>
                        <button type="button" class="tab-button" data-tab="tab-poderes">Poderes</button>
                        <button type="button" class="tab-button" data-tab="tab-equipamento">Equipamento</button>
                    </nav>

                    <!-- ABA ATRIBUTOS -->
                    <div id="tab-atributos" class="tab-content active">
                        <div class="bloco">
                            <h3>Atributos</h3>
                            <div class="atributos-grid">
                                <?php foreach (['forca', 'destreza', 'constituicao', 'inteligencia', 'sabedoria', 'carisma'] as $attr): ?>
                                    <div class="atributo">
                                        <label><?= strtoupper($attr) ?></label>
                                        <input type="number" class="atributo-valor" id="<?= $attr ?>" name="<?= $attr ?>" value="<?= htmlspecialchars($personagem_t20[$attr]) ?>" min="-3">

                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="bloco">
                            <h3>Perícias</h3>
                            <div class="pericias-container" id="lista-pericias">
                                <!-- Cabeçalho da Tabela de Perícias -->
                                <div class="pericia-item header">
                                    <span>Perícia</span>
                                    <span>Total</span>
                                    <span>1/2 Nvl</span>
                                    <span>Mod. Atr.</span>
                                    <span>Treino</span>
                                    <span>Outros</span>
                                </div>

                                <?php
                                // Definindo as perícias e seus atributos chave
                                $lista_completa_pericias = [
                                    // Ordem Alfabética T20 JdA
                                    ['nome' => 'Acrobacia', 'attr' => 'DES', 'id_sufixo' => 'acrobacia'],
                                    ['nome' => 'Adestramento', 'attr' => 'CAR', 'id_sufixo' => 'adestramento', 'so_treinado' => true],
                                    ['nome' => 'Atletismo', 'attr' => 'FOR', 'id_sufixo' => 'atletismo'],
                                    ['nome' => 'Atuação', 'attr' => 'CAR', 'id_sufixo' => 'atuacao', 'so_treinado' => true],
                                    ['nome' => 'Cavalgar', 'attr' => 'DES', 'id_sufixo' => 'cavalgar'],
                                    ['nome' => 'Conhecimento', 'attr' => 'INT', 'id_sufixo' => 'conhecimento', 'so_treinado' => true],
                                    ['nome' => 'Cura', 'attr' => 'SAB', 'id_sufixo' => 'cura', 'so_treinado' => true],
                                    ['nome' => 'Diplomacia', 'attr' => 'CAR', 'id_sufixo' => 'diplomacia'],
                                    ['nome' => 'Enganação', 'attr' => 'CAR', 'id_sufixo' => 'enganacao'],
                                    ['nome' => 'Fortitude', 'attr' => 'CON', 'id_sufixo' => 'fortitude'],
                                    ['nome' => 'Furtividade', 'attr' => 'DES', 'id_sufixo' => 'furtividade'],
                                    ['nome' => 'Guerra', 'attr' => 'INT', 'id_sufixo' => 'guerra', 'so_treinado' => true],
                                    ['nome' => 'Iniciativa', 'attr' => 'DES', 'id_sufixo' => 'iniciativa'],
                                    ['nome' => 'Intimidação', 'attr' => 'CAR', 'id_sufixo' => 'intimidacao'],
                                    ['nome' => 'Intuição', 'attr' => 'SAB', 'id_sufixo' => 'intuicao'],
                                    ['nome' => 'Investigação', 'attr' => 'INT', 'id_sufixo' => 'investigacao'],
                                    ['nome' => 'Jogatina', 'attr' => 'CAR', 'id_sufixo' => 'jogatina', 'so_treinado' => true],
                                    ['nome' => 'Ladinagem', 'attr' => 'DES', 'id_sufixo' => 'ladinagem', 'so_treinado' => true],
                                    ['nome' => 'Luta', 'attr' => 'FOR', 'id_sufixo' => 'luta'],
                                    ['nome' => 'Misticismo', 'attr' => 'INT', 'id_sufixo' => 'misticismo', 'so_treinado' => true],
                                    ['nome' => 'Nobreza', 'attr' => 'INT', 'id_sufixo' => 'nobreza', 'so_treinado' => true],
                                    ['nome' => 'Ofício', 'attr' => 'INT', 'id_sufixo' => 'oficio', 'so_treinado' => true], // Ofício genérico
                                    ['nome' => 'Percepção', 'attr' => 'SAB', 'id_sufixo' => 'percepcao'],
                                    ['nome' => 'Pilotagem', 'attr' => 'DES', 'id_sufixo' => 'pilotagem', 'so_treinado' => true],
                                    ['nome' => 'Pontaria', 'attr' => 'DES', 'id_sufixo' => 'pontaria'],
                                    ['nome' => 'Reflexos', 'attr' => 'DES', 'id_sufixo' => 'reflexos'],
                                    ['nome' => 'Religião', 'attr' => 'SAB', 'id_sufixo' => 'religiao', 'so_treinado' => true],
                                    ['nome' => 'Sobrevivência', 'attr' => 'SAB', 'id_sufixo' => 'sobrevivencia'],
                                    ['nome' => 'Vontade', 'attr' => 'SAB', 'id_sufixo' => 'vontade']
                                ];
                                ?>

                                <?php foreach ($lista_completa_pericias as $pericia): ?>
                                    <div class="pericia-item" data-atributo-chave="<?= strtolower($pericia['attr']) ?>" id="item_<?= $pericia['id_sufixo'] ?>">
                                        <span class="pericia-nome">
                                            <?= htmlspecialchars($pericia['nome']) ?>
                                            <span class="pericia-attr">(<?= $pericia['attr'] ?>)</span>
                                            <?= isset($pericia['so_treinado']) && $pericia['so_treinado'] ? '<span class="so-treinado-marker">*</span>' : '' ?>
                                        </span>
                                        <strong class="pericia-total" id="total_<?= $pericia['id_sufixo'] ?>">0</strong>
                                        <span class="pericia-metade-nivel" id="nivel_<?= $pericia['id_sufixo'] ?>">0</span>
                                        <span class="pericia-mod-attr" id="mod_<?= $pericia['id_sufixo'] ?>">0</span>
                                        <input type="checkbox" class="pericia-treino" id="treino_<?= $pericia['id_sufixo'] ?>" data-pericia-id="<?= $pericia['id_sufixo'] ?>">
                                        <input type="number" class="pericia-outros" id="outros_<?= $pericia['id_sufixo'] ?>" value="0" min="-20" max="20" data-pericia-id="<?= $pericia['id_sufixo'] ?>">
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>

                    <!-- ABA PODERES -->
                    <div id="tab-poderes" class="tab-content">
                        <div class="bloco">
                            <h3>Poderes e Habilidades</h3>
                            <div id="habilidades-raciais-lista">
                                <h4>Habilidades de Raça</h4>
                                <!-- JS vai popular aqui -->
                            </div>
                            <div id="poderes-divindade-display" style="margin-top: 1.5rem;">
                                <h4>Poderes Concedidos</h4>
                                <p>Selecione uma divindade para ver seus poderes.</p>
                            </div>
                            <div id="poder-origem-display-t20" class="poder-item">
                                <h4>Poder de Origem</h4>
                                <p>Selecione uma origem para ver seu poder.</p>
                            </div>
                            <div id="habilidades-classe-lista">
                                <h4>Habilidades de Classe</h4>
                                <!-- JS vai popular aqui -->
                            </div>
                            <div id="poderes-escolhidos-lista" style="margin-top: 1.5rem;">
                                <h4>Poderes Adquiridos</h4>
                                <!-- Lista de poderes que o JS irá preencher -->
                                <p>(Nenhum poder adquirido ainda)</p>
                            </div>
                            <button type="button" class="btn btn-small" id="btn-abrir-modal-poderes" style="margin-top: 1rem;">Adicionar Poder</button>
                        </div>
                    </div>

                    <div id="tab-equipamento" class="tab-content">
                        <div class="bloco">
                            <h3>Ataques</h3>
                            <table width="100%" id="tabela-ataques">
                                <thead>
                                    <tr style="text-align: left;">
                                        <th>Arma</th>
                                        <th>Teste</th>
                                        <th>Dano</th>
                                        <th>Crítico</th>
                                        <th>Tipo</th>
                                        <th>Alcance</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Ataques (baseados em itens equipados) irão aqui -->
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-small" id="btn-abrir-modal-ataque" disabled>(Adicionar Ataque)</button>
                        </div>

                        <div class="bloco">
                            <!-- Cabeçalho do Inventário: Carga e Dinheiro -->
                            <div class="inventario-header">
                                <h3>Equipamento</h3>
                                <div class="inventario-stats">
                                    <span>Carga (Espaços): <strong id="carga_usada">0</strong> / <strong id="carga_maxima">0</strong></span>
                                    <span><input type="number" id="tibares_atuais" name="tibares" value="<?= htmlspecialchars($personagem_t20['tibares']) ?>" min="0"> T$</span>
                                </div>
                            </div>
                            <div class="inventario-lista" id="lista-inventario">
                                <div class="inventario-list-header">
                                    <span class="col-equipado">Equipado</span>
                                    <span class="col-nome">Item</span>
                                    <span class="col-qtd">Qtd</span>
                                    <span class="col-espacos">Espaços</span>
                                    <span class="col-acoes">Ações</span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-small" id="btn-abrir-modal-item">Adicionar Item</button>
                        </div>
                    </div>

                </main>
            </div>

            <footer class="botoes-rodape">
                <a href="meus_personagens.php" class="btn btn-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary">Salvar Personagem</button>
            </footer>
        </form>
    </div>
    <!--Modais-->
    <div id="modal-adicionar-poder-t20" class="modal-overlay">
        <div class="modal-content modal-poderes">
            <h2>Adicionar Poder</h2>

            <!-- Abas de navegação dentro do modal -->
            <nav class="modal-abas-nav">
                <button type="button" class="modal-tab-button active" data-tab-modal="modal-tab-classe">Classe</button>
                <button type="button" class="modal-tab-button" data-tab-modal="modal-tab-combate">Combate</button>
                <button type="button" class="modal-tab-button" data-tab-modal="modal-tab-destino">Destino</button>
                <button type="button" class="modal-tab-button" data-tab-modal="modal-tab-magia">Magia</button>
            </nav>

            <div class="modal-filtros-poderes">
                <input type="text" id="filtro-poder-nome-t20" placeholder="Buscar por nome...">
            </div>

            <!-- Conteúdo das abas do modal -->
            <div class="modal-lista-wrapper">
                <!-- Aba Poderes de Classe -->
                <div id="modal-tab-classe" class="modal-tab-content active">
                    <p>Selecione uma classe para ver os poderes disponíveis.</p>
                </div>
                <!-- Aba Poderes de Combate -->
                <div id="modal-tab-combate" class="modal-tab-content">
                </div>
                <!-- Aba Poderes de Destino -->
                <div id="modal-tab-destino" class="modal-tab-content">
                </div>
                <!-- Aba Poderes de Magia -->
                <div id="modal-tab-magia" class="modal-tab-content">
                </div>
            </div>

            <button type="button" class="btn btn-secondary" id="btn-fechar-modal-poderes" style="margin-top: 20px;">Fechar</button>
        </div>
    </div>

    <!-- *** NOVO: MODAL PARA ADICIONAR ITENS T20 *** -->
    <div id="modal-adicionar-item-t20" class="modal-overlay">
        <div class="modal-content modal-itens">
            <h2>Adicionar Item ao Inventário</h2>

            <nav class="modal-abas-nav">
                <button type="button" class="modal-tab-button active" data-tab-modal="modal-tab-armas">Armas</button>
                <button type="button" class="modal-tab-button" data-tab-modal="modal-tab-armaduras">Armaduras</button>
                <button type="button" class="modal-tab-button" data-tab-modal="modal-tab-escudos">Escudos</button>
                <button type="button" class="modal-tab-button" data-tab-modal="modal-tab-geral">Item Geral</button>
            </nav>

            <div class="modal-filtros-poderes">
                <input type="text" id="filtro-item-nome-t20" placeholder="Buscar por nome...">
            </div>

            <div class="modal-lista-wrapper">
                <!-- Abas (conteúdo populado pelo JS) -->
                <div id="modal-tab-armas" class="modal-tab-content active"></div>
                <div id="modal-tab-armaduras" class="modal-tab-content"></div>
                <div id="modal-tab-escudos" class="modal-tab-content"></div>
                <div id="modal-tab-geral" class="modal-tab-content"></div>
            </div>

            <button type="button" class="btn btn-secondary" id="btn-fechar-modal-item" style="margin-top: 20px;">Fechar</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- DADOS DO PHP ---
            const racasData = <?= json_encode(isset($racas_t20) ? $racas_t20 : []) ?>;
            const classesDataT20 = <?= json_encode(isset($classes_t20) ? $classes_t20 : []) ?>;
            const origensDataT20 = <?= json_encode(isset($origens_t20) ? $origens_t20 : []) ?>;
            const divindadesDataT20 = <?= json_encode(isset($divindades_t20) ? $divindades_t20 : []) ?>;
            const poderesDivinosDataT20 = <?= json_encode(isset($poderes_divinos_t20) ? $poderes_divinos_t20 : []) ?>;
            const divindadePoderesLinks = <?= json_encode(isset($divindade_poderes_links) ? $divindade_poderes_links : []) ?>;
            const habilidadesClasseAutoData = <?= json_encode(isset($habilidades_classe_auto_t20) ? $habilidades_classe_auto_t20 : []) ?>;
            const poderesClasseData = <?= json_encode(isset($poderes_classe_t20) ? $poderes_classe_t20 : []) ?>;
            const poderesGeraisData = <?= json_encode(isset($poderes_gerais_t20) ? $poderes_gerais_t20 : []) ?>;
            const poderesPersonagemData = <?= json_encode(isset($poderes_personagem_t20) ? $poderes_personagem_t20 : []) ?>;
            const todosOsItensT20 = <?= json_encode(isset($itens_t20) ? $itens_t20 : []) ?>;
            const inventarioInicialPersonagemT20 = <?= json_encode(isset($inventario_personagem_t20) ? $inventario_personagem_t20 : []) ?>;

            // --- ELEMENTOS DO DOM ---
            const fichaContainer = document.getElementById('ficha-t20-container');
            const form = document.getElementById('ficha-t20-form');
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            const racaSelect = document.getElementById('raca_id');
            const classeSelect = document.getElementById('classe_id');
            const origemSelect = document.getElementById('origem_id');
            const divindadeSelect = document.getElementById('divindade_id');
            const nivelInput = document.getElementById('nivel');
            const atributosInputs = document.querySelectorAll('.atributo-valor');
            const periciasItems = document.querySelectorAll('.pericia-item:not(.header)');

            // Spans de Status
            const pvAtualSpan = document.getElementById('pv_atual');
            const pvMaxSpan = document.getElementById('pv_max');
            const pmAtualSpan = document.getElementById('pm_atual');
            const pmMaxSpan = document.getElementById('pm_max');
            const defesaTotalSpan = document.getElementById('defesa_total');
            // Divs de Poderes
            const ajustesInfoDiv = document.getElementById('ajustes-raciais-info');
            const poderOrigemDisplayDiv = document.getElementById('poder-origem-display-t20');
            const habilidadesRaciaisDisplayDiv = document.getElementById('habilidades-raciais-lista');
            const poderesDivindadeDisplayDiv = document.getElementById('poderes-divindade-display');
            const habilidadesClasseAutoDisplayDiv = document.getElementById('habilidades-classe-lista');
            const poderesEscolhidosDisplayDiv = document.getElementById('poderes-escolhidos-lista');
            // --- ELEMENTOS DO MODAL DE PODERES ---
            const modalAdicionarPoder = document.getElementById('modal-adicionar-poder-t20');
            const btnAbrirModalPoderes = document.getElementById('btn-abrir-modal-poderes');
            const btnFecharModalPoderes = document.getElementById('btn-fechar-modal-poderes');

            // *** CORREÇÃO AQUI: Seletores de abas agora são específicos para o modal de PODERES ***
            const modalTabs = document.querySelectorAll('#modal-adicionar-poder-t20 .modal-tab-button');
            const modalTabContents = document.querySelectorAll('#modal-adicionar-poder-t20 .modal-tab-content');

            const filtroPoderNome = document.getElementById('filtro-poder-nome-t20');
            // Elementos do Inventário e Modal de Itens
            const modalAdicionarItem = document.getElementById('modal-adicionar-item-t20');
            const btnAbrirModalItem = document.getElementById('btn-abrir-modal-item');
            const btnFecharModalItem = document.getElementById('btn-fechar-modal-item');

            // (Este seletor é específico para o modal de ITENS, o que está correto)
            const modalItemTabs = document.querySelectorAll('#modal-adicionar-item-t20 .modal-tab-button');
            const modalItemTabContents = document.querySelectorAll('#modal-adicionar-item-t20 .modal-tab-content');

            const filtroItemNome = document.getElementById('filtro-item-nome-t20');
            const cargaUsadaSpan = document.getElementById('carga_usada');
            const cargaMaximaSpan = document.getElementById('carga_maxima');
            const inventarioListaDiv = document.getElementById('lista-inventario');
            const btnImportar = document.getElementById('btn-importar-imagem-t20');
            const inputImagem = document.getElementById('input-imagem-t20');
            const previewImagem = document.getElementById('preview-imagem-t20');
            const containerImagem = document.getElementById('container-imagem-t20');
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

            // --- ESTADO GLOBAL DA FICHA ---
            // (Poderes de Raca, Origem, Divindade são exibidos, mas não rastreados aqui)
            // Este array rastreia apenas poderes que o usuário ESCOLHE (Classe, Gerais)
            let poderesEscolhidos = [...(poderesPersonagemData || [])]; // Array de objetos {poder_id: X, tipo_poder: 'classe'/'geral'}
            let inventarioAtual = [...inventarioInicialPersonagemT20];

            // --- MAPAS ---
            const periciasMap = {
                acrobacia: 'destreza',
                adestramento: 'carisma',
                atletismo: 'forca',
                atuacao: 'carisma',
                cavalgar: 'destreza',
                conhecimento: 'inteligencia',
                cura: 'sabedoria',
                diplomacia: 'carisma',
                enganacao: 'carisma',
                fortitude: 'constituicao',
                furtividade: 'destreza',
                guerra: 'inteligencia',
                iniciativa: 'destreza',
                intimidacao: 'carisma',
                intuicao: 'sabedoria',
                investigacao: 'inteligencia',
                jogatina: 'carisma',
                ladinagem: 'destreza',
                luta: 'forca',
                misticismo: 'inteligencia',
                nobreza: 'inteligencia',
                oficio: 'inteligencia',
                percepcao: 'sabedoria',
                pilotagem: 'destreza',
                pontaria: 'destreza',
                reflexos: 'destreza',
                religiao: 'sabedoria',
                sobrevivencia: 'sabedoria',
                vontade: 'sabedoria'
            };
            const mapAtributoChave = {
                'for': 'forca',
                'des': 'destreza',
                'con': 'constituicao',
                'int': 'inteligencia',
                'sab': 'sabedoria',
                'car': 'carisma'
            };

            // --- LÓGICA DE ENVIO DO FORMULÁRIO ---
            form.addEventListener('submit', (event) => {
                // Limpa inputs antigos
                form.querySelectorAll('input[name="poderes_escolhidos_id[]"], input[name="poderes_escolhidos_tipo[]"]').forEach(input => input.remove());

                // (Lógica de inventário para salvar)
                form.querySelectorAll('input[name="inv_item_id[]"], input[name="inv_quantidade[]"], input[name="inv_equipado[]"]').forEach(input => input.remove());


                // Adiciona inputs hidden para cada poder escolhido
                poderesEscolhidos.forEach(poder => {
                    const inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'poderes_escolhidos_id[]';
                    inputId.value = poder.poder_id;
                    form.appendChild(inputId);

                    const inputType = document.createElement('input');
                    inputType.type = 'hidden';
                    inputType.name = 'poderes_escolhidos_tipo[]';
                    inputType.value = poder.tipo_poder;
                    form.appendChild(inputType);
                });

                // Adiciona inputs hidden para o inventário
                inventarioAtual.forEach(item => {
                    const inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'inv_item_id[]';
                    inputId.value = item.id;
                    form.appendChild(inputId);

                    const inputQtd = document.createElement('input');
                    inputQtd.type = 'hidden';
                    inputQtd.name = 'inv_quantidade[]';
                    inputQtd.value = item.quantidade || 1;
                    form.appendChild(inputQtd);

                    const inputEquip = document.createElement('input');
                    inputEquip.type = 'hidden';
                    inputEquip.name = 'inv_equipado[]';
                    inputEquip.value = item.equipado ? '1' : '0';
                    form.appendChild(inputEquip);
                });
            });

            // --- FUNÇÕES DE ATUALIZAÇÃO DA INTERFACE ---

            function atualizarClasseCSS() {
                const classeIdSelecionada = classeSelect.value;
                let classeCSS = "";
                if (classeIdSelecionada && classesDataT20[classeIdSelecionada]) {
                    classeCSS = "classe-" + classesDataT20[classeIdSelecionada].nome.toLowerCase().replace(/\s+/g, '-');
                }
                if (fichaContainer) {
                    const classesToRemove = Array.from(fichaContainer.classList).filter(cls => cls.startsWith('classe-'));
                    fichaContainer.classList.remove(...classesToRemove);
                    if (classeCSS) {
                        fichaContainer.classList.add(classeCSS);
                    }
                }
            }

            function calcularTudoT20() {
                // --- 1. LEITURA DE DADOS ---
                const nivel = parseInt(nivelInput.value) || 1;
                const metadeNivel = Math.floor(nivel / 2);

                // IDs selecionados
                const racaId = parseInt(racaSelect.value) || null;
                const classeId = parseInt(classeSelect.value) || null;
                const origemId = parseInt(origemSelect.value) || null;
                const divindadeId = parseInt(divindadeSelect.value) || null;

                // Objetos de dados
                const classeAtual = classeId ? classesDataT20[classeId] : null;
                const racaInfo = racaId ? racasData[racaId] : null;

                // Modificadores de Atributo
                const modificadores = {};
                atributosInputs.forEach(input => {
                    const modificadorLido = parseInt(input.value);
                    modificadores[input.id] = isNaN(modificadorLido) ? 0 : modificadorLido;
                });

                // --- 2. GERAR LISTAS DE HABILIDADES ATIVAS ---
                const nomesHabilidadesRaca = new Set();
                if (racaInfo) {
                    if (racaInfo.habilidade_1_nome) nomesHabilidadesRaca.add(racaInfo.habilidade_1_nome);
                    if (racaInfo.habilidade_2_nome) nomesHabilidadesRaca.add(racaInfo.habilidade_2_nome);
                    if (racaInfo.habilidade_3_nome) nomesHabilidadesRaca.add(racaInfo.habilidade_3_nome);
                    nomesHabilidadesRaca.forEach(nome => {
                        if (nome && nome.includes(' & ')) {
                            nome.split(' & ').forEach(subNome => nomesHabilidadesRaca.add(subNome.trim()));
                        }
                    });
                }
                const nomesPoderesOrigem = new Set();
                if (origensDataT20[origemId]) {
                    const origemInfo = origensDataT20[origemId];
                    if (origemInfo.poder_1_nome) nomesPoderesOrigem.add(origemInfo.poder_1_nome);
                    if (origemInfo.poder_2_nome) nomesPoderesOrigem.add(origemInfo.poder_2_nome);
                    if (origemInfo.poder_3_nome) nomesPoderesOrigem.add(origemInfo.poder_3_nome);
                }
                const nomesPoderesEscolhidos = new Set(poderesEscolhidos.map(pRef => {
                    if (pRef.tipo_poder === 'classe') return (poderesClasseData.find(p => p.id == pRef.poder_id) || {}).nome;
                    else if (pRef.tipo_poder === 'geral') return (poderesGeraisData.find(p => p.id == pRef.poder_id) || {}).nome;
                    return null;
                }).filter(Boolean));
                const nomesPoderesDivinos = new Set();
                if (divindadeId) {
                    divindadePoderesLinks
                        .filter(link => link.divindade_id == divindadeId)
                        .forEach(link => {
                            const poder = poderesDivinosDataT20[link.poder_divino_id];
                            if (poder) nomesPoderesDivinos.add(poder.nome);
                        });
                }

                // --- 3. CÁLCULO PV e PM ---
                let pvMax = 0;
                let pmMax = 0;
                const conMod = modificadores['constituicao'] || 0;

                if (classeAtual) {
                    pvMax = (parseInt(classeAtual.pv_inicial) + conMod) + ((nivel - 1) * (parseInt(classeAtual.pv_por_nivel) + conMod));
                    pmMax = nivel * parseInt(classeAtual.pm_por_nivel);
                    if (classeAtual.nome === 'Nobre') {
                        pmMax += Math.floor(nivel / 2);
                    }
                }
                // Bônus Raça
                if (nomesHabilidadesRaca.has('Duro como Pedra')) {
                    pvMax += 3 + (nivel - 1);
                }
                if (nomesHabilidadesRaca.has('Sangue Mágico')) {
                    pmMax += nivel;
                }
                // Bônus Origem
                if (nomesPoderesOrigem.has('Vitalidade')) {
                    pvMax += (nivel * 2);
                }
                if (nomesPoderesOrigem.has('Esforçado')) {
                    pmMax += Math.floor(nivel / 2);
                }
                if (nomesPoderesOrigem.has('Vontade de Ferro')) {
                    pmMax += Math.floor(nivel / 2);
                }
                // Bônus Poderes Escolhidos
                if (nomesPoderesEscolhidos.has('Vitalidade')) {
                    pvMax += nivel;
                }
                if (nomesPoderesEscolhidos.has('À Prova de Tudo')) {
                    pvMax += nivel;
                }
                if (nomesPoderesEscolhidos.has('Vontade de Ferro')) {
                    pmMax += Math.floor(nivel / 2);
                }
                // Bônus Divindade
                if (nomesPoderesDivinos.has('Bênção do Mana')) {
                    pmMax += Math.floor((nivel + 1) / 2);
                }

                // Atualização dos Spans PV/PM (Lógica 'Atual acompanha Máximo')
                const pvMaxTextoAnterior = pvMaxSpan ? pvMaxSpan.textContent : '--';
                const pmMaxTextoAnterior = pmMaxSpan ? pmMaxSpan.textContent : '--';
                const pvAtualTextoAnterior = pvAtualSpan ? pvAtualSpan.textContent : '--';
                const pmAtualTextoAnterior = pmAtualSpan ? pmAtualSpan.textContent : '--';
                const pvMaxTextoNovo = pvMax > 0 ? pvMax.toString() : '--';
                const pmMaxTextoNovo = pmMax > 0 ? pmMax.toString() : '--';
                if (pvMaxSpan) pvMaxSpan.textContent = pvMaxTextoNovo;
                if (pmMaxSpan) pmMaxSpan.textContent = pmMaxTextoNovo;
                if (pvAtualSpan && (pvAtualTextoAnterior === '--' || pvAtualTextoAnterior === '0' || pvAtualTextoAnterior === pvMaxTextoAnterior)) {
                    pvAtualSpan.textContent = pvMaxTextoNovo;
                } else if (pvAtualSpan) {
                    if (pvMax > 0 && parseInt(pvAtualTextoAnterior) > pvMax) {
                        pvAtualSpan.textContent = pvMaxTextoNovo;
                    } else if (pvMax <= 0) {
                        pvAtualSpan.textContent = '--';
                    }
                }
                if (pmAtualSpan && (pmAtualTextoAnterior === '--' || pmAtualTextoAnterior === '0' || pmAtualTextoAnterior === pmMaxTextoAnterior)) {
                    pmAtualSpan.textContent = pmMaxTextoNovo;
                } else if (pmAtualSpan) {
                    if (pmMax > 0 && parseInt(pmAtualTextoAnterior) > pmMax) {
                        pmAtualSpan.textContent = pmMaxTextoNovo;
                    } else if (pmMax <= 0) {
                        pmAtualSpan.textContent = '--';
                    }
                }
                if (pvMaxTextoNovo === '--' && pvAtualSpan) pvAtualSpan.textContent = '--';
                if (pmMaxTextoNovo === '--' && pmAtualSpan) pmAtualSpan.textContent = '--';


                // --- 4. CÁLCULO INVENTÁRIO (CARGA) E DEFESA ---
                let cargaUsada = 0;
                let bonusCarga = 0;
                let defesaItens = 0;
                let penalidadeArmadura = 0;
                let isArmaduraLeve = false;
                let isArmaduraPesada = false;
                let escudoEquipado = false;

                if (typeof inventarioAtual !== 'undefined' && Array.isArray(inventarioAtual)) {
                    inventarioAtual.forEach(item => {
                        const qtd = parseInt(item.quantidade) || 1;
                        const esp = parseInt(item.espacos) || 0;
                        cargaUsada += esp * qtd; // Soma espaços usados

                        if (item.equipado) {
                            if (item.bonus_carga) {
                                bonusCarga += parseInt(item.bonus_carga) || 0; // Ex: Mochila
                            }
                            if (item.tipo === 'Armadura' || item.tipo === 'Escudo') {
                                defesaItens += parseInt(item.bonus_defesa) || 0;
                                penalidadeArmadura += parseInt(item.penalidade_armadura) || 0;
                            }
                            if (item.tipo === 'Escudo') {
                                escudoEquipado = true;
                            }
                            if (item.tipo === 'Armadura') {
                                if ((parseInt(item.penalidade_armadura) || 0) <= -3) {
                                    isArmaduraPesada = true;
                                } else {
                                    isArmaduraLeve = true; // Couro, Brunea
                                }
                            }
                        }
                    });
                }

                // Carga Máxima (Regra T20: 10 + ModFor * 2)
                const modForca = modificadores['forca'] || 0;
                let cargaMaxima = 10 + (modForca * 2);
                cargaMaxima += bonusCarga; // Adiciona bônus (ex: +5 da Mochila)

                // Bônus de Poderes na Carga
                if (nomesPoderesEscolhidos.has('Costas Largas')) cargaMaxima += 5;
                if (nomesPoderesOrigem.has('Mochileiro')) cargaMaxima += 5;

                // Atualiza Spans de Carga
                if (cargaUsadaSpan) cargaUsadaSpan.textContent = cargaUsada;
                if (cargaMaximaSpan) cargaMaximaSpan.textContent = cargaMaxima;


                // CÁLCULO DEFESA (Final)
                let defesaTotal = 10 + (modificadores['destreza'] || 0) + defesaItens;

                // Bônus Raça
                if (nomesHabilidadesRaca.has('Couro Rígido')) {
                    defesaTotal += 1;
                } // Minotauro
                if (nomesHabilidadesRaca.has('Reptiliano') && !isArmaduraPesada) {
                    defesaTotal += 1;
                } // Trog
                if (nomesHabilidadesRaca.has('Chassi')) {
                    defesaTotal += 2;
                } // Golem

                // Bônus Poderes
                if (nomesPoderesEscolhidos.has('Esquiva')) {
                    defesaTotal += 2;
                }
                if (nomesPoderesEscolhidos.has('Estilo de Uma Arma') && !escudoEquipado) {
                    defesaTotal += 2;
                }
                if (nomesPoderesEscolhidos.has('Estilo de Arma e Escudo') && escudoEquipado) {
                    defesaTotal += 0;
                }
                if (nomesPoderesEscolhidos.has('Encouraçado') && isArmaduraPesada) {
                    defesaTotal += 2;
                }

                // Atualiza Span de Defesa
                if (defesaTotalSpan) defesaTotalSpan.textContent = defesaTotal;


                // --- 5. CÁLCULO PERÍCIAS (Com Penalidade de Armadura) ---
                if (periciasItems) {
                    periciasItems.forEach((item) => {
                        if (!item || !item.id || !item.dataset || !item.dataset.atributoChave) return;
                        const sufixoId = item.id.replace('item_', '');
                        const atributoChaveAbrev = item.dataset.atributoChave;
                        const spanTotal = item.querySelector('.pericia-total');
                        const spanNivel = item.querySelector('.pericia-metade-nivel');
                        const spanModAttr = item.querySelector('.pericia-mod-attr');
                        const checkboxTreino = item.querySelector('.pericia-treino');
                        const inputOutros = item.querySelector('.pericia-outros');
                        const chaveModificadorCompleta = mapAtributoChave[atributoChaveAbrev];

                        if (typeof modificadores[chaveModificadorCompleta] === 'undefined') {
                            if (spanTotal) spanTotal.textContent = '--';
                            if (spanNivel) spanNivel.textContent = '--';
                            if (spanModAttr) spanModAttr.textContent = '--';
                            return;
                        }
                        const modValor = modificadores[chaveModificadorCompleta];

                        if (!spanTotal || !spanNivel || !spanModAttr || !checkboxTreino || !inputOutros) {
                            if (spanTotal) spanTotal.textContent = 'ER';
                            if (spanNivel) spanNivel.textContent = 'ER';
                            if (spanModAttr) spanModAttr.textContent = 'ER';
                            return;
                        }
                        try {
                            let bonusTreino = 0;
                            if (checkboxTreino.checked) {
                                if (nivel >= 15) bonusTreino = 6;
                                else if (nivel >= 7) bonusTreino = 4;
                                else bonusTreino = 2;
                            }
                            const outrosValor = parseInt(inputOutros.value) || 0;

                            let penalidadeAplicada = 0;
                            if (atributoChaveAbrev === 'for' || atributoChaveAbrev === 'des') {
                                penalidadeAplicada = penalidadeArmadura;
                            }

                            const totalPericia = metadeNivel + modValor + bonusTreino + outrosValor + penalidadeAplicada;

                            spanTotal.textContent = (totalPericia >= 0 ? '+' : '') + totalPericia;
                            spanNivel.textContent = (metadeNivel >= 0 ? '+' : '') + metadeNivel;
                            spanModAttr.textContent = (modValor >= 0 ? '+' : '') + modValor;
                        } catch (e) {
                            if (spanTotal) spanTotal.textContent = 'ER';
                            if (spanNivel) spanNivel.textContent = 'ER';
                            if (spanModAttr) spanModAttr.textContent = 'ER';
                        }
                    });
                }

                // --- 6. ATUALIZA OUTRAS PARTES DA UI ---
                atualizarHabilidadesClasseAuto(classeId, nivel);
                renderizarInventario(); // CHAMA A RENDERIZAÇÃO DO INVENTÁRIO
            }

            function mostrarAjustesRaciais() {
                const racaIdSelecionada = racaSelect.value;
                if (ajustesInfoDiv && racasData[racaIdSelecionada]) {
                    const ajustes = racasData[racaIdSelecionada].ajustes_atributos || "Nenhum";
                    let textoAjustes = ajustes;
                    if (ajustes.includes("ATRIBUTOS+1,+1,+1")) {
                        textoAjustes = "+1 em Três Atributos Diferentes";
                        if (ajustes.includes("CAR-1")) textoAjustes += ", CAR-1";
                        if (ajustes.includes("-exceto-CON, CON-1")) textoAjustes += " (exceto CON), CON-1";
                    } else if (ajustes.includes("(Aggelus);")) {
                        textoAjustes = "Escolha: Aggelus (SAB+2, CAR+1) OU Sulfure (DES+2, INT+1)";
                    }
                    ajustesInfoDiv.textContent = `Ajustes Raciais: ${textoAjustes}`;
                } else if (ajustesInfoDiv) {
                    ajustesInfoDiv.textContent = "";
                }
            }

            function atualizarHabilidadesRaciais() {
                const racaIdSelecionada = racaSelect.value;
                if (!habilidadesRaciaisDisplayDiv) return;
                const h4 = habilidadesRaciaisDisplayDiv.querySelector('h4');
                habilidadesRaciaisDisplayDiv.innerHTML = '';
                if (h4) habilidadesRaciaisDisplayDiv.appendChild(h4);
                const racaInfo = racasData[racaIdSelecionada];
                if (racaInfo) {
                    let htmlHabilidades = "";
                    if (racaInfo.habilidade_1_nome) {
                        htmlHabilidades += `<div class="poder-item"><h4>${racaInfo.habilidade_1_nome} (Raça)</h4><p>${racaInfo.habilidade_1_desc || ''}</p></div>`;
                    }
                    if (racaInfo.habilidade_2_nome) {
                        htmlHabilidades += `<div class="poder-item"><h4>${racaInfo.habilidade_2_nome} (Raça)</h4><p>${racaInfo.habilidade_2_desc || ''}</p></div>`;
                    }
                    if (racaInfo.habilidade_3_nome) {
                        htmlHabilidades += `<div class="poder-item"><h4>${racaInfo.habilidade_3_nome} (Raça)</h4><p>${racaInfo.habilidade_3_desc || ''}</p></div>`;
                    }
                    if (htmlHabilidades === "") {
                        htmlHabilidades = "<p>Esta raça não concede habilidades específicas.</p>";
                    }
                    habilidadesRaciaisDisplayDiv.insertAdjacentHTML('beforeend', htmlHabilidades);
                } else {
                    habilidadesRaciaisDisplayDiv.insertAdjacentHTML('beforeend', '<p>Selecione uma raça para ver suas habilidades.</p>');
                }
            }

            function atualizarPoderOrigemT20() {
                const origemIdSelecionada = origemSelect.value;
                if (!poderOrigemDisplayDiv) return;
                const origemInfo = origensDataT20[origemIdSelecionada];
                let htmlConteudo = '<h4>Poder de Origem</h4>';
                if (origemInfo) {
                    htmlConteudo += `<p>(${origemInfo.nome})</p>`;
                    let descricoes = "";
                    if (origemInfo.poder_1_nome) descricoes += `<p><strong>${origemInfo.poder_1_nome}:</strong> ${origemInfo.poder_1_desc || ''}</p>`;
                    if (origemInfo.poder_2_nome) descricoes += `<p><strong>${origemInfo.poder_2_nome}:</strong> ${origemInfo.poder_2_desc || ''}</p>`;
                    if (origemInfo.poder_3_nome) descricoes += `<p><strong>${origemInfo.poder_3_nome}:</strong> ${origemInfo.poder_3_desc || ''}</p>`;
                    if (descricoes === "") {
                        descricoes = "<p>Descrição não disponível.</p>";
                    }
                    htmlConteudo += descricoes;
                } else {
                    htmlConteudo += `<p>Selecione uma origem.</p>`;
                }
                poderOrigemDisplayDiv.innerHTML = htmlConteudo;
            }

            function atualizarPoderesDivindade() {
                const divindadeIdSelecionada = divindadeSelect.value;
                if (!poderesDivindadeDisplayDiv) return;
                const h4 = poderesDivindadeDisplayDiv.querySelector('h4');
                poderesDivindadeDisplayDiv.innerHTML = '';
                if (h4) poderesDivindadeDisplayDiv.appendChild(h4);
                if (divindadeIdSelecionada && divindadesDataT20[divindadeIdSelecionada]) {
                    let htmlPoderes = "";
                    const idsPoderes = divindadePoderesLinks.filter(link => link.divindade_id == divindadeIdSelecionada).map(link => link.poder_divino_id);
                    if (idsPoderes.length > 0) {
                        idsPoderes.forEach(poderId => {
                            const poderInfo = poderesDivinosDataT20[poderId];
                            if (poderInfo) {
                                htmlPoderes += `<div class="poder-item"><h4>${poderInfo.nome} (Divino)</h4><p>${poderInfo.descricao || ''}</p></div>`;
                            }
                        });
                    } else {
                        htmlPoderes = "<p>Esta divindade não concede poderes conhecidos.</p>";
                    }
                    poderesDivindadeDisplayDiv.insertAdjacentHTML('beforeend', htmlPoderes);
                } else {
                    poderesDivindadeDisplayDiv.insertAdjacentHTML('beforeend', '<p>Selecione uma divindade para ver seus poderes.</p>');
                }
            }

            function atualizarHabilidadesClasseAuto(classeId, nivel) {
                if (!habilidadesClasseAutoDisplayDiv) return;
                const h4 = habilidadesClasseAutoDisplayDiv.querySelector('h4');
                habilidadesClasseAutoDisplayDiv.innerHTML = '';
                if (h4) habilidadesClasseAutoDisplayDiv.appendChild(h4);
                if (classeId > 0) {
                    const habilidadesGanhast = habilidadesClasseAutoData.filter(hab => hab.classe_id == classeId && hab.nivel_obtido <= nivel);
                    let htmlHabilidades = "";
                    if (habilidadesGanhast.length > 0) {
                        habilidadesGanhast.forEach(hab => {
                            htmlHabilidades += `<div class="poder-item"><h4>${hab.nome} (Nível ${hab.nivel_obtido})</h4><p>${hab.descricao || ''}</p></div>`;
                        });
                    } else {
                        htmlHabilidades = "<p>Nenhuma habilidade automática de classe neste nível.</p>";
                    }
                    habilidadesClasseAutoDisplayDiv.insertAdjacentHTML('beforeend', htmlHabilidades);
                } else {
                    habilidadesClasseAutoDisplayDiv.insertAdjacentHTML('beforeend', '<p>Selecione uma classe.</p>');
                }
            }

            // *** INÍCIO: LÓGICA DO MODAL DE PODERES ***

            function renderizarPoderesEscolhidos() {
                if (!poderesEscolhidosDisplayDiv) return;
                const h4 = poderesEscolhidosDisplayDiv.querySelector('h4');
                poderesEscolhidosDisplayDiv.innerHTML = '';
                if (h4) poderesEscolhidosDisplayDiv.appendChild(h4);

                if (poderesEscolhidos.length === 0) {
                    poderesEscolhidosDisplayDiv.insertAdjacentHTML('beforeend', '<p>(Nenhum poder adquirido ainda)</p>');
                    return;
                }

                poderesEscolhidos.forEach((poderRef, index) => {
                    let poderInfo = null;
                    let tipoLabel = "";
                    if (poderRef.tipo_poder === 'classe') {
                        poderInfo = poderesClasseData.find(p => p.id == poderRef.poder_id);
                        tipoLabel = "Classe";
                    } else if (poderRef.tipo_poder === 'geral') {
                        poderInfo = poderesGeraisData.find(p => p.id == poderRef.poder_id);
                        tipoLabel = poderInfo ? poderInfo.categoria : "Geral";
                    }
                    if (poderInfo) {
                        const poderDiv = document.createElement('div');
                        poderDiv.className = 'poder-item poder-escolhido';
                        poderDiv.innerHTML = `
                <button type="button" class="btn-remover-poder" onclick="removerPoder(${index})">X</button>
                <h4>${poderInfo.nome} (${tipoLabel})</h4>
                <p>${poderInfo.descricao}</p>
                ${poderInfo.pre_requisito ? `<p class="pre-requisito"><strong>Pré-requisito:</strong> ${poderInfo.pre_requisito}</p>` : ''}
            `;
                        poderesEscolhidosDisplayDiv.appendChild(poderDiv);
                    }
                });
            }

            function criarHtmlPoder(poder, tipoPoder) {
                const existe = poderesEscolhidos.some(p => p.poder_id == poder.id && p.tipo_poder === tipoPoder);
                const desabilitado = existe ? 'disabled' : '';
                const textoBotao = existe ? 'Adicionado' : 'Adicionar';
                return `<div class="poder-item-modal">
                <div class="poder-item-modal-info">
                    <h4>${poder.nome}</h4>
                    <p>${poder.descricao}</p>
                    ${poder.pre_requisito ? `<p class="pre-requisito"><strong>Pré-requisito:</strong> ${poder.pre_requisito}</p>` : ''}
                </div>
                <button type="button" class="btn-adicionar-poder" onclick="adicionarPoder(${poder.id}, '${tipoPoder}')" ${desabilitado}>
                    ${textoBotao}
                </button>
            </div>`;
            }

            function popularModalPoderes() {
                const filtro = filtroPoderNome.value.toLowerCase();
                const classeId = classeSelect.value;

                const containerClasse = document.getElementById('modal-tab-classe');
                if (containerClasse) {
                    containerClasse.innerHTML = '';
                    if (classeId) {
                        const poderesDeClasseFiltrados = poderesClasseData.filter(p => p.classe_id == classeId && p.nome.toLowerCase().includes(filtro));
                        if (poderesDeClasseFiltrados.length > 0) {
                            poderesDeClasseFiltrados.forEach(p => containerClasse.innerHTML += criarHtmlPoder(p, 'classe'));
                        } else {
                            containerClasse.innerHTML = '<p>Nenhum poder de classe encontrado.</p>';
                        }
                    } else {
                        containerClasse.innerHTML = '<p>Selecione uma classe na ficha principal primeiro.</p>';
                    }
                }

                const containerCombate = document.getElementById('modal-tab-combate');
                if (containerCombate) {
                    containerCombate.innerHTML = '';
                    const poderesCombate = poderesGeraisData.filter(p => p.categoria === 'Combate' && p.nome.toLowerCase().includes(filtro));
                    if (poderesCombate.length > 0) {
                        poderesCombate.forEach(p => containerCombate.innerHTML += criarHtmlPoder(p, 'geral'));
                    } else {
                        containerCombate.innerHTML = '<p>Nenhum poder de combate encontrado.</p>';
                    }
                }

                const containerDestino = document.getElementById('modal-tab-destino');
                if (containerDestino) {
                    containerDestino.innerHTML = '';
                    const poderesDestino = poderesGeraisData.filter(p => p.categoria === 'Destino' && p.nome.toLowerCase().includes(filtro));
                    if (poderesDestino.length > 0) {
                        poderesDestino.forEach(p => containerDestino.innerHTML += criarHtmlPoder(p, 'geral'));
                    } else {
                        containerDestino.innerHTML = '<p>Nenhum poder de destino encontrado.</p>';
                    }
                }

                const containerMagia = document.getElementById('modal-tab-magia');
                if (containerMagia) {
                    containerMagia.innerHTML = '';
                    const poderesMagia = poderesGeraisData.filter(p => p.categoria === 'Magia' && p.nome.toLowerCase().includes(filtro));
                    if (poderesMagia.length > 0) {
                        poderesMagia.forEach(p => containerMagia.innerHTML += criarHtmlPoder(p, 'geral'));
                    } else {
                        containerMagia.innerHTML = '<p>Nenhum poder de magia encontrado.</p>';
                    }
                }
            }

            window.adicionarPoder = function(poderId, tipoPoder) {
                const idNum = parseInt(poderId);
                const existe = poderesEscolhidos.some(p => p.poder_id == idNum && p.tipo_poder === tipoPoder);
                if (!existe) {
                    poderesEscolhidos.push({
                        poder_id: idNum,
                        tipo_poder: tipoPoder
                    });
                    renderizarPoderesEscolhidos();
                    calcularTudoT20();
                    popularModalPoderes();
                }
            }

            window.removerPoder = function(index) {
                poderesEscolhidos.splice(index, 1);
                renderizarPoderesEscolhidos();
                calcularTudoT20();
                popularModalPoderes();
            }

            // Modal Inventario
            function renderizarInventario() {
                if (!inventarioListaDiv) {
                    console.error("Div #lista-inventario não encontrada.");
                    return;
                }

                // Limpa a lista (mantendo o cabeçalho)
                // Pega todos os filhos, exceto o primeiro (o cabeçalho)
                const itensParaRemover = Array.from(inventarioListaDiv.children).slice(1);
                itensParaRemover.forEach(child => inventarioListaDiv.removeChild(child));


                if (inventarioAtual.length === 0) {
                    inventarioListaDiv.insertAdjacentHTML('beforeend', '<div class="item-inventario-vazio"><p>Inventário vazio.</p></div>');
                    return; // Sai da função após mostrar a mensagem
                }

                inventarioAtual.forEach((item, index) => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'item-inventario';
                    const isEquipado = item.equipado ? 'checked' : '';

                    // As classes (ex: .item-equipado-check) correspondem ao CSS
                    itemDiv.innerHTML = `
                <input type="checkbox" class="item-equipado-check" onchange="alternarEquipado(${index})" ${isEquipado}>
                <span class="item-nome">${item.nome}</span>
                <input type="number" class="item-quantidade" value="${item.quantidade || 1}" min="1" onchange="mudarQuantidade(${index}, this.value)">
                <span class="item-espacos">${(item.espacos || 0) * (item.quantidade || 1)}</span>
                <button type="button" class="btn-remover-item" onclick="removerItemInventario(${index})">X</button>
            `;
                    inventarioListaDiv.appendChild(itemDiv);
                });
            }

            // Popula o modal de itens com base nos filtros
            function popularModalItens() {
                const filtro = filtroItemNome.value.toLowerCase();
                const tipoFiltroAtivo = document.querySelector('#modal-adicionar-item-t20 .modal-tab-button.active');
                if (!tipoFiltroAtivo) {
                    console.error("Bug de Abas: Nenhuma aba de item ativa encontrada. O modal de itens não pode ser populado.");
                    // Opcional: força a primeira aba a ficar ativa
                    // const primeiraAba = document.querySelector('#modal-adicionar-item-t20 .modal-tab-button');
                    // if(primeiraAba) primeiraAba.classList.add('active');
                    // ...e re-chama a função. Mas por enquanto, só parar é mais seguro.
                    return;
                }
                const tipoFiltro = tipoFiltroAtivo.dataset.tabModal;


                const containers = {
                    'modal-tab-armas': document.getElementById('modal-tab-armas'),
                    'modal-tab-armaduras': document.getElementById('modal-tab-armaduras'),
                    'modal-tab-escudos': document.getElementById('modal-tab-escudos'),
                    'modal-tab-geral': document.getElementById('modal-tab-geral')
                };

                // Limpa containers
                for (const key in containers) {
                    if (containers[key]) containers[key].innerHTML = '';
                }

                // Mapeia tipo do botão para tipo do item no banco
                const mapTipo = {
                    'modal-tab-armas': 'Arma',
                    'modal-tab-armaduras': 'Armadura',
                    'modal-tab-escudos': 'Escudo',
                    'modal-tab-geral': 'Item Geral'
                };
                const tipoItemAtual = mapTipo[tipoFiltro];

                // Filtra e exibe os itens
                const todosItensArray = Object.values(todosOsItensT20); // Converte objeto em array
                const itensFiltrados = todosItensArray.filter(item =>
                    item.tipo === tipoItemAtual && item.nome.toLowerCase().includes(filtro)
                );

                const containerAtual = containers[tipoFiltro];
                if (containerAtual) {
                    if (itensFiltrados.length === 0) {
                        containerAtual.innerHTML = '<p>Nenhum item encontrado.</p>';
                        return;
                    }
                    itensFiltrados.forEach(item => {
                        containerAtual.innerHTML += `
                    <div class="item-modal">
                        <div class="item-modal-info">
                            <h4>${item.nome} (Espaços: ${item.espacos})</h4>
                            <p>${item.descricao || ''}</p>
                            ${item.bonus_defesa ? `<p class="item-detalhe"><strong>Defesa:</strong> +${item.bonus_defesa}</p>` : ''}
                            ${item.dano ? `<p class="item-detalhe"><strong>Dano:</strong> ${item.dano} | <strong>Crít:</strong> ${item.critico}</p>` : ''}
                        </div>
                        <button type="button" class="btn-adicionar" onclick="adicionarItemInventario(${item.id})">Adicionar</button>
                    </div>
                `;
                    });
                }
            }

            // Funções chamadas pelos botões (globais)
            window.adicionarItemInventario = function(itemId) {
                const itemInfo = todosOsItensT20[itemId];
                if (!itemInfo) return;

                const itemParaAdicionar = JSON.parse(JSON.stringify(itemInfo)); // Cria cópia
                itemParaAdicionar.quantidade = 1;
                itemParaAdicionar.equipado = 0; // Inicia desequipado
                inventarioAtual.push(itemParaAdicionar);

                calcularTudoT20();
            }

            window.removerItemInventario = function(index) {
                inventarioAtual.splice(index, 1);
                calcularTudoT20();
            }

            window.mudarQuantidade = function(index, novaQuantidade) {
                if (inventarioAtual[index]) {
                    inventarioAtual[index].quantidade = parseInt(novaQuantidade) || 1;
                    calcularTudoT20();
                }
            }

            window.alternarEquipado = function(index) {
                if (inventarioAtual[index]) {
                    inventarioAtual[index].equipado = !inventarioAtual[index].equipado; // Inverte o valor

                    // Lógica de desequipar outras armaduras
                    if (inventarioAtual[index].tipo === 'Armadura' && inventarioAtual[index].equipado) {
                        inventarioAtual.forEach((item, i) => {
                            if (i !== index && item.tipo === 'Armadura') item.equipado = 0;
                        });
                    }
                    // Lógica de desequipar outros escudos
                    if (inventarioAtual[index].tipo === 'Escudo' && inventarioAtual[index].equipado) {
                        inventarioAtual.forEach((item, i) => {
                            if (i !== index && item.tipo === 'Escudo') item.equipado = 0;
                        });
                    }

                    calcularTudoT20(); // Recalcula
                }
            }

            // --- EVENT LISTENERS ---
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    button.classList.add('active');
                    const targetContent = document.getElementById(button.dataset.tab);
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });

            if (racaSelect) {
                racaSelect.addEventListener('change', () => {
                    mostrarAjustesRaciais();
                    atualizarHabilidadesRaciais();
                    calcularTudoT20();
                });
            }
            if (nivelInput) {
                nivelInput.addEventListener('input', calcularTudoT20);
            }
            atributosInputs.forEach(input => {
                input.addEventListener('input', calcularTudoT20);
            });
            if (classeSelect) {
                classeSelect.addEventListener('change', () => {
                    atualizarClasseCSS();
                    calcularTudoT20();
                    popularModalPoderes();
                });
            }
            if (origemSelect) {
                origemSelect.addEventListener('change', () => {
                    atualizarPoderOrigemT20();
                    calcularTudoT20(); // Adicionado para recalcular bônus de origem
                });
            }
            if (divindadeSelect) {
                divindadeSelect.addEventListener('change', () => {
                    atualizarPoderesDivindade();
                    calcularTudoT20(); // Adicionado para recalcular bônus de divindade
                });
            }

            if (periciasItems) {
                periciasItems.forEach(item => {
                    const checkboxTreino = item.querySelector('.pericia-treino');
                    const inputOutros = item.querySelector('.pericia-outros');
                    if (checkboxTreino) {
                        checkboxTreino.addEventListener('change', calcularTudoT20);
                    }
                    if (inputOutros) {
                        inputOutros.addEventListener('input', calcularTudoT20);
                    }
                });
            }

            // --- LISTENERS DO MODAL DE PODERES ---
            if (btnAbrirModalPoderes) {
                btnAbrirModalPoderes.addEventListener('click', () => {
                    popularModalPoderes(); // Popula antes de abrir
                    if (modalAdicionarPoder) modalAdicionarPoder.style.display = 'flex';
                });
            }
            if (btnFecharModalPoderes) {
                btnFecharModalPoderes.addEventListener('click', () => {
                    if (modalAdicionarPoder) modalAdicionarPoder.style.display = 'none';
                });
            }
            if (filtroPoderNome) {
                filtroPoderNome.addEventListener('input', popularModalPoderes);
            }
            // Este listener agora só afeta as abas do modal de PODERES
            if (modalTabs) {
                modalTabs.forEach(button => {
                    button.addEventListener('click', () => {
                        modalTabs.forEach(btn => btn.classList.remove('active'));
                        modalTabContents.forEach(content => content.classList.remove('active'));
                        button.classList.add('active');
                        const targetContent = document.getElementById(button.dataset.tabModal);
                        if (targetContent) {
                            targetContent.classList.add('active');
                        }
                    });
                });
            }

            // --- LISTENERS DO MODAL DE ITENS ---
            if (btnAbrirModalItem) {
                btnAbrirModalItem.addEventListener('click', () => {
                    popularModalItens();
                    if (modalAdicionarItem) modalAdicionarItem.style.display = 'flex';
                });
            }
            if (btnFecharModalItem) {
                btnFecharModalItem.addEventListener('click', () => {
                    if (modalAdicionarItem) modalAdicionarItem.style.display = 'none';
                });
            }
            if (filtroItemNome) {
                filtroItemNome.addEventListener('input', popularModalItens);
            }
            // Este listener só afeta as abas do modal de ITENS
            if (modalItemTabs) {
                modalItemTabs.forEach(button => {
                    button.addEventListener('click', () => {
                        modalItemTabs.forEach(btn => btn.classList.remove('active'));
                        modalItemTabContents.forEach(content => content.classList.remove('active'));
                        button.classList.add('active');
                        const targetContent = document.getElementById(button.dataset.tabModal);
                        if (targetContent) targetContent.classList.add('active');
                        popularModalItens(); // Re-popula ao trocar de aba
                    });
                });
            }

            // --- (Listeners da Imagem iriam aqui, se você os adicionou) ---


            // --- INICIALIZAÇÃO ---
            mostrarAjustesRaciais();
            atualizarClasseCSS();
            atualizarPoderOrigemT20();
            atualizarHabilidadesRaciais();
            atualizarPoderesDivindade();
            renderizarPoderesEscolhidos();
            // renderizarInventario(); // Removido, pois calcularTudoT20() já chama
            calcularTudoT20();
        });
    </script>

</body>

</html>