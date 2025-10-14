-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 14-Out-2025 às 13:25
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
-- Estrutura da tabela `historias`
--

CREATE TABLE IF NOT EXISTS `historias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `sistema_jogo` varchar(100) DEFAULT 'Desconhecido',
  `sinopse` text,
  `imagem_historia` varchar(255) DEFAULT 'default_historia.jpg',
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `inventario_op`
--

CREATE TABLE IF NOT EXISTS `inventario_op` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personagem_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `personagem_id` (`personagem_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens_op`
--

CREATE TABLE IF NOT EXISTS `itens_op` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `tipo_item_id` int(11) NOT NULL COMMENT '1: Arma, 2: Proteção, 3: Geral, 4: Amaldiçoado',
  `categoria` int(11) NOT NULL DEFAULT '0' COMMENT 'O número romano da categoria (I, II, III, IV)',
  `espacos` int(11) NOT NULL DEFAULT '1',
  `descricao` text,
  `dano` varchar(20) DEFAULT NULL,
  `critico` varchar(20) DEFAULT NULL,
  `alcance` varchar(50) DEFAULT NULL,
  `tipo_dano` varchar(20) DEFAULT NULL,
  `defesa_bonus` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=78 ;

--
-- Extraindo dados da tabela `itens_op`
--

INSERT INTO `itens_op` (`id`, `nome`, `tipo_item_id`, `categoria`, `espacos`, `descricao`, `dano`, `critico`, `alcance`, `tipo_dano`, `defesa_bonus`) VALUES
(1, 'Coronhada', 1, 0, 1, NULL, '1d4/1d6', 'x2', '-', 'I', NULL),
(2, 'Faca', 1, 0, 1, NULL, '1d4', '19', 'Curto', 'C', NULL),
(3, 'Martelo', 1, 0, 1, NULL, '1d6', 'x2', '-', 'I', NULL),
(4, 'Punhal', 1, 0, 1, NULL, '1d4', 'x3', '-', 'P', NULL),
(5, 'Bastão', 1, 0, 1, NULL, '1d6/1d8', 'x2', '-', 'I', NULL),
(6, 'Machete', 1, 0, 1, NULL, '1d6', '19', '-', 'C', NULL),
(7, 'Lança', 1, 0, 1, NULL, '1d6', 'x2', 'Curto', 'P', NULL),
(8, 'Cajado', 1, 0, 2, NULL, '1d6/1d6', 'x2', '-', 'I', NULL),
(9, 'Arco', 1, 0, 2, NULL, '1d6', 'x3', 'Médio', 'P', NULL),
(10, 'Flechas', 1, 0, 1, NULL, '-', '-', '-', '-', NULL),
(11, 'Besta', 1, 0, 2, NULL, '1d8', '19', 'Médio', 'P', NULL),
(12, 'Pistola', 1, 1, 1, NULL, '1d12', '18', 'Curto', 'B', NULL),
(13, 'Balas Curtas', 1, 0, 1, NULL, '-', '-', '-', '-', NULL),
(14, 'Revólver', 1, 1, 1, NULL, '2d6', '19/x3', 'Curto', 'B', NULL),
(15, 'Fuzil de Caça', 1, 1, 2, NULL, '2d8', '19/x3', 'Médio', 'B', NULL),
(16, 'Balas Longas', 1, 1, 1, NULL, '-', '-', '-', '-', NULL),
(17, 'Machadinha', 1, 0, 1, NULL, '1d6', 'x3', 'Curto', 'C', NULL),
(18, 'Nunchaku', 1, 0, 1, NULL, '1d8', 'x2', '-', 'I', NULL),
(19, 'Corrente', 1, 0, 1, NULL, '1d8', 'x2', '-', 'I', NULL),
(20, 'Espada', 1, 1, 1, NULL, '1d8/1d10', '19', '-', 'C', NULL),
(21, 'Florete', 1, 1, 1, NULL, '1d6', '18', '-', 'C', NULL),
(22, 'Machado', 1, 1, 1, NULL, '1d8', 'x3', '-', 'C', NULL),
(23, 'Maça', 1, 1, 1, NULL, '2d4', 'x2', '-', 'I', NULL),
(24, 'Acha', 1, 1, 2, NULL, '1d12', 'x3', '-', 'C', NULL),
(25, 'Gadanho', 1, 1, 2, NULL, '2d4', 'x4', '-', 'C', NULL),
(26, 'Katana', 1, 1, 2, NULL, '1d10', '19', '-', 'C', NULL),
(27, 'Marreta', 1, 1, 2, NULL, '3d4', 'x2', '-', 'I', NULL),
(28, 'Montante', 1, 1, 2, NULL, '2d6', '19', '-', 'C', NULL),
(29, 'Motosserra', 1, 1, 2, NULL, '3d6', 'x2', '-', 'C', NULL),
(30, 'Arco Composto', 1, 1, 2, NULL, '1d10', 'x3', 'Médio', 'P', NULL),
(31, 'Balestra', 1, 1, 2, NULL, '1d12', '19', 'Médio', 'P', NULL),
(32, 'Submetralhadora', 1, 1, 1, NULL, '2d6', '19/x3', 'Curto', 'B', NULL),
(33, 'Espingarda', 1, 1, 2, NULL, '4d6', 'x3', 'Curto', 'B', NULL),
(34, 'Cartuchos', 1, 1, 1, NULL, '-', '-', '-', '-', NULL),
(35, 'Fuzil de Assalto', 1, 2, 2, NULL, '2d10', '19/x3', 'Médio', 'B', NULL),
(36, 'Fuzil de Precisão', 1, 3, 2, NULL, '2d10', '19/x3', 'Longo', 'B', NULL),
(37, 'Bazuca', 1, 3, 2, NULL, '10d8', 'x2', 'Médio', 'I', NULL),
(38, 'Foguete', 1, 1, 1, NULL, '-', '-', '-', '-', NULL),
(39, 'Lança-Chamas', 1, 3, 2, NULL, '6d6', 'x2', 'Curto', 'Fogo', NULL),
(40, 'Combustível', 1, 1, 1, NULL, '-', '-', '-', '-', NULL),
(41, 'Metralhadora', 1, 2, 2, NULL, '2d12', 'x2', 'Médio', 'B', NULL),
(42, 'Proteção Leve', 2, 1, 2, NULL, NULL, NULL, NULL, NULL, 5),
(43, 'Proteção Pesada', 2, 2, 5, NULL, NULL, NULL, NULL, NULL, 10),
(44, 'Escudo', 2, 1, 2, NULL, NULL, NULL, NULL, NULL, 2),
(45, 'Kit de perícia', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 'Utensílio', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'Vestimenta', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 'Granada de atordoamento', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 'Granada de fragmentação', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 'Granada de fumaça', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 'Granada incendiária', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 'Mina antipessoal', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 'Algemas', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 'Arpéu', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 'Bandoleira', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 'Binóculos', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 'Bloqueador de sinal', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 'Cicatrizante', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 'Corda', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 'Equipamento de sobrevivência', 3, 0, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 'Lanterna tática', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 'Máscara de gás', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 'Mochila militar', 3, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 'Óculos de visão térmica', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 'Pé de cabra', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 'Pistola de dardos', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 'Pistola sinalizadora', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 'Soqueira', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 'Spray de pimenta', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 'Taser', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 'Traje hazmat', 3, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 'Amarras de (elemento)', 4, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 'Câmera de aura paranormal', 4, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 'Componentes ritualísticos de (elemento)', 4, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 'Emissor de pulsos paranormais', 4, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 'Escuta de ruídos paranormais', 4, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 'Scanner de manifestação paranormal de (elemento)', 4, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `mundos`
--

CREATE TABLE IF NOT EXISTS `mundos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `sistema_jogo` varchar(100) DEFAULT 'Desconhecido',
  `descricao` text,
  `imagem_mundo` varchar(255) DEFAULT 'default_mundo.jpg',
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

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
  `trilha_id` int(11) DEFAULT NULL,
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
  `poderes_classe` text,
  `poderes_paranormais` text,
  `rituais` text,
  `habilidades_trilha` text,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_origem` (`origem_id`),
  KEY `fk_classe` (`classe_id`),
  KEY `fk_trilha` (`trilha_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `personagens_op_poderes`
--

CREATE TABLE IF NOT EXISTS `personagens_op_poderes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personagem_id` int(11) NOT NULL,
  `poder_id` int(11) NOT NULL,
  `tipo_poder` enum('classe','paranormal') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `personagem_id` (`personagem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `poderes_classe`
--

CREATE TABLE IF NOT EXISTS `poderes_classe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classe_id` int(11) DEFAULT NULL,
  `nex_requerido` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classe_id` (`classe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=50 ;

--
-- Extraindo dados da tabela `poderes_classe`
--

INSERT INTO `poderes_classe` (`id`, `classe_id`, `nex_requerido`, `nome`, `descricao`) VALUES
(1, NULL, 15, 'Transcender', 'Você abre mão de um aumento de Sanidade para aprender um poder paranormal.'),
(2, NULL, 15, 'Treinamento em Perícia', 'Escolha duas perícias. Você se torna treinado nelas. Pode ser escolhido múltiplas vezes para se tornar Veterano (NEX 35%) e Expert (NEX 70%).'),
(3, 1, 15, 'Armamento Pesado', 'Você recebe proficiência com armas pesadas. (Pré-req: For 2)'),
(4, 1, 15, 'Artista Marcial', 'Seus ataques desarmados causam 1d6 de dano e contam como armas ágeis. O dano aumenta para 1d8 em NEX 35% e 1d10 em NEX 70%.'),
(5, 1, 15, 'Ataque de Oportunidade', 'Pode gastar 1 PE e uma reação para fazer um ataque corpo a corpo em um ser que sair de um espaço adjacente a você.'),
(6, 1, 15, 'Combater com Duas Armas', 'Se estiver usando duas armas (uma leve), pode fazer dois ataques na ação agredir, com -2 nos testes de ataque. (Pré-req: Agi 3)'),
(7, 1, 15, 'Combate Defensivo', 'Quando usa a ação agredir, pode sofrer -2 nos ataques para receber +5 na Defesa. (Pré-req: Int 2)'),
(8, 1, 15, 'Golpe Demolidor', 'Pode gastar 1 PE para causar +2d de dano do mesmo tipo da sua arma contra objetos. (Pré-req: For 2)'),
(9, 1, 15, 'Golpe Pesado', 'O dano de suas armas corpo a corpo aumenta em mais um dado do mesmo tipo.'),
(10, 1, 15, 'Incansável', 'Uma vez por cena, pode gastar 2 PE para fazer uma ação de investigação adicional usando Força ou Agilidade.'),
(11, 1, 15, 'Presteza Atlética', 'Pode gastar 1 PE para usar Força ou Agilidade em um teste para facilitar a investigação.'),
(12, 1, 30, 'Proteção Pesada', 'Você recebe proficiência com Proteções Pesadas. (Pré-req: NEX 30%)'),
(13, 1, 15, 'Reflexos Defensivos', 'Você recebe +2 em Defesa e em testes de resistência. (Pré-req: Agi 2)'),
(14, 1, 15, 'Saque Rápido', 'Você pode sacar ou guardar itens como uma ação livre.'),
(15, 1, 60, 'Segurar o Gatilho', 'Pode gastar PE para fazer ataques extras com armas de fogo. (Pré-req: NEX 60%)'),
(16, 1, 15, 'Sentido Tático', 'Pode gastar 2 PE para receber um bônus em Defesa e resistência igual ao seu Intelecto. (Pré-req: Int 2)'),
(17, 1, 30, 'Tanque de Guerra', 'Se estiver usando proteção pesada, a Defesa e RD dela aumentam em +2. (Pré-req: Proteção Pesada)'),
(18, 1, 15, 'Tiro Certeiro', 'Soma sua Agilidade no dano com armas de disparo e ignora penalidade por alvo em combate corpo a corpo.'),
(19, 1, 15, 'Tiro de Cobertura', 'Pode gastar 1 PE e uma ação padrão para forçar um alvo a se proteger, sofrendo -5 em testes de ataque.'),
(20, 2, 15, 'Perito', 'Escolha uma perícia. Você recebe +5 nela (não cumulativo com bônus de treinamento).'),
(21, 2, 40, 'Engenhosidade', 'Em NEX 40%, ao usar Eclético, pode gastar +2 PE para receber os benefícios de ser veterano na perícia.'),
(22, 3, 15, 'Fortalecimento Ritual', 'Seus rituais com duração de cena ou mais têm sua duração duplicada.'),
(23, 3, 30, 'Mente Sã', 'Você recebe +1 de Sanidade para cada 5% de NEX.'),
(24, 2, 15, 'Balística Avançada', 'Você recebe proficiência com armas táticas de fogo e +2 em rolagens de dano com armas de fogo.'),
(25, 2, 15, 'Conhecimento Aplicado', 'Quando faz um teste de perícia (exceto Luta e Pontaria), você pode gastar 2 PE para mudar o atributo-base da perícia para Intelecto. (Pré-req: Int 2)'),
(26, 2, 15, 'Hacker', 'Você recebe +5 em testes de Tecnologia para invadir sistemas e diminui o tempo necessário para hackear para uma ação completa. (Pré-req: treinado em Tecnologia)'),
(27, 2, 15, 'Mãos Rápidas', 'Ao fazer um teste de Crime, você pode pagar 1 PE para fazê-lo como uma ação livre. (Pré-req: Agi 3, treinado em Crime)'),
(28, 2, 15, 'Mochila de Utilidades', 'Um item a sua escolha (exceto armas) conta como uma categoria abaixo e ocupa 1 espaço a menos.'),
(29, 2, 15, 'Movimento Tático', 'Você pode gastar 1 PE para ignorar a penalidade em deslocamento por terreno difícil e por escalar até o final do turno. (Pré-req: treinado em Atletismo)'),
(30, 2, 15, 'Na Trilha Certa', 'Sempre que tiver sucesso em um teste para procurar pistas, você pode gastar 1 PE para receber +2 no próximo teste (cumulativo).'),
(31, 2, 15, 'Nerd', 'Uma vez por cena, pode gastar 2 PE para fazer um teste de Atualidades (DT 20) para receber uma informação útil para a cena.'),
(32, 2, 15, 'Ninja Urbano', 'Você recebe proficiência com armas táticas de ataque corpo a corpo e de disparo (exceto de fogo) e +2 em rolagens de dano com elas.'),
(33, 2, 15, 'Pensamento Ágil', 'Uma vez por rodada, durante uma cena de investigação, você pode gastar 2 PE para fazer uma ação de procurar pistas adicional.'),
(34, 2, 15, 'Perito em Explosivos', 'Você soma seu Intelecto na DT para resistir aos seus explosivos e pode excluir dos efeitos da explosão um número de alvos igual ao seu Intelecto.'),
(35, 2, 15, 'Primeira Impressão', 'Você recebe +10 no primeiro teste de Diplomacia, Enganação, Intimidação ou Intuição que fizer em uma cena.'),
(36, 3, 15, 'Camuflar Ocultismo', 'Você pode gastar +2 PE para lançar um ritual sem componentes ritualísticos e gesticulação. Outros seres só percebem o ritual com um teste de Ocultismo (DT 25).'),
(37, 3, 15, 'Criar Selo', 'Você sabe fabricar selos paranormais de rituais que conheça. Fabricar um selo gasta uma ação de interlúdio e PE iguais ao custo do ritual.'),
(38, 3, 15, 'Envolto em Mistério', 'Você recebe +5 em Enganação e Intimidação contra pessoas não treinadas em Ocultismo.'),
(39, 3, 15, 'Especialista em Elemento', 'Escolha um elemento. A DT para resistir aos seus rituais desse elemento aumenta em +2.'),
(40, 3, 15, 'Ferramentas Paranormais', 'Você reduz a categoria de um item paranormal em I e pode ativá-los sem pagar seu custo em PE.'),
(41, 3, 60, 'Fluxo de Poder', 'Você pode manter dois rituais sustentados ativos ao mesmo tempo com uma única ação livre. (Pré-req: NEX 60%)'),
(42, 3, 15, 'Guiado pelo Paranormal', 'Uma vez por cena, você pode gastar 2 PE para fazer uma ação de investigação adicional.'),
(43, 3, 15, 'Identificação Paranormal', 'Você recebe +10 em testes de Ocultismo para identificar criaturas, objetos ou rituais.'),
(44, 3, 15, 'Improvisar Componentes', 'Uma vez por cena, pode gastar uma ação completa e um teste de Investigação (DT 15) para encontrar componentes de um elemento à sua escolha.'),
(45, 3, 15, 'Intuição Paranormal', 'Sempre que usa a ação facilitar investigação, você soma seu Intelecto ou Presença no teste (à sua escolha).'),
(46, 3, 45, 'Mestre em Elemento', 'Escolha um elemento em que já seja Especialista. O custo para lançar rituais desse elemento diminui em –1 PE. (Pré-req: NEX 45%)'),
(47, 3, 15, 'Ritual Potente', 'Você soma seu Intelecto nas rolagens de dano ou nos efeitos de cura de seus rituais. (Pré-req: Int 2)'),
(48, 3, 15, 'Ritual Predileto', 'Escolha um ritual que você conhece. Você reduz em –1 PE o custo do ritual.'),
(49, 3, 15, 'Tatuagem Ritualística', 'Símbolos em sua pele reduzem em –1 PE o custo de rituais de alcance pessoal que têm você como alvo.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `poderes_paranormais`
--

CREATE TABLE IF NOT EXISTS `poderes_paranormais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `elemento` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=23 ;

--
-- Extraindo dados da tabela `poderes_paranormais`
--

INSERT INTO `poderes_paranormais` (`id`, `nome`, `descricao`, `elemento`) VALUES
(1, 'Anatomia Insana', 'Seu corpo é transfigurado. Você tem 50% de chance de ignorar o dano adicional de um acerto crítico ou ataque furtivo. Pré-requisito: Sangue 2.', 'Sangue'),
(2, 'Arma de Sangue', 'Você pode gastar uma ação de movimento e 2 PE para produzir garras, chifres ou uma lâmina de sangue que brota de seu corpo (arma simples, 1d6 de dano).', 'Sangue'),
(3, 'Sangue de Ferro', 'Seu sangue flui de forma paranormal e agressiva. Você recebe +2 pontos de vida por NEX.', 'Sangue'),
(4, 'Sangue Fervente', 'Enquanto estiver machucado, você recebe +1 em Agilidade ou Força (à sua escolha). Pré-requisito: Sangue 2.', 'Sangue'),
(5, 'Sangue Vivo', 'Na primeira vez que ficar machucado durante uma cena, você recebe cura acelerada 2. O efeito dura até o final da cena. Pré-requisito: Sangue 1.', 'Sangue'),
(6, 'Afortunado', 'Uma vez por rolagem, você pode rolar novamente um resultado 1 em qualquer dado que não seja d20.', 'Energia'),
(7, 'Campo Protetor', 'Você consegue gerar um campo de Energia que o protege de perigos. Quando usa a ação esquiva, pode gastar 1 PE para receber +5 em Defesa. Pré-requisito: Energia 1.', 'Energia'),
(8, 'Causalidade Fortuita', 'A Energia o conduz rumo a descobertas. Em cenas de investigação, a DT para procurar pistas diminui em –5 para você.', 'Energia'),
(9, 'Golpe de Sorte', 'Seus ataques recebem +1 na margem de ameaça. Pré-requisito: Energia 1.', 'Energia'),
(10, 'Manipular Entropia', 'Você pode gastar 2 PE para fazer um alvo em alcance curto rolar novamente um dos dados em um teste de perícia. Pré-requisito: Energia 1.', 'Energia'),
(11, 'Encarar a Morte', 'Sua conexão com a Morte faz com que você não hesite em situações de perigo. Durante cenas de ação, seu limite de gasto de PE aumenta em +1.', 'Morte'),
(12, 'Escapar da Morte', 'Uma vez por cena, quando um dano o deixaria com 0 PV, você fica com 1 PV. Não funciona em caso de dano massivo. Pré-requisito: Morte 1.', 'Morte'),
(13, 'Potencial Aprimorado', 'Você recebe +1 ponto de esforço por NEX.', 'Morte'),
(14, 'Potencial Reaproveitado', 'Uma vez por rodada, quando passa num teste de resistência, você ganha 2 PE temporários cumulativos.', 'Morte'),
(15, 'Surto Temporal', 'Uma vez por cena, você pode gastar 3 PE para realizar uma ação padrão adicional. Pré-requisito: Morte 2.', 'Morte'),
(16, 'Expansão de Conhecimento', 'Você se conecta com o Conhecimento, rompendo os limites de sua compreensão. Você aprende um poder de classe que não pertença à sua classe. Pré-requisito: Conhecimento 1.', 'Conhecimento'),
(17, 'Percepção Paranormal', 'Em cenas de investigação, sempre que fizer um teste para procurar pistas, você pode rolar novamente um dado com resultado menor que 10.', 'Conhecimento'),
(18, 'Precognição', 'Você possui um "sexto sentido". Você recebe +2 em Defesa e em testes de resistência.', 'Conhecimento'),
(19, 'Sensitivo', 'Você consegue sentir as emoções e intenções de outros personagens, recebendo +5 em testes de Diplomacia, Intimidação e Intuição.', 'Conhecimento'),
(20, 'Visão do Oculto', 'Você não enxerga mais pelos olhos, mas pela percepção do Conhecimento. Você recebe +5 em testes de Percepção e enxerga no escuro.', 'Conhecimento'),
(21, 'Aprender Ritual', 'Você aprende e pode conjurar um ritual de 1º círculo à sua escolha. A partir de NEX 45%, você aprende um ritual de até 2º círculo.', 'Nenhum'),
(22, 'Resistir a Elemento', 'Escolha entre Conhecimento, Energia, Morte ou Sangue. Você recebe resistência 10 contra esse elemento.', 'Nenhum');

-- --------------------------------------------------------

--
-- Estrutura da tabela `poderes_trilha`
--

CREATE TABLE IF NOT EXISTS `poderes_trilha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trilha_id` int(11) NOT NULL,
  `nex_requerido` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `trilha_id` (`trilha_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=61 ;

--
-- Extraindo dados da tabela `poderes_trilha`
--

INSERT INTO `poderes_trilha` (`id`, `trilha_id`, `nex_requerido`, `nome`, `descricao`) VALUES
(1, 1, 10, 'A Favorita', 'Escolha uma arma para ser sua favorita, como katana ou fuzil de assalto. A categoria da arma escolhida é reduzida em I.'),
(2, 1, 40, 'Técnica Secreta', 'Sua arma favorita reduz a categoria em mais I. Além disso, você pode gastar 2 PE para adicionar o efeito "amplo" ou "destruidor" a um ataque com ela.'),
(3, 1, 65, 'Técnica Sublime', 'Sua arma favorita recebe +2 modificações. Você também aprende a modificação "letal".'),
(4, 1, 99, 'Máquina de Matar', 'A categoria da sua arma favorita é reduzida em mais I (totalizando III). Além disso, sua margem de ameaça com ela diminui em 2 e seu dano aumenta em mais um dado do mesmo tipo.'),
(5, 2, 10, 'Inspirar Confiança', 'Você pode gastar uma ação padrão e 2 PE para fazer um aliado em alcance curto rolar novamente um teste recém realizado.'),
(6, 2, 40, 'Estrategista', 'Você pode direcionar aliados em alcance curto. Gaste uma ação padrão e 1 PE por aliado que quiser direcionar (limitado pelo seu Intelecto). No próximo turno dos aliados afetados, eles ganham uma ação de movimento adicional.'),
(7, 2, 65, 'Brecha na Guarda', 'Uma vez por rodada, quando um aliado causar dano em um inimigo que esteja em seu alcance curto, você pode gastar uma reação e 2 PE para que você ou outro aliado em alcance curto faça um ataque adicional contra o mesmo inimigo.'),
(8, 2, 99, 'Oficial Comandante', 'Você pode gastar uma ação padrão e 5 PE para que cada aliado que você possa ver em alcance médio receba uma ação padrão adicional no próximo turno dele.'),
(9, 3, 10, 'Técnica Letal', 'Você recebe um aumento de +2 na margem de ameaça com todos os seus ataques corpo a corpo.'),
(10, 3, 40, 'Revidar', 'Sempre que bloquear um ataque, você pode gastar uma reação e 2 PE para fazer um ataque corpo a corpo no inimigo que o atacou.'),
(11, 3, 65, 'Força Opressora', 'Quando acerta um ataque corpo a corpo, você pode gastar 1 PE para realizar a manobra derrubar ou empurrar contra o alvo do ataque como ação livre.'),
(12, 3, 99, 'Potência Máxima', 'Quando usa seu Ataque Especial com armas corpo a corpo, todos os bônus numéricos são dobrados.'),
(13, 4, 10, 'Iniciativa Aprimorada', 'Você recebe +5 em Iniciativa e uma ação de movimento adicional na primeira rodada.'),
(14, 4, 40, 'Ataque Extra', 'Uma vez por rodada, quando faz um ataque, você pode gastar 2 PE para fazer um ataque adicional.'),
(15, 4, 65, 'Surto de Adrenalina', 'Uma vez por rodada, você pode gastar 5 PE para realizar uma ação padrão ou de movimento adicional.'),
(16, 4, 99, 'Sempre Alerta', 'Você recebe uma ação padrão adicional no início de cada cena de combate.'),
(17, 5, 10, 'Casca Grossa', 'Você recebe +1 PV para cada 5% de NEX e, quando faz um bloqueio, soma seu Vigor na resistência a dano recebida.'),
(18, 5, 40, 'Cai Dentro', 'Sempre que um oponente em alcance curto atacar um de seus aliados, você pode gastar uma reação e 1 PE para fazer com que esse oponente faça um teste de Vontade (DT Vig). Se falhar, o oponente deve atacar você em vez de seu aliado.'),
(19, 5, 65, 'Duro de Matar', 'Ao sofrer dano não paranormal, você pode gastar uma reação e 2 PE para reduzir esse dano à metade. Em NEX 85%, pode usar esta habilidade para reduzir dano paranormal.'),
(20, 5, 99, 'Inquebrável', 'Enquanto estiver machucado, você recebe +5 na Defesa e resistência a dano 5. Enquanto estiver morrendo, em vez de normal, você não fica indefeso e ainda pode realizar ações.'),
(21, 6, 10, 'Mira de Elite', 'Você recebe proficiência com armas de fogo de elite e soma seu Intelecto em rolagens de dano com essas armas.'),
(22, 6, 40, 'Disparo Letal', 'Quando faz a ação mirar, você pode gastar 1 PE para aumentar em +2 a margem de ameaça do próximo ataque que fizer até o final de seu próximo turno.'),
(23, 6, 65, 'Disparo Impactante', 'Se estiver usando uma arma de fogo com calibre grosso, você pode gastar 2 PE para fazer as manobras derrubar, desarmar, empurrar ou quebrar usando um ataque à distância.'),
(24, 6, 99, 'Atirar para Matar', 'Quando faz um acerto crítico com uma arma de fogo, você causa dano máximo, sem precisar rolar dados.'),
(25, 7, 10, 'Ataque Furtivo', 'Você sabe atingir os pontos vitais de um inimigo distraído. Uma vez por rodada, quando atinge um alvo desprevenido ou que você esteja flanqueando, pode gastar 1 PE para causar +1d6 pontos de dano. O dano adicional aumenta em +1d6 em NEX 40%, 65% e 99%.'),
(26, 7, 40, 'Gatuno', 'Você recebe +5 em Atletismo e Crime e pode percorrer seu deslocamento normal quando se esconder sem penalidade.'),
(27, 7, 65, 'Assassinar', 'Você pode gastar uma ação de movimento e 3 PE para analisar um alvo em alcance curto. Até o fim do seu próximo turno, seu primeiro Ataque Furtivo que causar dano a ele tem seus dados de dano extras dessa habilidade dobrados.'),
(28, 7, 99, 'Sombra Fugaz', 'Quando faz um teste de Furtividade após atacar ou fazer outra ação chamativa, você pode gastar 3 PE para não sofrer a penalidade de -10 no teste.'),
(29, 8, 10, 'Paramédico', 'Você pode usar uma ação padrão e 2 PE para curar 2d10 pontos de vida de si mesmo ou de um aliado adjacente. Pode gastar +1 PE para aumentar a cura em +1d10 em NEX 40% e 65%.'),
(30, 8, 40, 'Equipe de Trauma', 'Você pode usar uma ação padrão e 2 PE para remover uma condição negativa (exceto morrendo) de um aliado adjacente.'),
(31, 8, 65, 'Resgate', 'Uma vez por rodada, em alcance curto de um aliado machucado ou morrendo, você pode gastar uma ação livre para se aproximar dele e curá-lo. Sempre que curar PV ou remover condições, você e o aliado recebem +5 na Defesa até o início do seu próximo turno.'),
(32, 8, 99, 'Reanimação', 'Uma vez por cena, você pode gastar uma ação completa e 10 PE para trazer de volta à vida um personagem que tenha morrido na mesma cena (exceto morte por dano massivo).'),
(33, 9, 10, 'Eloquência', 'Você pode usar uma ação completa e 1 PE por alvo em alcance curto para afetar outros personagens com sua fala. Faça um teste de Diplomacia. Um alvo que falhar no teste de Vontade fica fascinado enquanto você se concentrar.'),
(34, 9, 40, 'Discurso Motivador', 'Você pode gastar uma ação padrão e 4 PE para inspirar seus aliados com suas palavras. Você e todos os seus aliados em alcance curto ganham +2 em testes de perícia até o fim da cena.'),
(35, 9, 65, 'Conheço um Cara', 'Uma vez por missão, você pode ativar sua rede de contatos para pedir um favor, como trocar todo o equipamento do seu grupo ou conseguir uma segunda chance de preparação de missão.'),
(36, 9, 99, 'Truque de Mestre', 'Você pode gastar 5 PE para simular o efeito de qualquer habilidade de Vontade que tenha visto um de seus aliados usar durante a cena.'),
(37, 10, 10, 'Inventário Otimizado', 'Você soma seu Intelecto à sua Força para calcular sua capacidade de carga. Por exemplo, se você tem Força 1 e Intelecto 3, seu inventário tem 20 espaços.'),
(38, 10, 40, 'Remendo', 'Você pode gastar uma ação completa e 1 PE para remover a condição quebrado de um equipamento adjacente até o final da cena.'),
(39, 10, 65, 'Improvisar', 'Você pode improvisar equipamentos com material ao seu redor. Escolha um equipamento geral e gaste uma ação completa e 2 PE; ele é de categoria 0. Você cria uma versão funcional do equipamento.'),
(40, 10, 99, 'Preparado para Tudo', 'Sempre que precisar de um item qualquer (exceto armas), pode gastar uma ação de movimento e 3 PE por categoria do item para lembrar que colocou ele no fundo da bolsa!'),
(41, 11, 10, 'Ampliar Ritual', 'Quando lança um ritual, você pode gastar +2 PE para aumentar seu alcance em um passo (de curto para médio, de médio para longo ou de longo para extremo) ou dobrar sua área de efeito.'),
(42, 11, 40, 'Acelerar Ritual', 'Uma vez por rodada, você pode aumentar o custo de um ritual em 4 PE para conjurá-lo como uma ação livre.'),
(43, 11, 65, 'Anular Ritual', 'Quando for alvo de um ritual, você pode gastar uma quantidade de PE igual ao custo pago por esse ritual e fazer um teste oposto de Ocultismo contra o conjurador. Se vencer, você anula o ritual.'),
(44, 11, 99, 'Canalizar o Medo', 'Você aprende o ritual Canalizar o Medo.'),
(45, 12, 10, 'Poder do Flagelo', 'Ao conjurar um ritual, você pode gastar seus próprios pontos de vida para pagar o custo em pontos de esforço, à taxa de 2 PV por PE pago. Pontos de vida gastos dessa forma só podem ser recuperados com descanso.'),
(46, 12, 40, 'Abraçar a Dor', 'Sempre que sofrer dano não paranormal, você pode gastar uma reação e 2 PE para reduzir esse dano à metade.'),
(47, 12, 65, 'Absorver a Agonia', 'Sempre que você reduz um ou mais inimigos a 0 PV com um ritual, você recebe uma quantidade de PE temporários igual ao círculo do ritual utilizado.'),
(48, 12, 99, 'Medo Tangível', 'Você aprende o ritual Medo Tangível.'),
(49, 13, 10, 'Saber Ampliado', 'Você aprende um ritual de 1º círculo. Toda vez que ganha acesso a um novo círculo, aprende um ritual adicional daquele círculo. Esses rituais não contam em seu limite de rituais.'),
(50, 13, 40, 'Grimório Ritualístico', 'Você cria um grimório especial, que armazena uma quantidade de rituais de 1º ou 2º círculos igual ao seu Intelecto. O grimório ocupa 1 espaço.'),
(51, 13, 65, 'Rituais Eficientes', 'A DT para resistir a todos os seus rituais aumenta em +5.'),
(52, 13, 99, 'Conhecendo o Medo', 'Você aprende o ritual Conhecendo o Medo.'),
(53, 14, 10, 'Mente Sã', 'Você compreende melhor as entidades do Outro Lado. Você recebe resistência paranormal +5.'),
(54, 14, 40, 'Presença Poderosa', 'Você adiciona sua Presença ao seu limite de PE por turno, mas apenas para conjurar rituais.'),
(55, 14, 65, 'Inabalável', 'Você recebe resistência a dano mental e paranormal 10. Se passar em um teste de Vontade para reduzir dano paranormal, não sofre dano algum.'),
(56, 14, 99, 'Presença do Medo', 'Você aprende o ritual Presença do Medo.'),
(57, 15, 10, 'Lâmina Maldita', 'Você aprende o ritual Amaldiçoar Arma. Seu custo é reduzido em -1 PE. Além disso, pode usar Ocultismo para testes de ataque com a arma amaldiçoada.'),
(58, 15, 40, 'Gladiador Paranormal', 'Sempre que acerta um ataque corpo a corpo, você recebe 2 PE temporários (máximo igual ao seu limite de PE por turno).'),
(59, 15, 65, 'Conjuração Marcial', 'Uma vez por rodada, ao lançar um ritual com execução de uma ação padrão, pode gastar 2 PE para fazer um ataque corpo a corpo como ação livre.'),
(60, 15, 99, 'Lâmina do Medo', 'Você aprende o ritual Lâmina do Medo.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `trilhas`
--

CREATE TABLE IF NOT EXISTS `trilhas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classe_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classe_id` (`classe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=16 ;

--
-- Extraindo dados da tabela `trilhas`
--

INSERT INTO `trilhas` (`id`, `classe_id`, `nome`) VALUES
(1, 1, 'Aniquilador'),
(2, 1, 'Comandante de Campo'),
(3, 1, 'Guerreiro'),
(4, 1, 'Operações Especiais'),
(5, 1, 'Tropa de Choque'),
(6, 2, 'Atirador de elite'),
(7, 2, 'Infiltrador'),
(8, 2, 'Médico de campo'),
(9, 2, 'Negociador'),
(10, 2, 'Técnico'),
(11, 3, 'Conduite'),
(12, 3, 'Flagelador'),
(13, 3, 'Graduado'),
(14, 3, 'Intuitivo'),
(15, 3, 'Lâmina Paranormal');

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
-- Limitadores para a tabela `historias`
--
ALTER TABLE `historias`
  ADD CONSTRAINT `historias_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `inventario_op`
--
ALTER TABLE `inventario_op`
  ADD CONSTRAINT `fk_inventario_op_item` FOREIGN KEY (`item_id`) REFERENCES `itens_op` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inventario_op_personagem` FOREIGN KEY (`personagem_id`) REFERENCES `personagens_op` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `mundos`
--
ALTER TABLE `mundos`
  ADD CONSTRAINT `mundos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_trilha` FOREIGN KEY (`trilha_id`) REFERENCES `trilhas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_classe` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_origem` FOREIGN KEY (`origem_id`) REFERENCES `origens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user_op` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `personagens_op_poderes`
--
ALTER TABLE `personagens_op_poderes`
  ADD CONSTRAINT `fk_poder_personagem` FOREIGN KEY (`personagem_id`) REFERENCES `personagens_op` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `poderes_classe`
--
ALTER TABLE `poderes_classe`
  ADD CONSTRAINT `poderes_classe_ibfk_1` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `poderes_trilha`
--
ALTER TABLE `poderes_trilha`
  ADD CONSTRAINT `poderes_trilha_ibfk_1` FOREIGN KEY (`trilha_id`) REFERENCES `trilhas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `trilhas`
--
ALTER TABLE `trilhas`
  ADD CONSTRAINT `trilhas_ibfk_1` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
