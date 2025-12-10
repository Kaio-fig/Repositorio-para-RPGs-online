<?php
// ficha_t20.php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login/login.php");
    exit();
}

require_once '../conection/db_connect.php';
$user_id = $_SESSION['user_id'];

$personagem_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$personagem_t20 = null;
$poderes_personagem_t20 = array();
$inventario_personagem_t20 = array();
$magias_personagem_t20 = array();

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
        echo "Personagem não encontrado ou não autorizado.";
        exit();
    }
    $stmt->close();

    // 2. Carregar poderes escolhidos
    $stmt_poderes = $conn->prepare("SELECT poder_id, tipo_poder FROM personagem_t20_poderes WHERE personagem_id = ?");
    $stmt_poderes->bind_param("i", $personagem_id);
    $stmt_poderes->execute();
    $result_poderes = $stmt_poderes->get_result();
    while ($row = $result_poderes->fetch_assoc()) {
        $poderes_personagem_t20[] = $row;
    }
    $stmt_poderes->close();

    // 3. Carregar inventário
    $stmt_inv = $conn->prepare(
        "SELECT inv.item_id as id, inv.quantidade, inv.equipado, item.nome, item.espacos, item.bonus_carga, item.tipo, item.bonus_defesa, item.penalidade_armadura 
         FROM personagem_t20_inventario AS inv 
         JOIN t20_itens AS item ON inv.item_id = item.id 
         WHERE inv.personagem_id = ?"
    );
    $stmt_inv->bind_param("i", $personagem_id);
    $stmt_inv->execute();
    $result_inv = $stmt_inv->get_result();
    while ($row = $result_inv->fetch_assoc()) {
        $inventario_personagem_t20[] = $row;
    }
    $stmt_inv->close();

    // 4. Carregar magias conhecidas
    $stmt_magias = $conn->prepare("SELECT magia_id FROM personagem_t20_magias WHERE personagem_id = ?");
    $stmt_magias->bind_param("i", $personagem_id);
    $stmt_magias->execute();
    $result_magias = $stmt_magias->get_result();
    while ($row = $result_magias->fetch_assoc()) {
        $magias_personagem_t20[] = $row;
    }
    $stmt_magias->close();
} else {
    // --- MODO DE CRIAÇÃO (DADOS EM BRANCO) ---
    $personagem_t20 = array(
        'id' => 0,
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
    $magias_personagem_t20 = array();
}

// --- CARREGAR DADOS GERAIS DO JOGO (Para Modais e Selects) ---

function fetch_all_by_key($result)
{
    $data = array();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[$row['id']] = $row;
        }
    }
    return $data;
}

function fetch_all_safe($result)
{
    $data = array();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}


// Racas
$result_racas = $conn->query("SELECT * FROM t20_racas");
$racas_t20 = fetch_all_by_key($result_racas);

// Classes
$result_classes = $conn->query("SELECT * FROM t20_classes ORDER BY nome ASC");
$classes_t20 = fetch_all_by_key($result_classes);

// Origens
$result_origens = $conn->query("SELECT * FROM t20_origens");
$origens_t20 = fetch_all_by_key($result_origens);

// Divindades
$result_divindades = $conn->query("SELECT * FROM t20_divindades");
$divindades_t20 = fetch_all_by_key($result_divindades);

// Poderes Divinos (e links)
$result_poderes_divinos = $conn->query("SELECT * FROM t20_poderes_divinos");
$poderes_divinos_t20 = fetch_all_by_key($result_poderes_divinos);
$result_divindade_links = $conn->query("SELECT * FROM t20_divindade_poderes");
$divindade_poderes_links = $result_divindade_links ? fetch_all_safe($result_divindade_links) : array();

// Poderes e Habilidades de Classe
$result_hab_auto = $conn->query("SELECT * FROM t20_habilidades_classe_auto");
$habilidades_classe_auto_t20 = $result_hab_auto ? fetch_all_safe($result_hab_auto) : array();
$result_poderes_classe = $conn->query("SELECT * FROM t20_poderes_classe");
$poderes_classe_t20 = $result_poderes_classe ? fetch_all_safe($result_poderes_classe) : array();

// Poderes Gerais
$result_poderes_gerais = $conn->query("SELECT * FROM t20_poderes_gerais");
$poderes_gerais_t20 = $result_poderes_gerais ? fetch_all_safe($result_poderes_gerais) : array();

// Magias
$result_magias = $conn->query("SELECT * FROM t20_magias ORDER BY circulo, nome");
$magias_t20 = $result_magias ? fetch_all_by_key($result_magias) : array();

// Itens
$stmt_itens = $conn->prepare("SELECT * FROM t20_itens WHERE user_id IS NULL OR user_id = ?");
$stmt_itens->bind_param("i", $user_id);
$stmt_itens->execute();
$result_itens = $stmt_itens->get_result();
$itens_t20 = fetch_all_by_key($result_itens);
$stmt_itens->close();

$conn->close();

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/ficha_t20.css">
    <title>Ficha T20 - <?= htmlspecialchars($personagem_t20['nome']) ?></title>
</head>

<body>

    <div class="ficha-container" id="ficha-t20-container">
        <form id="ficha-t20-form" method="POST" action="../conection/save_character_t20.php" enctype="multipart/form-data">
            <input type="hidden" name="personagem_id" value="<?= htmlspecialchars($personagem_t20['id']) ?>">

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
                        <option value="">(Nenhuma)</option>
                        <?php foreach ($divindades_t20 as $id_divindade => $divindade): ?>
                            <option value="<?= $id_divindade ?>" <?= (isset($personagem_t20['divindade_id']) && $personagem_t20['divindade_id'] == $id_divindade) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($divindade['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </header>

            <div class="conteudo-grid">
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
                            <div class="valor">
                                <input type="number" name="pv_atual" id="pv_atual_input" class="status-atual" value="<?= htmlspecialchars($personagem_t20['pv_atual']) ?>">
                                <span>/</span>
                                <span id="pv_max_span"><?= htmlspecialchars($personagem_t20['pv_max']) ?></span>
                            </div>
                            <input type="hidden" id="pv_max_hidden" name="pv_max" value="<?= htmlspecialchars($personagem_t20['pv_max']) ?>">
                        </div>
                        <div class="status-box">
                            <label>💧 PM</label>
                            <div class="valor">
                                <input type="number" name="pm_atual" id="pm_atual_input" class="status-atual" value="<?= htmlspecialchars($personagem_t20['pm_atual']) ?>">
                                <span>/</span>
                                <span id="pm_max_span"><?= htmlspecialchars($personagem_t20['pm_max']) ?></span>
                            </div>
                            <input type="hidden" id="pm_max_hidden" name="pm_max" value="<?= htmlspecialchars($personagem_t20['pm_max']) ?>">
                        </div>
                        <div class="status-box">
                            <label>🛡️ Defesa</label>
                            <div class="valor" id="defesa_total">--</div>
                        </div>
                    </div>
                </aside>

                <main class="coluna-principal">
                    <nav class="abas-nav">
                        <button type="button" class="tab-button active" data-tab="tab-atributos">Atributos & Perícias</button>
                        <button type="button" class="tab-button" data-tab="tab-poderes">Poderes</button>
                        <button type="button" class="tab-button" data-tab="tab-magias">Magias</button>
                        <button type="button" class="tab-button" data-tab="tab-equipamento">Equipamento</button>
                    </nav>

                    <div id="tab-atributos" class="tab-content active">
                        <div class="bloco">
                            <h3>Atributos</h3>
                            <div class="atributos-grid">
                                <?php foreach (array('forca', 'destreza', 'constituicao', 'inteligencia', 'sabedoria', 'carisma') as $attr): ?>
                                    <div class="atributo">
                                        <label><?= strtoupper($attr) ?></label>
                                        <input type="number" class="atributo-valor" id="<?= $attr ?>" name="<?= $attr ?>" value="<?= htmlspecialchars($personagem_t20[$attr]) ?>" min="-5" max="10">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="bloco">
                            <h3>Perícias</h3>
                            <div class="pericias-container" id="lista-pericias">
                                <!-- Cabeçalho da Tabela -->
                                <div class="pericia-item header">
                                    <span>Perícia</span>
                                    <span>Total</span>
                                    <span>1/2 Nvl</span>
                                    <span>Mod. Atr.</span>
                                    <span>Treino</span>
                                    <span>Outros</span>
                                </div>

                                <?php
                                // A "lógica" PHP principal das perícias está AQUI, no HTML.
                                $lista_completa_pericias = [
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
                                    ['nome' => 'Ofício', 'attr' => 'INT', 'id_sufixo' => 'oficio', 'so_treinado' => true],
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
                                    <?php
                                    // PHP para buscar os valores salvos
                                    $sufixo = $pericia['id_sufixo'];
                                    // CORREÇÃO PHP 5.4 (sem '??')
                                    $treino_val = isset($personagem_t20["treino_$sufixo"]) ? $personagem_t20["treino_$sufixo"] : 0;
                                    $outros_val = isset($personagem_t20["outros_$sufixo"]) ? $personagem_t20["outros_$sufixo"] : 0;
                                    $is_treinado = $treino_val ? 'checked' : '';
                                    ?>
                                    <!-- A tag 'data-atributo-chave' é o link com o JS -->
                                    <div class="pericia-item" data-atributo-chave="<?= strtolower($pericia['attr']) ?>" id="item_<?= $sufixo ?>">
                                        <span class="pericia-nome">
                                            <?= htmlspecialchars($pericia['nome']) ?>
                                            <span class="pericia-attr">(<?= $pericia['attr'] ?>)</span>
                                            <?= isset($pericia['so_treinado']) && $pericia['so_treinado'] ? '<span class="so-treinado-marker">*</span>' : '' ?>
                                        </span>
                                        <strong class="pericia-total" id="total_<?= $sufixo ?>">0</strong>
                                        <span class="pericia-metade-nivel" id="nivel_<?= $sufixo ?>">0</span>
                                        <span class="pericia-mod-attr" id="mod_<?= $sufixo ?>">0</span>

                                        <!-- Inputs que guardam o estado e são enviados para o 'save' -->
                                        <input type="checkbox" class="pericia-treino" id="treino_<?= $sufixo ?>" name="treino_<?= $sufixo ?>" <?= $is_treinado ?> value="1">
                                        <input type="number" class="pericia-outros" id="outros_<?= $sufixo ?>" name="outros_<?= $sufixo ?>" value="<?= htmlspecialchars($outros_val) ?>" min="-20" max="20">
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>

                    <div id="tab-poderes" class="tab-content">
                        <div class="bloco">
                            <h3>Poderes e Habilidades</h3>
                            <div id="habilidades-raciais-lista">
                                <h4>Habilidades de Raça</h4>
                                <p>Selecione uma raça.</p>
                            </div>
                            <div id="poderes-divindade-display" style="margin-top: 1.5rem;">
                                <h4>Poderes Concedidos</h4>
                                <p>Selecione uma divindade.</p>
                            </div>
                            <div id="poder-origem-display-t20" class="poder-item">
                                <h4>Poder de Origem</h4>
                                <p>Selecione uma origem.</p>
                            </div>
                            <div id="habilidades-classe-lista">
                                <h4>Habilidades de Classe</h4>
                                <p>Selecione uma classe.</p>
                            </div>
                            <div id="poderes-escolhidos-lista" style="margin-top: 1.5rem;">
                                <h4>Poderes Adquiridos</h4>
                                <p>(Nenhum poder adquirido ainda)</p>
                            </div>
                            <button type="button" class="btn btn-small" id="btn-abrir-modal-poderes" style="margin-top: 1rem;">Adicionar Poder</button>
                        </div>
                    </div>

                    <div id="tab-magias" class="tab-content">
                        <div class="bloco">
                            <h3>Magias Conhecidas</h3>
                            <div class="magias-conhecidas-lista" id="magias-conhecidas-lista">
                                <p>(Nenhuma magia conhecida)</p>
                            </div>
                            <button type="button" class="btn btn-small" id="btn-abrir-modal-magias" style="margin-top: 1rem;" disabled>Adicionar Magia</button>
                            <small id="magias-info-texto" style="display: block; margin-top: 0.5rem; color: #6c757d;">(Selecione uma Raça ou Classe conjuradora para habilitar)</small>
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
                                </tbody>
                            </table>
                        </div>

                        <div class="bloco">
                            <div class="inventario-header">
                                <h3>Equipamento</h3>
                                <div class="inventario-stats">
                                    <span>Carga: <strong id="carga_usada">0</strong> / <strong id="carga_maxima">0</strong></span>
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

    <div id="modal-adicionar-poder-t20" class="modal-overlay">
        <div class="modal-content modal-poderes">
            <h2>Adicionar Poder</h2>
            <nav class="modal-abas-nav">
                <button type="button" class="modal-tab-button active" data-tab-modal="modal-tab-classe">Classe</button>
                <button type="button" class="modal-tab-button" data-tab-modal="modal-tab-combate">Combate</button>
                <button type="button" class="modal-tab-button" data-tab-modal="modal-tab-destino">Destino</button>
                <button type="button" class="modal-tab-button" data-tab-modal="modal-tab-magia">Magia</button>
            </nav>
            <div class="modal-filtros-poderes">
                <input type="text" id="filtro-poder-nome-t20" placeholder="Buscar por nome...">
            </div>
            <div class="modal-lista-wrapper">
                <div id="modal-tab-classe" class="modal-tab-content active">
                    <p>Selecione uma classe...</p>
                </div>
                <div id="modal-tab-combate" class="modal-tab-content"></div>
                <div id="modal-tab-destino" class="modal-tab-content"></div>
                <div id="modal-tab-magia" class="modal-tab-content"></div>
            </div>
            <button type="button" class="btn btn-secondary" id="btn-fechar-modal-poderes" style="margin-top: 20px;">Fechar</button>
        </div>
    </div>

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
                <div id="modal-tab-armas" class="modal-tab-content active"></div>
                <div id="modal-tab-armaduras" class="modal-tab-content"></div>
                <div id="modal-tab-escudos" class="modal-tab-content"></div>
                <div id="modal-tab-geral" class="modal-tab-content"></div>
            </div>
            <button type="button" class="btn btn-secondary" id="btn-fechar-modal-item" style="margin-top: 20px;">Fechar</button>
        </div>
    </div>

    <div id="modal-adicionar-magia-t20" class="modal-overlay">
        <div class="modal-content modal-magias">
            <h2>Adicionar Magia</h2>

            <div class="modal-filtros-magias">
                <input type="text" id="filtro-magia-nome" placeholder="Buscar por nome...">
                <select id="filtro-magia-circulo">
                    <option value="">Todos Círculos</option>
                    <option value="1">1º Círculo</option>
                    <option value="2">2º Círculo</option>
                    <option value="3">3º Círculo</option>
                    <option value="4">4º Círculo</option>
                    <option value="5">5º Círculo</option>
                </select>
                <select id="filtro-magia-tipo">
                    <option value="">Todos Tipos</option>
                    <option value="Arcana">Arcana</option>
                    <option value="Divina">Divina</option>
                    <option value="Universal">Universal</option>
                </select>
            </div>

            <div class="modal-lista-wrapper" id="modal-lista-magias">
                <p>Carregando magias...</p>
            </div>

            <button type="button" class="btn btn-secondary" id="btn-fechar-modal-magias" style="margin-top: 20px;">Fechar</button>
        </div>
    </div>


    <script>
        // FIX JS: Convertido para JS compatível com PHP 5.4 (sem 'let', 'const', '()=>' ou 'new Set()')
        document.addEventListener('DOMContentLoaded', function() {
            // --- DADOS DO PHP ---
            var racasData = <?= json_encode(isset($racas_t20) ? $racas_t20 : new stdClass()) ?>;
            var classesDataT20 = <?= json_encode(isset($classes_t20) ? $classes_t20 : new stdClass()) ?>;
            var origensDataT20 = <?= json_encode(isset($origens_t20) ? $origens_t20 : new stdClass()) ?>;
            var divindadesDataT20 = <?= json_encode(isset($divindades_t20) ? $divindades_t20 : new stdClass()) ?>;
            var poderesDivinosDataT20 = <?= json_encode(isset($poderes_divinos_t20) ? $poderes_divinos_t20 : new stdClass()) ?>;
            var divindadePoderesLinks = <?= json_encode(isset($divindade_poderes_links) ? $divindade_poderes_links : array()) ?>;
            var habilidadesClasseAutoData = <?= json_encode(isset($habilidades_classe_auto_t20) ? $habilidades_classe_auto_t20 : array()) ?>;
            var todosOsItensT20 = <?= json_encode(isset($itens_t20) ? $itens_t20 : new stdClass()) ?>;
            var todasAsMagias = <?= json_encode(isset($magias_t20) ? $magias_t20 : new stdClass()) ?>;
            var magiasIniciaisPersonagem = <?= json_encode(isset($magias_personagem_t20) ? $magias_personagem_t20 : array()) ?>;
            const poderesPersonagemData = <?= json_encode(isset($poderes_personagem_t20) ? $poderes_personagem_t20 : []) ?>;
            const poderesGeraisData = <?= json_encode(isset($poderes_gerais_t20) ? $poderes_gerais_t20 : []) ?>;
            const poderesClasseData = <?= json_encode(isset($poderes_classe_t20) ? $poderes_classe_t20 : []) ?>;
            const inventarioInicialPersonagemT20 = <?= json_encode(isset($inventario_personagem_t20) ? $inventario_personagem_t20 : []) ?>;


            // --- ELEMENTOS DO DOM ---
            var fichaContainer = document.getElementById('ficha-t20-container');
            var form = document.getElementById('ficha-t20-form');
            var tabButtons = document.querySelectorAll('.tab-button');
            var tabContents = document.querySelectorAll('.tab-content');
            var racaSelect = document.getElementById('raca_id');
            var classeSelect = document.getElementById('classe_id');
            var origemSelect = document.getElementById('origem_id');
            var divindadeSelect = document.getElementById('divindade_id');
            const nivelInput = document.getElementById('nivel');
            const atributosInputs = document.querySelectorAll('.atributo-valor');
            const periciasItems = document.querySelectorAll('.pericia-item:not(.header)');
            const tabelaAtaquesBody = document.querySelector("#tabela-ataques tbody");

            // Spans e Inputs de Status
            var pvAtualInput = document.getElementById('pv_atual_input');
            var pvMaxSpan = document.getElementById('pv_max_span');
            var pvMaxHidden = document.getElementById('pv_max_hidden');
            var pmAtualInput = document.getElementById('pm_atual_input');
            var pmMaxSpan = document.getElementById('pm_max_span');
            var pmMaxHidden = document.getElementById('pm_max_hidden');
            var defesaTotalSpan = document.getElementById('defesa_total');

            // Divs de Poderes
            var ajustesInfoDiv = document.getElementById('ajustes-raciais-info');
            var poderOrigemDisplayDiv = document.getElementById('poder-origem-display-t20');
            var habilidadesRaciaisDisplayDiv = document.getElementById('habilidades-raciais-lista');
            var poderesDivindadeDisplayDiv = document.getElementById('poderes-divindade-display');
            var habilidadesClasseAutoDisplayDiv = document.getElementById('habilidades-classe-lista');
            var poderesEscolhidosDisplayDiv = document.getElementById('poderes-escolhidos-lista');

            // Modal de Poderes
            var modalAdicionarPoder = document.getElementById('modal-adicionar-poder-t20');
            var btnAbrirModalPoderes = document.getElementById('btn-abrir-modal-poderes');
            var btnFecharModalPoderes = document.getElementById('btn-fechar-modal-poderes');
            var modalTabs = document.querySelectorAll('#modal-adicionar-poder-t20 .modal-tab-button');
            var modalTabContents = document.querySelectorAll('#modal-adicionar-poder-t20 .modal-tab-content');
            var filtroPoderNome = document.getElementById('filtro-poder-nome-t20');

            // Modal de Itens
            var modalAdicionarItem = document.getElementById('modal-adicionar-item-t20');
            var btnAbrirModalItem = document.getElementById('btn-abrir-modal-item');
            var btnFecharModalItem = document.getElementById('btn-fechar-modal-item');
            var modalItemTabs = document.querySelectorAll('#modal-adicionar-item-t20 .modal-tab-button');
            var modalItemTabContents = document.querySelectorAll('#modal-adicionar-item-t20 .modal-tab-content');
            var filtroItemNome = document.getElementById('filtro-item-nome-t20');
            var cargaUsadaSpan = document.getElementById('carga_usada');
            var cargaMaximaSpan = document.getElementById('carga_maxima');
            var inventarioListaDiv = document.getElementById('lista-inventario');

            // Elementos de Magias
            var btnAbrirModalMagias = document.getElementById('btn-abrir-modal-magias');
            var magiasInfoTexto = document.getElementById('magias-info-texto');
            var magiasConhecidasDisplayDiv = document.getElementById('magias-conhecidas-lista');
            var modalAdicionarMagia = document.getElementById('modal-adicionar-magia-t20');
            var btnFecharModalMagias = document.getElementById('btn-fechar-modal-magias');
            var filtroMagiaNome = document.getElementById('filtro-magia-nome');
            var filtroMagiaCirculo = document.getElementById('filtro-magia-circulo');
            var filtroMagiaTipo = document.getElementById('filtro-magia-tipo');
            var modalListaMagiasDiv = document.getElementById('modal-lista-magias');

            // Imagem
            var btnImportar = document.getElementById('btn-importar-imagem-t20');
            var inputImagem = document.getElementById('input-imagem-t20');
            var previewImagem = document.getElementById('preview-imagem-t20');
            var containerImagem = document.getElementById('container-imagem-t20');
            if (btnImportar && inputImagem) btnImportar.addEventListener('click', function() {
                inputImagem.click();
            });
            if (containerImagem && inputImagem) containerImagem.addEventListener('click', function() {
                inputImagem.click();
            });
            if (inputImagem) {
                inputImagem.addEventListener('change', function(event) {
                    var file = event.target.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            if (previewImagem) previewImagem.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // --- ESTADO GLOBAL DA FICHA ---
            let poderesEscolhidos = [...(poderesPersonagemData || [])];
            let inventarioAtual = [...inventarioInicialPersonagemT20];
            var magiasConhecidas = magiasIniciaisPersonagem.map(function(m) {
                return m.magia_id.toString();
            });


            // --- MAPAS ---
            const mapAtributoChave = {
                'for': 'forca',
                'des': 'destreza',
                'con': 'constituicao',
                'int': 'inteligencia',
                'sab': 'sabedoria',
                'car': 'carisma'
            };

            // --- LÓGICA DE ENVIO DO FORMULÁRIO ---
            form.addEventListener('submit', function(event) {
                var inputsParaRemover = form.querySelectorAll('input[name^="poderes_escolhidos_"], input[name^="inv_item_"], input[name^="magias_conhecidas_id"]');
                for (var i = 0; i < inputsParaRemover.length; i++) {
                    inputsParaRemover[i].remove();
                }

                poderesEscolhidos.forEach(function(poder) {
                    var inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'poderes_escolhidos_id[]';
                    inputId.value = poder.poder_id;
                    form.appendChild(inputId);

                    var inputType = document.createElement('input');
                    inputType.type = 'hidden';
                    inputType.name = 'poderes_escolhidos_tipo[]';
                    inputType.value = poder.tipo_poder;
                    form.appendChild(inputType);
                });

                inventarioAtual.forEach(function(item) {
                    var inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'inv_item_id[]';
                    inputId.value = item.id;
                    form.appendChild(inputId);

                    var inputQtd = document.createElement('input');
                    inputQtd.type = 'hidden';
                    inputQtd.name = 'inv_quantidade[]';
                    inputQtd.value = item.quantidade || 1;
                    form.appendChild(inputQtd);

                    var inputEquip = document.createElement('input');
                    inputEquip.type = 'hidden';
                    inputEquip.name = 'inv_equipado[]';
                    inputEquip.value = item.equipado ? '1' : '0';
                    form.appendChild(inputEquip);
                });

                magiasConhecidas.forEach(function(magiaId) {
                    var inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'magias_conhecidas_id[]';
                    inputId.value = magiaId;
                    form.appendChild(inputId);
                });

                if (pvMaxHidden) pvMaxHidden.value = pvMaxSpan.textContent;
                if (pmMaxHidden) pmMaxHidden.value = pmMaxSpan.textContent;
            });

            // --- FUNÇÕES DE ATUALIZAÇÃO DA INTERFACE ---

            function atualizarClasseCSS() {
                var classeIdSelecionada = classeSelect.value;
                var classeCSS = "";
                if (classeIdSelecionada && classesDataT20[classeIdSelecionada]) {
                    classeCSS = "classe-" + classesDataT20[classeIdSelecionada].nome.toLowerCase().replace(/\s+/g, '-');
                }
                if (fichaContainer) {
                    var classesToRemove = [];
                    for (var i = 0; i < fichaContainer.classList.length; i++) {
                        if (fichaContainer.classList[i].startsWith('classe-')) {
                            classesToRemove.push(fichaContainer.classList[i]);
                        }
                    }
                    classesToRemove.forEach(function(cls) {
                        fichaContainer.classList.remove(cls);
                    });
                    if (classeCSS) {
                        fichaContainer.classList.add(classeCSS);
                    }
                }
            }

            function atualizarDisponibilidadeMagias() {
                var classeId = classeSelect.value;
                var racaId = racaSelect.value;

                var classesMagicas = ['4', '5', '7', '10']; // Arcanista, Clérigo, Bardo, Druida

                var racaInfo = racaId ? racasData[racaId] : null;
                var racaFazMagia = racaInfo ? racaInfo.faz_magia == 1 : false;

                var podeUsarMagia = classesMagicas.indexOf(classeId) !== -1 || racaFazMagia;

                if (btnAbrirModalMagias) {
                    btnAbrirModalMagias.disabled = !podeUsarMagia;
                }
                if (magiasInfoTexto) {
                    magiasInfoTexto.style.display = podeUsarMagia ? 'none' : 'block';
                }
            }


            function calcularTudoT20() {
                // 1. LEITURA DE DADOS
                var nivel = parseInt(nivelInput.value) || 1;
                var metadeNivel = Math.floor(nivel / 2);

                var racaId = parseInt(racaSelect.value) || null;
                var classeId = parseInt(classeSelect.value) || null;
                var origemId = parseInt(origemSelect.value) || null;
                var divindadeId = parseInt(divindadeSelect.value) || null;

                var classeAtual = classeId ? classesDataT20[classeId] : null;
                var racaInfo = racaId ? racasData[racaId] : null;

                var modificadores = {};
                atributosInputs.forEach(function(input) {
                    var modificadorLido = parseInt(input.value);
                    modificadores[input.id] = isNaN(modificadorLido) ? 0 : modificadorLido;
                });

                // 2. GERAR LISTAS DE HABILIDADES ATIVAS
                // FIX JS: Trocado 'new Set()' por '[]'
                var nomesPoderesEscolhidos = [];
                poderesEscolhidos.forEach(function(pRef) {
                    var poderInfo = null;
                    if (pRef.tipo_poder === 'classe') {
                        // FIX JS: find() não existe em IE, usando loop
                        for (var i = 0; i < poderesClasseData.length; i++) {
                            if (poderesClasseData[i].id == pRef.poder_id) {
                                poderInfo = poderesClasseData[i];
                                break;
                            }
                        }
                    } else if (pRef.tipo_poder === 'geral') {
                        for (var i = 0; i < poderesGeraisData.length; i++) {
                            if (poderesGeraisData[i].id == pRef.poder_id) {
                                poderInfo = poderesGeraisData[i];
                                break;
                            }
                        }
                    }
                    if (poderInfo && nomesPoderesEscolhidos.indexOf(poderInfo.nome) === -1) {
                        nomesPoderesEscolhidos.push(poderInfo.nome);
                    }
                });

                // 3. CÁLCULO PV e PM
                var pvMax = 0;
                var pmMax = 0;
                var conMod = modificadores['constituicao'] || 0;
                var sabMod = modificadores['sabedoria'] || 0;
                var intMod = modificadores['inteligencia'] || 0;
                var carMod = modificadores['carisma'] || 0;

                if (classeAtual) {
                    var pvBaseClasse = parseInt(classeAtual.pv_inicial) || 0;
                    var pvNivelClasse = parseInt(classeAtual.pv_por_nivel) || 0;
                    pvMax = pvBaseClasse + (conMod * nivel) + ((nivel - 1) * pvNivelClasse);

                    var pmNivelClasse = parseInt(classeAtual.pm_por_nivel) || 0;
                    var modAtributoPM = 0;

                    if (classeAtual.nome === 'Arcanista') {
                        modAtributoPM = Math.max(intMod, carMod);
                    } else if (classeAtual.nome === 'Clérigo' || classeAtual.nome === 'Druida') {
                        modAtributoPM = sabMod;
                    } else if (classeAtual.nome === 'Bardo' || classeAtual.nome === 'Nobre' || classeAtual.nome === 'Paladino') {
                        modAtributoPM = carMod;
                    }

                    pmMax = (pmNivelClasse + modAtributoPM) * nivel;

                    if (['Guerreiro', 'Bárbaro', 'Bucaneiro', 'Caçador', 'Cavaleiro', 'Inventor', 'Ladino', 'Lutador'].indexOf(classeAtual.nome) !== -1) {
                        pmMax = pmNivelClasse * nivel;
                    }
                    if (classeAtual.nome === 'Nobre') {
                        pmMax = (pmNivelClasse + carMod) * nivel + Math.floor(nivel / 2);
                    }
                }

                // Bônus Poderes (FIX JS: Trocado .has() por .indexOf())
                if (nomesPoderesEscolhidos.indexOf('Vitalidade') !== -1) pvMax += nivel;
                if (nomesPoderesEscolhidos.indexOf('Vontade de Ferro') !== -1) pmMax += Math.floor(nivel / 2);

                if (pvMaxSpan) pvMaxSpan.textContent = pvMax > 0 ? pvMax : '--';
                if (pmMaxSpan) pmMaxSpan.textContent = pmMax > 0 ? pmMax : '--';

                if (pvAtualInput.value === '0' || pvAtualInput.value === (pvMaxSpan.dataset.oldMax || '0')) {
                    pvAtualInput.value = pvMax > 0 ? pvMax : 0;
                }
                if (pmAtualInput.value === '0' || pmAtualInput.value === (pmMaxSpan.dataset.oldMax || '0')) {
                    pmAtualInput.value = pmMax > 0 ? pmMax : 0;
                }
                pvMaxSpan.dataset.oldMax = pvMax > 0 ? pvMax : '0';
                pmMaxSpan.dataset.oldMax = pmMax > 0 ? pmMax : '0';


                // 4. CÁLCULO INVENTÁRIO (CARGA) E DEFESA
                var cargaUsada = 0;
                var bonusCarga = 0;
                var defesaItens = 0;
                var penalidadeArmadura = 0;
                var escudoEquipado = false;
                var modDesNaDefesa = modificadores['destreza'] || 0;

                if (typeof inventarioAtual !== 'undefined' && Array.isArray(inventarioAtual)) {
                    inventarioAtual.forEach(function(item) {
                        var itemInfo = todosOsItensT20[item.id];
                        if (!itemInfo) return;

                        var qtd = parseInt(item.quantidade) || 1;
                        var esp = parseInt(itemInfo.espacos) || 0;
                        cargaUsada += esp * qtd;

                        if (item.equipado) {
                            if (itemInfo.bonus_carga) {
                                bonusCarga += parseInt(itemInfo.bonus_carga) || 0;
                            }
                            if (itemInfo.tipo === 'Armadura' || itemInfo.tipo === 'Escudo') {
                                defesaItens += parseInt(itemInfo.bonus_defesa) || 0;
                                penalidadeArmadura += parseInt(itemInfo.penalidade_armadura) || 0;
                            }
                            if (itemInfo.tipo === 'Escudo') {
                                escudoEquipado = true;
                            }
                            if (itemInfo.tipo === 'Armadura') {
                                if ((parseInt(itemInfo.penalidade_armadura) || 0) <= -3) {
                                    if (modDesNaDefesa > 1) modDesNaDefesa = 1;
                                }
                            }
                        }
                    });
                }

                var modForca = modificadores['forca'] || 0;
                var cargaMaxima = (5 + modForca) * 2;
                if (cargaMaxima < 10) cargaMaxima = 10;
                cargaMaxima += bonusCarga;

                // FIX JS: Trocado .has() por .indexOf()
                if (nomesPoderesEscolhidos.indexOf('Costas Largas') !== -1) cargaMaxima += 5;

                if (cargaUsadaSpan) cargaUsadaSpan.textContent = cargaUsada;
                if (cargaMaximaSpan) cargaMaximaSpan.textContent = cargaMaxima;

                var defesaTotal = 10 + modDesNaDefesa + defesaItens;

                // FIX JS: Trocado .has() por .indexOf()
                if (nomesPoderesEscolhidos.indexOf('Esquiva') !== -1) defesaTotal += 2;
                if (nomesPoderesEscolhidos.indexOf('Estilo de Uma Arma') !== -1 && !escudoEquipado) defesaTotal += 2;
                if (nomesPoderesEscolhidos.indexOf('Estilo de Arma e Escudo') !== -1 && escudoEquipado) defesaTotal += 2;

                if (defesaTotalSpan) defesaTotalSpan.textContent = defesaTotal;


                // 5. CÁLCULO PERÍCIAS
                if (periciasItems) {
                    periciasItems.forEach((item) => {
                        // Seção de segurança para evitar erros
                        if (!item || !item.id || !item.dataset || !item.dataset.atributoChave) return;

                        const atributoChaveAbrev = item.dataset.atributoChave; // 'des', 'for', etc.
                        const spanTotal = item.querySelector('.pericia-total');
                        const spanNivel = item.querySelector('.pericia-metade-nivel');
                        const spanModAttr = item.querySelector('.pericia-mod-attr');
                        const checkboxTreino = item.querySelector('.pericia-treino');
                        const inputOutros = item.querySelector('.pericia-outros');
                        // Mapeia 'des' -> 'destreza'
                        const chaveModificadorCompleta = mapAtributoChave[atributoChaveAbrev];

                        if (typeof modificadores[chaveModificadorCompleta] === 'undefined') {
                            if (spanTotal) spanTotal.textContent = '--';
                            if (spanNivel) spanNivel.textContent = '--';
                            if (spanModAttr) spanModAttr.textContent = '--';
                            return;
                        }
                        const modValor = modificadores[chaveModificadorCompleta]; // O bônus (ex: +3)

                        if (!spanTotal || !spanNivel || !spanModAttr || !checkboxTreino || !inputOutros) {
                            if (spanTotal) spanTotal.textContent = 'ER';
                            return;
                        }
                        try {
                            let bonusTreino = 0;
                            if (checkboxTreino.checked) {
                                if (nivel >= 15) bonusTreino = 6;
                                else if (nivel >= 7) bonusTreino = 4;
                                else bonusTreino = 2; // Nível 1-6
                            }
                            const outrosValor = parseInt(inputOutros.value) || 0;

                            let penalidadeAplicada = 0;
                            // Penalidade de armadura só se aplica a Força e Destreza
                            if (atributoChaveAbrev === 'for' || atributoChaveAbrev === 'des') {
                                // (Esta variável 'penalidadeArmadura' foi calculada
                                // no Bloco 4 da função 'calcularTudoT20')
                                penalidadeAplicada = penalidadeArmadura;
                            }

                            // A fórmula final
                            const totalPericia = metadeNivel + modValor + bonusTreino + outrosValor + penalidadeAplicada;

                            spanTotal.textContent = (totalPericia >= 0 ? '+' : '') + totalPericia;
                            spanNivel.textContent = (metadeNivel >= 0 ? '+' : '') + metadeNivel;
                            spanModAttr.textContent = (modValor >= 0 ? '+' : '') + modValor;
                        } catch (e) {
                            if (spanTotal) spanTotal.textContent = 'ER';
                        }
                    });
                }

                // 6. CALCULO AUTOMATICO DE ATAQUES

                function atualizarTabelaAtaques(modificadores, nomesPoderesEscolhidos, metadeNivel) {
                    // Segurança
                    if (!tabelaAtaquesBody) return;
                    if (typeof todosOsItensT20 === 'undefined') return;
                    if (typeof inventarioAtual === 'undefined') return;
                    if (typeof nivelInput === 'undefined') return;

                    tabelaAtaquesBody.innerHTML = ''; // Limpa a tabela

                    var modForca = modificadores['forca'] || 0;
                    var modDestreza = modificadores['destreza'] || 0;

                    var armasEquipadas = [];
                    if (typeof inventarioAtual !== 'undefined' && Array.isArray(inventarioAtual)) {

                        // 1. Filtra pelos IDs dos itens equipados
                        var idsEquipados = [];
                        for (var i = 0; i < inventarioAtual.length; i++) {
                            if (inventarioAtual[i].equipado) {
                                idsEquipados.push(inventarioAtual[i]);
                            }
                        }

                        // 2. Mapeia os IDs para os objetos completos dos itens
                        var itensEquipados = [];
                        for (var j = 0; j < idsEquipados.length; j++) {
                            var invItem = idsEquipados[j];
                            var fullItem = todosOsItensT20[invItem.id]; // Busca na base de dados de itens

                            if (fullItem) {
                                // Anexa a quantidade (para referência futura, se necessário)
                                // É importante criar uma CÓPIA para não sujar o objeto original
                                var itemCopia = JSON.parse(JSON.stringify(fullItem));
                                itemCopia.quantidade = invItem.quantidade;
                                itensEquipados.push(itemCopia);
                            }
                        }

                        // 3. Filtra apenas os itens que são armas
                        for (var k = 0; k < itensEquipados.length; k++) {
                            var item = itensEquipados[k];
                            // O seu DB usa 'Arma' como tipo
                            if (item && item.tipo && item.tipo.toLowerCase().indexOf('arma') !== -1) {
                                armasEquipadas.push(item);
                            }
                        }
                    }

                    // Se não tiver armas, adiciona ataque desarmado
                    if (armasEquipadas.length === 0) {
                        var modLuta = modForca; // Ataque desarmado usa Força
                        var treinoLutaEl = document.getElementById('treino_luta');
                        var outrosLutaEl = document.getElementById('outros_luta');
                        var treinoLuta = 0;
                        if (treinoLutaEl && treinoLutaEl.checked) {
                            var nivel = parseInt(nivelInput.value) || 1;
                            if (nivel >= 15) treinoLuta = 6;
                            else if (nivel >= 7) treinoLuta = 4;
                            else treinoLuta = 2;
                        }
                        var outrosLuta = (outrosLutaEl && parseInt(outrosLutaEl.value)) || 0;
                        var totalLuta = modLuta + metadeNivel + treinoLuta + outrosLuta;

                        tabelaAtaquesBody.innerHTML =
                            '<tr>' +
                            '    <td>Ataque Desarmado</td>' +
                            '    <td>1d20+' + totalLuta + '</td>' +
                            '    <td>1d3+' + modForca + '</td>' +
                            '    <td>x2</td>' +
                            '    <td>Impacto</td>' +
                            '    <td>Curto</td>' +
                            '    <td></td>' +
                            '</tr>';
                        return; // Sai da função
                    }

                    // Se tem armas, itera sobre elas
                    armasEquipadas.forEach(function(arma) {
                        var modAtributo = 0;
                        var modDano = 0;
                        var periciaNome = 'luta';

                        var isDisparo = arma.alcance && (arma.alcance.toLowerCase().indexOf('médio') !== -1 || arma.alcance.toLowerCase().indexOf('longo') !== -1);
                        var isArremesso = false; // TBD (DB não tem info)
                        var isLeve = (arma.espacos || 1) <= 1; // Heurística: se ocupa 1 espaço, é leve

                        var usaAcuidade = nomesPoderesEscolhidos.indexOf('Acuidade com Arma') !== -1 && (isLeve || isArremesso);

                        if (isDisparo) {
                            periciaNome = 'pontaria';
                            modAtributo = modDestreza;
                            modDano = 0;
                        } else if (isArremesso) {
                            periciaNome = 'pontaria';
                            modAtributo = usaAcuidade ? Math.max(modForca, modDestreza) : modForca;
                            modDano = modForca;
                        } else {
                            // Armas Corpo-a-Corpo
                            periciaNome = 'luta';
                            modAtributo = (usaAcuidade && isLeve) ? modDestreza : modForca;
                            modDano = modForca;
                        }

                        if (usaAcuidade && isLeve && !isArremesso) {
                            modDano = modDestreza;
                        }

                        var bonusTreino = 0;
                        var checkboxTreino = document.getElementById('treino_' + periciaNome);
                        var outrosPericiaEl = document.getElementById('outros_' + periciaNome);
                        if (checkboxTreino && checkboxTreino.checked) {
                            var nivel = parseInt(nivelInput.value) || 1;
                            if (nivel >= 15) bonusTreino = 6;
                            else if (nivel >= 7) bonusTreino = 4;
                            else bonusTreino = 2;
                        }
                        var outrosPericia = (outrosPericiaEl && parseInt(outrosPericiaEl.value)) || 0;

                        var totalAtributo = modAtributo + metadeNivel + bonusTreino + outrosPericia;
                        var testeTotal = '1d20+' + totalAtributo;
                        var danoBase = arma.dano || '1d3';
                        var danoTotal = danoBase + '+' + modDano;

                        tabelaAtaquesBody.innerHTML +=
                            '<tr>' +
                            '    <td>' + arma.nome + '</td>' +
                            '    <td>' + testeTotal + '</td>' +
                            '    <td>' + danoTotal + '</td>' +
                            '    <td>' + (arma.critico || 'x2') + '</td>' +
                            '    <td>' + (arma.tipo_dano || 'N/D') + '</td>' +
                            '    <td>' + (arma.alcance || 'Curto') + '</td>' +
                            '    <td></td>' +
                            '</tr>';
                    });
                }

                // 7. ATUALIZA OUTRAS PARTES DA UI
                atualizarHabilidadesClasseAuto(classeId, nivel);
                renderizarInventario();
                atualizarTabelaAtaques(modificadores, nomesPoderesEscolhidos, metadeNivel);
                atualizarDisponibilidadeMagias();
            }

            function mostrarAjustesRaciais() {
                var racaIdSelecionada = racaSelect.value;
                if (ajustesInfoDiv && racasData[racaIdSelecionada]) {
                    var ajustes = racasData[racaIdSelecionada].ajustes_atributos || "Nenhum";
                    ajustesInfoDiv.textContent = "Ajustes: " + ajustes;
                } else if (ajustesInfoDiv) {
                    ajustesInfoDiv.textContent = "";
                }
            }

            function atualizarHabilidadesRaciais() {
                var racaIdSelecionada = racaSelect.value;
                if (!habilidadesRaciaisDisplayDiv) return;
                var h4 = habilidadesRaciaisDisplayDiv.querySelector('h4');
                habilidadesRaciaisDisplayDiv.innerHTML = '';
                if (h4) habilidadesRaciaisDisplayDiv.appendChild(h4);
                var racaInfo = racasData[racaIdSelecionada];
                if (racaInfo) {
                    var htmlHabilidades = "";
                    if (racaInfo.habilidade_1_nome) {
                        htmlHabilidades += '<div class="poder-item"><h4>' + racaInfo.habilidade_1_nome + ' (Raça)</h4><p>' + (racaInfo.habilidade_1_desc || '') + '</p></div>';
                    }
                    if (racaInfo.habilidade_2_nome) {
                        htmlHabilidades += '<div class="poder-item"><h4>' + racaInfo.habilidade_2_nome + ' (Raça)</h4><p>' + (racaInfo.habilidade_2_desc || '') + '</p></div>';
                    }
                    if (racaInfo.habilidade_3_nome) {
                        htmlHabilidades += '<div class="poder-item"><h4>' + racaInfo.habilidade_3_nome + ' (Raça)</h4><p>' + (racaInfo.habilidade_3_desc || '') + '</p></div>';
                    }
                    habilidadesRaciaisDisplayDiv.insertAdjacentHTML('beforeend', htmlHabilidades || "<p>Esta raça não concede habilidades.</p>");
                } else {
                    habilidadesRaciaisDisplayDiv.insertAdjacentHTML('beforeend', '<p>Selecione uma raça.</p>');
                }
            }

            function atualizarPoderOrigemT20() {
                var origemIdSelecionada = origemSelect.value;
                if (!poderOrigemDisplayDiv) return;
                var origemInfo = origensDataT20[origemIdSelecionada];
                var htmlConteudo = '<h4>Poder de Origem</h4>';
                if (origemInfo) {
                    htmlConteudo += '<p>(' + origemInfo.nome + ')</p>';
                    var descricoes = "";
                    if (origemInfo.poder_1_nome) descricoes += '<p><strong>' + origemInfo.poder_1_nome + ':</strong> ' + (origemInfo.poder_1_desc || '') + '</p>';
                    if (origemInfo.poder_2_nome) descricoes += '<p><strong>' + origemInfo.poder_2_nome + ':</strong> ' + (origemInfo.poder_2_desc || '') + '</p>';
                    if (origemInfo.poder_3_nome) descricoes += '<p><strong>' + origemInfo.poder_3_nome + ':</strong> ' + (origemInfo.poder_3_desc || '') + '</p>';
                    htmlConteudo += descricoes || "<p>Descrição não disponível.</p>";
                } else {
                    htmlConteudo += '<p>Selecione uma origem.</p>';
                }
                poderOrigemDisplayDiv.innerHTML = htmlConteudo;
            }

            function atualizarPoderesDivindade() {
                var divindadeIdSelecionada = divindadeSelect.value;
                if (!poderesDivindadeDisplayDiv) return;
                var h4 = poderesDivindadeDisplayDiv.querySelector('h4');
                poderesDivindadeDisplayDiv.innerHTML = '';
                if (h4) poderesDivindadeDisplayDiv.appendChild(h4);
                if (divindadeIdSelecionada && divindadesDataT20[divindadeIdSelecionada]) {
                    var htmlPoderes = "";
                    var idsPoderes = divindadePoderesLinks.filter(function(link) {
                        return link.divindade_id == divindadeIdSelecionada;
                    }).map(function(link) {
                        return link.poder_divino_id;
                    });

                    idsPoderes.forEach(function(poderId) {
                        var poderInfo = poderesDivinosDataT20[poderId];
                        if (poderInfo) {
                            htmlPoderes += '<div class="poder-item"><h4>' + poderInfo.nome + ' (Divino)</h4><p>' + (poderInfo.descricao || '') + '</p></div>';
                        }
                    });
                    poderesDivindadeDisplayDiv.insertAdjacentHTML('beforeend', htmlPoderes || "<p>Esta divindade não concede poderes.</p>");
                } else {
                    poderesDivindadeDisplayDiv.insertAdjacentHTML('beforeend', '<p>Selecione uma divindade.</p>');
                }
            }

            function atualizarHabilidadesClasseAuto(classeId, nivel) {
                if (!habilidadesClasseAutoDisplayDiv) return;
                var h4 = habilidadesClasseAutoDisplayDiv.querySelector('h4');
                habilidadesClasseAutoDisplayDiv.innerHTML = '';
                if (h4) habilidadesClasseAutoDisplayDiv.appendChild(h4);
                if (classeId > 0) {
                    var habilidadesGanhadas = habilidadesClasseAutoData.filter(function(hab) {
                        return hab.classe_id == classeId && hab.nivel_obtido <= nivel;
                    });
                    var htmlHabilidades = "";
                    if (habilidadesGanhadas.length > 0) {
                        habilidadesGanhadas.forEach(function(hab) {
                            htmlHabilidades += '<div class="poder-item"><h4>' + hab.nome + ' (Nível ' + hab.nivel_obtido + ')</h4><p>' + (hab.descricao || '') + '</p></div>';
                        });
                    } else {
                        htmlHabilidades = "<p>Nenhuma habilidade automática.</p>";
                    }
                    habilidadesClasseAutoDisplayDiv.insertAdjacentHTML('beforeend', htmlHabilidades);
                } else {
                    habilidadesClasseAutoDisplayDiv.insertAdjacentHTML('beforeend', '<p>Selecione uma classe.</p>');
                }
            }

            // --- LÓGICA DO MODAL DE PODERES ---

            function renderizarPoderesEscolhidos() {
                if (!poderesEscolhidosDisplayDiv) return;
                var h4 = poderesEscolhidosDisplayDiv.querySelector('h4');
                poderesEscolhidosDisplayDiv.innerHTML = '';
                if (h4) poderesEscolhidosDisplayDiv.appendChild(h4);

                if (poderesEscolhidos.length === 0) {
                    poderesEscolhidosDisplayDiv.insertAdjacentHTML('beforeend', '<p>(Nenhum poder adquirido ainda)</p>');
                    return;
                }

                poderesEscolhidos.forEach(function(poderRef, index) {
                    var poderInfo = null;
                    var tipoLabel = "";
                    if (poderRef.tipo_poder === 'classe') {
                        for (var i = 0; i < poderesClasseData.length; i++) {
                            if (poderesClasseData[i].id == poderRef.poder_id) {
                                poderInfo = poderesClasseData[i];
                                break;
                            }
                        }
                        tipoLabel = "Classe";
                    } else if (poderRef.tipo_poder === 'geral') {
                        for (var i = 0; i < poderesGeraisData.length; i++) {
                            if (poderesGeraisData[i].id == poderRef.poder_id) {
                                poderInfo = poderesGeraisData[i];
                                break;
                            }
                        }
                        tipoLabel = poderInfo ? poderInfo.categoria : "Geral";
                    }
                    if (poderInfo) {
                        var poderDiv = document.createElement('div');
                        poderDiv.className = 'poder-item poder-escolhido';
                        poderDiv.innerHTML =
                            '<button type="button" class="btn-remover-poder" data-index="' + index + '">X</button>' +
                            '<h4>' + poderInfo.nome + ' (' + tipoLabel + ')</h4>' +
                            '<p>' + poderInfo.descricao + '</p>' +
                            (poderInfo.pre_requisito ? '<p class="pre-requisito"><strong>Pré-requisito:</strong> ' + poderInfo.pre_requisito + '</p>' : '');

                        poderesEscolhidosDisplayDiv.appendChild(poderDiv);
                    }
                });

                document.querySelectorAll('#poderes-escolhidos-lista .btn-remover-poder').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        removerPoder(parseInt(this.dataset.index));
                    });
                });
            }

            function criarHtmlPoder(poder, tipoPoder) {
                var existe = false;
                for (var i = 0; i < poderesEscolhidos.length; i++) {
                    if (poderesEscolhidos[i].poder_id == poder.id && poderesEscolhidos[i].tipo_poder === tipoPoder) {
                        existe = true;
                        break;
                    }
                }
                var desabilitado = existe ? 'disabled' : '';
                var textoBotao = existe ? 'Adicionado' : 'Adicionar';

                return '<div class="poder-item-modal">' +
                    '<div class="poder-item-modal-info">' +
                    '<h4>' + poder.nome + '</h4>' +
                    '<p>' + poder.descricao + '</p>' +
                    (poder.pre_requisito ? '<p class="pre-requisito"><strong>Pré-requisito:</strong> ' + poder.pre_requisito + '</p>' : '') +
                    '</div>' +
                    '<button type="button" class="btn-adicionar-poder" data-id="' + poder.id + '" data-tipo="' + tipoPoder + '" ' + desabilitado + '>' +
                    textoBotao +
                    '</button>' +
                    '</div>';
            }

            function popularModalPoderes() {
                var filtro = filtroPoderNome.value.toLowerCase();
                var classeId = classeSelect.value;

                var containers = {
                    'modal-tab-classe': document.getElementById('modal-tab-classe'),
                    'modal-tab-combate': document.getElementById('modal-tab-combate'),
                    'modal-tab-destino': document.getElementById('modal-tab-destino'),
                    'modal-tab-magia': document.getElementById('modal-tab-magia')
                };

                if (containers['modal-tab-classe']) {
                    containers['modal-tab-classe'].innerHTML = '';
                    if (classeId) {
                        var poderesDeClasseFiltrados = poderesClasseData.filter(function(p) {
                            return p.classe_id == classeId && p.nome.toLowerCase().includes(filtro);
                        });
                        poderesDeClasseFiltrados.forEach(function(p) {
                            containers['modal-tab-classe'].innerHTML += criarHtmlPoder(p, 'classe');
                        });
                        if (poderesDeClasseFiltrados.length === 0) containers['modal-tab-classe'].innerHTML = '<p>Nenhum poder de classe encontrado.</p>';
                    } else {
                        containers['modal-tab-classe'].innerHTML = '<p>Selecione uma classe na ficha.</p>';
                    }
                }

                ['Combate', 'Destino', 'Magia'].forEach(function(categoria) {
                    var containerKey = 'modal-tab-' + categoria.toLowerCase();
                    if (containers[containerKey]) {
                        containers[containerKey].innerHTML = '';
                        var poderesGeraisFiltrados = poderesGeraisData.filter(function(p) {
                            return p.categoria === categoria && p.nome.toLowerCase().includes(filtro);
                        });
                        poderesGeraisFiltrados.forEach(function(p) {
                            containers[containerKey].innerHTML += criarHtmlPoder(p, 'geral');
                        });
                        if (poderesGeraisFiltrados.length === 0) containers[containerKey].innerHTML = '<p>Nenhum poder de ' + categoria + ' encontrado.</p>';
                    }
                });

                document.querySelectorAll('#modal-adicionar-poder-t20 .btn-adicionar-poder').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        adicionarPoder(this.dataset.id, this.dataset.tipo);
                    });
                });
            }

            window.adicionarPoder = function(poderId, tipoPoder) {
                var idNum = parseInt(poderId);
                var existe = false;
                for (var i = 0; i < poderesEscolhidos.length; i++) {
                    if (poderesEscolhidos[i].poder_id == idNum && poderesEscolhidos[i].tipo_poder === tipoPoder) {
                        existe = true;
                        break;
                    }
                }
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

            // --- LÓGICA DO MODAL DE MAGIAS ---

            function renderizarMagiasConhecidas() {
                if (!magiasConhecidasDisplayDiv) return;
                magiasConhecidasDisplayDiv.innerHTML = '';

                if (magiasConhecidas.length === 0) {
                    magiasConhecidasDisplayDiv.innerHTML = '<p>(Nenhuma magia conhecida)</p>';
                    return;
                }

                magiasConhecidas.forEach(function(magiaId, index) {
                    var magiaInfo = todasAsMagias[magiaId];
                    if (magiaInfo) {
                        var magiaDiv = document.createElement('div');
                        magiaDiv.className = 'magia-item-conhecida';

                        magiaDiv.innerHTML =
                            '<button type="button" class="btn-remover-poder" data-index="' + index + '">X</button>' +
                            '<h4>' + magiaInfo.nome + ' (' + magiaInfo.circulo + 'º Círculo)</h4>' +
                            '<div class="magia-meta">' +
                            '<strong>Tipo:</strong> ' + magiaInfo.tipo + ' | ' +
                            '<strong>Escola:</strong> ' + magiaInfo.escola + ' | ' +
                            '<strong>Execução:</strong> ' + magiaInfo.execucao + ' | ' +
                            '<strong>Alcance:</strong> ' + magiaInfo.alcance +
                            '</div>' +
                            '<p>' + magiaInfo.descricao + '</p>' +
                            (magiaInfo.resistencia ? '<p><strong>Resistência:</strong> ' + magiaInfo.resistencia + '</p>' : '');

                        magiasConhecidasDisplayDiv.appendChild(magiaDiv);
                    }
                });

                document.querySelectorAll('#magias-conhecidas-lista .btn-remover-poder').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        removerMagia(parseInt(this.dataset.index));
                    });
                });
            }

            function popularModalMagias() {
                if (!modalListaMagiasDiv) return;

                var filtroNome = filtroMagiaNome.value.toLowerCase();
                var filtroCirculo = filtroMagiaCirculo.value;
                var filtroTipo = filtroMagiaTipo.value;

                var htmlMagias = "";
                var magiasIds = Object.keys(todasAsMagias);

                magiasIds.forEach(function(id) {
                    var magia = todasAsMagias[id];

                    if (filtroNome && !magia.nome.toLowerCase().includes(filtroNome)) return;
                    if (filtroCirculo && magia.circulo != filtroCirculo) return;
                    if (filtroTipo && magia.tipo !== filtroTipo) return;

                    var existe = magiasConhecidas.indexOf(id) !== -1;
                    var desabilitado = existe ? 'disabled' : '';
                    var textoBotao = existe ? 'Adicionada' : 'Adicionar';

                    htmlMagias += '<div class="poder-item-modal">' +
                        '<div class="poder-item-modal-info">' +
                        '<h4>' + magia.nome + ' (' + magia.circulo + 'º Círculo)</h4>' +
                        '<p><strong>' + magia.tipo + ' ' + magia.escola + '</strong> | ' + '<strong>Execução:</strong> ' + magia.execucao + '</p>' +
                        '<p>' + magia.descricao + '</p>' +
                        '</div>' +
                        '<button type="button" class="btn-adicionar-magia" data-id="' + id + '" ' + desabilitado + '>' +
                        textoBotao +
                        '</button>' +
                        '</div>';
                });

                modalListaMagiasDiv.innerHTML = htmlMagias || '<p>Nenhuma magia encontrada.</p>';

                document.querySelectorAll('#modal-lista-magias .btn-adicionar-magia').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        adicionarMagia(this.dataset.id);
                    });
                });
            }

            window.adicionarMagia = function(magiaId) {
                var existe = magiasConhecidas.indexOf(magiaId) !== -1;
                if (!existe) {
                    magiasConhecidas.push(magiaId);
                    renderizarMagiasConhecidas();
                    popularModalMagias();
                }
            }

            window.removerMagia = function(index) {
                magiasConhecidas.splice(index, 1);
                renderizarMagiasConhecidas();
                popularModalMagias();
            }


            // --- LÓGICA DO MODAL DE ITENS ---
            function renderizarInventario() {
                if (!inventarioListaDiv) return;

                var itensParaRemover = [];
                for (var i = 1; i < inventarioListaDiv.children.length; i++) {
                    itensParaRemover.push(inventarioListaDiv.children[i]);
                }
                itensParaRemover.forEach(function(child) {
                    inventarioListaDiv.removeChild(child);
                });

                if (inventarioAtual.length === 0) {
                    inventarioListaDiv.insertAdjacentHTML('beforeend', '<div class="item-inventario-vazio"><p>Inventário vazio.</p></div>');
                    return;
                }

                inventarioAtual.forEach(function(item, index) {
                    var itemInfo = todosOsItensT20[item.id];
                    if (!itemInfo) itemInfo = {
                        nome: "Item Desconhecido",
                        espacos: 0
                    };

                    var itemDiv = document.createElement('div');
                    itemDiv.className = 'item-inventario';
                    var isEquipado = item.equipado ? 'checked' : '';
                    var espacosReais = (parseInt(itemInfo.espacos) || 0);

                    itemDiv.innerHTML =
                        '<input type="checkbox" class="item-equipado-check" data-index="' + index + '" ' + isEquipado + '>' +
                        '<span class="item-nome">' + itemInfo.nome + '</span>' +
                        '<input type="number" class="item-quantidade" value="' + (item.quantidade || 1) + '" min="1" data-index="' + index + '">' +
                        '<span class="item-espacos">' + (espacosReais * (item.quantidade || 1)) + '</span>' +
                        '<button type="button" class="btn-remover-item" data-index="' + index + '">X</button>';

                    inventarioListaDiv.appendChild(itemDiv);
                });

                document.querySelectorAll('#lista-inventario .item-equipado-check').forEach(function(check) {
                    check.addEventListener('change', function() {
                        alternarEquipado(this.dataset.index);
                    });
                });
                document.querySelectorAll('#lista-inventario .item-quantidade').forEach(function(input) {
                    input.addEventListener('input', function() {
                        mudarQuantidade(this.dataset.index, this.value);
                    });
                });
                document.querySelectorAll('#lista-inventario .btn-remover-item').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        removerItemInventario(this.dataset.index);
                    });
                });
            }

            function popularModalItens() {
                var filtro = filtroItemNome.value.toLowerCase();
                var tipoFiltroAtivo = document.querySelector('#modal-adicionar-item-t20 .modal-tab-button.active');
                if (!tipoFiltroAtivo) return;

                var tipoFiltro = tipoFiltroAtivo.dataset.tabModal;

                var containers = {
                    'modal-tab-armas': document.getElementById('modal-tab-armas'),
                    'modal-tab-armaduras': document.getElementById('modal-tab-armaduras'),
                    'modal-tab-escudos': document.getElementById('modal-tab-escudos'),
                    'modal-tab-geral': document.getElementById('modal-tab-geral')
                };

                for (var key in containers) {
                    if (containers[key]) containers[key].innerHTML = '';
                }

                var mapTipo = {
                    'modal-tab-armas': 'Arma',
                    'modal-tab-armaduras': 'Armadura',
                    'modal-tab-escudos': 'Escudo',
                    'modal-tab-geral': 'Item Geral'
                };
                var tipoItemAtual = mapTipo[tipoFiltro];

                var todosItensArray = Object.keys(todosOsItensT20).map(function(key) {
                    return todosOsItensT20[key];
                });
                var itensFiltrados = todosItensArray.filter(function(item) {
                    return item.tipo === tipoItemAtual && item.nome.toLowerCase().includes(filtro);
                });

                var containerAtual = containers[tipoFiltro];
                if (containerAtual) {
                    if (itensFiltrados.length === 0) {
                        containerAtual.innerHTML = '<p>Nenhum item encontrado.</p>';
                        return;
                    }
                    itensFiltrados.forEach(function(item) {
                        containerAtual.innerHTML +=
                            '<div class="item-modal">' +
                            '<div class="item-modal-info">' +
                            '<h4>' + item.nome + ' (Espaços: ' + item.espacos + ')</h4>' +
                            '<p>' + (item.descricao || '') + '</p>' +
                            (item.bonus_defesa ? '<p class="item-detalhe"><strong>Defesa:</strong> +' + item.bonus_defesa + '</p>' : '') +
                            (item.dano ? '<p class="item-detalhe"><strong>Dano:</strong> ' + item.dano + ' | <strong>Crít:</strong> ' + item.critico + '</p>' : '') +
                            '</div>' +
                            '<button type="button" class="btn-adicionar" data-id="' + item.id + '">Adicionar</button>' +
                            '</div>';
                    });
                }

                document.querySelectorAll('#' + tipoFiltro + ' .btn-adicionar').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        adicionarItemInventario(this.dataset.id);
                    });
                });
            }

            function atualizarTabelaAtaques(modificadores, nomesPoderesEscolhidos, metadeNivel) {
                const tabelaAtaquesBody = document.querySelector("#tabela-ataques tbody");
                if (!tabelaAtaquesBody) return;
                tabelaAtaquesBody.innerHTML = '';
                const modForca = modificadores['forca'] || 0;
                const modDestreza = modificadores['destreza'] || 0;
                let armasEquipadas = [];
                if (typeof inventarioAtual !== 'undefined' && Array.isArray(inventarioAtual)) {
                    armasEquipadas = inventarioAtual.filter(item => item.equipado && item.tipo === 'Arma');
                }
                if (armasEquipadas.length === 0) {
                    const modLuta = modForca;
                    // *** CORREÇÃO AQUI: Acessando pericias que podem não existir ***
                    const treinoLuta = document.getElementById('treino_luta')?.checked ? (nivel >= 15 ? 6 : (nivel >= 7 ? 4 : 2)) : 0;
                    const outrosLuta = parseInt(document.getElementById('outros_luta')?.value) || 0;
                    const totalLuta = modLuta + metadeNivel + treinoLuta + outrosLuta;
                    // *** FIM DA CORREÇÃO ***
                    tabelaAtaquesBody.innerHTML = `
            <tr>
                <td>Ataque Desarmado</td>
                <td>1d20+${totalLuta}</td>
                <td>1d3+${modForca}</td>
                <td>x2</td>
                <td>Impacto</td>
                <td>Curto</td>
                <td></td>
            </tr>
        `;
                    return;
                }
                armasEquipadas.forEach(arma => {
                    let modAtributo = 0;
                    let modDano = 0;
                    let periciaNome = 'luta';
                    let atributoChave = arma.atributo_chave ? arma.atributo_chave.toLowerCase() : 'for';
                    let usaAcuidade = nomesPoderesEscolhidos.has('Acuidade com Arma') && (arma.categoria === 'Leve' || arma.categoria === 'Arremesso');
                    if (arma.categoria === 'Disparo') {
                        periciaNome = 'pontaria';
                        modAtributo = modDestreza;
                        modDano = 0;
                    } else if (arma.categoria === 'Arremesso') {
                        periciaNome = 'pontaria';
                        modAtributo = usaAcuidade ? Math.max(modForca, modDestreza) : modForca;
                        modDano = modForca;
                    } else {
                        periciaNome = 'luta';
                        modAtributo = (usaAcuidade && atributoChave !== 'for') ? modDestreza : modForca;
                        modDano = modForca;
                    }
                    if (atributoChave === 'des' && arma.categoria !== 'Disparo' && arma.categoria !== 'Arremesso') {
                        modAtributo = modDestreza;
                        if (usaAcuidade && arma.categoria === 'Leve') {
                            modDano = Math.max(modForca, modDestreza);
                        } else {
                            modDano = modForca;
                        }
                    }

                    // *** CORREÇÃO AQUI: Acessando pericias que podem não existir ***
                    let bonusTreino = 0;
                    const checkboxTreino = document.getElementById(`treino_${periciaNome}`);
                    if (checkboxTreino && checkboxTreino.checked) {
                        if (nivel >= 15) bonusTreino = 6;
                        else if (nivel >= 7) bonusTreino = 4;
                        else bonusTreino = 2;
                    }
                    const outrosPericia = parseInt(document.getElementById(`outros_${periciaNome}`)?.value) || 0;
                    // *** FIM DA CORREÇÃO ***

                    const testeTotal = `1d20+${modAtributo + metadeNivel + bonusTreino + outrosPericia}`;
                    const danoTotal = `${arma.dano} + ${modDano}`;
                    tabelaAtaquesBody.innerHTML += `
            <tr>
                <td>${arma.nome}</td>
                <td>${testeTotal}</td>
                <td>${danoTotal}</td>
                <td>${arma.critico || 'x2'}</td>
                <td>${arma.tipo_dano || 'N/D'}</td>
                <td>${arma.alcance || 'Curto'}</td>
                <td></td>
            </tr>
        `;
                });
            }

            window.adicionarItemInventario = function(itemId) {
                const itemInfo = todosOsItensT20[itemId];
                if (!itemInfo) return;
                const itemParaAdicionar = JSON.parse(JSON.stringify(itemInfo)); // Cria cópia
                itemParaAdicionar.quantidade = 1;
                itemParaAdicionar.equipado = 0;
                inventarioAtual.push(itemParaAdicionar);
                calcularTudoT20(); // <-- GATILHO
            }
            window.removerItemInventario = function(index) {
                inventarioAtual.splice(index, 1);
                calcularTudoT20(); // <-- GATILHO
            }
            window.alternarEquipado = function(index) {
                if (inventarioAtual[index]) {
                    inventarioAtual[index].equipado = !inventarioAtual[index].equipado;
                    // (Lógica para desequipar outras armaduras/escudos)
                    // ...
                    calcularTudoT20(); // <-- GATILHO
                }
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
                    inventarioAtual[index].equipado = !inventarioAtual[index].equipado;

                    if (inventarioAtual[index].tipo === 'Armadura' && inventarioAtual[index].equipado) {
                        inventarioAtual.forEach((item, i) => {
                            if (i !== index && item.tipo === 'Armadura') {
                                item.equipado = 0;
                            }
                        });
                    }

                    if (inventarioAtual[index].tipo === 'Escudo' && inventarioAtual[index].equipado) {
                        inventarioAtual.forEach((item, i) => {
                            if (i !== index && item.tipo === 'Escudo') {
                                item.equipado = 0; // 0 ou false
                            }
                        });
                    }
                    calcularTudoT20();
                }
            }


            // --- EVENT LISTENERS (GERAL) ---
            tabButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    tabButtons.forEach(function(btn) {
                        btn.classList.remove('active');
                    });
                    tabContents.forEach(function(content) {
                        content.classList.remove('active');
                    });
                    button.classList.add('active');
                    var targetContent = document.getElementById(button.dataset.tab);
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });

            if (racaSelect) {
                racaSelect.addEventListener('change', function() {
                    mostrarAjustesRaciais();
                    atualizarHabilidadesRaciais();
                    calcularTudoT20();
                });
            }
            if (nivelInput) {
                nivelInput.addEventListener('input', calcularTudoT20);
            }
            atributosInputs.forEach(function(input) {
                input.addEventListener('input', calcularTudoT20);
            });
            if (classeSelect) {
                classeSelect.addEventListener('change', function() {
                    atualizarClasseCSS();
                    magiasConhecidas = [];
                    renderizarMagiasConhecidas();
                    calcularTudoT20();
                    popularModalPoderes();
                });
            }
            if (origemSelect) {
                origemSelect.addEventListener('change', function() {
                    atualizarPoderOrigemT20();
                    calcularTudoT20();
                });
            }
            if (divindadeSelect) {
                divindadeSelect.addEventListener('change', function() {
                    atualizarPoderesDivindade();
                    calcularTudoT20();
                });
            }

            if (periciasItems) {
                periciasItems.forEach(function(item) {
                    var checkboxTreino = item.querySelector('.pericia-treino');
                    var inputOutros = item.querySelector('.pericia-outros');
                    if (checkboxTreino) {
                        checkboxTreino.addEventListener('change', calcularTudoT20);
                    }
                    if (inputOutros) {
                        inputOutros.addEventListener('input', calcularTudoT20);
                    }
                });
            }

            // --- LISTENERS MODAL DE PODERES ---
            if (btnAbrirModalPoderes) {
                btnAbrirModalPoderes.addEventListener('click', function() {
                    popularModalPoderes();
                    if (modalAdicionarPoder) modalAdicionarPoder.style.display = 'flex';
                });
            }
            if (btnFecharModalPoderes) {
                btnFecharModalPoderes.addEventListener('click', function() {
                    if (modalAdicionarPoder) modalAdicionarPoder.style.display = 'none';
                });
            }
            if (filtroPoderNome) {
                filtroPoderNome.addEventListener('input', popularModalPoderes);
            }
            if (modalTabs) {
                modalTabs.forEach(function(button) {
                    button.addEventListener('click', function() {
                        modalTabs.forEach(function(btn) {
                            btn.classList.remove('active');
                        });
                        modalTabContents.forEach(function(content) {
                            content.classList.remove('active');
                        });
                        button.classList.add('active');
                        var targetContent = document.getElementById(button.dataset.tabModal);
                        if (targetContent) targetContent.classList.add('active');
                    });
                });
            }

            // --- LISTENERS MODAL DE ITENS ---
            if (btnAbrirModalItem) {
                btnAbrirModalItem.addEventListener('click', function() {
                    popularModalItens();
                    if (modalAdicionarItem) modalAdicionarItem.style.display = 'flex';
                });
            }
            if (btnFecharModalItem) {
                btnFecharModalItem.addEventListener('click', function() {
                    if (modalAdicionarItem) modalAdicionarItem.style.display = 'none';
                });
            }
            if (filtroItemNome) {
                filtroItemNome.addEventListener('input', popularModalItens);
            }
            if (modalItemTabs) {
                modalItemTabs.forEach(function(button) {
                    button.addEventListener('click', function() {
                        modalItemTabs.forEach(function(btn) {
                            btn.classList.remove('active');
                        });
                        modalItemTabContents.forEach(function(content) {
                            content.classList.remove('active');
                        });
                        button.classList.add('active');
                        var targetContent = document.getElementById(button.dataset.tabModal);
                        if (targetContent) targetContent.classList.add('active');
                        popularModalItens();
                    });
                });
            }

            // --- LISTENERS MODAL DE MAGIAS ---
            if (btnAbrirModalMagias) {
                btnAbrirModalMagias.addEventListener('click', function() {
                    popularModalMagias();
                    if (modalAdicionarMagia) modalAdicionarMagia.style.display = 'flex';
                });
            }
            if (btnFecharModalMagias) {
                btnFecharModalMagias.addEventListener('click', function() {
                    if (modalAdicionarMagia) modalAdicionarMagia.style.display = 'none';
                });
            }
            if (filtroMagiaNome) filtroMagiaNome.addEventListener('input', popularModalMagias);
            if (filtroMagiaCirculo) filtroMagiaCirculo.addEventListener('change', popularModalMagias);
            if (filtroMagiaTipo) filtroMagiaTipo.addEventListener('change', popularModalMagias);


            // --- INICIALIZAÇÃO ---
            mostrarAjustesRaciais();
            atualizarClasseCSS();
            atualizarPoderOrigemT20();
            atualizarHabilidadesRaciais();
            atualizarPoderesDivindade();
            renderizarPoderesEscolhidos();
            renderizarMagiasConhecidas();
            calcularTudoT20(); // Chama o cálculo principal no final
        });
    </script>

</body>

</html>