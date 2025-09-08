<?php
session_start();


// Verificar login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
require_once '../conection/db_connect.php';
require_once '../conection/item_functions.php';

// Pega id do personagem (se existir, é edição)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Busca personagem se for edição
$personagem = null;
if ($id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM personagens WHERE id = $id");
    $personagem = mysqli_fetch_assoc($result);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome   = $_POST['nome'];
    $sistema = $_POST['sistema'];
    $nex    = $_POST['nex'];
    $vida   = $_POST['vida'];
    $pe     = $_POST['pe'];
    $san    = $_POST['san'];
    $imagem = isset($_POST['imagem']) ? $_POST['imagem'] : "default.jpg";
    $user_id = $_SESSION['user_id'];

    if ($id > 0) {
        // Atualizar personagem existente
        $sql = "UPDATE personagens 
                SET nome='$nome',
                    sistema='$sistema',
                    nex='$nex',
                    vida='$vida',
                    pe='$pe',
                    san='$san',
                    imagem='$imagem'
                WHERE id='$id'";
    } else {
        // Criar novo personagem
        $sql = "INSERT INTO personagens (user_id, nome, sistema, nex, vida, pe, san, imagem)
                VALUES ('$user_id', '$nome', '$sistema', '$nex', '$vida', '$pe', '$san', '$imagem')";
    }
}

// Carregar itens do banco de dados
$armas = getArmas();
$protecoes = getProtecoes();
$itens_gerais = getItensGerais();
$itens_paranormais = getItensParanormais();

// Converter para JSON para uso no JavaScript
$armas_json = json_encode($armas);
$protecoes_json = json_encode($protecoes);
$itens_gerais_json = json_encode($itens_gerais);
$itens_paranormais_json = json_encode($itens_paranormais);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Ordem Paranormal Automatizada</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../static/ficha_op.css">
</head>

<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-dragon"></i> Ficha de Ordem Paranormal</h1>
            <p>Automatizada para Agentes da Ordem</p>
            <?php if ($personagem): ?>
            <?php endif; ?>
        </header>

        <!-- Painel Esquerdo: Token e Nome -->

        <div class="left-panel">
            <div class="token-container">
                <div class="token-preview" id="token-preview">
                    <?php if ($personagem && $personagem['imagem'] != 'default.jpg'): ?>
                        <img src="../uploads/<?php echo $personagem['imagem']; ?>" alt="Token do Personagem">
                    <?php else: ?>
                        <i class="fas fa-user-circle"></i>
                    <?php endif; ?>
                </div>
                <input type="file" id="token-upload" accept="image/*" class="token-upload">
                <button class="btn-save" onclick="document.getElementById('token-upload').click()">
                    <i class="fas fa-upload"></i> Importar Token
                </button>
            </div>

            <div class="character-name">
                <input type="text" id="char-name" placeholder="Nome do Personagem"
                    value="<?php echo $personagem ? htmlspecialchars($personagem['nome']) : ''; ?>">
                <br><br>
                <!-- Seleção de NEX -->
                <select name="nex" id="nex" class="nex-select" required>
                    <option value="">Selecione um NEX</option>
                    <option value="1">NEX – 5%</option>
                    <option value="2">NEX – 10%</option>
                    <option value="3">NEX – 15%</option>
                    <option value="4">NEX – 20%</option>
                    <option value="5">NEX – 25%</option>
                    <option value="6">NEX – 30%</option>
                    <option value="7">NEX – 35%</option>
                    <option value="8">NEX – 40%</option>
                    <option value="9">NEX – 45%</option>
                    <option value="10">NEX – 50%</option>
                    <option value="11">NEX – 55%</option>
                    <option value="12">NEX – 60%</option>
                    <option value="13">NEX – 65%</option>
                    <option value="14">NEX – 70%</option>
                    <option value="15">NEX – 75%</option>
                    <option value="16">NEX – 80%</option>
                    <option value="17">NEX – 85%</option>
                    <option value="18">NEX – 90%</option>
                    <option value="19">NEX – 95%</option>
                    <option value="20">NEX – 99%</option>
                </select>

                <!-- Vida -->
                <input type="number" name="vida" id="vida" placeholder="Vida" required>
                <br><br>
                <!-- Pontos de Esforço -->
                <input type="number" name="pe" id="pe" placeholder="PE" required>
                <br><br>
                <!-- Sanidade -->
                <input type="number" name="san" id="san" placeholder="Sanidade" required>
            </div>
        </div>
        <!-- Painel Direito: Abas e Conteúdo -->
        <div class="right-panel">
            <div class="tab-controls">
                <div class="tab-btn active" data-tab="status-tab">
                    <i class="fas fa-heart"></i> Status
                </div>
                <div class="tab-btn" data-tab="powers-tab">
                    <i class="fas fa-fire"></i> Poderes e Rituais
                </div>
                <div class="tab-btn" data-tab="equipment-tab">
                    <i class="fas fa-shield-alt"></i> Equipamento
                </div>
            </div>

            <div class="tab-content">
                <!-- Aba Status -->
                <div id="status-tab" class="tab-pane active">
                    <h2>Atributos e Perícias</h2>

                    <div class="attributes-grid">
                        <div class="attribute">
                            <label>FORÇA</label>
                            <input type="number" id="for" min="0" max="5" value="1">
                            <div class="modifier" id="for-mod">+1</div>
                        </div>
                        <div class="attribute">
                            <label>AGILIDADE</label>
                            <input type="number" id="agi" min="0" max="5" value="1">
                            <div class="modifier" id="agi-mod">+1</div>
                        </div>
                        <div class="attribute">
                            <label>INTELECTO</label>
                            <input type="number" id="int" min="0" max="5" value="1">
                            <div class="modifier" id="int-mod">+1</div>
                        </div>
                        <div class="attribute">
                            <label>VIGOR</label>
                            <input type="number" id="vig" min="0" max="5" value="1">
                            <div class="modifier" id="vig-mod">+1</div>
                        </div>
                        <div class="attribute">
                            <label>PRESENÇA</label>
                            <input type="number" id="pre" min="0" max="5" value="1">
                            <div class="modifier" id="pre-mod">+1</div>
                        </div>
                    </div>

                    <div class="skills-grid">
                        <div class="skill-category">
                            <h3>Físicas</h3>
                            <div class="skill">
                                <span>Acrobacia (AGI)</span>
                                <input type="number" id="acrobacia" value="1">
                            </div>
                            <div class="skill">
                                <span>Atletismo (FOR)</span>
                                <input type="number" id="atletismo" value="1">
                            </div>
                            <div class="skill">
                                <span>Furtividade (AGI)</span>
                                <input type="number" id="furtividade" value="1">
                            </div>
                            <div class="skill">
                                <span>Pilotagem (AGI)</span>
                                <input type="number" id="pilotagem" value="1">
                            </div>
                            <div class="skill">
                                <span>Pontaria (AGI)</span>
                                <input type="number" id="pontaria" value="1">
                            </div>
                        </div>

                        <div class="skill-category">
                            <h3>Mentais</h3>
                            <div class="skill">
                                <span>Atualidades (INT)</span>
                                <input type="number" id="atualidades" value="1">
                            </div>
                            <div class="skill">
                                <span>Ciências (INT)</span>
                                <input type="number" id="ciencias" value="1">
                            </div>
                            <div class="skill">
                                <span>Crime (INT)</span>
                                <input type="number" id="crime" value="1">
                            </div>
                            <div class="skill">
                                <span>Investigação (INT)</span>
                                <input type="number" id="investigacao" value="1">
                            </div>
                            <div class="skill">
                                <span>Medicina (INT)</span>
                                <input type="number" id="medicina" value="1">
                            </div>
                        </div>

                        <div class="skill-category">
                            <h3>Sociais</h3>
                            <div class="skill">
                                <span>Diplomacia (PRE)</span>
                                <input type="number" id="diplomacia" value="1">
                            </div>
                            <div class="skill">
                                <span>Enganação (PRE)</span>
                                <input type="number" id="enganacao" value="1">
                            </div>
                            <div class="skill">
                                <span>Intimidação (PRE)</span>
                                <input type="number" id="intimidacao" value="1">
                            </div>
                            <div class="skill">
                                <span>Percepção (PRE)</span>
                                <input type="number" id="percepcao" value="1">
                            </div>
                            <div class="skill">
                                <span>Vontade (PRE)</span>
                                <input type="number" id="vontade" value="1">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aba Poderes e Rituais -->
                <div id="powers-tab" class="tab-pane">
                    <h2>Poderes de Classe e Rituais</h2>

                    <div class="abilities-list">
                        <div class="ability">
                            <h3>Ataque Especial</h3>
                            <p>Gaste 2 PE para receber +5 em um ataque</p>
                        </div>
                        <div class="ability">
                            <h3>Golpe Pesado</h3>
                            <p>Seu ataque causa +1 dado de dano</p>
                        </div>
                        <div class="ability">
                            <h3>Combate Defensivo</h3>
                            <p>Ganhe +5 em Defesa</p>
                        </div>
                        <div class="ability">
                            <h3>Escudo Mental</h3>
                            <p>Protege sua mente contra efeitos paranormais</p>
                        </div>
                        <div class="ability">
                            <h3>Amaldiçoar Arma</h3>
                            <p>Ritual: Arma causa dano paranormal adicional</p>
                        </div>
                        <div class="ability">
                            <h3>Definhar</h3>
                            <p>Ritual: Enfraquece inimigos próximos</p>
                        </div>
                        <div class="ability">
                            <h3>Perturbação</h3>
                            <p>Ritual: Cria ilusões para confundir oponentes</p>
                        </div>
                        <div class="ability">
                            <h3>Arma Atroz</h3>
                            <p>Ritual: Cria uma arma feita de energia paranormal</p>
                        </div>
                    </div>

                    <div class="add-item">
                        <button class="btn-save">
                            <i class="fas fa-plus"></i> Adicionar Novo Poder
                        </button>
                    </div>
                </div>

                <!-- Aba Equipamento -->
                <div id="equipment-tab" class="tab-pane">
                    <h2>Equipamentos e Itens</h2>

                    <div class="equipment-grid">
                        <div class="equipment-category">
                            <h3>Armas</h3>
                            <div id="weapons-list">
                                <!-- As armas serão carregadas via JavaScript -->
                            </div>
                            <div class="add-item">
                                <button class="btn-save" id="add-weapon">
                                    <i class="fas fa-plus"></i> Adicionar Arma
                                </button>
                            </div>
                        </div>

                        <div class="equipment-category">
                            <h3>Armaduras</h3>
                            <div id="armors-list">
                                <!-- As proteções serão carregadas via JavaScript -->
                            </div>
                            <div class="add-item">
                                <button class="btn-save" id="add-armor">
                                    <i class="fas fa-plus"></i> Adicionar Armadura
                                </button>
                            </div>
                        </div>

                        <div class="equipment-category">
                            <h3>Itens Paranormais</h3>
                            <div id="paranormal-list">
                                <!-- Os itens paranormais serão carregados via JavaScript -->
                            </div>
                            <div class="add-item">
                                <button class="btn-save" id="add-paranormal">
                                    <i class="fas fa-plus"></i> Adicionar Item Paranormal
                                </button>
                            </div>
                        </div>

                        <div class="equipment-category">
                            <h3>Itens Gerais</h3>
                            <div id="general-list">
                                <!-- Os itens gerais serão carregados via JavaScript -->
                            </div>
                            <div class="add-item">
                                <button class="btn-save" id="add-general">
                                    <i class="fas fa-plus"></i> Adicionar Item Geral
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="save-button">
                <button class="btn-save" id="save-btn">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para seleção de equipamentos -->
    <div class="modal" id="equipment-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Selecionar Equipamento</h2>
                <button class="close-modal">&times;</button>
            </div>

            <div class="equipment-filters">
                <button class="filter-btn active" data-filter="all">Todos</button>
                <button class="filter-btn" data-filter="weapon">Armas</button>
                <button class="filter-btn" data-filter="armor">Proteções</button>
                <button class="filter-btn" data-filter="paranormal">Paranormais</button>
                <button class="filter-btn" data-filter="general">Gerais</button>
            </div>

            <div class="equipment-list" id="equipment-options">
                <!-- As opções de equipamento serão preenchidas via JavaScript -->
            </div>

            <div class="equipment-details" id="equipment-details">
                <h3>Detalhes do Item</h3>
                <p>Selecione um item para ver os detalhes</p>
            </div>

            <div class="save-button">
                <button class="btn-save" id="select-equipment">
                    <i class="fas fa-check"></i> Selecionar Item
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para modificações -->
    <div class="modal" id="modifications-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modifications-title">Modificações do Item</h2>
                <button class="close-modal">&times;</button>
            </div>

            <div class="modifications-list" id="modifications-options">
                <!-- As opções de modificação serão preenchidas via JavaScript -->
            </div>

            <div class="selected-modifications" id="selected-modifications">
                <h3>Modificações Selecionadas</h3>
                <ul id="selected-mods-list"></ul>
            </div>

            <div class="save-button">
                <button class="btn-save" id="save-modifications">
                    <i class="fas fa-check"></i> Aplicar Modificações
                </button>
            </div>
        </div>
    </div>

    <script>
        // Dados iniciais (do PHP para JS)
        const equipmentData = {
            weapons: <?php echo $armas_json ?: '[]'; ?>,
            armors: <?php echo $protecoes_json ?: '[]'; ?>,
            paranormal: <?php echo $itens_paranormais_json ?: '[]'; ?>,
            general: <?php echo $itens_gerais_json ?: '[]'; ?>
        };

        const currentCharacter = {
            id: <?php echo $personagem ? intval($personagem['id']) : 0; ?>,
            name: "<?php echo $personagem ? addslashes($personagem['nome']) : ''; ?>",
           nex: <?php echo isset($personagem['nex']) ? intval($personagem['nex']) : 0; ?>
        };

        // Elementos DOM usados de fato
        const tokenPreview = document.getElementById('token-preview');
        const tokenUpload = document.getElementById('token-upload');

        const equipmentModal = document.getElementById('equipment-modal');
        const modificationsModal = document.getElementById('modifications-modal');
        const modalTitle = document.getElementById('modal-title');
        const modificationsTitle = document.getElementById('modifications-title');
        const equipmentOptions = document.getElementById('equipment-options');
        const modificationsOptions = document.getElementById('modifications-options');
        const equipmentDetails = document.getElementById('equipment-details');
        const closeModal = document.querySelectorAll('.close-modal');
        const selectEquipmentBtn = document.getElementById('select-equipment');
        const saveModificationsBtn = document.getElementById('save-modifications');

        const addWeaponBtn = document.getElementById('add-weapon');
        const addArmorBtn = document.getElementById('add-armor');
        const addParanormalBtn = document.getElementById('add-paranormal');
        const addGeneralBtn = document.getElementById('add-general');

        const weaponsList = document.getElementById('weapons-list');
        const armorsList = document.getElementById('armors-list');
        const paranormalList = document.getElementById('paranormal-list');
        const generalList = document.getElementById('general-list');

        // Estado atual
        let currentEquipmentType = "";
        let selectedEquipment = null;
        let currentModifications = [];

        // Inicialização
        document.addEventListener('DOMContentLoaded', () => {
            setupEventListeners();
            if (currentCharacter.id > 0) {
                loadCharacterEquipment();
            }
        });

        // Eventos
        function setupEventListeners() {
            // Upload de token
            tokenUpload?.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        tokenPreview.innerHTML = `<img src="${e.target.result}" alt="Token do Personagem">`;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Botões de adicionar equipamentos
            addWeaponBtn?.addEventListener('click', () => openEquipmentModal('weapons', 'Armas'));
            addArmorBtn?.addEventListener('click', () => openEquipmentModal('armors', 'Proteções'));
            addParanormalBtn?.addEventListener('click', () => openEquipmentModal('paranormal', 'Itens Paranormais'));
            addGeneralBtn?.addEventListener('click', () => openEquipmentModal('general', 'Itens Gerais'));

            // Fechar modais
            closeModal.forEach(btn => {
                btn.addEventListener('click', () => {
                    equipmentModal.style.display = 'none';
                    modificationsModal.style.display = 'none';
                });
            });

            // Selecionar equipamento e aplicar modificações
            selectEquipmentBtn?.addEventListener('click', addSelectedEquipment);
            saveModificationsBtn?.addEventListener('click', applyModifications);

            // Botão de salvar ficha
            document.getElementById('save-btn')?.addEventListener('click', saveCharacter);
        }

        // Fluxo de equipamentos
        function openEquipmentModal(type, title) {
            currentEquipmentType = type;
            modalTitle.textContent = `Selecionar ${title}`;
            equipmentModal.style.display = 'flex';

            selectedEquipment = null;
            equipmentDetails.innerHTML = '<h3>Detalhes do Item</h3><p>Selecione um item para ver os detalhes</p>';

            filterEquipmentOptions(type);
        }

        function filterEquipmentOptions(type) {
            equipmentOptions.innerHTML = '';
            if (!equipmentData[type]) {
                equipmentOptions.innerHTML = '<p>Nenhum item disponível.</p>';
                return;
            }

            equipmentData[type].forEach(item => {
                const option = document.createElement('div');
                option.className = 'equipment-option';
                option.dataset.type = type;
                option.dataset.id = item.id;

                option.innerHTML = `<h4>${item.nome}</h4><p><strong>Categoria:</strong> ${item.categoria}</p>`;
                option.addEventListener('click', () => selectEquipment(item, type));

                equipmentOptions.appendChild(option);
            });
        }

        function selectEquipment(item, type) {
            document.querySelectorAll('.equipment-option').forEach(opt => {
                opt.style.border = '1px solid var(--border-color)';
            });
            event.currentTarget.style.border = '2px solid var(--primary-color)';

            equipmentDetails.innerHTML = `<h3>${item.nome}</h3><p><strong>Categoria:</strong> ${item.categoria}</p>`;
            selectedEquipment = {
                ...item,
                sourceType: type
            };
        }

        function addSelectedEquipment() {
            if (!selectedEquipment) {
                alert('Por favor, selecione um item primeiro.');
                return;
            }
            equipmentModal.style.display = 'none';
            openModificationsModal();
        }

        function openModificationsModal() {
            modificationsTitle.textContent = `Modificações para ${selectedEquipment.nome}`;
            modificationsModal.style.display = 'flex';
            currentModifications = [];
            document.getElementById('selected-mods-list').innerHTML = '';

            loadModifications(selectedEquipment.sourceType);
        }

        function loadModifications(itemType) {
            fetch(`../conection/get_modifications.php?tipo=${itemType}`)
                .then(r => r.json())
                .then(displayModifications)
                .catch(() => modificationsOptions.innerHTML = '<p>Erro ao carregar modificações.</p>');
        }

        function displayModifications(modifications) {
            modificationsOptions.innerHTML = '';
            if (!modifications.length) {
                modificationsOptions.innerHTML = '<p>Nenhuma modificação disponível.</p>';
                return;
            }

            modifications.forEach(mod => {
                const option = document.createElement('div');
                option.className = 'modification-option';
                option.innerHTML = `
            <h4>${mod.nome}</h4>
            <p><strong>Efeito:</strong> ${mod.efeito}</p>
            <button class="btn-add-mod">Adicionar</button>
        `;
                option.querySelector('.btn-add-mod').addEventListener('click', () => addModification(mod));
                modificationsOptions.appendChild(option);
            });
        }

        function addModification(modification) {
            currentModifications.push(modification);
            updateSelectedModificationsList();
        }

        function updateSelectedModificationsList() {
            const list = document.getElementById('selected-mods-list');
            list.innerHTML = '';
            currentModifications.forEach((mod, index) => {
                const li = document.createElement('li');
                li.innerHTML = `${mod.nome} <button class="btn-remove-mod" data-index="${index}">Remover</button>`;
                li.querySelector('.btn-remove-mod').addEventListener('click', () => {
                    currentModifications.splice(index, 1);
                    updateSelectedModificationsList();
                });
                list.appendChild(li);
            });
        }

        function applyModifications() {
            let categoriaFinal = selectedEquipment.categoria;
            currentModifications.forEach(mod => categoriaFinal += mod.categoria_extra || 1);
            addItemToCharacter(selectedEquipment, currentModifications, categoriaFinal);
            modificationsModal.style.display = 'none';
        }

        function addItemToCharacter(item, modifications, categoriaFinal) {
            const formData = new FormData();
            formData.append('personagem_id', currentCharacter.id);
            formData.append('item_id', item.id);
            formData.append('tipo_item', item.sourceType);
            formData.append('modificacoes', JSON.stringify(modifications.map(m => m.id)));
            formData.append('categoria_final', categoriaFinal);

            fetch('../conection/add_item_personagem.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        loadCharacterEquipment();
                        alert('Item adicionado!');
                    } else {
                        alert('Erro: ' + data.message);
                    }
                })
                .catch(() => alert('Erro ao adicionar item.'));
        }

        function loadCharacterEquipment() {
            if (!currentCharacter.id) return;
            fetch(`../conection/get_character_equipment.php?personagem_id=${currentCharacter.id}`)
                .then(r => r.json())
                .then(displayCharacterEquipment)
                .catch(() => console.error('Erro ao carregar equipamentos.'));
        }

        function displayCharacterEquipment(equipment) {
            weaponsList.innerHTML = '';
            armorsList.innerHTML = '';
            paranormalList.innerHTML = '';
            generalList.innerHTML = '';

            fillEquipmentList(equipment.filter(i => i.tipo_item === 'arma'), weaponsList);
            fillEquipmentList(equipment.filter(i => i.tipo_item === 'protecao'), armorsList);
            fillEquipmentList(equipment.filter(i => i.tipo_item === 'paranormal'), paranormalList);
            fillEquipmentList(equipment.filter(i => i.tipo_item === 'geral'), generalList);
        }

        function fillEquipmentList(items, listElement) {
            if (!items.length) {
                listElement.innerHTML = '<p>Nenhum item.</p>';
                return;
            }
            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'equipment-item';
                div.innerHTML = `
            <span>${item.nome}</span>
            <button class="btn-remove" data-id="${item.id}">Remover</button>
        `;
                div.querySelector('.btn-remove').addEventListener('click', () => removeEquipmentItem(item.id));
                listElement.appendChild(div);
            });
        }

        function removeEquipmentItem(itemId) {
            if (!confirm('Remover este item?')) return;
            const formData = new FormData();
            formData.append('item_id', itemId);
            fetch('../conection/remove_item_personagem.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        loadCharacterEquipment();
                    } else {
                        alert('Erro: ' + data.message);
                    }
                });
        }

        // Salvar ficha (AJAX)
        function saveCharacter() {
            const formData = new FormData(document.getElementById('ficha-form'));
            fetch('../conection/save_character.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Personagem salvo!');
                        if (data.personagem_id) {
                            currentCharacter.id = data.personagem_id;
                            const newUrl = window.location.href.split('?')[0] + '?id=' + data.personagem_id;
                            window.history.replaceState({}, '', newUrl);
                        }
                    } else {
                        alert('Erro: ' + data.message);
                    }
                })
                .catch(() => alert('Erro ao salvar.'));
        }
    </script>

</body>

</html>