-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 17-Set-2025 às 17:54
-- Versão do servidor: 5.6.13
-- versão do PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `arca_do_aventureiro`
--
CREATE DATABASE IF NOT EXISTS `arca_do_aventureiro` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `arca_do_aventureiro`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `pv_inicial` int(11) NOT NULL,
  `pv_por_nivel` int(11) NOT NULL,
  `pe_inicial` int(11) NOT NULL DEFAULT '1',
  `pe_por_nivel` int(11) NOT NULL,
  `san_inicial` int(11) NOT NULL,
  `san_por_nivel` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `classes`
--

INSERT INTO `classes` (`id`, `nome`, `pv_inicial`, `pv_por_nivel`, `pe_inicial`, `pe_por_nivel`, `san_inicial`, `san_por_nivel`) VALUES
(1, 'Combatente', 20, 4, 2, 2, 12, 3),
(2, 'Especialista', 16, 3, 3, 3, 16, 4),
(3, 'Ocultista', 12, 2, 4, 4, 20, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `op_armas`
--

CREATE TABLE IF NOT EXISTS `op_armas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `dano` varchar(20) NOT NULL,
  `crit` varchar(20) NOT NULL,
  `alcance` varchar(20) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `categoria` int(11) NOT NULL DEFAULT '0',
  `espaco` int(11) NOT NULL DEFAULT '1',
  `descricao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Extraindo dados da tabela `op_armas`
--

INSERT INTO `op_armas` (`id`, `nome`, `dano`, `crit`, `alcance`, `tipo`, `categoria`, `espaco`, `descricao`) VALUES
(1, 'Coronhada', '1d4/1d6', 'x2', '-', 'C', 0, 1, 'Corpo a Corpo - Leves'),
(2, 'Faca', '1d4', '19', 'Curto', 'C', 0, 1, 'Corpo a Corpo - Leves'),
(3, 'Martelo', '1d6', 'x2', '-', 'I', 0, 1, 'Corpo a Corpo - Leves'),
(4, 'Punhal', '1d4', 'x2', '-', 'P', 0, 1, 'Corpo a Corpo - Leves'),
(5, 'Bastão', '1d6/1d8', 'x2', '-', 'I', 0, 1, 'Corpo a Corpo - Uma Mão'),
(6, 'Machete', '1d6', '19', '-', 'C', 0, 1, 'Corpo a Corpo - Uma Mão'),
(7, 'Lança', '1d6', 'x2', 'Curto', 'P', 0, 1, 'Corpo a Corpo - Uma Mão'),
(8, 'Cajado', '1d6/1d6', 'x2', '-', 'I', 0, 2, 'Corpo a Corpo - Duas Mãos'),
(9, 'Arco', '1d6', 'x3', 'Médio', 'P', 0, 2, 'Armas de Disparo - Duas Mãos'),
(10, 'Besta', '1d8', 'x3', 'Médio', 'P', 0, 2, 'Armas de Disparo - Duas Mãos'),
(11, 'Pistola', '1d12', '18', 'Curto', 'B', 1, 1, 'Armas de Fogo - Leves'),
(12, 'Revólver', '2d6', '19/x3', 'Curto', 'B', 1, 1, 'Armas de Fogo - Leves'),
(13, 'Fuzil de caça', '2d8', '19/x3', 'Médio', 'B', 1, 2, 'Armas de Fogo - Duas Mãos'),
(14, 'Machadinha', '1d6', 'x3', 'Curto', 'C', 0, 1, 'Armas Táticas - Corpo a Corpo - Leves'),
(15, 'Nunchaku', '1d8', 'x2', '-', 'I', 0, 1, 'Armas Táticas - Corpo a Corpo - Leves'),
(16, 'Corrente', '1d8', 'x2', '-', 'I', 0, 1, 'Armas Táticas - Corpo a Corpo - Uma Mão'),
(17, 'Espada', '1d8/1d10', '19', '-', 'C', 1, 1, 'Armas Táticas - Corpo a Corpo - Uma Mão'),
(18, 'Florete', '1d6', '18', '-', 'C', 1, 1, 'Armas Táticas - Corpo a Corpo - Uma Mão'),
(19, 'Machado', '1d8', 'x3', '-', 'C', 1, 1, 'Armas Táticas - Corpo a Corpo - Uma Mão'),
(20, 'Maça', '2d4', 'x2', '-', 'I', 1, 1, 'Armas Táticas - Corpo a Corpo - Uma Mão'),
(21, 'Acha', '1d12', 'x2', '-', 'C', 1, 2, 'Armas Táticas - Corpo a Corpo - Duas Mãos'),
(22, 'Gadanho', '2d4', 'x4', '-', 'C', 1, 2, 'Armas Táticas - Corpo a Corpo - Duas Mãos'),
(23, 'Katana', '1d10', '19', '-', 'C', 1, 2, 'Armas Táticas - Corpo a Corpo - Duas Mãos'),
(24, 'Marreta', '3d4', 'x2', '-', 'I', 1, 2, 'Armas Táticas - Corpo a Corpo - Duas Mãos'),
(25, 'Montante', '2d6', '19', '-', 'C', 1, 2, 'Armas Táticas - Corpo a Corpo - Duas Mãos'),
(26, 'Motosserra', '3d6', 'x2', '-', 'C', 1, 2, 'Armas Táticas - Corpo a Corpo - Duas Mãos'),
(27, 'Arco composto', '1d10', 'x3', 'Médio', 'P', 1, 2, 'Armas Táticas - Armas de Disparo - Duas Mãos'),
(28, 'Balestra', '1d12', '19', 'Médio', 'P', 1, 2, 'Armas Táticas - Armas de Disparo - Duas Mãos'),
(29, 'Submetralhadora', '2d6', '19/x3', 'Curto', 'B', 1, 1, 'Armas Táticas - Armas de Fogo - Uma Mão'),
(30, 'Espingarda', '4d6', 'x3', 'Curto', 'B', 1, 2, 'Armas Táticas - Armas de Fogo - Duas Mãos'),
(31, 'Fuzil de assalto', '2d10', '19/x3', 'Médio', 'B', 2, 2, 'Armas Táticas - Armas de Fogo - Duas Mãos'),
(32, 'Fuzil de precisão', '2d10', '19/x3', 'Longo', 'B', 3, 2, 'Armas Táticas - Armas de Fogo - Duas Mãos'),
(33, 'Bazuca', '10d8', 'x2', 'Médio', 'I', 3, 2, 'Armas Pesadas - À Distância - Duas Mãos'),
(34, 'Lança-chamas', '6d6', 'x2', 'Curto', 'Fogo', 3, 2, 'Armas Pesadas - À Distância - Duas Mãos'),
(35, 'Metralhadora', '2d12', 'x2', 'Médio', 'B', 2, 2, 'Armas Pesadas - À Distância - Duas Mãos');

-- --------------------------------------------------------

--
-- Estrutura da tabela `op_gerais`
--

CREATE TABLE IF NOT EXISTS `op_gerais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `categoria` int(11) NOT NULL,
  `espaco` int(11) NOT NULL,
  `descricao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Extraindo dados da tabela `op_gerais`
--

INSERT INTO `op_gerais` (`id`, `nome`, `categoria`, `espaco`, `descricao`) VALUES
(1, 'Kit de Perícia', 0, 1, 'Um conjunto de ferramentas necessárias para algumas perícias.'),
(2, 'Utensílio', 0, 1, 'Um item comum que tenha uma utilidade específica.'),
(3, 'Vestimenta', 1, 1, 'Uma peça de vestuário que fornece +2 em uma perícia.'),
(4, 'Granada de atordoamento', 0, 1, 'Explosivo para atordoar alvos.'),
(5, 'Granada de fragmentação', 1, 1, 'Explosivo que causa dano em área.'),
(6, 'Granada de fumaça', 0, 1, 'Cria uma nuvem de fumaça.'),
(7, 'Granada incendiária', 1, 1, 'Explosivo que causa dano por fogo.'),
(8, 'Mina antipessoal', 1, 1, 'Mina que explode ao ser ativada.'),
(9, 'Algemas', 0, 1, 'Usadas para prender alvos.'),
(10, 'Arpéu', 0, 1, 'Gancho para escalar ou se fixar.'),
(11, 'Bandoleira', 0, 1, 'Cinto para carregar itens.'),
(12, 'Binóculos', 0, 1, 'Fornece +5 em testes de Percepção.'),
(13, 'Bloqueador de sinal', 1, 1, 'Emite ondas que bloqueiam sinais.'),
(14, 'Cicatrizante', 1, 1, 'Recupera pontos de vida.'),
(15, 'Corda', 0, 1, 'Corda resistente.'),
(16, 'Equipamento de sobrevivência', 0, 2, 'Equipamento para sobreviver em ambientes selvagens.'),
(17, 'Lanterna tática', 1, 1, 'Ilumina lugares escuros.'),
(18, 'Máscara de gás', 0, 1, 'Proteção contra gases.'),
(19, 'Mochila militar', 1, 2, 'Mochila que aumenta a capacidade de carga.'),
(20, 'Óculos de visão térmica', 1, 1, 'Óculos que eliminam a penalidade em testes de camuflagem.'),
(21, 'Pé de cabra', 0, 1, 'Usado para arrombar portas.'),
(22, 'Pistola de dardos', 0, 1, 'Pistola que dispara dardos.'),
(23, 'Pistola sinalizadora', 0, 1, 'Pistola que dispara sinalizadores.'),
(24, 'Soqueira', 0, 1, 'Aumenta o dano de ataques desarmados.'),
(25, 'Spray de pimenta', 0, 1, 'Causa dano e penalidades nos inimigos.'),
(26, 'Taser', 1, 1, 'Dispositivo de choque.'),
(27, 'Traje hazmat', 1, 2, 'Traje de proteção contra produtos químicos.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `op_item_modificacoes`
--

CREATE TABLE IF NOT EXISTS `op_item_modificacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `modificacao_id` int(11) NOT NULL,
  `tipo_item` enum('arma','protecao','geral','paranormal') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `modificacao_id` (`modificacao_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `op_modificacoes`
--

CREATE TABLE IF NOT EXISTS `op_modificacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `tipo_item` varchar(50) NOT NULL,
  `efeito` text NOT NULL,
  `categoria_extra` int(11) NOT NULL DEFAULT '0',
  `tipo_modificacao` enum('mundana','paranormal') NOT NULL,
  `elemento` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=39 ;

--
-- Extraindo dados da tabela `op_modificacoes`
--

INSERT INTO `op_modificacoes` (`id`, `nome`, `tipo_item`, `efeito`, `categoria_extra`, `tipo_modificacao`, `elemento`) VALUES
(1, 'Certeira', 'arma', '+2 em testes de ataque.', 1, 'mundana', NULL),
(2, 'Cruel', 'arma', '+2 em rolagens de dano.', 1, 'mundana', NULL),
(3, 'Discreta', 'arma', '+5 em testes para ser ocultada e reduz o espaço em -1.', 1, 'mundana', NULL),
(4, 'Perigosa', 'arma', '+2 em margem de ameaça.', 1, 'mundana', NULL),
(5, 'Tática', 'arma', 'Pode sacar como ação livre.', 1, 'mundana', NULL),
(6, 'Alongada', 'arma', '+2 em testes de ataque.', 1, 'mundana', NULL),
(7, 'Calibre Grosso', 'arma', 'Aumenta o dano em mais um dado do mesmo tipo.', 1, 'mundana', NULL),
(8, 'Compensador', 'arma', 'Anula penalidade por rajadas.', 1, 'mundana', NULL),
(9, 'Ferrolho Automático', 'arma', 'A arma se torna automática.', 1, 'mundana', NULL),
(10, 'Mira Laser', 'arma', '+2 em margem de ameaça.', 1, 'mundana', NULL),
(11, 'Mira Telescópica', 'arma', 'Aumenta alcance da arma e a habilidade Ataque Furtivo.', 1, 'mundana', NULL),
(12, 'Silenciador', 'arma', 'Reduz em -2d20 a penalidade em Furtividade para se esconder após atacar.', 1, 'mundana', NULL),
(13, 'Visão de Calor', 'arma', 'Ignora camuflagem.', 1, 'mundana', NULL),
(14, 'Dum dum', 'arma', '+1 em multiplicador de crítico.', 1, 'mundana', NULL),
(15, 'Explosiva', 'arma', 'Aumenta o dano em +2d6.', 1, 'mundana', NULL),
(16, 'Abascanta', 'protecao', '+5 em testes de resistência contra rituais.', 1, 'paranormal', 'Conhecimento'),
(17, 'Profética', 'protecao', 'Resistência a Conhecimento 10.', 1, 'paranormal', 'Conhecimento'),
(18, 'Cinética', 'protecao', '+2 em Defesa e resistência a dano 2.', 1, 'paranormal', 'Energia'),
(19, 'Lépida', 'protecao', '+10 em testes de Atletismo e +3m de deslocamento.', 1, 'paranormal', 'Energia'),
(20, 'Voltáica', 'protecao', 'Resistência a Energia 10.', 1, 'paranormal', 'Energia'),
(21, 'Letárgica', 'protecao', '+2 em Defesa e chance de ignorar dano extra.', 1, 'paranormal', 'Morte'),
(22, 'Repulsiva', 'protecao', 'Resistência a Morte 10.', 1, 'paranormal', 'Morte'),
(23, 'Sombria', 'protecao', '+5 em Furtividade e ignora penalidade de carga.', 1, 'paranormal', 'Morte'),
(24, 'Regenerativa', 'protecao', 'Resistência a Sangue 10.', 1, 'paranormal', 'Sangue'),
(25, 'Sádica', 'protecao', '+1 em testes de ataque e rolagens de dano para cada 10 pontos de dano sofrido.', 1, 'paranormal', 'Sangue'),
(26, 'Carisma', 'acessorio', '+1 em Presença.', 1, 'paranormal', 'Conhecimento'),
(27, 'Conjuração', 'acessorio', 'Permite conjurar um ritual de 1º círculo.', 1, 'paranormal', 'Conhecimento'),
(28, 'Escudo Mental', 'acessorio', 'Resistência mental 10.', 1, 'paranormal', 'Conhecimento'),
(29, 'Reflexão', 'acessorio', 'Pode refletir um ritual de volta ao conjurador.', 1, 'paranormal', 'Conhecimento'),
(30, 'Sagacidade', 'acessorio', '+1 em Intelecto.', 1, 'paranormal', 'Conhecimento'),
(31, 'Defesa', 'acessorio', '+5 em Defesa.', 1, 'paranormal', 'Energia'),
(32, 'Destreza', 'acessorio', '+1 em Agilidade.', 1, 'paranormal', 'Energia'),
(33, 'Potência', 'acessorio', 'Aumenta a DT de habilidades, poderes e rituais em +1.', 1, 'paranormal', 'Energia'),
(34, 'Esforço Adicional', 'acessorio', '+5 PE.', 1, 'paranormal', 'Morte'),
(35, 'Disposição', 'acessorio', '+1 em Vigor.', 1, 'paranormal', 'Sangue'),
(36, 'Pujança', 'acessorio', '+1 em Força.', 1, 'paranormal', 'Sangue'),
(37, 'Vitalidade', 'acessorio', '+15 PV.', 1, 'paranormal', 'Sangue'),
(38, 'Proteção Elemental', 'acessorio', 'Resistência 10 contra um elemento.', 1, 'paranormal', 'Varia');

-- --------------------------------------------------------

--
-- Estrutura da tabela `op_paranormal`
--

CREATE TABLE IF NOT EXISTS `op_paranormal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `categoria` int(11) NOT NULL DEFAULT '0',
  `espaco` int(11) NOT NULL DEFAULT '1',
  `elemento` varchar(20) DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `op_paranormal`
--

INSERT INTO `op_paranormal` (`id`, `nome`, `categoria`, `espaco`, `elemento`, `descricao`) VALUES
(1, 'Amarra de elementos', 2, 1, 'Varia', 'Imobiliza criaturas com base em elementos.'),
(2, 'Câmera de aura paranormal', 2, 1, 'Energia', 'Detecta auras paranormais.'),
(3, 'Componentes ritualísticos', 0, 1, 'Varia', 'Componentes necessários para conjurar rituais.'),
(4, 'Emissor de pulsos paranormais', 2, 1, 'Varia', 'Cria uma pulsação que afasta criaturas do elemento oposto.'),
(5, 'Escuta de ruídos paranormais', 2, 1, 'Conhecimento', 'Ajuda a ouvir e identificar ruídos paranormais.'),
(6, 'Medidor de estabilidade da membrana', 2, 1, 'Conhecimento', 'Avalia a estabilidade de um local.'),
(7, 'Scanner de manifestação paranormal', 2, 1, 'Varia', 'Detecta manifestações paranormais.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `op_personagem_itens`
--

CREATE TABLE IF NOT EXISTS `op_personagem_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personagem_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `tipo_item` enum('arma','protecao','geral','paranormal') NOT NULL,
  `modificacoes` text,
  `categoria_final` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `personagem_id` (`personagem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `op_protecoes`
--

CREATE TABLE IF NOT EXISTS `op_protecoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `defesa` int(11) NOT NULL,
  `categoria` int(11) NOT NULL,
  `espaco` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `op_protecoes`
--

INSERT INTO `op_protecoes` (`id`, `nome`, `defesa`, `categoria`, `espaco`) VALUES
(1, 'Leve', 5, 1, 2),
(2, 'Pesada', 10, 2, 5),
(3, 'Escudo', 2, 1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `origens`
--

CREATE TABLE IF NOT EXISTS `origens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `poder_nome` varchar(100) NOT NULL,
  `poder_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=27 ;

--
-- Extraindo dados da tabela `origens`
--

INSERT INTO `origens` (`id`, `nome`, `poder_nome`, `poder_desc`) VALUES
(1, 'Acadêmico', 'Saber é Poder', 'Você pode usar seu Intelecto em vez de Presença em testes de Ocultismo e Religião.'),
(2, 'Agente de Saúde', 'Médico de Campo', 'Sempre que você curar um personagem com a perícia Medicina, você adiciona seu Intelecto no total de PV curados.'),
(3, 'Amnésico', 'Vislumbres do Passado', 'Uma vez por cena, você pode gastar 2 PE para rolar novamente um teste de perícia recém realizado. Você deve usar o segundo resultado.'),
(4, 'Artista', 'Obra Prima', 'Você pode gastar uma ação padrão e 2 PE para inspirar seus aliados. Você e aliados em alcance curto recebem +2 em testes de Vontade até o fim da cena.'),
(5, 'Atleta', '110%', 'Você pode gastar 2 PE para receber +5 em um teste de Atletismo ou Acrobacia.'),
(6, 'Chef', 'Comida Caseira', 'Você pode gastar uma ação padrão e 2 PE para preparar um alimento. Um personagem pode consumir este alimento para recuperar 1d6 pontos de vida.'),
(7, 'Criminoso', 'O Crime Compensa', 'No início de cada missão, você recebe um item adicional de categoria I à sua escolha.'),
(8, 'Cultista Arrependido', 'Traços do Outro Lado', 'Você sabe identificar e falar o idioma do Outro Lado, "Língua das Profundezas". Além disso, recebe +2 em testes de Ocultismo.'),
(9, 'Desgarrado', 'Calejado', 'Você recebe +1 PV para cada 5% de NEX.'),
(10, 'Engenheiro', 'Ferramentas Favoritas', 'Você recebe um kit de perícia de Profissão (Engenharia) com a modificação "Melhorada", que fornece +2 em testes.'),
(11, 'Executivo', 'Processo Otimizado', 'Uma vez por cena, você pode gastar 2 PE para realizar uma ação de interlúdio de "Obter Informação" como uma ação padrão.'),
(12, 'Lutador', 'Mão Pesada', 'Seus ataques desarmados causam 1d6 de dano e podem causar dano letal.'),
(13, 'Magnata', 'Patrocinador da Ordem', 'Você recebe +2 de Crédito no início de cada missão.'),
(14, 'Militar', 'Para Bellum', 'Você recebe +2 em rolagens de dano com armas de fogo.'),
(15, 'Operário', 'Ferramenta de Trabalho', 'Escolha uma arma simples ou tática que se assemelhe a uma ferramenta. Você recebe proficiência com essa arma.'),
(16, 'Policial', 'Investigador', 'Você recebe +2 na defesa\r\n'),
(17, 'Religioso', 'Acalentar', 'Você pode gastar uma ação padrão e 2 PE para acalmar um alvo em alcance curto, que recupera 1d6 pontos de Sanidade.'),
(18, 'Servidor Público', 'Espírito Cívico', 'Você recebe +2 em testes de Diplomacia e Intimidação.'),
(19, 'Teórico da Conspiração', 'Eu Sabia!', 'Uma vez por cena, você pode gastar 2 PE para receber +5 em um teste de Atualidades ou Investigação para descobrir uma pista ou segredo.'),
(20, 'T.I.', 'Motor de Busca', 'Você pode gastar 2 PE para usar a perícia Tecnologia para procurar informações como se estivesse usando Investigação.'),
(21, 'Trabalhador Rural', 'Desbravador', 'Você recebe +5 em testes de Sobrevivência.'),
(22, 'Trambiqueiro', 'Impostor', 'Você pode gastar 2 PE para receber +5 em um teste de Enganação.'),
(23, 'Universitário', 'Dedicado', 'Você recebe +2 em testes de Intelecto.'),
(24, 'Vítima', 'Cicatrizes Psicológicas', 'Você recebe +1 de Sanidade para cada 5% de NEX.'),
(25, 'Investigador', 'Faro para Pistas', 'Uma vez por cena, quando fizer um teste  para procurar pistas, você pode gastar 1 PE para receber +5 nesse teste.'),
(26, 'Mercenário', 'Posição de combate', 'No primeiro turno de cada cena de ação, você pode gastar 2 PE para receber uma ação de movimento adicional.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pericias`
--

CREATE TABLE IF NOT EXISTS `pericias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personagem_id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `valor` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personagem_pericia_unique` (`personagem_id`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `personagens_op`
--

CREATE TABLE IF NOT EXISTS `personagens_op` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `imagem` varchar(255) DEFAULT 'default.jpg',
  `nex` int(11) NOT NULL DEFAULT '5',
  `origem_id` int(11) DEFAULT NULL,
  `classe_id` int(11) DEFAULT NULL,
  `patente` varchar(50) DEFAULT NULL,
  `pontos_prestigio` int(11) DEFAULT '0',
  `vida_max` int(11) DEFAULT '0',
  `pe_max` int(11) DEFAULT '0',
  `sanidade_max` int(11) DEFAULT '0',
  `forca` int(11) NOT NULL DEFAULT '1',
  `agilidade` int(11) NOT NULL DEFAULT '1',
  `intelecto` int(11) NOT NULL DEFAULT '1',
  `vigor` int(11) NOT NULL DEFAULT '1',
  `presenca` int(11) NOT NULL DEFAULT '1',
  `defesa` int(11) NOT NULL DEFAULT '11',
  `inventario_espacos` int(11) NOT NULL DEFAULT '7',
  `trilha` varchar(100) DEFAULT NULL,
  `poderes_classe` text,
  `poderes_paranormais` text,
  `rituais` text,
  `habilidades_trilha` text,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_origem` (`origem_id`),
  KEY `fk_classe` (`classe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome_usuario`, `email`, `senha`) VALUES
(1, 'Lemuaira', 'rodrigueskaio337@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b'),
(2, 'Testilson', 'Testilson@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `op_personagem_itens`
--
ALTER TABLE `op_personagem_itens`
  ADD CONSTRAINT `op_personagem_itens_ibfk_1` FOREIGN KEY (`personagem_id`) REFERENCES `personagens_op` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `pericias`
--
ALTER TABLE `pericias`
  ADD CONSTRAINT `fk_pericia_personagem` FOREIGN KEY (`personagem_id`) REFERENCES `personagens_op` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `personagens_op`
--
ALTER TABLE `personagens_op`
  ADD CONSTRAINT `fk_classe` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_origem` FOREIGN KEY (`origem_id`) REFERENCES `origens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user_op` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
