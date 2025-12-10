<?php
// item_functions.php
require_once 'db_connect.php';

function getArmas() {
    global $conn;
    $result = $conn->query("SELECT * FROM Op_armas ORDER BY nome");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getProtecoes() {
    global $conn;
    $result = $conn->query("SELECT * FROM Op_protecoes ORDER BY nome");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getItensGerais() {
    global $conn;
    $result = $conn->query("SELECT * FROM Op_gerais ORDER BY nome");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getItensParanormais() {
    global $conn;
    $result = $conn->query("SELECT * FROM Op_paranormal ORDER BY nome");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getModificacoes($tipo_item) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Op_modificacoes WHERE tipo_item = ? ORDER BY nome");
    $stmt->bind_param("s", $tipo_item);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function calcularCategoriaFinal($categoria_base, $modificacoes) {
    $categoria_final = $categoria_base;
    $primeira_mod_paranormal = true;
    
    foreach ($modificacoes as $mod_id) {
        // Buscar informações da modificação
        $stmt = $conn->prepare("SELECT categoria_extra FROM Op_modificacoes WHERE id = ?");
        $stmt->bind_param("i", $mod_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $mod = $result->fetch_assoc();
        
        if ($mod) {
            // Verificar se é modificação paranormal (categoria_extra >= 2)
            if ($mod['categoria_extra'] >= 2) {
                if ($primeira_mod_paranormal) {
                    $categoria_final += 2;
                    $primeira_mod_paranormal = false;
                } else {
                    $categoria_final += 1;
                }
            } else {
                $categoria_final += 1;
            }
        }
    }
    
    return $categoria_final;
}

function adicionarItemPersonagem($personagem_id, $item_id, $tipo_item, $modificacoes = []) {
    global $conn;
    
    // Determinar a tabela base baseada no tipo de item
    switch ($tipo_item) {
        case 'arma':
            $tabela = 'Op_armas';
            $campo_id = 'id';
            $campo_categoria = 'categoria';
            break;
        case 'protecao':
            $tabela = 'Op_protecoes';
            $campo_id = 'id';
            $campo_categoria = 'categoria';
            break;
        case 'geral':
            $tabela = 'Op_gerais';
            $campo_id = 'id';
            $campo_categoria = 'categoria';
            break;
        case 'paranormal':
            $tabela = 'Op_paranormal';
            $campo_id = 'id';
            $campo_categoria = 'categoria';
            break;
        default:
            return false;
    }
    
    // Buscar categoria base do item
    $stmt = $conn->prepare("SELECT $campo_categoria FROM $tabela WHERE $campo_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    
    if (!$item) {
        return false;
    }
    
    $categoria_base = $item[$campo_categoria];
    $categoria_final = calcularCategoriaFinal($categoria_base, $modificacoes);
    
    // Serializar array de modificações para armazenamento
    $modificacoes_json = json_encode($modificacoes);
    
    // Inserir na tabela de itens do personagem
    $stmt = $conn->prepare("INSERT INTO Op_personagem_itens (personagem_id, item_id, tipo_item, modificacoes, categoria_final) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $personagem_id, $item_id, $tipo_item, $modificacoes_json, $categoria_final);
    
    return $stmt->execute();
}
?>