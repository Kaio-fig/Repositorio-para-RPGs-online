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
// Poderes de classe gerais (ainda podem ficar aqui ou ir para o banco também)
$todos_os_poderes = [
    ['id' => 101, 'nome' => 'Ataque Especial', 'desc' => 'Ao fazer um ataque, você pode gastar 1 PE para receber +5 no teste de ataque ou na rolagem de dano.', 'classe_id' => 1, 'nex_requerido' => 15],
];

// Perícias agrupadas
$pericias_agrupadas = [
    'Agilidade' => [['id' => 1, 'nome' => 'Acrobacia'], ['id' => 7, 'nome' => 'Crime', 'so_treinado' => true], ['id' => 11, 'nome' => 'Furtividade'], ['id' => 12, 'nome' => 'Iniciativa'], ['id' => 20, 'nome' => 'Pilotagem', 'so_treinado' => true], ['id' => 21, 'nome' => 'Pontaria'], ['id' => 23, 'nome' => 'Reflexos']],
    'Força' => [['id' => 4, 'nome' => 'Atletismo'], ['id' => 16, 'nome' => 'Luta']],
    'Inteligência' => [['id' => 5, 'nome' => 'Atualidades'], ['id' => 6, 'nome' => 'Ciências', 'so_treinado' => true], ['id' => 14, 'nome' => 'Intuição'], ['id' => 15, 'nome' => 'Investigação'], ['id' => 17, 'nome' => 'Medicina', 'so_treinado' => true], ['id' => 18, 'nome' => 'Ocultismo', 'so_treinado' => true], ['id' => 22, 'nome' => 'Profissão', 'so_treinado' => true], ['id' => 25, 'nome' => 'Sobrevivência'], ['id' => 26, 'nome' => 'Tática', 'so_treinado' => true], ['id' => 27, 'nome' => 'Tecnologia', 'so_treinado' => true]],
    'Presença' => [['id' => 2, 'nome' => 'Adestramento', 'so_treinado' => true], ['id' => 3, 'nome' => 'Artes', 'so_treinado' => true], ['id' => 8, 'nome' => 'Diplomacia'], ['id' => 9, 'nome' => 'Enganação'], ['id' => 13, 'nome' => 'Intimidação'], ['id' => 19, 'nome' => 'Percepção'], ['id' => 24, 'nome' => 'Religião', 'so_treinado' => true], ['id' => 28, 'nome' => 'Vontade', 'so_treinado' => true]],
    'Vigor' => [['id' => 10, 'nome' => 'Fortitude']]
];
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

                    <div id="tab-poderes" class="tab-content">
                        <div class="info-poderes">
                            <div>
                                <label for="classe-select">Classe</label>
                                <select id="classe-select" name="classe_id">
                                    <?php foreach ($classes as $id => $classe): ?>
                                        <option value="<?= $id ?>" <?= ($personagem['classe_id'] == $id) ? 'selected' : '' ?>><?= $classe['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="origem-select">Origem</label>
                                <select id="origem-select" name="origem_id">
                                    <?php foreach ($origens as $id => $origem): ?>
                                        <option value="<?= $id ?>" <?= ($personagem['origem_id'] == $id) ? 'selected' : '' ?>><?= $origem['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="trilha-select">Trilha</label>
                                <select id="trilha-select" name="trilha_id" disabled>
                                    <option value="0">Apenas em NEX 10%+</option>
                                </select>
                            </div>
                            <div id="dt-rituais-container" style="display: none;">DT Rituais: <span id="dt-rituais-span"></span></div>
                        </div>
                        <button type="button" class="btn-acao" id="btn-adicionar-poder">Adicionar Poder</button>
                    </div>

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
                                </select>
                            </div>

                            <div id="dt-rituais-container" style="display: none;">
                                DT Rituais: <span id="dt-rituais-span"></span>
                            </div>
                        </div>

                        <h2>Poderes e Habilidades</h2>
                        <div class="lista-poderes">
                        </div>
                    </div>
                </div>
            </div>
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- DADOS DO PHP PARA O JS (COM SINTAXE ANTIGA E SEGURA) ---
            const classesData = <?= json_encode(isset($classes) ? $classes : []) ?>;
            const origens = <?= json_encode(isset($origens) ? $origens : []) ?>;
            const todasAsTrilhas = <?= json_encode(isset($trilhas) ? $trilhas : []) ?>;
            const todosOsPoderesDeTrilha = <?= json_encode(isset($poderes_trilha) ? $poderes_trilha : []) ?>;

            // --- ELEMENTOS GLOBAIS ---
            const form = document.getElementById('ficha-form');
            if (!form) return;
            const inputsParaMonitorar = form.querySelectorAll('input.atributo-input, select.atributo-input, #classe-select, #origem-select, #trilha-select');

            // --- LÓGICA DE UPLOAD E PREVIEW DA IMAGEM ---
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

            // --- LÓGICA DAS ABAS ---
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

                trilhaSelect.innerHTML = '<option value="0">Nenhuma Trilha</option>';

                if (classeId > 0 && nex >= 10) {
                    trilhaSelect.disabled = false;

                    // CORREÇÃO: Usando '==' para uma comparação menos estrita entre o ID numérico e o ID em texto que pode vir do banco
                    const trilhasDisponiveis = todasAsTrilhas.filter(trilha => trilha.classe_id == classeId);

                    if (trilhasDisponiveis.length === 0) {
                        trilhaSelect.innerHTML = '<option value="0">Nenhuma trilha para esta classe</option>';
                    } else {
                        trilhasDisponiveis.forEach(trilha => {
                            const option = document.createElement('option');
                            option.value = trilha.id;
                            option.textContent = trilha.nome;
                            trilhaSelect.appendChild(option);
                        });
                    }
                } else {
                    trilhaSelect.disabled = true;
                    trilhaSelect.innerHTML = '<option value="0">Apenas em NEX 10%+</option>';
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

            // --- FUNÇÃO MASTER DE CÁLCULO ---
            function calcularTudo() {
                const nex = parseInt(document.getElementById('nex').value) || 0;
                const classeId = parseInt(document.getElementById('classe-select').value) || 0;
                const origemId = parseInt(document.getElementById('origem-select').value) || 0;

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

                let vidaMax = parseInt(classeAtual.pv_inicial) + (atributos.vigor * niveis) + (parseInt(classeAtual.pv_por_nivel) * niveisAposPrimeiro);
                if (origemId == 9) {
                    vidaMax += niveis;
                }

                let peMax = parseInt(classeAtual.pe_inicial) + atributos.presenca + (parseInt(classeAtual.pe_por_nivel) * niveisAposPrimeiro);

                let sanidadeMax = parseInt(classeAtual.san_inicial) + (parseInt(classeAtual.san_por_nivel) * niveisAposPrimeiro);
                if (origemId == 24) {
                    sanidadeMax += niveis;
                }

                let defesaTotal = 10 + atributos.agilidade;
                if (origemId == 16) {
                    defesaTotal += 2;
                }

                document.getElementById('vida-display').textContent = vidaMax;
                document.getElementById('pe-display').textContent = peMax;
                document.getElementById('sanidade-display').textContent = sanidadeMax;
                document.getElementById('defesa-display').textContent = defesaTotal;

                const dtRituaisContainer = document.getElementById('dt-rituais-container');
                if (dtRituaisContainer) {
                    if (classeId === 3) {
                        dtRituaisContainer.style.display = 'block';
                        document.getElementById('dt-rituais-span').textContent = 10 + atributos.presenca + Math.floor(nex / 10);
                    } else {
                        dtRituaisContainer.style.display = 'none';
                    }
                }

                // Chama as funções de atualização
                atualizarPoderOrigem();
                atualizarTrilhasDisponiveis();
                atualizarPoderesDaTrilha();
            }

            // --- EVENT LISTENERS E INICIALIZAÇÃO ---
            if (inputsParaMonitorar) {
                inputsParaMonitorar.forEach(input => {
                    input.addEventListener('change', calcularTudo);
                });
            }



            calcularTudo(); // Roda tudo uma vez no carregamento
        });
    </script>
</body>

</html>