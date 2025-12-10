-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 06-Nov-2025 às 12:11
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
  `user_id` int(11) DEFAULT NULL,
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
  PRIMARY KEY (`id`),
  KEY `idx_user_item_op` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=78 ;

--
-- Extraindo dados da tabela `itens_op`
--

INSERT INTO `itens_op` (`id`, `user_id`, `nome`, `tipo_item_id`, `categoria`, `espacos`, `descricao`, `dano`, `critico`, `alcance`, `tipo_dano`, `defesa_bonus`) VALUES
(1, NULL, 'Coronhada', 1, 0, 1, NULL, '1d4/1d6', 'x2', '-', 'I', NULL),
(2, NULL, 'Faca', 1, 0, 1, NULL, '1d4', '19', 'Curto', 'C', NULL),
(3, NULL, 'Martelo', 1, 0, 1, NULL, '1d6', 'x2', '-', 'I', NULL),
(4, NULL, 'Punhal', 1, 0, 1, NULL, '1d4', 'x3', '-', 'P', NULL),
(5, NULL, 'Bastão', 1, 0, 1, NULL, '1d6/1d8', 'x2', '-', 'I', NULL),
(6, NULL, 'Machete', 1, 0, 1, NULL, '1d6', '19', '-', 'C', NULL),
(7, NULL, 'Lança', 1, 0, 1, NULL, '1d6', 'x2', 'Curto', 'P', NULL),
(8, NULL, 'Cajado', 1, 0, 2, NULL, '1d6/1d6', 'x2', '-', 'I', NULL),
(9, NULL, 'Arco', 1, 0, 2, NULL, '1d6', 'x3', 'Médio', 'P', NULL),
(10, NULL, 'Flechas', 1, 0, 1, NULL, '-', '-', '-', '-', NULL),
(11, NULL, 'Besta', 1, 0, 2, NULL, '1d8', '19', 'Médio', 'P', NULL),
(12, NULL, 'Pistola', 1, 1, 1, NULL, '1d12', '18', 'Curto', 'B', NULL),
(13, NULL, 'Balas Curtas', 1, 0, 1, NULL, '-', '-', '-', '-', NULL),
(14, NULL, 'Revólver', 1, 1, 1, NULL, '2d6', '19/x3', 'Curto', 'B', NULL),
(15, NULL, 'Fuzil de Caça', 1, 1, 2, NULL, '2d8', '19/x3', 'Médio', 'B', NULL),
(16, NULL, 'Balas Longas', 1, 1, 1, NULL, '-', '-', '-', '-', NULL),
(17, NULL, 'Machadinha', 1, 0, 1, NULL, '1d6', 'x3', 'Curto', 'C', NULL),
(18, NULL, 'Nunchaku', 1, 0, 1, NULL, '1d8', 'x2', '-', 'I', NULL),
(19, NULL, 'Corrente', 1, 0, 1, NULL, '1d8', 'x2', '-', 'I', NULL),
(20, NULL, 'Espada', 1, 1, 1, NULL, '1d8/1d10', '19', '-', 'C', NULL),
(21, NULL, 'Florete', 1, 1, 1, NULL, '1d6', '18', '-', 'C', NULL),
(22, NULL, 'Machado', 1, 1, 1, NULL, '1d8', 'x3', '-', 'C', NULL),
(23, NULL, 'Maça', 1, 1, 1, NULL, '2d4', 'x2', '-', 'I', NULL),
(24, NULL, 'Acha', 1, 1, 2, NULL, '1d12', 'x3', '-', 'C', NULL),
(25, NULL, 'Gadanho', 1, 1, 2, NULL, '2d4', 'x4', '-', 'C', NULL),
(26, NULL, 'Katana', 1, 1, 2, NULL, '1d10', '19', '-', 'C', NULL),
(27, NULL, 'Marreta', 1, 1, 2, NULL, '3d4', 'x2', '-', 'I', NULL),
(28, NULL, 'Montante', 1, 1, 2, NULL, '2d6', '19', '-', 'C', NULL),
(29, NULL, 'Motosserra', 1, 1, 2, NULL, '3d6', 'x2', '-', 'C', NULL),
(30, NULL, 'Arco Composto', 1, 1, 2, NULL, '1d10', 'x3', 'Médio', 'P', NULL),
(31, NULL, 'Balestra', 1, 1, 2, NULL, '1d12', '19', 'Médio', 'P', NULL),
(32, NULL, 'Submetralhadora', 1, 1, 1, NULL, '2d6', '19/x3', 'Curto', 'B', NULL),
(33, NULL, 'Espingarda', 1, 1, 2, NULL, '4d6', 'x3', 'Curto', 'B', NULL),
(34, NULL, 'Cartuchos', 1, 1, 1, NULL, '-', '-', '-', '-', NULL),
(35, NULL, 'Fuzil de Assalto', 1, 2, 2, NULL, '2d10', '19/x3', 'Médio', 'B', NULL),
(36, NULL, 'Fuzil de Precisão', 1, 3, 2, NULL, '2d10', '19/x3', 'Longo', 'B', NULL),
(37, NULL, 'Bazuca', 1, 3, 2, NULL, '10d8', 'x2', 'Médio', 'I', NULL),
(38, NULL, 'Foguete', 1, 1, 1, NULL, '-', '-', '-', '-', NULL),
(39, NULL, 'Lança-Chamas', 1, 3, 2, NULL, '6d6', 'x2', 'Curto', 'Fogo', NULL),
(40, NULL, 'Combustível', 1, 1, 1, NULL, '-', '-', '-', '-', NULL),
(41, NULL, 'Metralhadora', 1, 2, 2, NULL, '2d12', 'x2', 'Médio', 'B', NULL),
(42, NULL, 'Proteção Leve', 2, 1, 2, NULL, NULL, NULL, NULL, NULL, 5),
(43, NULL, 'Proteção Pesada', 2, 2, 5, NULL, NULL, NULL, NULL, NULL, 10),
(44, NULL, 'Escudo', 2, 1, 2, NULL, NULL, NULL, NULL, NULL, 2),
(45, NULL, 'Kit de perícia', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(46, NULL, 'Utensílio', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(47, NULL, 'Vestimenta', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(48, NULL, 'Granada de atordoamento', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(49, NULL, 'Granada de fragmentação', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(50, NULL, 'Granada de fumaça', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(51, NULL, 'Granada incendiária', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(52, NULL, 'Mina antipessoal', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(53, NULL, 'Algemas', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(54, NULL, 'Arpéu', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(55, NULL, 'Bandoleira', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(56, NULL, 'Binóculos', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(57, NULL, 'Bloqueador de sinal', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(58, NULL, 'Cicatrizante', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(59, NULL, 'Corda', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(60, NULL, 'Equipamento de sobrevivência', 3, 0, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(61, NULL, 'Lanterna tática', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(62, NULL, 'Máscara de gás', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(63, NULL, 'Mochila militar', 3, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(64, NULL, 'Óculos de visão térmica', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(65, NULL, 'Pé de cabra', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(66, NULL, 'Pistola de dardos', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(67, NULL, 'Pistola sinalizadora', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(68, NULL, 'Soqueira', 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(69, NULL, 'Spray de pimenta', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(70, NULL, 'Taser', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(71, NULL, 'Traje hazmat', 3, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(72, NULL, 'Amarras de (elemento)', 4, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(73, NULL, 'Câmera de aura paranormal', 4, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(74, NULL, 'Componentes ritualísticos de (elemento)', 4, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(75, NULL, 'Emissor de pulsos paranormais', 4, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(76, NULL, 'Escuta de ruídos paranormais', 4, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(77, NULL, 'Scanner de manifestação paranormal de (elemento)', 4, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL);

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
-- Estrutura da tabela `personagem_t20_inventario`
--

CREATE TABLE IF NOT EXISTS `personagem_t20_inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personagem_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT '1',
  `equipado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_personagem_inventario` (`personagem_id`),
  KEY `fk_item_inventario_t20` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `personagem_t20_magias`
--

CREATE TABLE IF NOT EXISTS `personagem_t20_magias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personagem_id` int(11) NOT NULL,
  `magia_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personagem_magia_unique` (`personagem_id`,`magia_id`),
  KEY `fk_magia_personagem` (`personagem_id`),
  KEY `fk_magia_id` (`magia_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `personagem_t20_poderes`
--

CREATE TABLE IF NOT EXISTS `personagem_t20_poderes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personagem_id` int(11) NOT NULL,
  `poder_id` int(11) NOT NULL,
  `tipo_poder` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_personagem_poder` (`personagem_id`)
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
-- Estrutura da tabela `personagens_op_rituais`
--

CREATE TABLE IF NOT EXISTS `personagens_op_rituais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personagem_id` int(11) NOT NULL,
  `ritual_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personagem_ritual_unique` (`personagem_id`,`ritual_id`),
  KEY `fk_ritual_personagem` (`personagem_id`),
  KEY `fk_ritual_id` (`ritual_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `personagens_t20`
--

CREATE TABLE IF NOT EXISTS `personagens_t20` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `jogador` varchar(100) DEFAULT NULL,
  `nivel` int(11) NOT NULL DEFAULT '1',
  `raca_id` int(11) DEFAULT NULL,
  `origem_id` int(11) DEFAULT NULL,
  `classe_id` int(11) DEFAULT NULL,
  `divindade_id` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT 'default_t20.jpg',
  `pv_max` int(11) DEFAULT '0',
  `pv_atual` int(11) DEFAULT '0',
  `pm_max` int(11) DEFAULT '0',
  `pm_atual` int(11) DEFAULT '0',
  `tibares` int(11) NOT NULL DEFAULT '0',
  `carga_maxima` int(11) NOT NULL DEFAULT '0',
  `forca` int(11) NOT NULL DEFAULT '10',
  `destreza` int(11) NOT NULL DEFAULT '10',
  `constituicao` int(11) NOT NULL DEFAULT '10',
  `inteligencia` int(11) NOT NULL DEFAULT '10',
  `sabedoria` int(11) NOT NULL DEFAULT '10',
  `carisma` int(11) NOT NULL DEFAULT '10',
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `treino_acrobacia` tinyint(1) NOT NULL DEFAULT '0',
  `treino_adestramento` tinyint(1) NOT NULL DEFAULT '0',
  `treino_atletismo` tinyint(1) NOT NULL DEFAULT '0',
  `treino_atuacao` tinyint(1) NOT NULL DEFAULT '0',
  `treino_cavalgar` tinyint(1) NOT NULL DEFAULT '0',
  `treino_conhecimento` tinyint(1) NOT NULL DEFAULT '0',
  `treino_cura` tinyint(1) NOT NULL DEFAULT '0',
  `treino_diplomacia` tinyint(1) NOT NULL DEFAULT '0',
  `treino_enganacao` tinyint(1) NOT NULL DEFAULT '0',
  `treino_fortitude` tinyint(1) NOT NULL DEFAULT '0',
  `treino_furtividade` tinyint(1) NOT NULL DEFAULT '0',
  `treino_guerra` tinyint(1) NOT NULL DEFAULT '0',
  `treino_iniciativa` tinyint(1) NOT NULL DEFAULT '0',
  `treino_intimidacao` tinyint(1) NOT NULL DEFAULT '0',
  `treino_intuicao` tinyint(1) NOT NULL DEFAULT '0',
  `treino_investigacao` tinyint(1) NOT NULL DEFAULT '0',
  `treino_jogatina` tinyint(1) NOT NULL DEFAULT '0',
  `treino_ladinagem` tinyint(1) NOT NULL DEFAULT '0',
  `treino_luta` tinyint(1) NOT NULL DEFAULT '0',
  `treino_misticismo` tinyint(1) NOT NULL DEFAULT '0',
  `treino_nobreza` tinyint(1) NOT NULL DEFAULT '0',
  `treino_oficio` tinyint(1) NOT NULL DEFAULT '0',
  `treino_percepcao` tinyint(1) NOT NULL DEFAULT '0',
  `treino_pilotagem` tinyint(1) NOT NULL DEFAULT '0',
  `treino_pontaria` tinyint(1) NOT NULL DEFAULT '0',
  `treino_reflexos` tinyint(1) NOT NULL DEFAULT '0',
  `treino_religiao` tinyint(1) NOT NULL DEFAULT '0',
  `treino_sobrevivencia` tinyint(1) NOT NULL DEFAULT '0',
  `treino_vontade` tinyint(1) NOT NULL DEFAULT '0',
  `outros_acrobacia` int(11) NOT NULL DEFAULT '0',
  `outros_adestramento` int(11) NOT NULL DEFAULT '0',
  `outros_atletismo` int(11) NOT NULL DEFAULT '0',
  `outros_atuacao` int(11) NOT NULL DEFAULT '0',
  `outros_cavalgar` int(11) NOT NULL DEFAULT '0',
  `outros_conhecimento` int(11) NOT NULL DEFAULT '0',
  `outros_cura` int(11) NOT NULL DEFAULT '0',
  `outros_diplomacia` int(11) NOT NULL DEFAULT '0',
  `outros_enganacao` int(11) NOT NULL DEFAULT '0',
  `outros_fortitude` int(11) NOT NULL DEFAULT '0',
  `outros_furtividade` int(11) NOT NULL DEFAULT '0',
  `outros_guerra` int(11) NOT NULL DEFAULT '0',
  `outros_iniciativa` int(11) NOT NULL DEFAULT '0',
  `outros_intimidacao` int(11) NOT NULL DEFAULT '0',
  `outros_intuicao` int(11) NOT NULL DEFAULT '0',
  `outros_investigacao` int(11) NOT NULL DEFAULT '0',
  `outros_jogatina` int(11) NOT NULL DEFAULT '0',
  `outros_ladinagem` int(11) NOT NULL DEFAULT '0',
  `outros_luta` int(11) NOT NULL DEFAULT '0',
  `outros_misticismo` int(11) NOT NULL DEFAULT '0',
  `outros_nobreza` int(11) NOT NULL DEFAULT '0',
  `outros_oficio` int(11) NOT NULL DEFAULT '0',
  `outros_percepcao` int(11) NOT NULL DEFAULT '0',
  `outros_pilotagem` int(11) NOT NULL DEFAULT '0',
  `outros_pontaria` int(11) NOT NULL DEFAULT '0',
  `outros_reflexos` int(11) NOT NULL DEFAULT '0',
  `outros_religiao` int(11) NOT NULL DEFAULT '0',
  `outros_sobrevivencia` int(11) NOT NULL DEFAULT '0',
  `outros_vontade` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id_t20` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

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
-- Estrutura da tabela `rituais_op`
--

CREATE TABLE IF NOT EXISTS `rituais_op` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `elemento` varchar(20) NOT NULL,
  `circulo` int(11) NOT NULL,
  `execucao` varchar(50) NOT NULL,
  `alcance` varchar(50) NOT NULL,
  `alvo` varchar(100) DEFAULT NULL,
  `duracao` varchar(50) DEFAULT NULL,
  `resistencia` varchar(100) DEFAULT NULL,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_circulo_elemento` (`circulo`,`elemento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=81 ;

--
-- Extraindo dados da tabela `rituais_op`
--

INSERT INTO `rituais_op` (`id`, `nome`, `elemento`, `circulo`, `execucao`, `alcance`, `alvo`, `duracao`, `resistencia`, `descricao`) VALUES
(1, 'Amaldiçoar Arma', 'Sangue', 1, 'Padrão', 'Toque', '1 arma corpo a corpo ou 10 munições', 'Cena', 'Nenhuma', 'A arma ou munições se tornam amaldiçoadas. Elas recebem +1d6 de dano de Sangue. Em NEX 35%, o bônus aumenta para +2d6.'),
(2, 'Armadura de Sangue', 'Sangue', 1, 'Padrão', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Você cria uma armadura de sangue sobre sua pele, recebendo +5 na Defesa. Em NEX 35%, você também recebe Resistência a dano 2.'),
(3, 'Cicatrização', 'Sangue', 1, 'Padrão', 'Toque', '1 ser', 'Instantânea', 'Nenhuma', 'O alvo recupera 2d8+2 pontos de vida. Em NEX 35%, a cura aumenta para 4d8+4 PV.'),
(4, 'Consumir Manancial', 'Sangue', 1, 'Reação', 'Pessoal', 'Você', 'Instantânea', 'Nenhuma', 'Quando um ser em alcance curto morre, você pode usar este ritual para recuperar 1d6 pontos de esforço.'),
(5, 'Descarnar', 'Sangue', 1, 'Padrão', 'Curto', '1 ser', 'Instantânea', 'Fortitude parcial', 'Você causa 3d6 pontos de dano de Sangue. Se a vítima for reduzida a 0 PV, seu corpo é destruído. Se passar na resistência, sofre metade do dano.'),
(6, 'Ferver Sangue', 'Sangue', 1, 'Padrão', 'Curto', '1 ser', 'Sustentada', 'Fortitude parcial', 'O alvo sofre 2d6 pontos de dano de Sangue e fica fatigado. Se passar na resistência, sofre metade do dano e não fica fatigado.'),
(7, 'Forma Monstruosa', 'Sangue', 1, 'Padrão', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Você assume uma aparência horrenda, recebendo +2 em Luta e +5 em Intimidação, mas sofre -5 em Diplomacia e Enganação.'),
(8, 'Ouvir os Sinos', 'Sangue', 1, 'Padrão', 'Curto', '1 ser', 'Cena', 'Vontade anula', 'O alvo ouve o badalar de sinos de uma igreja que não existe. Ele fica surdo e perturbado (-1d20 em testes de perícia).'),
(9, 'Sensação de Morte', 'Sangue', 1, 'Padrão', 'Toque', '1 ser', '1 dia', 'Vontade anula', 'O alvo sente um calafrio e tem a sensação de que irá morrer. Ele sofre -5 em testes de resistência de Vontade.'),
(10, 'Embaralhar', 'Energia', 1, 'Padrão', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Você distorce sua imagem, recebendo 20% de camuflagem (ataques têm 20% de chance de errar).'),
(11, 'Enfeitiçar', 'Energia', 1, 'Padrão', 'Curto', '1 criatura', 'Cena', 'Vontade anula', 'A criatura se torna prestativa a você. Ela não arriscará a vida, mas ajudará com ações simples.'),
(12, 'Localização', 'Energia', 1, 'Padrão', 'Longo', '1 objeto', 'Cena', 'Nenhuma', 'Você detecta a direção e distância aproximada de um objeto familiar em alcance longo.'),
(13, 'Polarização Caótica', 'Energia', 1, 'Padrão', 'Curto', '1 objeto', 'Cena', 'Nenhuma', 'Você afeta um objeto eletrônico ou mecânico. Ele para de funcionar (se for mundano) ou tem seu efeito reduzido (se for paranormal).'),
(14, 'Relâmpago', 'Energia', 1, 'Padrão', 'Curto', '1 ser', 'Instantânea', 'Reflexos parcial', 'Um raio acerta o alvo, causando 3d8 pontos de dano de Energia. Se passar na resistência, sofre metade do dano.'),
(15, 'Tela de Ruído', 'Energia', 1, 'Padrão', 'Pessoal', 'Área (esfera de 6m de raio)', 'Cena', 'Nenhuma', 'Uma esfera de estática e distorção surge centrada em você. Câmeras e microfones não funcionam na área.'),
(16, 'Travar Alvo', 'Energia', 1, 'Padrão', 'Curto', '1 ser', 'Cena', 'Nenhuma', 'Seus ataques contra o alvo recebem +2 na margem de ameaça e ignoram camuflagem leve.'),
(17, 'Visão Mística', 'Energia', 1, 'Movimento', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Você pode enxergar o fluxo de Energia. Você vê no escuro e pode ver criaturas invisíveis.'),
(18, 'Aprimorar Mente', 'Conhecimento', 1, 'Padrão', 'Toque', '1 ser', 'Cena', 'Nenhuma', 'O alvo recebe +2 em Intelecto. Em NEX 35%, o bônus aumenta para +5.'),
(19, 'Aprimorar Físico', 'Conhecimento', 1, 'Padrão', 'Toque', '1 ser', 'Cena', 'Nenhuma', 'O alvo recebe +2 em um atributo físico (Força, Agilidade ou Vigor). Em NEX 35%, o bônus aumenta para +5.'),
(20, 'Cicatrizar', 'Conhecimento', 1, 'Padrão', 'Toque', '1 ser', 'Cena', 'Nenhuma', 'O alvo recebe Resistência a dano 2. Em NEX 35%, a resistência aumenta para 5.'),
(21, 'Coincidência Forçada', 'Conhecimento', 1, 'Livre', 'Pessoal', 'Você', 'Instantânea', 'Nenhuma', 'Você pode usar este ritual após rolar um teste de perícia para rolar novamente o dado. Você deve usar o segundo resultado.'),
(22, 'Detectar Ameaça', 'Conhecimento', 1, 'Padrão', 'Pessoal', 'Área (esfera de 6m de raio)', 'Cena', 'Nenhuma', 'Você detecta a presença de perigos paranormais na área. Você sabe a localização de armadilhas, criaturas ou itens paranormais.'),
(23, 'Distorcer Aparência', 'Conhecimento', 1, 'Padrão', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Você muda sua aparência (cor de cabelo, pele, roupas). Não muda sua forma ou silhueta.'),
(24, 'Enfeitiçar', 'Conhecimento', 1, 'Padrão', 'Curto', '1 criatura', 'Cena', 'Vontade anula', 'A criatura se torna prestativa a você. Ela não arriscará a vida, mas ajudará com ações simples.'),
(25, 'Localizar Objeto', 'Conhecimento', 1, 'Padrão', 'Longo', '1 objeto', 'Cena', 'Nenhuma', 'Você detecta a direção e distância aproximada de um objeto familiar em alcance longo.'),
(26, 'Poeira da Podridão', 'Morte', 1, 'Padrão', 'Curto', '1 ser', 'Instantânea', 'Fortitude parcial', 'O alvo sofre 2d8 pontos de dano de Morte e fica fraco. Se passar na resistência, sofre metade do dano e não fica fraco.'),
(27, 'Arma Atroz', 'Morte', 1, 'Padrão', 'Toque', '1 arma corpo a corpo', 'Cena', 'Nenhuma', 'A arma se torna enegrecida. Ela causa +1d8 de dano de Morte e o alvo atingido não pode ser curado até o início do seu próximo turno.'),
(28, 'Desfigurar', 'Morte', 1, 'Padrão', 'Curto', '1 ser', 'Cena', 'Fortitude anula', 'O alvo tem seu corpo coberto por feridas. Ele sofre -1d6 em testes de Agilidade e Vigor.'),
(29, 'Espirais da Perdição', 'Morte', 1, 'Padrão', 'Curto', '1 ser', 'Instantânea', 'Vontade parcial', 'O alvo sofre 2d6 pontos de dano mental e fica abalado. Se passar na resistência, sofre metade do dano e não fica abalado.'),
(30, 'Miasma Entrópico', 'Morte', 1, 'Padrão', 'Pessoal', 'Área (esfera de 6m de raio)', 'Sustentada', 'Nenhuma', 'Uma névoa escura surge. A área é considerada escuridão e qualquer ser (exceto você) que entrar ou começar o turno na área fica fatigado.'),
(31, 'Soprar Cinzas', 'Morte', 1, 'Padrão', 'Curto', '1 ser', 'Cena', 'Fortitude anula', 'O alvo respira cinzas e poeira, ficando cego e surdo.'),
(32, 'Arma de Sangue', 'Sangue', 2, 'Padrão', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Você cria uma arma corpo a corpo de Sangue (como machado, martelo, espada). Ela causa 2d8 de dano de Sangue (dano adicional).'),
(33, 'Controle de Multidão', 'Sangue', 2, 'Padrão', 'Curto', 'Até 3 seres', 'Cena', 'Vontade anula', 'Você instila fúria ou medo nos alvos. Você pode fazê-los ficarem amedrontados ou agressivos (atacando o ser mais próximo).'),
(34, 'Paralisia', 'Sangue', 2, 'Padrão', 'Curto', '1 ser', 'Sustentada', 'Vontade anula', 'O alvo fica paralisado (incapaz de agir).'),
(35, 'Possessão', 'Sangue', 2, 'Padrão', 'Curto', '1 ser', 'Cena', 'Vontade anula', 'Você controla as ações do alvo. Ele age no seu turno e você pode usar uma ação padrão para fazê-lo realizar uma ação padrão.'),
(36, 'Hemofagia', 'Sangue', 2, 'Padrão', 'Toque', '1 ser', 'Instantânea', 'Fortitude parcial', 'Você causa 6d6 de dano de Sangue no alvo e recupera metade desse valor em PV. Se passar, o alvo sofre metade do dano e você não cura.'),
(37, 'Acelerar Entropia', 'Energia', 2, 'Padrão', 'Curto', '1 ser ou objeto', 'Instantânea', 'Fortitude parcial', 'Você acelera o tempo para o alvo. Se for um objeto, ele se desgasta até virar pó. Se for uma criatura, sofre 6d10 de dano de Energia. Resistência reduz o dano à metade.'),
(38, 'Alterar Destino', 'Energia', 2, 'Livre', 'Pessoal', 'Você', 'Instantânea', 'Nenhuma', 'Você pode usar este ritual após rolar um d20. Você pode rolar novamente o dado e deve usar o segundo resultado.'),
(39, 'Campo de Força', 'Energia', 2, 'Padrão', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Você cria um escudo de energia que fornece 15 PV temporários. Você pode gastar uma reação para receber +10 na Defesa contra um ataque.'),
(40, 'Invisibilidade', 'Energia', 2, 'Padrão', 'Toque', '1 ser ou objeto', 'Cena', 'Nenhuma', 'O alvo fica invisível. O ritual termina se o alvo fizer uma ação hostil.'),
(41, 'Relâmpago em Cadeia', 'Energia', 2, 'Padrão', 'Curto', 'Até 3 seres', 'Instantânea', 'Reflexos parcial', 'Raios saltam entre os alvos, causando 4d8 de dano de Energia em cada. Resistência reduz o dano à metade.'),
(42, 'Velocidade Mortal', 'Energia', 2, 'Padrão', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Você se move mais rápido que o normal. Você recebe +2 em Agilidade, +5 na Defesa, +10 em Reflexos e uma ação de movimento adicional por turno.'),
(43, 'Cinerária', 'Morte', 2, 'Padrão', 'Pessoal', 'Área (cone de 9m)', 'Instantânea', 'Fortitude parcial', 'Uma nuvem de cinzas e fuligem causa 5d8 de dano de Morte e deixa os alvos cegos por 1 rodada. Resistência reduz o dano à metade e anula a cegueira.'),
(44, 'Contaminar', 'Morte', 2, 'Padrão', 'Toque', '1 ser', 'Cena', 'Fortitude anula', 'O alvo fica vulnerável e fatigado.'),
(45, 'Definhar', 'Morte', 2, 'Padrão', 'Curto', '1 ser', 'Instantânea', 'Fortitude parcial', 'O alvo sofre 4d10 pontos de dano de Morte e 2d4 de dano de Vigor. Resistência reduz o dano à metade e anula o dano de Vigor.'),
(46, 'Desprezo do Ceifador', 'Morte', 2, 'Reação', 'Pessoal', 'Você', 'Instantânea', 'Nenhuma', 'Quando você sofreria dano de Morte, você pode usar este ritual para anular esse dano.'),
(47, 'Ecoar da Morte', 'Morte', 2, 'Padrão', 'Curto', '1 ser', 'Sustentada', 'Vontade anula', 'O alvo fica apavorado.'),
(48, 'Selo Paranormal', 'Morte', 2, 'Padrão', 'Curto', '1 ser', 'Cena', 'Vontade anula', 'O alvo fica mudo (incapaz de falar ou usar componentes verbais de rituais).'),
(49, 'Chamar o Outro Lado', 'Conhecimento', 2, 'Padrão', 'Curto', 'Nenhum', 'Até 1d4 criaturas', 'Nenhuma', 'Você invoca 1d4 criaturas de Conhecimento (VD 20) que o obedecem. Elas duram até o fim da cena.'),
(50, 'Dissipar Ritual', 'Conhecimento', 2, 'Padrão', 'Curto', '1 ritual', 'Instantânea', 'Nenhuma', 'Você anula um ritual de 1º ou 2º círculo ativo na área.'),
(51, 'Invadir Mente', 'Conhecimento', 2, 'Padrão', 'Toque', '1 ser', 'Sustentada', 'Vontade anula', 'Você pode ler os pensamentos superficiais do alvo. Você pode gastar uma ação padrão para se aprofundar e descobrir uma informação específica.'),
(52, 'Proteção contra Rituais', 'Conhecimento', 2, 'Padrão', 'Toque', '1 ser', 'Cena', 'Nenhuma', 'O alvo recebe +5 em testes de resistência contra rituais.'),
(53, 'Tela de Proteção', 'Conhecimento', 2, 'Padrão', 'Pessoal', 'Área (esfera de 3m de raio)', 'Sustentada', 'Nenhuma', 'Uma cúpula de energia protege a área. Rituais não podem ser conjurados de dentro para fora ou de fora para dentro da área.'),
(54, 'Terror', 'Conhecimento', 2, 'Padrão', 'Curto', '1 ser', 'Sustentada', 'Vontade anula', 'O alvo vê seus piores medos. Ele fica apavorado e sofre 1d6 de dano mental por rodada.'),
(55, 'Transmissão de Pensamento', 'Conhecimento', 2, 'Padrão', 'Longo', 'Até 5 seres', 'Cena', 'Nenhuma', 'Você cria um elo telepático com os alvos. Todos podem se comunicar mentalmente.'),
(56, 'Âncora Dimensional', 'Energia', 3, 'Padrão', 'Curto', '1 ser', 'Cena', 'Vontade anula', 'O alvo é preso no espaço-tempo. Ele não pode se teleportar, atravessar dimensões ou sair da realidade.'),
(57, 'Chamar o Outro Lado (Energia)', 'Energia', 3, 'Padrão', 'Curto', 'Nenhum', 'Até 1d4 criaturas', 'Nenhuma', 'Você invoca 1d4 criaturas de Energia (VD 40) que o obedecem. Elas duram até o fim da cena.'),
(58, 'Distorcer Tempo', 'Energia', 3, 'Padrão', 'Curto', 'Até 5 seres', 'Cena', 'Vontade anula', 'Os alvos ficam lentos (apenas uma ação padrão ou de movimento por turno).'),
(59, 'Teletransporte', 'Energia', 3, 'Padrão', 'Qualquer', 'Você e 1 ser por NEX', 'Instantânea', 'Nenhuma', 'Você e os alvos tocados são transportados instantaneamente para qualquer lugar que você conheça.'),
(60, 'Sopro do Caos', 'Energia', 3, 'Padrão', 'Pessoal', 'Área (cone de 9m)', 'Instantânea', 'Reflexos parcial', 'Uma rajada de energia caótica causa 10d6 de dano de Energia. Resistência reduz o dano à metade.'),
(61, 'Chamar o Outro Lado (Sangue)', 'Sangue', 3, 'Padrão', 'Curto', 'Nenhum', 'Até 1d4 criaturas', 'Nenhuma', 'Você invoca 1d4 criaturas de Sangue (VD 40) que o obedecem. Elas duram até o fim da cena.'),
(62, 'Controle Mental', 'Sangue', 3, 'Padrão', 'Curto', '1 ser', 'Sustentada', 'Vontade anula', 'Você assume controle total sobre o alvo. Ele recebe suas ordens telepaticamente.'),
(63, 'Muralha de Sangue', 'Sangue', 3, 'Padrão', 'Médio', 'Muralha (até 15m de comp, 3m de alt)', 'Cena', 'Nenhuma', 'Uma muralha de sangue surge. Ela bloqueia visão e movimento. A muralha tem 100 PV e RD 10.'),
(64, 'Reviver', 'Sangue', 3, 'Livre', 'Longo', '1 ser', 'Instantânea', 'Nenhuma', 'O alvo que tenha morrido na última rodada volta à vida com 1 PV.'),
(65, 'Tentáculos de Lodo', 'Sangue', 3, 'Padrão', 'Médio', 'Área (cilindro de 6m de raio)', 'Sustentada', 'Reflexos anula', 'Tentáculos de sangue surgem do chão. Seres na área ficam agarrados e sofrem 3d6 de dano de Sangue por rodada. Reflexos evita ser agarrado.'),
(66, 'Chamar o Outro Lado (Conhecimento)', 'Conhecimento', 3, 'Padrão', 'Curto', 'Nenhum', 'Até 1d4 criaturas', 'Nenhuma', 'Você invoca 1d4 criaturas de Conhecimento (VD 40) que o obedecem. Elas duram até o fim da cena.'),
(67, 'Despertar Consciência', 'Conhecimento', 3, 'Padrão', 'Toque', '1 ser', 'Instantânea', 'Nenhuma', 'Você toca um ser. Se for uma criatura, ela se torna prestativa. Se for um personagem, ele recupera toda a Sanidade.'),
(68, 'Forma Etérea', 'Conhecimento', 3, 'Padrão', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Você se torna etéreo (incorpóreo). Você pode atravessar objetos e sofre apenas metade do dano de fontes mundanas.'),
(69, 'Interrogar', 'Conhecimento', 3, 'Padrão', 'Toque', '1 ser', 'Sustentada', 'Vontade anula', 'Você força o alvo a responder suas perguntas. Ele não pode mentir.'),
(70, 'Olhar do Desespero', 'Conhecimento', 3, 'Padrão', 'Curto', '1 ser', 'Sustentada', 'Vontade anula', 'O alvo fica pasmo (não pode agir). A cada rodada, ele pode tentar um novo teste de Vontade para se libertar.'),
(71, 'Chamar o Outro Lado (Morte)', 'Morte', 3, 'Padrão', 'Curto', 'Nenhum', 'Até 1d4 criaturas', 'Nenhuma', 'Você invoca 1d4 criaturas de Morte (VD 40) que o obedecem. Elas duram até o fim da cena.'),
(72, 'Explosão de Cinzas', 'Morte', 3, 'Padrão', 'Pessoal', 'Área (esfera de 6m de raio)', 'Instantânea', 'Fortitude parcial', 'Uma explosão de energia entrópica causa 8d8 de dano de Morte e deixa os alvos exaustos. Resistência reduz o dano à metade e anula a exaustão.'),
(73, 'Nuvem de Cinzas', 'Morte', 3, 'Padrão', 'Longo', 'Área (esfera de 12m de raio)', 'Cena', 'Nenhuma', 'Uma nuvem de fumaça e cinzas bloqueia visão (camuflagem total) e causa 2d8 de dano de Morte por rodada a quem estiver dentro.'),
(74, 'Possessão Entrópica', 'Morte', 3, 'Padrão', 'Toque', '1 cadáver', 'Sustentada', 'Nenhuma', 'Você transfere sua consciência para um cadáver recente. Você o controla e usa seus atributos físicos, mas mantém seus mentais.'),
(75, 'Zerar Entropia', 'Morte', 3, 'Padrão', 'Curto', '1 ser', 'Instantânea', 'Fortitude anula', 'O alvo é reduzido a 0 PV. Se for bem-sucedido na resistência, sofre 8d10 de dano de Morte.'),
(76, 'Canalizar o Medo', 'Medo', 4, 'Padrão', 'Curto', '1 ser', 'Cena', 'Vontade parcial', 'O alvo sofre 15d6 de dano mental e fica apavorado. Se passar, sofre metade do dano e fica abalado.'),
(77, 'Conhecendo o Medo', 'Medo', 4, 'Padrão', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Você compreende a verdade do Medo. Você recebe +5 em Ocultismo e em testes de resistência contra efeitos mentais.'),
(78, 'Lâmina do Medo', 'Medo', 4, 'Padrão', 'Toque', '1 arma', 'Cena', 'Nenhuma', 'A arma se torna um pesadelo tangível. Ela causa +5d8 de dano de Medo e qualquer ser atingido fica abalado por 1 rodada.'),
(79, 'Medo Tangível', 'Medo', 4, 'Padrão', 'Curto', 'Área (esfera de 6m de raio)', 'Sustentada', 'Vontade anula', 'A área é preenchida por uma manifestação de Medo. Seres na área ficam apavorados e sofrem 4d8 de dano mental por rodada.'),
(80, 'Presença do Medo', 'Medo', 4, 'Padrão', 'Pessoal', 'Área (esfera de 12m de raio)', 'Cena', 'Vontade anula', 'Seres na área devem passar no teste de Vontade ou ficam apavorados. Mesmo que passem, ficam abalados enquanto na área.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `t20_classes`
--

CREATE TABLE IF NOT EXISTS `t20_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `pv_inicial` int(11) NOT NULL,
  `pv_por_nivel` int(11) NOT NULL,
  `pm_por_nivel` int(11) NOT NULL,
  `pericias_treinadas_fixas` text,
  `pericias_escolha_lista` text,
  `pericias_escolha_qtd` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=15 ;

--
-- Extraindo dados da tabela `t20_classes`
--

INSERT INTO `t20_classes` (`id`, `nome`, `pv_inicial`, `pv_por_nivel`, `pm_por_nivel`, `pericias_treinadas_fixas`, `pericias_escolha_lista`, `pericias_escolha_qtd`) VALUES
(1, 'Caçador', 16, 4, 4, 'Luta ou Pontaria, Sobrevivência', 'Adestramento, Atletismo, Cavalgar, Cura, Fortitude, Furtividade, Iniciativa, Investigação, Luta, Ofício, Percepção, Pontaria, Reflexos', 6),
(2, 'Guerreiro', 20, 5, 3, 'Luta ou Pontaria, Fortitude', 'Adestramento, Atletismo, Cavalgar, Guerra, Iniciativa, Intimidação, Luta, Ofício, Percepção, Pontaria, Reflexos', 2),
(3, 'Nobre', 16, 4, 4, 'Diplomacia ou Intimidação, Vontade', 'Adestramento, Atuação, Cavalgar, Conhecimento, Diplomacia, Enganação, Fortitude, Guerra, Iniciativa, Intimidação, Intuição, Investigação, Jogatina, Luta, Nobreza, Ofício, Percepção, Pontaria', 4),
(4, 'Arcanista', 12, 3, 6, 'Misticismo, Vontade', 'Conhecimento, Diplomacia, Enganação, Guerra, Iniciativa, Intuição, Investigação, Nobreza, Ofício, Percepção', 2),
(5, 'Clérigo', 16, 4, 5, 'Religião, Vontade', 'Conhecimento, Cura, Diplomacia, Fortitude, Guerra, Intuição, Luta, Misticismo, Nobreza, Ofício, Percepção', 2),
(6, 'Bárbaro', 24, 6, 3, 'Fortitude, Luta', 'Adestramento, Atletismo, Cavalgar, Guerra, Iniciativa, Intimidação, Luta, Ofício, Percepção, Pontaria, Reflexos, Sobrevivência, Vontade', 4),
(7, 'Bardo', 12, 3, 4, 'Atuação, Reflexos', 'Acrobacia, Cavalgar, Conhecimento, Diplomacia, Enganação, Furtividade, Iniciativa, Intuição, Investigação, Jogatina, Ladinagem, Luta, Misticismo, Nobreza, Ofício, Percepção, Pontaria, Vontade', 6),
(8, 'Bucaneiro', 16, 4, 3, 'Luta ou Pontaria, Reflexos', 'Acrobacia, Atletismo, Atuação, Enganação, Fortitude, Furtividade, Iniciativa, Intimidação, Jogatina, Luta, Ofício, Percepção, Pilotagem, Pontaria', 4),
(9, 'Cavaleiro', 20, 5, 3, 'Luta, Vontade', 'Adestramento, Atletismo, Cavalgar, Diplomacia, Fortitude, Guerra, Iniciativa, Intimidação, Luta, Nobreza, Percepção, Vontade', 2),
(10, 'Druida', 16, 4, 4, 'Sobrevivência, Vontade', 'Adestramento, Atletismo, Cavalgar, Conhecimento, Cura, Fortitude, Iniciativa, Intuição, Luta, Misticismo, Ofício, Percepção, Religião, Sobrevivência', 4),
(11, 'Inventor', 12, 3, 4, 'Ofício, Vontade', 'Conhecimento, Cura, Diplomacia, Enganação, Fortitude, Iniciativa, Intuição, Investigação, Luta, Misticismo, Ofício, Pilotagem, Pontaria', 4),
(12, 'Ladino', 12, 3, 4, 'Ladinagem, Reflexos', 'Acrobacia, Atletismo, Atuação, Cavalgar, Conhecimento, Diplomacia, Enganação, Furtividade, Iniciativa, Intimidação, Intuição, Investigação, Jogatina, Luta, Misticismo, Ofício, Percepção, Pilotagem, Pontaria', 8),
(13, 'Lutador', 20, 5, 3, 'Fortitude, Luta', 'Acrobacia, Atletismo, Enganação, Furtividade, Iniciativa, Intimidação, Luta, Ofício, Percepção, Pontaria, Reflexos', 4),
(14, 'Paladino', 20, 5, 3, 'Fortitude, Vontade', 'Adestramento, Atletismo, Cavalgar, Cura, Diplomacia, Fortitude, Guerra, Iniciativa, Intimidação, Luta, Nobreza, Percepção, Religião, Vontade', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `t20_divindades`
--

CREATE TABLE IF NOT EXISTS `t20_divindades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `energia` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=21 ;

--
-- Extraindo dados da tabela `t20_divindades`
--

INSERT INTO `t20_divindades` (`id`, `nome`, `energia`) VALUES
(1, 'Aharadak', 'Negativa'),
(2, 'Allihanna', 'Positiva'),
(3, 'Arsenal', 'Qualquer'),
(4, 'Azgher', 'Positiva'),
(5, 'Hyninn', 'Qualquer'),
(6, 'Kallyadranoch', 'Negativa'),
(7, 'Khalmyr', 'Positiva'),
(8, 'Lena', 'Positiva'),
(9, 'Lin-Wu', 'Qualquer'),
(10, 'Marah', 'Positiva'),
(11, 'Megalokk', 'Negativa'),
(12, 'Nimb', 'Qualquer'),
(13, 'Oceano', 'Qualquer'),
(14, 'Sszzaas', 'Negativa'),
(15, 'Tanna-Toh', 'Qualquer'),
(16, 'Tenebra', 'Negativa'),
(17, 'Thwor', 'Qualquer'),
(18, 'Thyatis', 'Positiva'),
(19, 'Valkaria', 'Positiva'),
(20, 'Wynna', 'Qualquer');

-- --------------------------------------------------------

--
-- Estrutura da tabela `t20_divindade_poderes`
--

CREATE TABLE IF NOT EXISTS `t20_divindade_poderes` (
  `divindade_id` int(11) NOT NULL,
  `poder_divino_id` int(11) NOT NULL,
  PRIMARY KEY (`divindade_id`,`poder_divino_id`),
  KEY `fk_poder_divino` (`poder_divino_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `t20_divindade_poderes`
--

INSERT INTO `t20_divindade_poderes` (`divindade_id`, `poder_divino_id`) VALUES
(1, 1),
(17, 2),
(19, 2),
(13, 3),
(5, 4),
(19, 5),
(13, 6),
(14, 7),
(8, 8),
(18, 8),
(6, 9),
(10, 10),
(8, 11),
(20, 12),
(16, 13),
(20, 14),
(2, 15),
(15, 16),
(3, 17),
(3, 18),
(7, 18),
(9, 18),
(19, 18),
(8, 19),
(8, 20),
(2, 21),
(2, 22),
(10, 23),
(18, 24),
(18, 25),
(18, 26),
(7, 27),
(6, 28),
(20, 29),
(7, 30),
(4, 31),
(1, 32),
(12, 32),
(14, 33),
(5, 34),
(3, 35),
(5, 36),
(4, 37),
(17, 38),
(5, 39),
(4, 40),
(4, 41),
(9, 42),
(19, 43),
(16, 44),
(15, 45),
(9, 46),
(13, 47),
(11, 48),
(17, 48),
(10, 49),
(1, 50),
(15, 51),
(12, 52),
(6, 53),
(11, 53),
(14, 54),
(1, 55),
(7, 56),
(3, 57),
(14, 58),
(6, 59),
(13, 60),
(12, 61),
(10, 62),
(20, 63),
(9, 64),
(12, 65),
(17, 66),
(11, 67),
(16, 68),
(15, 69),
(2, 70),
(11, 71),
(16, 72);

-- --------------------------------------------------------

--
-- Estrutura da tabela `t20_habilidades_classe_auto`
--

CREATE TABLE IF NOT EXISTS `t20_habilidades_classe_auto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classe_id` int(11) NOT NULL,
  `nivel_obtido` int(11) NOT NULL,
  `nome` varchar(191) NOT NULL,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_classe_nivel` (`classe_id`,`nivel_obtido`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=96 ;

--
-- Extraindo dados da tabela `t20_habilidades_classe_auto`
--

INSERT INTO `t20_habilidades_classe_auto` (`id`, `classe_id`, `nivel_obtido`, `nome`, `descricao`) VALUES
(1, 1, 1, 'Marca da Presa', 'Você pode gastar uma ação de movimento e 1 PM para marcar uma criatura em alcance curto. Você recebe +1d4 em testes de ataque e rolagens de dano contra essa criatura. O bônus aumenta com o nível.'),
(2, 1, 1, 'Rastreador', 'Você recebe +2 em Sobrevivência e pode usar esta perícia para rastrear mesmo em movimento normal (sem penalidade).'),
(3, 1, 3, 'Explorador', 'Você aprende um tipo de terreno (como floresta, montanha, etc.). Você recebe +2 em Percepção, Sobrevivência e Furtividade nesse terreno. Você aprende um novo tipo de terreno nos níveis 7, 11, 15 e 19.'),
(4, 1, 5, 'Caminho do Explorador', 'O bônus de Explorador (+2) aumenta para +4 nos terrenos escolhidos.'),
(5, 1, 5, 'Marca da Presa (+1d8)', 'O bônus de sua Marca da Presa aumenta para +1d8.'),
(6, 1, 9, 'Marca da Presa (+1d12)', 'O bônus de sua Marca da Presa aumenta para +1d12.'),
(7, 1, 13, 'Marca da Presa (+2d8)', 'O bônus de sua Marca da Presa aumenta para +2d8.'),
(8, 1, 17, 'Marca da Presa (+2d10)', 'O bônus de sua Marca da Presa aumenta para +2d10.'),
(9, 1, 20, 'Mestre Caçador', 'Quando você marca uma criatura, pode gastar 2 PM para aplicar o efeito de Marca da Presa em todos os inimigos em alcance curto.'),
(10, 2, 1, 'Ataque Especial (+4)', 'Você pode gastar uma ação padrão e 2 PM para fazer um ataque corpo a corpo ou à distância. Você soma +4 no teste de ataque ou na rolagem de dano. O bônus aumenta com o nível.'),
(11, 2, 3, 'Durão', 'Quando sofre dano, você pode gastar 3 PM para reduzir esse dano à metade.'),
(12, 2, 5, 'Ataque Especial (+8)', 'O bônus de seu Ataque Especial aumenta para +8.'),
(13, 2, 6, 'Ataque Extra', 'Quando usa a ação agredir, você pode gastar 2 PM para realizar um ataque adicional (limitado a um ataque extra por rodada).'),
(14, 2, 9, 'Ataque Especial (+12)', 'O bônus de seu Ataque Especial aumenta para +12.'),
(15, 2, 13, 'Ataque Especial (+16)', 'O bônus de seu Ataque Especial aumenta para +16.'),
(16, 2, 17, 'Ataque Especial (+20)', 'O bônus de seu Ataque Especial aumenta para +20.'),
(17, 2, 20, 'Campeão', 'No 20º nível, você se torna um mestre do combate. Seu multiplicador de crítico com armas marciais aumenta em +1 (x2 torna-se x3, etc.).'),
(18, 3, 1, 'Autoconfiança', 'Quando faz um teste de resistência, você pode gastar 2 PM para somar seu modificador de Carisma ao resultado do teste.'),
(19, 3, 1, 'Espólio', 'Você recebe +2 PM para cada poder de Nobre que possui (além do poder inicial, Riqueza). Além disso, recebe +1 PM por nível par (2, 4, 6, etc.).'),
(20, 3, 1, 'Orgulho', 'Você é imune a efeitos de medo (mágicos ou não) e recebe +5 em Vontade contra efeitos que afetem seu Carisma (como magias de Encantamento).'),
(21, 3, 2, 'Palavras Afiadas (2d6)', 'Você pode gastar uma ação padrão e 2 PM para hostilizar uma criatura em alcance curto. Faça um teste de Diplomacia, Enganação ou Intimidação oposto ao de Vontade da criatura. Se vencer, ela sofre 2d6 pontos de dano mental.'),
(22, 3, 3, 'Riqueza', 'Você recebe 10 T$ x seu nível de Nobre no início de cada aventura (acumulativo).'),
(23, 3, 4, 'Gritar Ordens', 'Você pode gastar 1 PM e uma ação de movimento para fazer um teste de Diplomacia, Enganação ou Intimidação (CD 15). Se passar, seus aliados em alcance médio recebem +1 em testes de ataque por 1 rodada.'),
(24, 3, 5, 'Presença Aristocrática', 'Quando usa a ação de movimento para comandar (habilidade Gritar Ordens), você pode gastar 1 PM adicional para aumentar o bônus em +1 (total +2).'),
(25, 3, 6, 'Palavras Afiadas (4d6)', 'O dano de Palavras Afiadas aumenta para 4d6.'),
(26, 3, 10, 'Palavras Afiadas (6d6)', 'O dano de Palavras Afiadas aumenta para 6d6.'),
(27, 3, 14, 'Palavras Afiadas (8d6)', 'O dano de Palavras Afiadas aumenta para 8d6.'),
(28, 3, 18, 'Palavras Afiadas (10d6)', 'O dano de Palavras Afiadas aumenta para 10d6.'),
(29, 3, 20, 'Realeza', 'Sua Presença Aristocrática aumenta seu bônus de comando para +2 (total +3) e seu custo para 3 PM. Além disso, você pode gastar 5 PM para que todos os aliados afetados por seu comando recebam uma ação de movimento ou padrão adicional (à escolha deles).'),
(30, 4, 1, 'Magias (Arcanista)', 'Você pode lançar magias arcanas de 1º círculo. A cada nível ímpar (3, 5, etc.), você aprende magias de um círculo maior (2º, 3º, 4º, 5º).'),
(31, 4, 1, 'Caminho do Arcanista', 'Escolha entre Mago (foco em Inteligência), Bruxo (foco em Carisma) ou Feiticeiro (foco em Carisma, magias inatas).'),
(32, 4, 2, 'Poder de Arcanista', 'Você aprende um novo poder de arcanista.'),
(33, 4, 3, 'Poder de Arcanista', 'Você aprende um novo poder de arcanista.'),
(34, 4, 4, 'Poder de Arcanista', 'Você aprende um novo poder de arcanista.'),
(35, 4, 5, 'Magias (2º Círculo)', 'Você aprende a lançar magias arcanas de 2º círculo.'),
(36, 4, 5, 'Poder de Arcanista', 'Você aprende um novo poder de arcanista.'),
(37, 4, 20, 'Alta Arcana', 'No 20º nível, o custo em PM de suas magias arcanas é reduzido à metade (após aplicar aprimoramentos e quaisquer outros efeitos que reduzam o custo).'),
(38, 6, 1, 'Fúria', 'Você pode gastar 2 PM para invocar uma fúria selvagem. Você recebe +2 em testes de ataque e rolagens de dano corpo a corpo, mas não pode fazer nenhuma ação que exija calma (como usar a perícia Furtividade ou lançar magias). A cada 5 níveis, +1 PM para aumentar o bônus em +1.'),
(39, 6, 2, 'Poder de Bárbaro', 'Você aprende um novo poder de bárbaro.'),
(40, 6, 3, 'Instinto Selvagem', 'Você recebe +1 poder de bárbaro. Além disso, no início do combate, pode gastar 2 PM para receber +1d8 em testes de ataque e rolagens de dano (limitado pela sua Sabedoria).'),
(41, 6, 4, 'Poder de Bárbaro', 'Você aprende um novo poder de bárbaro.'),
(42, 6, 5, 'Poder de Bárbaro', 'Você aprende um novo poder de bárbaro.'),
(43, 6, 5, 'Redução de Dano 2', 'Você recebe Redução de Dano 2 (todo dano que sofre é reduzido em 2).'),
(44, 6, 20, 'Fúria Titânica', 'No 20º nível, quando em fúria, você recebe +2 em testes de ataque e rolagens de dano (além dos bônus normais) e seus bônus de Fúria são dobrados. Custo +10 PM.'),
(45, 5, 3, 'Poder de Clérigo', 'Você aprende um novo poder de clérigo.'),
(46, 5, 5, 'Magias (2º Círculo)', 'Você aprende a lançar magias divinas de 2º círculo.'),
(47, 7, 1, 'Inspiração +1', 'Você pode gastar uma ação padrão e 1 PM para inspirar aliados em alcance curto. Eles recebem +1 em testes de perícia até o fim da cena. A cada 4 níveis, o bônus aumenta em +1 (Inspiração +2, +3, +4, +5).'),
(48, 7, 1, 'Magias (Bardo)', 'Você pode lançar magias arcanas de 1º círculo (atributo-chave Carisma). A cada nível ímpar (3, 5, etc.), você aprende magias de um círculo maior (2º, 3º, 4º, 5º).'),
(49, 7, 3, 'Poder de Bardo', 'Você aprende um novo poder de bardo.'),
(50, 7, 3, 'Eclético', 'A partir do 2º nível, você pode gastar 2 PM para receber +2 em um teste de perícia (aumenta com o nível).'),
(51, 7, 5, 'Inspiração +2', 'O bônus de Inspiração aumenta para +2.'),
(52, 8, 1, 'Audácia', 'Você pode gastar 2 PM para somar seu Carisma no teste de ataque (não pode usar esta habilidade em testes de ataque).'),
(53, 8, 1, 'Insolência', 'Você soma seu Carisma na Defesa (limitado pelo seu nível). Esta habilidade exige liberdade de movimentos (não funciona com armadura pesada ou condição imóvel).'),
(54, 8, 2, 'Evasão', 'A partir do 2º nível, se sofrer um efeito que permite um teste de Reflexos para reduzir o dano à metade, você não sofre dano algum se passar no teste.'),
(55, 8, 3, 'Esquiva Sagaz +1', 'No 3º nível, você recebe +1 na Defesa. Este bônus aumenta em +1 a cada quatro níveis (Esquiva Sagaz +2, +3, +4, +5).'),
(56, 8, 5, 'Panache', 'No 5º nível, quando faz um acerto crítico ou reduz um inimigo a 0 PV, você recupera 1 PM.'),
(57, 9, 1, 'Baluarte', 'Quando sofre um ataque, você pode gastar 1 PM para receber +2 na Defesa e em testes de resistência até o início do seu próximo turno. A cada 4 níveis, o bônus aumenta em +2 (Baluarte +4, +6, +8, +10).'),
(58, 9, 1, 'Código de Honra', 'Você deve seguir um código de honra (ex: nunca atacar um oponente caído, nunca usar armas de ataque à distância). Se violar o código, perde todos os PM e só os recupera no próximo dia.'),
(59, 9, 2, 'Duelo', 'A partir do 2º nível, pode gastar 2 PM para desafiar um oponente. Você recebe +2 em testes de ataque e rolagens de dano contra ele, mas sofre -5 contra outros. O bônus aumenta em +1 a cada 4 níveis.'),
(60, 9, 5, 'Caminho do Cavaleiro', 'No 5º nível, escolha entre Bastão ou Montaria.'),
(61, 10, 1, 'Devoto Fiel', 'Você se torna devoto de Allihanna, Megalokk ou Oceano. Você recebe dois poderes concedidos por ser devoto, em vez de apenas um.'),
(62, 10, 1, 'Empatia Selvagem', 'Você pode se comunicar com animais. Você pode usar Adestramento para mudar atitude e persuasão com animais.'),
(63, 10, 1, 'Magias (Druida)', 'Você pode lançar magias divinas de 1º círculo (atributo-chave Sabedoria) das escolas de Abjuração, Adivinhação e Transmutação. A cada nível ímpar (3, 5, etc.), aprende magias de um círculo maior.'),
(64, 10, 3, 'Caminho dos Ermos', 'No 3º nível, você pode atravessar terrenos difíceis (floresta, etc.) sem sofrer redução no deslocamento.'),
(65, 10, 5, 'Magias (2º Círculo)', 'Você aprende a lançar magias divinas de 2º círculo.'),
(66, 10, 20, 'Força da Natureza', 'No 20º nível, você se torna uma força da natureza. Você recebe +2 em Sabedoria e pode gastar 1 PM para lançar magias como ação livre (uma vez por rodada).'),
(67, 11, 1, 'Engenhosidade', 'Você pode gastar 2 PM para somar sua Inteligência (em vez do atributo normal) em um teste de perícia. Você não pode usar esta habilidade em testes de ataque.'),
(68, 11, 1, 'Protótipo', 'Você começa com um item superior (preço de até T$ 500) ou com 10 itens alquímicos (preço de até T$ 50 cada).'),
(69, 11, 3, 'Comerciante', 'No 3º nível, você pode vender itens 10% mais caro.'),
(70, 11, 7, 'Encontrar Fraqueza', 'A partir do 7º nível, você pode gastar 2 PM para analisar um oponente. Você ignora a RD dele e ele sofre -2 em testes de resistência contra seus itens.'),
(71, 11, 10, 'Olho do Dragão', 'No 10º nível, você pode gastar uma ação completa para analisar um item. Você automaticamente descobre se ele é mágico e suas propriedades.'),
(72, 11, 20, 'Obra-Prima', 'No 20º nível, você fabrica sua obra-prima, um item com quatro melhorias e dois aprimoramentos (ou um item mágico menor).'),
(73, 12, 1, 'Ataque Furtivo +1d6', 'Você sabe atingir os pontos vitais de inimigos distraídos. Uma vez por rodada, quando atinge um alvo desprevenido ou flanqueado, seu ataque causa +1d6 de dano. A cada dois níveis, esse dano extra aumenta em +1d6.'),
(74, 12, 1, 'Especialista', 'Escolha um número de perícias (mínimo 1) igual à sua Inteligência. Você pode gastar 1 PM para receber +1d6 em um teste de uma dessas perícias.'),
(75, 12, 2, 'Evasão', 'A partir do 2º nível, se sofrer um efeito que permite um teste de Reflexos para reduzir o dano à metade, você não sofre dano algum se passar no teste.'),
(76, 12, 4, 'Esquiva Sobrenatural', 'No 4º nível, seus instintos reagem ao perigo. Você nunca fica surpreendido.'),
(77, 12, 8, 'Olhos nas Costas', 'No 8º nível, você não pode ser flanqueado.'),
(78, 12, 10, 'Evasão Aprimorada', 'No 10º nível, quando você falha no teste de Reflexos para reduzir o dano à metade, você ainda sofre apenas metade do dano.'),
(79, 12, 20, 'A Pessoa Certa', 'No 20º nível, você se torna um mestre da lábia. Uma vez por dia, pode usar a perícia Diplomacia (com um bônus de +10) para pedir um favor a alguém, como se usasse a magia Desejo.'),
(80, 13, 1, 'Briga (1d6)', 'Seus ataques desarmados causam 1d6 de dano (letais ou não letais). A cada quatro níveis, o dano aumenta (1d8, 1d10, 1d12, 2d8, 2d10).'),
(81, 13, 1, 'Golpe Relâmpago', 'Quando usa a ação agredir, pode gastar 1 PM para realizar um ataque desarmado adicional.'),
(82, 13, 5, 'Briga (1d8)', 'O dano de seus ataques desarmados aumenta para 1d8.'),
(83, 13, 6, 'Casca Grossa (+1)', 'Você soma sua Constituição na Defesa (limitado pelo seu nível), mas não pode usar armadura nem escudo. O bônus aumenta com o nível.'),
(84, 13, 9, 'Briga (1d10)', 'O dano de seus ataques desarmados aumenta para 1d10.'),
(85, 13, 13, 'Briga (1d12)', 'O dano de seus ataques desarmados aumenta para 1d12.'),
(86, 13, 17, 'Briga (2d8)', 'O dano de seus ataques desarmados aumenta para 2d8.'),
(87, 13, 20, 'Dono da Rua (2d10)', 'O dano de seus ataques desarmados aumenta para 2d10 e você pode usar a ação agredir para fazer três ataques desarmados (em vez de dois).'),
(88, 14, 1, 'Abençoado', 'Você soma seu Carisma nos testes de resistência (além do bônus normal).'),
(89, 14, 1, 'Código do Herói', 'Você deve seguir um código de honra. Se violar o código, você perde todos os seus PM e só os recupera no próximo dia.'),
(90, 14, 1, 'Devoto', 'Você se torna devoto de uma divindade (Khalmyr, Thyatis, etc.) e deve seguir suas Obrigações & Restrições. Você ganha Poderes Concedidos por ela.'),
(91, 14, 2, 'Cura pelas Mãos (1d8+1 PV)', 'Você pode gastar uma ação de movimento e 2 PM para curar 1d8+1 PV em um alvo (incluindo você). A cada quatro níveis, a cura aumenta em +1d8+1 PV.'),
(92, 14, 3, 'Aura Sagrada', 'No 3º nível, você pode gastar 1 PM para gerar uma aura de 9m. Você e seus aliados na aura somam seu Carisma nos testes de resistência.'),
(93, 14, 5, 'Bênção da Justiça', 'No 5º nível, sua Cura pelas Mãos pode curar 2d8+2 PV.'),
(94, 14, 5, 'Golpe Divino (+1d8)', 'Quando faz um ataque corpo a corpo, pode gastar 2 PM para causar +1d8 de dano de luz e +1d8 na rolagem de dano. O dano de luz aumenta em +1d8 a cada quatro níveis.'),
(95, 14, 20, 'Vingador Sagrado', 'No 20º nível, você pode gastar 10 PM para se cobrir de energia divina. Você recebe +18m de deslocamento de voo e RD 20. Além disso, seu Golpe Divino tem o custo reduzido à metade e causa mais dois dados de dano.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `t20_itens`
--

CREATE TABLE IF NOT EXISTS `t20_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nome` varchar(191) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `preco` varchar(50) DEFAULT NULL,
  `espacos` int(11) NOT NULL DEFAULT '1',
  `bonus_carga` int(11) NOT NULL DEFAULT '0',
  `dano` varchar(50) DEFAULT NULL,
  `critico` varchar(50) DEFAULT NULL,
  `alcance` varchar(50) DEFAULT NULL,
  `tipo_dano` varchar(50) DEFAULT NULL,
  `bonus_defesa` int(11) DEFAULT NULL,
  `penalidade_armadura` int(11) DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_item_unique` (`nome`),
  KEY `idx_user_item_t20` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=16 ;

--
-- Extraindo dados da tabela `t20_itens`
--

INSERT INTO `t20_itens` (`id`, `user_id`, `nome`, `tipo`, `preco`, `espacos`, `bonus_carga`, `dano`, `critico`, `alcance`, `tipo_dano`, `bonus_defesa`, `penalidade_armadura`, `descricao`) VALUES
(1, NULL, 'Espada Curta', 'Arma', 'T$ 10', 1, 0, '1d6', '19', NULL, 'Perfuração/Corte', NULL, NULL, 'Uma arma leve e ágil.'),
(2, NULL, 'Espada Longa', 'Arma', 'T$ 15', 2, 0, '1d8', '19', NULL, 'Corte', NULL, NULL, 'Uma arma marcial versátil.'),
(3, NULL, 'Machado de Batalha', 'Arma', 'T$ 20', 2, 0, '1d10', 'x3', NULL, 'Corte', NULL, NULL, 'Uma arma marcial de duas mãos.'),
(4, NULL, 'Arco Curto', 'Arma', 'T$ 25', 2, 0, '1d6', 'x3', 'Médio', 'Perfuração', NULL, NULL, 'Um arco simples para disparos rápidos.'),
(5, NULL, 'Besta Leve', 'Arma', 'T$ 35', 2, 0, '1d8', '19', 'Médio', 'Perfuração', NULL, NULL, 'Uma arma de disparo que não exige Força.'),
(6, NULL, 'Armadura de Couro', 'Armadura', 'T$ 20', 2, 0, NULL, NULL, NULL, NULL, 2, -1, 'Armadura leve feita de couro fervido.'),
(7, NULL, 'Brunea', 'Armadura', 'T$ 50', 3, 0, NULL, NULL, NULL, NULL, 3, -2, 'Armadura leve de couro com anéis de metal.'),
(8, NULL, 'Cota de Malha', 'Armadura', 'T$ 150', 4, 0, NULL, NULL, NULL, NULL, 5, -3, 'Armadura pesada feita de anéis de metal entrelaçados.'),
(9, NULL, 'Escudo Leve', 'Escudo', 'T$ 5', 1, 0, '1d4', 'x2', NULL, 'Impacto', 1, -1, 'Um escudo leve de madeira.'),
(10, NULL, 'Escudo Pesado', 'Escudo', 'T$ 15', 2, 0, '1d6', 'x2', NULL, 'Impacto', 2, -2, 'Um escudo pesado de madeira e metal.'),
(11, NULL, 'Mochila', 'Item Geral', 'T$ 2', 0, 5, NULL, NULL, NULL, NULL, NULL, NULL, 'Aumenta seu limite de carga (espaços) em +5. (Bônus não cumulativo)'),
(12, NULL, 'Corda', 'Item Geral', 'T$ 1', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, '15m de corda de cânhamo.'),
(13, NULL, 'Poção de Vida', 'Item Geral', 'T$ 50', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Beber esta poção cura 2d4+2 PV.'),
(14, NULL, 'Kit de Ladrão', 'Item Geral', 'T$ 50', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Concede +2 em testes de Ladinagem.'),
(15, NULL, 'Saco de Dormir', 'Item Geral', 'T$ 1', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Permite dormir confortavelmente.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `t20_magias`
--

CREATE TABLE IF NOT EXISTS `t20_magias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `tipo` enum('Arcana','Divina','Universal') NOT NULL,
  `circulo` int(11) NOT NULL,
  `escola` varchar(50) NOT NULL,
  `execucao` varchar(50) DEFAULT NULL,
  `alcance` varchar(50) DEFAULT NULL,
  `alvo_area` varchar(100) DEFAULT NULL,
  `duracao` varchar(50) DEFAULT NULL,
  `resistencia` varchar(100) DEFAULT NULL,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_magia_unica` (`nome`),
  KEY `idx_tipo_circulo_escola` (`tipo`,`circulo`,`escola`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=77 ;

--
-- Extraindo dados da tabela `t20_magias`
--

INSERT INTO `t20_magias` (`id`, `nome`, `tipo`, `circulo`, `escola`, `execucao`, `alcance`, `alvo_area`, `duracao`, `resistencia`, `descricao`) VALUES
(1, 'Abençoar', 'Divina', 1, 'Encantamento', 'Padrão', 'Curto', '1 aliado', 'Cena', 'Nenhuma', 'O alvo recebe +1 em testes de ataque e rolagens de dano.'),
(2, 'Amarrar Animal', 'Divina', 1, 'Encantamento', 'Padrão', 'Curto', '1 animal', 'Cena', 'Vontade anula', 'O animal fica prestativo em relação a você. Ele não sabe falar, mas obedece comandos simples. Ele não colocará sua vida em risco.'),
(3, 'Aviso', 'Divina', 1, 'Adivinhação', 'Movimento', 'Longo', '1 criatura', '1 dia', 'Nenhuma', 'Você avisa o alvo de um perigo telepaticamente, desde que ele não esteja em combate. O alvo recebe +1 na Defesa e em Reflexos na próxima cena.'),
(4, 'Bênção', 'Divina', 1, 'Encantamento', 'Padrão', 'Curto', 'Aliados escolhidos', 'Cena', 'Nenhuma', 'Alvos escolhidos recebem +1 em testes de ataque e rolagens de dano. Aprimoramento: +1 PM: muda o bônus para +2.'),
(5, 'Curar Ferimentos', 'Divina', 1, 'Evocação', 'Padrão', 'Toque', '1 criatura', 'Instantânea', 'Nenhuma', 'O alvo recupera 1d8+1 pontos de vida. Aprimoramento: +1 PM: aumenta os PV curados em +1d8+1.'),
(6, 'Adaga Mental', 'Arcana', 1, 'Encantamento', 'Padrão', 'Curto', '1 criatura', 'Instantânea', 'Vontade parcial', 'Você manifesta uma adaga de energia mental e a dispara. Causa 2d6 de dano psíquico. Se a vítima passar na resistência, sofre metade do dano.'),
(7, 'Alarme', 'Arcana', 1, 'Abjuração', 'Padrão', 'Curto', 'Área de 6m de raio', 'Cena', 'Nenhuma', 'Cria uma barreira protetora invisível. Se uma criatura entrar na área, você é alertado mentalmente.'),
(8, 'Área Escorregadia', 'Arcana', 1, 'Convocação', 'Padrão', 'Curto', 'Quadrado de 3m de lado', 'Cena', 'Reflexos (veja texto)', 'O chão fica escorregadio. Criaturas na área devem passar em Reflexos (CD Int) ou ficam caídas. Mover-se na área exige Acrobacia (CD 15).'),
(9, 'Amedrontar', 'Arcana', 1, 'Necromancia', 'Padrão', 'Curto', '1 criatura', 'Cena', 'Vontade parcial', 'O alvo é envolvido por sombras e fica abalado. Se falhar na resistência por 5 ou mais, fica apavorado por 1 rodada e abalado pelo resto da cena.'),
(10, 'Seta Infalível', 'Arcana', 1, 'Evocação', 'Padrão', 'Curto', '1 criatura', 'Instantânea', 'Nenhuma', 'Você cria um dardo de energia que atinge o alvo, causando 1d4+1 pontos de dano de força. Aprimoramento: +1 PM: +1 dardo (máx 5 dardos).'),
(11, 'Arma Espiritual', 'Divina', 2, 'Evocação', 'Padrão', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Uma arma feita de energia flutua ao seu lado. Uma vez por rodada, você pode gastar uma ação livre para atacar com ela (teste de Luta ou Pontaria + Sab). Causa 1d8+Sab de dano de força.'),
(12, 'Resistência a Energia', 'Divina', 2, 'Abjuração', 'Padrão', 'Toque', '1 criatura', 'Cena', 'Nenhuma', 'O alvo recebe resistência 10 contra um tipo de dano (ácido, eletricidade, fogo, frio ou sônico).'),
(13, 'Aparência Perfeita', 'Arcana', 2, 'Ilusão', 'Padrão', 'Pessoal', 'Você', 'Cena', 'Nenhuma', 'Você oculta imperfeições e parece mais belo e confiante. Você recebe +5 em testes de Carisma (exceto Intimidação).'),
(14, 'Alterar Tamanho', 'Arcana', 2, 'Transmutação', 'Padrão', 'Curto', '1 criatura', 'Cena', 'Fortitude anula', 'Você altera o tamanho da criatura. Aumentar: +2 For, -1 Des, +1d6 dano. Diminuir: -2 For, +1 Des, -1d6 dano.'),
(15, 'Bola de Fogo', 'Arcana', 3, 'Evocação', 'Padrão', 'Médio', 'Esfera com 6m de raio', 'Instantânea', 'Reflexos parcial', 'Uma explosão causa 6d6 de dano de fogo. Vítimas na área sofrem o dano total (metade se passarem na resistência).'),
(17, 'Controlar Terra', 'Divina', 5, 'Transmutação', 'Padrão', 'Longo', 'cubos com 1,5m de lado', 'Sustentada', 'Veja texto', 'Você manipula a densidade e a forma da terra, lama, areia ou pedra. A Mágica não funciona se o objeto está sendo carregado por outra criatura.'),
(18, 'Convocação Instântanea', 'Arcana', 5, 'Convocação', 'Padrão', 'Alcance', 'limitado', 'Permanente', 'Nenhuma', 'Você invoca um objeto de até 2 espaços que deve ter sido preparado com antecedência.'),
(19, 'Crânio Voador de Vladislav', 'Arcana', 2, 'Necromancia', 'Padrão', 'Médio', '1 crânio', 'Instantânea', 'Fortitude parcial', 'Você atira um crânio envolto em energia negativa. Ele causa 4d4+4 de dano de trevas e o alvo fica abalado por 1 rodada.'),
(20, 'Criar Elementos', 'Divina', 1, 'Convocação', 'Padrão', 'Curto', 'veja texto', 'Veja texto', 'Nenhuma', 'Você cria uma pequena porção de matéria de um dos quatro elementos básicos. Terra, Água, Fogo ou Ar.'),
(21, 'Criar Ilusão', 'Arcana', 1, 'Ilusão', 'Padrão', 'Médio', 'efeito', 'Cena', 'Vontade desacredita', 'Você cria uma ilusão visual com até 4 cubos de 1,5m de lado. A magia é puramente visual; não é tátil, sonora, etc.'),
(22, 'Cúpula de Repulsão', 'Divina', 5, 'Abjuração', 'Padrão', 'Pessoal', 'você', 'Sustentada', 'Vontade anula', 'Uma cúpula de energia impede a aproximação de criaturas, empurrando-as para 1,5m de você.'),
(24, 'Deflagração de Mana', 'Divina', 5, 'Evocação', 'Padrão', 'Pessoal', 'esfera com 15m de raio', 'Instantânea', 'Resistência', 'Você libera sua mana em uma estrela em plena terra. Causa 10d10 de dano de essência e itens mágicos (exceto artefatos) são afetados.'),
(25, 'Desespero Esmagador', 'Arcana', 2, 'Encantamento', 'Padrão', 'Curto', 'área', 'Cena', 'Fortitude parcial', 'Humanoides na área são acometidos de grande tristeza, ficando pasmados por 1 rodada.'),
(26, 'Desejo', 'Arcana', 5, 'Transmutação', 'Padrão', 'Alcance', 'veja texto', 'Veja texto', 'Veja texto', 'Esta é a mais poderosa das magias, permitindo alterar a realidade a seu bel-prazer.'),
(27, 'Desintegrar', 'Arcana', 4, 'Evocação', 'Padrão', 'Médio', '1 criatura ou objeto', 'Instantânea', 'Fortitude parcial', 'Você dispara um raio fino e esverdeado que causa 10d12 de dano de essência.'),
(28, 'Despedaçar', 'Divina', 1, 'Evocação', 'Padrão', 'Curto', '1 objeto ou construto', 'Instantânea', 'Fortitude parcial', 'Esta magia emite um som alto e agudo. Causa 2d8 de dano sônico (ou 4d8 contra construto).'),
(29, 'Despertar Consciência', 'Divina', 3, 'Encantamento', 'Completa', 'Toque', '1 animal ou planta', '1 dia', 'Nenhuma', 'Você desperta a consciência de um animal ou planta. Ele se torna seu parceiro iniciante.'),
(30, 'Detectar Ameaças', 'Divina', 1, 'Adivinhação', 'Padrão', 'Pessoal', 'área', 'Cena', 'Nenhuma', 'Você recebe uma intuição aguçada sobre perigos em seu redor, recebendo +1 em Defesa e bônus em Percepção.'),
(31, 'Detectar Magia', 'Arcana', 1, 'Adivinhação', 'Padrão', 'Curto', 'área', 'Cena', 'Nenhuma', 'Você detecta a presença de magias e itens mágicos na área. Auras mágicas brilham para você.'),
(32, 'Dificultar Detecção', 'Arcana', 3, 'Abjuração', 'Padrão', 'Toque', '1 alvo', '1 dia', 'Nenhuma', 'Você protege o alvo contra magias de Adivinhação. Magias que tentarem detectar o alvo devem fazer um teste de Misticismo (CD 20).'),
(33, 'Disfarce Ilusório', 'Arcana', 1, 'Ilusão', 'Padrão', 'Pessoal', 'você', 'Cena', 'Vontade anula', 'Você muda a aparência do seu equipamento. O disfarce é puramente visual. Você recebe +10 em testes de Enganação para parecer outra pessoa.'),
(34, 'Dispersar as Trevas', 'Divina', 3, 'Evocação', 'Padrão', 'Pessoal', 'área', 'Veja texto', 'Nenhuma', 'Esta magia cria um forte brilho (mesma intensidade de luz do dia) em um raio de 9m.'),
(35, 'Dissipar Magia', 'Universal', 2, 'Abjuração', 'Padrão', 'Médio', '1 criatura ou 1 objeto', 'Instantânea', 'Nenhuma', 'Você dissipa magias que estejam ativas. Faça um teste de Misticismo (CD 15 + círculo da magia) para dissipar.'),
(36, 'Duplicata Ilusória', 'Arcana', 4, 'Ilusão', 'Padrão', 'Alcance', 'efeito', 'Cena', 'Vontade anula', 'Você cria uma cópia ilusória e semirreal de você mesmo. Ela é idêntica a você e compartilha seus sentidos.'),
(37, 'Enfeitiçar', 'Arcana', 1, 'Encantamento', 'Padrão', 'Curto', '1 alvo', 'Cena', 'Vontade anula', 'O alvo fica enfeitiçado (prestativo) a você. Se você ou seus aliados o atacarem, o efeito é quebrado.'),
(38, 'Engenho de Mana', 'Arcana', 5, 'Abjuração', 'Padrão', 'Médio', 'disco com 1,5m de diâmetro', 'Sustentada', 'Nenhuma', 'Você cria um disco de energia flutuante que emite uma aura antimagia. Magias não podem ser lançadas para dentro ou fora do disco.'),
(39, 'Enxame de Pestes', 'Divina', 2, 'Convocação', 'Completa', 'Médio', 'esfera de 1,5m de raio', 'Sustentada', 'Fortitude reduz à metade', 'Você conjura um enxame de criaturas (insetos, morcegos, etc.) que causa 2d12 de dano de trevas.'),
(40, 'Enxame Rubro de Ichabod', 'Arcana', 1, 'Evocação', 'Padrão', 'Alcance', 'enxame grande (quadrado de 3m)', 'Sustentada', 'Reflexos reduz à metade', 'Você cria um enxame de pequenas criaturas da Tormenta. Causa 4d12 de dano de ácido.'),
(41, 'Erupção Glacial', 'Arcana', 5, 'Evocação', 'Padrão', 'Alcance', 'área', 'Instantânea', 'Resistência', 'Pedaços de gelo irrompem do chão, causando 4d6 de dano de corte e 4d6 de dano de frio e ficam caídas.'),
(42, 'Escudo da Fé', 'Divina', 1, 'Abjuração', 'Reação', 'Curto', '1 criatura', '1 rodada', 'Nenhuma', 'Um escudo místico se manifesta no momento de um bloqueio. O alvo recebe +2 na Defesa.'),
(43, 'Esculpir Sons', 'Arcana', 2, 'Ilusão', 'Padrão', 'Médio', 'alvo', 'Cena', 'Vontade anula', 'Esta magia altera os sons emitidos pelo alvo. Ela é capaz de criar sons, torná-los audíveis ou inaudíveis, etc.'),
(44, 'Escuridão', 'Divina', 1, 'Necromancia', 'Padrão', 'Alcance', 'objeto', 'Cena', 'Vontade anula (veja texto)', 'O alvo emana sombras em uma área com 6m de raio, causando escuridão (camuflagem leve).'),
(45, 'Explosão Caleidoscópica', 'Arcana', 4, 'Ilusão', 'Padrão', 'Alcance', 'área', 'Instantânea', 'Resistência', 'Esta magia cria uma forte explosão de luzes estroboscópicas e sons caóticos.'),
(46, 'Explosão de Chamas', 'Arcana', 1, 'Evocação', 'Padrão', 'Alcance', 'área', 'Instantânea', 'Reflexos reduz à metade', 'Um leque de chamas irrompe de suas mãos, causando 2d6 de dano de fogo às criaturas na área.'),
(47, 'Ferver Sangue', 'Arcana', 1, 'Evocação', 'Padrão', 'Curto', 'alvo', 'Sustentada', 'Fortitude parcial', 'O sangue do alvo aquece. Causa 6d8 de dano de fogo e fica enjoado.'),
(48, 'Físico Divino', 'Divina', 1, 'Transmutação', 'Padrão', 'Toque', '1 alvo', 'Cena', 'Nenhuma', 'Você fortalece o corpo do alvo. Ele recebe +2 em Força, Destreza ou Constituição (à sua escolha).'),
(49, 'Flecha Ácida', 'Arcana', 2, 'Evocação', 'Padrão', 'Médio', '1 criatura ou objeto', 'Duração', 'Reflexos parcial', 'Você dispara um projétil de ácido. Se falhar no teste de resistência, o alvo sofre 4d6 de dano de ácido e 2d6 no início dos seus próximos turnos.'),
(50, 'Forma Etérea', 'Arcana', 4, 'Transmutação', 'Completa', 'Alcance', 'pessoal', 'Cena', 'Nenhuma', 'Você e todo o equipamento que está carregando tornam-se etéreos (incorpóreos).'),
(51, 'Fúria do Panteão', 'Divina', 5, 'Evocação', 'Completa', 'Alcance', 'cubo com 9m de lado', 'Sustentada', 'Resistência', 'Você cria uma tempestade de tormenta. Causa 10d6 de dano de eletricidade, 10d6 de dano de trevas e 10d6 de dano de impacto.'),
(52, 'Globo da Verdade de Gwen', 'Divina', 2, 'Adivinhação', 'Padrão', 'Alcance', '1 globo', 'Duração', 'Nenhuma', 'Cria um globo flutuante e intangível que o segue. Ele brilha e revela a verdade, criaturas e objetos invisíveis são revelados.'),
(53, 'Globo de Invulnerabilidade', 'Arcana', 5, 'Abjuração', 'Padrão', 'Pessoal', 'área', 'Sustentada', 'Nenhuma', 'Você cria uma esfera mágica brilhante com 3m de raio que detém qualquer magia de 2º círculo ou menor.'),
(54, 'Guardião Divino', 'Divina', 4, 'Convocação', 'Padrão', 'Alcance', '1 criatura', 'Cena', 'Nenhuma', 'Você invoca um elemental Pequeno (Água, Ar, Fogo ou Terra) que o auxilia em combate.'),
(55, 'Heroísmo', 'Divina', 3, 'Encantamento', 'Padrão', 'Toque', '1 alvo', 'Cena', 'Nenhuma', 'Esta magia imbui uma criatura com coragem e valentia. O alvo fica imune a medo e recebe +5 PV temporários.'),
(56, 'Hipnotismo', 'Arcana', 1, 'Encantamento', 'Padrão', 'Alcance', '1 alvo ou humanoide', 'Duração', 'Vontade anula', 'Suas palavras e movimentos ritmados deixam o alvo fascinado. Ele fica imóvel.'),
(57, 'Ilusão Lancinante', 'Arcana', 2, 'Ilusão', 'Padrão', 'Médio', 'área', 'Sustentada', 'Vontade anula', 'Você cria uma área de ilusão. Criaturas na área devem fazer um teste de Vontade; se falharem, acreditam que é real e sofrem 1d6 de dano mental.'),
(58, 'Imagem Espelhada', 'Arcana', 1, 'Ilusão', 'Padrão', 'Pessoal', 'você', 'Duração', 'Nenhuma', 'Três cópias ilusórias suas aparecem. Elas o imitam, tornando difícil saber quem é você. Você recebe +6 na Defesa.'),
(59, 'Imobilizar', 'Arcana', 5, 'Encantamento', 'Padrão', 'Curto', 'alvo', 'Sustentada', 'Vontade parcial', 'O alvo fica paralisado. No final de cada turno, pode fazer um novo teste de Vontade para se libertar.'),
(60, 'Infligir Ferimentos', 'Divina', 1, 'Necromancia', 'Padrão', 'Toque', '1 alvo', 'Instantânea', 'Fortitude reduz à metade', 'Você catalisa energia negativa contra um alvo, causando 2d8+2 de dano de trevas.'),
(61, 'Intervenção Divina', 'Divina', 5, 'Evocação', 'Completa', 'Alcance', 'veja texto', 'Duração', 'Veja texto', 'Você pede a sua divindade para interceder diretamente.'),
(62, 'Invisibilidade', 'Arcana', 2, 'Ilusão', 'Padrão', 'Alcance', 'alvo', '1 rodada', 'Nenhuma', 'O alvo fica invisível. O efeito termina se o alvo fizer uma ação hostil.'),
(63, 'Invulnerabilidade', 'Universal', 5, 'Abjuração', 'Padrão', 'Pessoal', 'você', 'Duração', 'Nenhuma', 'Esta magia cria uma barreira mística que protege você contra efeitos nocivos mentais ou físicos.'),
(64, 'Lágrimas de Wynna', 'Divina', 1, 'Abjuração', 'Padrão', 'Alcance', '1 criatura', 'Instantânea', 'Vontade parcial', 'Se o alvo falhar, perde a habilidade de lançar magias até o fim da cena.'),
(65, 'Lança Ígnea de Aleph', 'Arcana', 5, 'Evocação', 'Padrão', 'Alcance', 'alvo', 'Duração', 'Reflexos parcial', 'Esta magia foi desenvolvida pelo mago Aleph. Um relâmpago de chamas causa 6d6 de dano de fogo.'),
(66, 'Legião', 'Arcana', 5, 'Encantamento', 'Padrão', 'Alcance', 'até 10 criaturas', 'Duração', 'Vontade anula', 'Você domina a mente dos alvos. Os alvos ficam sob seu controle, mas têm direito a um teste de Vontade a cada turno.'),
(67, 'Leque Cromático', 'Arcana', 1, 'Ilusão', 'Padrão', 'Alcance', 'pessoal', 'Cena', 'Vontade parcial', 'Uma série de luzes brilhantes surge de suas mãos, deixando os animas e humanoides na área atordoados.'),
(68, 'Lendas e Histórias', 'Universal', 3, 'Adivinhação', 'Padrão', 'Alcance', '1 objeto ou local', 'Duração', 'Nenhuma', 'Você descobre informações sobre um objeto ou local que esteja tocando.'),
(69, 'Libertação', 'Universal', 4, 'Abjuração', 'Padrão', 'Alcance', '1 criatura', 'Duração', 'Nenhuma', 'O alvo fica imune a efeitos de movimento e pode ignorar qualquer efeito que impeça o movimento.'),
(70, 'Ligação Sombria', 'Divina', 4, 'Necromancia', 'Padrão', 'Alcance', '1 criatura', '1 dia', 'Fortitude anula', 'Cria um elo entre seu coração e o alvo. Você sofre metade do dano que o alvo sofrer (e vice-versa).'),
(71, 'Ligação Telepática', 'Arcana', 2, 'Adivinhação', 'Padrão', 'Alcance', '2 criaturas voluntárias', '1 dia', 'Nenhuma', 'Você cria um elo mental entre duas criaturas. Elas podem se comunicar telepaticamente.'),
(72, 'Localização', 'Arcana', 2, 'Adivinhação', 'Padrão', 'Alcance', 'pessoal', 'Cena', 'Nenhuma', 'Esta magia pode encontrar uma criatura ou objeto. Você sabe a direção e distância aproximada.'),
(73, 'Luz', 'Universal', 1, 'Evocação', 'Padrão', 'Alcance', 'objeto', 'Cena', 'Vontade anula (veja texto)', 'O alvo emite luz (como uma tocha). Se for um objeto em posse de uma criatura, ela pode anular.'),
(74, 'Manto de Sombras', 'Universal', 3, 'Ilusão', 'Padrão', 'Alcance', 'toque', 'Cena', 'Nenhuma', 'O alvo fica coberto por um manto de sombras. Ele ganha camuflagem total na escuridão.'),
(75, 'Manto do Cruzado', 'Divina', 4, 'Evocação', 'Padrão', 'Alcance', 'pessoal', 'Duração', 'Nenhuma', 'Você invoca o poder de sua divindade na forma de um manto de energia. O manto ilumina e causa dano a inimigos adjacentes.'),
(76, 'Mão Poderosa de Talude', 'Arcana', 4, 'Evocação', 'Padrão', 'Alcance', 'médio', 'efeito', 'Sustentada', 'Esta magia cria uma mão flutuante de grande tamanho que você pode controlar.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `t20_origens`
--

CREATE TABLE IF NOT EXISTS `t20_origens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `pericias_treinadas` text,
  `poder_1_nome` varchar(255) DEFAULT NULL,
  `poder_1_desc` text,
  `poder_2_nome` varchar(255) DEFAULT NULL,
  `poder_2_desc` text,
  `poder_3_nome` varchar(255) DEFAULT NULL,
  `poder_3_desc` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=36 ;

--
-- Extraindo dados da tabela `t20_origens`
--

INSERT INTO `t20_origens` (`id`, `nome`, `pericias_treinadas`, `poder_1_nome`, `poder_1_desc`, `poder_2_nome`, `poder_2_desc`, `poder_3_nome`, `poder_3_desc`) VALUES
(1, 'Acólito', 'Cura, Religião, Vontade', 'Membro da Igreja', 'Você consegue hospedagem confortável e informação em qualquer templo de sua divindade, para você e seus aliados.', 'Vontade de Ferro', 'Você recebe +1 PM para cada dois níveis de personagem e +2 em Vontade. Pré-requisito: Sab 1.', NULL, NULL),
(2, 'Amigo dos Animais', 'Adestramento, Cavalgar', 'Amigo Especial', 'Você recebe +5 em testes de Adestramento com animais. Além disso, possui um animal de estimação que o auxilia e o acompanha em suas aventuras. Em termos de jogo, é um parceiro que fornece +2 em uma perícia a sua escolha (exceto Luta ou Pontaria e aprovada pelo mestre) e não conta em seu limite de parceiros.', NULL, NULL, NULL, NULL),
(3, 'Amnésico', 'Uma perícia à escolha', 'Lembranças Graduais', 'Durante suas aventuras, em determinados momentos a critério do mestre, você pode fazer um teste de Sabedoria (CD 10) para reconhecer pessoas, criaturas ou lugares que tenha encontrado antes de perder a memória.', 'Poder Adicional (Definido pelo Mestre)', 'O mestre define um poder geral adicional que você possui, mas não se lembrava.', NULL, NULL),
(4, 'Aristocrata', 'Diplomacia, Enganação, Nobreza', 'Comandar', 'Você pode gastar uma ação de movimento e 1 PM para gritar ordens para seus aliados em alcance médio. Eles recebem +1 em testes de perícia até o fim da cena. Pré-requisito: Car 1.', 'Sangue Azul', 'Você tem alguma influência política, suficiente para ser tratado com mais leniência pela guarda, conseguir uma audiência com o nobre local etc.', NULL, NULL),
(5, 'Artesão', 'Ofício, Vontade', 'Frutos do Trabalho', 'No início de cada aventura, você recebe até 5 itens gerais que possa fabricar num valor total de até T$ 50. Esse valor aumenta para T$ 100 no patamar aventureiro, T$ 300 no heroico e T$ 500 no lenda.', 'Sortudo', 'Você pode gastar 3 PM para rolar novamente um teste recém realizado (apenas uma vez por teste).', NULL, NULL),
(6, 'Artista', 'Atuação, Enganação', 'Atraente', 'Você recebe +2 em testes de Diplomacia, Enganação e Intuição, mas apenas se não estiver usando armadura pesada.', 'Dom Artístico', 'Você recebe +2 em testes de Atuação, e recebe o dobro de tibares em apresentações.', 'Escolha: Sortudo ou Torcida', 'Sortudo: Você pode gastar 3 PM para rolar novamente um teste recém realizado (apenas uma vez por teste).\nTorcida: Você pode gastar 1 PM e uma ação padrão para fazer um teste de Atuação (CD 15). Se passar, você e todos os aliados em alcance curto recebem +1 em testes de ataque e rolagens de dano por uma rodada.'),
(7, 'Assistente de Laboratório', 'Ofício (alquimista), Misticismo', 'Esse Cheiro...', 'Você recebe +2 em testes de Misticismo e Ofício (alquimia).', 'Venefício', 'Você recebe +2 em testes de Ofício (alquimia) para fabricar venenos e aplica venenos como uma ação de movimento (em vez de ação padrão).', 'Poder da Tormenta (Escolha)', 'Você recebe um poder da Tormenta a sua escolha (funciona como o poder Transcender, exigindo escolha posterior).'),
(8, 'Batedor', 'Furtividade, Percepção, Sobrevivência', 'À Prova de Tudo', 'Você recebe +1 ponto de vida por nível de personagem.', 'Estilo de Disparo', 'Você recebe o poder Estilo de Disparo (escolha um).', 'Sentidos Aguçados', 'Você recebe +2 em testes de Percepção e não fica desprevenido contra inimigos que não possa ver em alcance curto.'),
(9, 'Capanga', 'Luta, Intimidação', 'Confissão', 'Você pode usar Intimidação para obter informação (veja Investigação). Esta ação leva uma hora e você não precisa fazer um teste — a vítima automaticamente revela a informação (se a tiver). Você só pode usar esta habilidade uma vez por cena contra o mesmo alvo.', 'Poder de Combate (Escolha)', 'Você recebe um poder de combate a sua escolha.', NULL, NULL),
(10, 'Charlatão', 'Enganação, Jogatina', 'Alpinista Social', 'Você pode usar Enganação no lugar de Diplomacia ou Intimidação para mudar atitude de alvos. Pré-requisito: treinado em Enganação.', 'Aparência Inofensiva', 'A dificuldade para resistir aos seus testes de Enganação aumenta em +2.', 'Sortudo', 'Você pode gastar 3 PM para rolar novamente um teste recém realizado (apenas uma vez por teste).'),
(11, 'Circense', 'Acrobacia, Atuação, Reflexos', 'Acrobático', 'Você recebe +2 em testes de Acrobacia. Além disso, quando sofre dano de queda, pode fazer um teste de Acrobacia (CD 15); se passar, reduz o dano da queda à metade.', 'Torcida', 'Você pode gastar 1 PM e uma ação padrão para fazer um teste de Atuação (CD 15). Se passar, você e todos os aliados em alcance curto recebem +1 em testes de ataque e rolagens de dano por uma rodada.', 'Truque de Mágica', 'Você aprende e pode lançar Ilusão. Se aprender novamente essa magia, seu custo diminui em –1 PM.'),
(12, 'Criminoso', 'Enganação, Furtividade, Ladinagem', 'Punguista', 'Você recebe +2 em testes de Ladinagem e pode usar esta perícia para furtar PM em vez de itens.', 'Venefício', 'Você recebe +2 em testes de Ofício (alquimia) para fabricar venenos e aplica venenos como uma ação de movimento (em vez de ação padrão).', NULL, NULL),
(13, 'Curandeiro', 'Cura, Vontade', 'Medicina', 'Você recebe +2 em testes de Cura. Além disso, pode gastar uma ação de movimento e 1 PM para curar 1d6 pontos de vida de um aliado adjacente.', 'Médico de Campo', 'Você pode usar a perícia Cura em alcance curto (em vez de apenas em alcance pessoal ou adjacente).', 'Venefício', 'Você recebe +2 em testes de Ofício (alquimia) para fabricar venenos e aplica venenos como uma ação de movimento (em vez de ação padrão).'),
(14, 'Eremita', 'Misticismo, Religião, Sobrevivência', 'Busca Interior', 'Você pode gastar uma hora meditando para recuperar 1 PM. Você pode usar esta habilidade um número de vezes por dia igual ao seu bônus de Sabedoria (mínimo 1).', 'Lobo Solitário', 'Você recebe +1 em testes de perícia e Defesa se estiver sem nenhum aliado em alcance curto. Você não sofre penalidade por usar Cura em si mesmo.', NULL, NULL),
(15, 'Escravo', 'Atletismo, Fortitude, Furtividade', 'Desejo de Liberdade', 'Você recebe +1 PM para cada dois níveis de personagem e +2 em Vontade.', 'Vitalidade', 'Você recebe +2 pontos de vida para cada nível de personagem.', NULL, NULL),
(16, 'Estudioso', 'Conhecimento, Guerra, Misticismo', 'Aparência Inofensiva', 'A dificuldade para resistir aos seus testes de Enganação aumenta em +2.', 'Palpite Fundamentado', 'Você pode gastar uma ação completa analisando um item, local ou criatura para descobrir uma de suas características, como RD, valores de atributos, se é mágico etc., a critério do mestre.', NULL, NULL),
(17, 'Fazendeiro', 'Adestramento, Cavalgar, Ofício (fazendeiro)', 'Água no Feijão', 'Você gasta apenas metade da ração diária normal para se alimentar. Além disso, sabe cozinhar: pode preparar alimentos para um número de pessoas igual ao seu bônus de Sabedoria +1 por 1 Tibar por pessoa. Um personagem que coma uma refeição recupera 1 PV por nível.', 'Ginete', 'Você recebe +2 em testes de Cavalgar. Além disso, não sofre penalidade em testes de ataque por lutar montado.', NULL, NULL),
(18, 'Forasteiro', 'Cavalgar, Pilotagem, Sobrevivência', 'Cultura Exótica', 'Você recebe +2 em testes de Conhecimento e Misticismo.', 'Lobo Solitário', 'Você recebe +1 em testes de perícia e Defesa se estiver sem nenhum aliado em alcance curto. Você não sofre penalidade por usar Cura em si mesmo.', NULL, NULL),
(19, 'Gladiador', 'Atuação, Luta', 'Atraente', 'Você recebe +2 em testes de Diplomacia, Enganação e Intuição, mas apenas se não estiver usando armadura pesada.', 'Pão e Circo', 'Você pode gastar uma hora e T$ 10 para entreter uma plateia mundana. Faça um teste de Atuação (CD 15). Se passar, você recebe T$ 10 mais T$ 1 por ponto que exceder a CD.', 'Torcida OU Poder de Combate', 'Torcida: Gastar 1 PM, ação padrão, teste Atuação (CD 15); sucesso: +1 ataque/dano para aliados em alcance curto por 1 rodada.\nPoder de Combate: Você recebe um poder de combate a sua escolha.'),
(20, 'Guarda', 'Investigação, Luta, Percepção', 'Detetive', 'Você recebe +2 em testes de Investigação.', 'Investigador', 'Você pode usar Investigação para procurar pistas. Esta ação leva um minuto e exige um teste de Investigação (CD 15). Se passar, você encontra uma pista sobre o mistério que estiver investigando.', 'Poder de Combate (Escolha)', 'Você recebe um poder de combate a sua escolha.'),
(21, 'Herdeiro', 'Misticismo, Nobreza, Ofício', 'Comandar', 'Você pode gastar uma ação de movimento e 1 PM para gritar ordens para seus aliados em alcance médio. Eles recebem +1 em testes de perícia até o fim da cena. Pré-requisito: Car 1.', 'Herança', 'Você começa o jogo com T$ 1.000 extras.', NULL, NULL),
(22, 'Herói Camponês', 'Adestramento, Ofício', 'Coração Heroico', 'Você recebe +1 em testes de resistência.', 'Sortudo', 'Você pode gastar 3 PM para rolar novamente um teste recém realizado (apenas uma vez por teste).', 'Escolha: Surto Heroico ou Torcida', 'Surto Heroico: Uma vez por dia, pode gastar 5 PM para realizar uma ação padrão ou de movimento adicional.\nTorcida: Gastar 1 PM, ação padrão, teste Atuação (CD 15); sucesso: +1 ataque/dano para aliados em alcance curto por 1 rodada.'),
(23, 'Marujo', 'Atletismo, Jogatina, Pilotagem', 'Acrobático', 'Você recebe +2 em testes de Acrobacia. Além disso, quando sofre dano de queda, pode fazer um teste de Acrobacia (CD 15); se passar, reduz o dano da queda à metade.', 'Passagem de Navio', 'Você consegue transporte marítimo gratuito para você e seus aliados.', NULL, NULL),
(24, 'Mateiro', 'Atletismo, Furtividade, Sobrevivência', 'Lobo Solitário', 'Você recebe +1 em testes de perícia e Defesa se estiver sem nenhum aliado em alcance curto. Você não sofre penalidade por usar Cura em si mesmo.', 'Sentidos Aguçados', 'Você recebe +2 em testes de Percepção e não fica desprevenido contra inimigos que não possa ver em alcance curto.', 'Vendedor de Carcaças', 'Você pode gastar 1 hora esfolando um animal que tenha caçado para obter couro e carne, que valem T$ 10.'),
(25, 'Membro de Guilda', 'Diplomacia, Enganação, Misticismo, Ofício', 'Foco em Perícia', 'Escolha uma perícia. Você recebe +2 nesta perícia.', 'Rede de Contatos', 'Você pode usar Diplomacia para obter informação (veja Investigação). Esta ação leva um dia.', NULL, NULL),
(26, 'Mercador', 'Diplomacia, Intuição, Ofício', 'Negociação', 'Você recebe +2 em testes de Diplomacia para barganhar.', 'Proficiência', 'Você recebe proficiência em uma arma exótica à sua escolha.', 'Sortudo', 'Você pode gastar 3 PM para rolar novamente um teste recém realizado (apenas uma vez por teste).'),
(27, 'Minerador', 'Atletismo, Fortitude, Ofício (minerador)', 'Ataque Poderoso', 'Você pode sofrer –2 em testes de ataque corpo a corpo para receber +5 nas rolagens de dano.', 'Escavador', 'Você não sofre penalidade em deslocamento por terreno difícil causado por pedras ou terra.', 'Sentidos Aguçados', 'Você recebe +2 em testes de Percepção e não fica desprevenido contra inimigos que não possa ver em alcance curto.'),
(28, 'Nômade', 'Cavalgar, Pilotagem, Sobrevivência', 'Lobo Solitário', 'Você recebe +1 em testes de perícia e Defesa se estiver sem nenhum aliado em alcance curto. Você não sofre penalidade por usar Cura em si mesmo.', 'Mochileiro', 'Seu limite de carga aumenta em +5 espaços.', 'Sentidos Aguçados', 'Você recebe +2 em testes de Percepção e não fica desprevenido contra inimigos que não possa ver em alcance curto.'),
(29, 'Pivete', 'Furtividade, Iniciativa, Ladinagem', 'Acrobático', 'Você recebe +2 em testes de Acrobacia. Além disso, quando sofre dano de queda, pode fazer um teste de Acrobacia (CD 15); se passar, reduz o dano da queda à metade.', 'Aparência Inofensiva', 'A dificuldade para resistir aos seus testes de Enganação aumenta em +2.', 'Quebra-Galho', 'Você pode usar Ladinagem no lugar de Ofício para consertar itens mundanos.'),
(30, 'Refugiado', 'Fortitude, Reflexos, Vontade', 'Estoico', 'Você recebe +2 em testes de Fortitude e Vontade.', 'Vontade de Ferro', 'Você recebe +1 PM para cada dois níveis de personagem e +2 em Vontade. Pré-requisito: Sab 1.', NULL, NULL),
(31, 'Seguidor', 'Adestramento, Ofício', 'Antigo Mestre', 'Uma vez por dia, você pode fazer um teste de Sabedoria (CD 15). Se passar, recebe +2 em um teste de perícia à sua escolha até o fim do dia.', 'Proficiência', 'Você recebe proficiência em uma arma exótica à sua escolha.', 'Surto Heroico', 'Uma vez por dia, pode gastar 5 PM para realizar uma ação padrão ou de movimento adicional.'),
(32, 'Selvagem', 'Percepção, Reflexos, Sobrevivência', 'Lobo Solitário', 'Você recebe +1 em testes de perícia e Defesa se estiver sem nenhum aliado em alcance curto. Você não sofre penalidade por usar Cura em si mesmo.', 'Vida Rústica', 'Você recebe +2 em testes de Percepção e Sobrevivência.', 'Vitalidade', 'Você recebe +2 pontos de vida para cada nível de personagem.'),
(33, 'Soldado', 'Fortitude, Guerra, Luta, Pontaria', 'Influência Militar', 'Você recebe +2 em testes de Intimidação.', 'Poder de Combate (Escolha)', 'Você recebe um poder de combate a sua escolha.', NULL, NULL),
(34, 'Taverneiro', 'Diplomacia, Jogatina, Ofício (cozinheiro)', 'Gororoba', 'Você pode preparar alimentos para um número de pessoas igual ao seu bônus de Carisma +1 por 1 Tibar por pessoa. Um personagem que coma uma refeição recupera 1 PV por nível.', 'Proficiência', 'Você recebe proficiência em uma arma exótica à sua escolha.', 'Vitalidade', 'Você recebe +2 pontos de vida para cada nível de personagem.'),
(35, 'Trabalhador', 'Atletismo, Fortitude', 'Atlético', 'Você recebe +2 em testes de Atletismo.', 'Esforçado', 'Você recebe +1 PM para cada dois níveis de personagem.', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `t20_poderes_classe`
--

CREATE TABLE IF NOT EXISTS `t20_poderes_classe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classe_id` int(11) NOT NULL,
  `nome` varchar(191) NOT NULL,
  `descricao` text NOT NULL,
  `pre_requisito` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_classe_unique` (`classe_id`,`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=229 ;

--
-- Extraindo dados da tabela `t20_poderes_classe`
--

INSERT INTO `t20_poderes_classe` (`id`, `classe_id`, `nome`, `descricao`, `pre_requisito`) VALUES
(1, 1, 'Ambidestria', 'Se estiver empunhando duas armas (e pelo menos uma delas for leve) e fizer a ação agredir, você pode fazer dois ataques, um com cada arma. Se fizer isso, sofre –2 em todos os testes de ataque até o seu próximo turno.', 'Des 2'),
(2, 1, 'Armadilha: Arataca', 'A vítima sofre 2d6 pontos de dano de perfuração e fica agarrada. Uma criatura agarrada pode escapar com uma ação padrão e um teste de Força ou Acrobacia (CD Sab).', NULL),
(3, 1, 'Armadilha: Espinhos', 'A vítima sofre 6d6 pontos de dano de perfuração. Um teste de Reflexos (CD Sab) reduz o dano à metade.', NULL),
(4, 1, 'Armadilha: Laço', 'A vítima deve fazer um teste de Reflexos (CD Sab). Se passar, fica caída. Se falhar, fica agarrada. Uma criatura agarrada pode se soltar com uma ação padrão e um teste de Força ou Acrobacia (CD Sab).', NULL),
(5, 1, 'Armadilha: Rede', 'Todas as criaturas na área ficam enredadas e não podem sair da área. Uma vítima pode se libertar com uma ação padrão e um teste de Força ou Acrobacia (CD 25). Além disso, a área ocupada pela rede é considerada terreno difícil.', NULL),
(6, 1, 'Armadilheiro', 'A CD para encontrar e resistir às suas armadilhas aumenta em +5 e você soma sua Sabedoria ao dano delas.', '5º nível de caçador'),
(7, 1, 'Arqueiro', 'Se estiver usando uma arma de ataque à distância, você soma sua Sabedoria nas rolagens de dano (limitado pelo seu nível).', 'Sab 1'),
(8, 1, 'Aumento de Atributo', 'Você recebe +1 em um atributo. Você pode escolher este poder várias vezes, mas apenas uma vez por patamar para o mesmo atributo.', NULL),
(9, 1, 'Bote', 'Se estiver empunhando duas armas e fizer uma investida, você pode pagar 1 PM para fazer um ataque adicional com sua arma secundária.', 'Ambidestria, 6º nível de caçador'),
(10, 1, 'Camuflagem', 'Você pode gastar 2 PM para se esconder mesmo sem camuflagem ou cobertura disponível.', '6º nível de caçador'),
(11, 1, 'Chuva de Lâminas', 'Uma vez por rodada, quando usa Ambidestria, você pode pagar 2 PM para fazer um ataque adicional com sua arma primária.', 'Des 4, Ambidestria, 12º nível de caçador'),
(12, 1, 'Companheiro Animal', 'Você recebe um companheiro animal. Você veja o quadro na página 62.', 'Car 1, treinado em Adestramento'),
(13, 1, 'Emboscar', 'Você pode gastar 2 PM para realizar uma ação padrão adicional em seu turno. Você só pode usar este poder na primeira rodada de um combate.', 'Treinado em Furtividade'),
(14, 1, 'Empatia Selvagem', 'Você pode se comunicar com animais por meio de linguagem corporal e vocalizações. Você pode usar Adestramento para mudar atitude e persuasão com animais (veja Diplomacia).', NULL),
(15, 1, 'Escaramuça', 'Quando se move 6m ou mais, você recebe +2 na Defesa e Reflexos e +1d8 nas rolagens de dano de ataques corpo a corpo e à distância até o início do seu próximo turno.', 'Des 2'),
(16, 1, 'Escaramuça Superior', 'Quando usa Escaramuça, seus bônus aumentam para +5 na Defesa e Reflexos e +1d12 em rolagens de dano.', 'Escaramuça, 12º nível de caçador'),
(17, 1, 'Espreitar', 'Quando usa a habilidade Marca da Presa, você recebe um bônus de +1 em testes de perícia contra a criatura marcada. Esse bônus aumenta em +1 para cada PM adicional gasto na habilidade e também dobra com a habilidade Inimigo.', NULL),
(18, 1, 'Elo com a Natureza', 'Você recebe +2 em Adestramento, Cavalgar, Cura e Sobrevivência.', NULL),
(19, 1, 'Ervas Curativas', 'Você pode gastar uma ação completa e uma quantidade de PM a sua escolha (limitado por sua Sabedoria) para aplicar ervas que curam ou desintoxicam em você ou num aliado adjacente. Para cada PM que gastar, cura 2d6 PV ou remove uma condição de veneno.', NULL),
(20, 1, 'Despistar', 'Você pode gastar 1 PM para aumentar a dificuldade para rastreá-lo em +10 por uma rodada.', NULL),
(21, 1, 'Inimigo (Criatura)', 'Escolha um tipo de criatura entre animal, construto, espírito, monstro ou morto-vivo, ou duas raças humanoides. Você recebe +2 em testes de Intimidação e Sobrevivência contra esse tipo/raças e +1d8 nas rolagens de dano. Você pode escolher este poder outras vezes para inimigos diferentes.', NULL),
(22, 1, 'Olho de Falcão', 'Você pode usar a habilidade Marca da Presa em criaturas em alcance longo.', NULL),
(23, 1, 'Ponto Fraco', 'Quando usa a habilidade Marca da Presa, seus ataques contra a criatura marcada recebem +2 na margem de ameaça. Esse bônus dobra com a habilidade Inimigo.', NULL),
(24, 2, 'Ambidestria', 'Se estiver empunhando duas armas (e pelo menos uma delas for leve) e fizer a ação agredir, você pode fazer dois ataques, um com cada arma. Se fizer isso, sofre –2 em todos os testes de ataque até o seu próximo turno.', 'Des 2'),
(25, 2, 'Arqueiro', 'Se estiver usando uma arma de ataque à distância, você soma sua Sabedoria em rolagens de dano (limitado pelo seu nível).', 'Sab 1'),
(26, 2, 'Ataque Reflexo', 'Se um alvo em alcance de seus ataques corpo a corpo ficar desprevenido ou se mover voluntariamente para fora do seu alcance, você pode gastar 1 PM para fazer um ataque corpo a corpo contra esse alvo (apenas uma vez por alvo a cada rodada).', 'Des 1'),
(27, 2, 'Aumento de Atributo', 'Você recebe +1 em um atributo. Você pode escolher este poder várias vezes, mas apenas uma vez por patamar para o mesmo atributo.', NULL),
(28, 2, 'Bater e Correr', 'Quando faz uma investida, você pode continuar se movendo após o ataque, até o limite de seu deslocamento. Se gastar 2 PM, pode fazer uma investida sobre terreno difícil e sem sofrer a penalidade de Defesa.', NULL),
(29, 2, 'Destruidor', 'Quando causa dano com uma arma corpo a corpo de duas mãos, você pode rolar novamente qualquer resultado 1 ou 2 da rolagem de dano da arma.', 'For 1'),
(30, 2, 'Esgrimista', 'Quando usa uma arma corpo a corpo leve ou ágil, você soma sua Inteligência em rolagens de dano (limitado pelo seu nível).', 'Int 1'),
(31, 2, 'Especialização em Arma', 'Escolha uma arma. Você recebe +2 em rolagens de dano com essa arma. Você pode escolher este poder outras vezes para armas diferentes.', NULL),
(32, 2, 'Especialização em Armadura', 'Você recebe redução de dano 5 se estiver usando uma armadura pesada.', '12º nível de guerreiro'),
(33, 2, 'Golpe de Raspão', 'Uma vez por rodada, quando erra um ataque, você pode gastar 2 PM. Se fizer isso, causa metade do dano que causaria (ignorando efeitos que se aplicariam caso o ataque acertasse).', NULL),
(34, 2, 'Golpe Demolidor', 'Quando usa a manobra quebrar ou ataca um objeto, você pode gastar 2 PM para ignorar a redução de dano dele.', NULL),
(35, 2, 'Golpe Pessoal', 'Quando faz um ataque, você pode desferir seu Golpe Pessoal, uma técnica única, com efeitos determinados por você. (Veja a lista completa de efeitos no livro, ex: Amplo, Brutal, Conjurador, etc.)', '5º nível de guerreiro'),
(36, 3, 'Armadura Brilhante', 'Você pode usar seu Carisma na Defesa quando usa armadura pesada. Se fizer isso, não pode somar sua Destreza, mesmo que outras habilidades ou efeitos permitam isso.', '8º nível de nobre'),
(37, 3, 'Aumento de Atributo', 'Você recebe +1 em um atributo. Você pode escolher este poder várias vezes, mas apenas uma vez por patamar para o mesmo atributo.', NULL),
(38, 3, 'Autoridade Feudal', 'Você pode gastar uma hora e 2 PM para conclamar o povo a ajudá-lo. Isso conta como um parceiro iniciante de um tipo a sua escolha (aprovado pelo mestre) que lhe acompanha até o fim da aventura.', '6º nível de nobre'),
(39, 3, 'Educação Privilegiada', 'Você se torna treinado em duas perícias de nobre a sua escolha.', NULL),
(40, 3, 'Estrategista', 'Você pode direcionar aliados em alcance curto. Gaste uma ação padrão e 1 PM por aliado (limitado pelo seu Carisma). No próximo turno do aliado, ele ganha uma ação de movimento.', 'Int 1, treinado em Guerra, 6º nível de nobre'),
(41, 3, 'Favor', 'Você pode usar sua influência para pedir favores a pessoas poderosas. Gaste 5 PM e uma hora de conversa. Role Diplomacia (CD 20). Se passar, consegue o favor. Se falhar, não pode pedir novamente por uma semana.', NULL),
(42, 3, 'General', 'Quando você usa o poder Estrategista, aliados direcionados recebem 1d4 PM temporários. Esses PM duram até o fim do turno do aliado.', 'Estrategista, 12º nível de nobre'),
(43, 3, 'Grito Típico', 'Você pode gastar um PM para usar Palavras Afiadas como uma ação completa, em vez de padrão. Se fizer isso, seus dados de dano aumentam para d8 e você atinge todos os inimigos em alcance curto.', '8º nível de nobre'),
(44, 3, 'Inspirar Confiança', 'Sua presença faz as pessoas darem o melhor de si. Quando um aliado em alcance curto faz um teste, você pode gastar 2 PM para fazer com que ele possa rolar esse teste novamente.', NULL),
(45, 3, 'Inspirar Glória', 'Quando você usa Inspirar Confiança, pode gastar +1 PM para fazer o aliado rolar novamente um teste de ataque ou resistência. Se o aliado passar no teste, você pode gastar 5 PM para que ele ganhe uma ação padrão adicional no próximo turno dele.', 'Inspirar Confiança, 8º nível de nobre'),
(46, 3, 'Jogo da Corte', 'Você pode gastar 1 PM para rolar novamente um teste recém realizado de Diplomacia, Intuição ou Nobreza.', NULL),
(47, 3, 'Liderar pelo Exemplo', 'Você pode gastar 2 PM para servir de inspiração. Até o início de seu próximo turno, sempre que você passar em um teste de perícia, aliados em alcance curto que fizerem um teste da mesma perícia podem usar o resultado do seu teste em vez de seu próprio.', '6º nível de nobre'),
(48, 3, 'Língua de Ouro', 'Você pode gastar uma ação padrão e 4 PM para gerar o efeito da magia Enfeitiçar com os aprimoramentos de Sugestão e Afetar Criatura. A CD é baseada em Carisma.', 'Língua de Prata, 8º nível de nobre'),
(49, 3, 'Língua de Prata', 'Quando faz um teste de perícia baseada em Carisma, você pode gastar 2 PM para receber um bônus no teste igual à metade do seu nível.', NULL),
(50, 3, 'Língua Rápida', 'Quando faz um teste de Diplomacia para mudar atitude, pode gastar uma ação completa, em vez de uma minuto.', NULL),
(51, 3, 'Presunção Aristocrática', 'Sua Presença Aristocrática passa a funcionar contra qualquer criatura com valor de Inteligência (incluindo criaturas sem mente), mas não funcionando contra criaturas Int -).', 'Presença Aristocrática, 16º nível de nobre'),
(52, 3, 'Título', 'Você adquire um título de nobreza. Você recebe 20 T$ no início de cada aventura e um servo (parceiro iniciante).', NULL),
(53, 3, 'Voz Poderosa', 'Você recebe +2 em Diplomacia e Intimidação. Suas habilidades de nobre com alcance curto passam para alcance médio.', NULL),
(54, 4, 'Aumento de Atributo', 'Você recebe +1 em um atributo. Pode ser escolhido várias vezes.', NULL),
(55, 4, 'Arcano de Batalha', 'Quando lança uma magia, você soma seu atributo-chave na rolagem de dano.', NULL),
(56, 4, 'Caldeirão do Bruxo', 'Você pode criar poções de até 5º círculo com magias que conhece.', 'Bruxo, treinado em Ofício (alquimista)'),
(57, 4, 'Conhecimento Mágico', 'Você aprende duas magias de qualquer círculo que possa lançar.', NULL),
(58, 4, 'Contramágica Aprimorada', 'Uma vez por rodada, pode fazer uma contramágica como uma reação (Requer 3º círculo).', 'Pré-requisito: Disputar Magia, 6º nível de arcanista'),
(59, 4, 'Escriba Arcano', 'Você pode aprender magias copiando de pergaminhos. Custo: T$ 250 em matéria-prima por PM (1º círculo).', 'Mago, treinado em Ofício (escriba)'),
(60, 4, 'Especialista em Escola', 'Escolha uma escola de magia. A CD para resistir a ela aumenta em +2. Pré-requisito: Bruxo ou Mago.', NULL),
(61, 4, 'Familiar', 'Você possui um animal de estimação mágico (Borboleta, Cobra, Coruja, Gato, etc.).', NULL),
(62, 4, 'Fluxo de Magia', 'Pode manter dois efeitos sustentados ativos simultaneamente com uma ação livre.', '10º nível de arcanista'),
(63, 4, 'Foco Vital', 'Se estiver segurando seu foco e sofrer dano que o levaria a 0 PV ou menos, você fica com 1 PV. Pré-requisito: Bruxo.', NULL),
(64, 4, 'Fortalecimento Arcano', 'A CD para resistir a suas magias aumenta em +1. Você pode lançar magias de 5º círculo, em vez disso, ela aumenta em +2.', '5º nível de arcanista'),
(65, 4, 'Herança Aprimorada', 'Você recebe a herança aprimorada de sua linhagem sobrenatural.', 'Feiticeiro, 6º nível de arcanista'),
(66, 4, 'Herança Superior', 'Você recebe a herança superior de sua linhagem sobrenatural.', 'Herança Aprimorada, 11º nível de arcanista'),
(67, 4, 'Magia Pungente', 'Quando lança uma magia, pode pagar 1 PM para aumentar em +2 a CD para resistir a ela.', NULL),
(68, 4, 'Mestre em Escola', 'O custo para lançar magias dessa escola diminui em –1 PM.', 'Especialista em Escola, 8º nível de arcanista'),
(69, 4, 'Poder Mágico', 'Você recebe +1 ponto de mana por nível de arcanista.', NULL),
(70, 4, 'Raio Arcano', 'Você pode gastar uma ação padrão para causar 1d8 de dano de essência (aumenta com PM).', 'Bruxo'),
(71, 4, 'Linhagem Sobrenatural (Básica)', 'Escolha uma linhagem (Dracônica, Feérica, Rubra). Você recebe a herança básica dela.', 'Feiticeiro'),
(72, 6, 'Alma de Bronze', 'Quando entra em fúria, você recebe PV temporários igual ao seu nível + Força.', NULL),
(73, 6, 'Aumento de Atributo', 'Você recebe +1 em um atributo. Pode ser escolhido várias vezes.', NULL),
(74, 6, 'Brado Assustador', 'Você pode gastar uma ação de movimento e 1 PM para gritar. Inimigos em alcance curto ficam vulneráveis até o fim da cena.', 'Treinado em Intimidação'),
(75, 6, 'Crítico Brutal', 'Seu multiplicador de crítico com armas corpo a corpo e de arremesso aumenta em +1.', '6º nível de bárbaro'),
(76, 6, 'Destruidor', 'Quando causa dano com uma arma corpo a corpo de duas mãos, pode rolar novamente qualquer resultado 1 ou 2 das rolagens de dano.', 'For 1'),
(77, 6, 'Espírito Inquebrável', 'Enquanto está em fúria, você não fica inconsciente por estar com 0 PV ou menos.', NULL),
(78, 6, 'Animais Totêmicos', 'Escolha um animal (Coruja, Falcão, Grifo, Lobo, Urso, etc.) e recebe seu bônus.', NULL),
(79, 6, 'Esquiva Sobrenatural', 'Seus instintos o protegem. Você nunca fica surpreendido.', NULL),
(80, 6, 'Força Indomável', 'Pode gastar 1 PM para somar seu nível em um teste de Força ou Atletismo.', NULL),
(81, 6, 'Fúria Raivosa', 'Sua fúria se torna mais mortal. Se terminar seu turno sem ter atacado, pode gastar 1 PM para manter a fúria.', NULL),
(82, 6, 'Fúria da Savana', 'Seu deslocamento aumenta em +3m. Quando usa fúria, aplica o bônus em Atletismo e Fortitude.', NULL),
(83, 6, 'Golpe Poderoso', 'Ao acertar um ataque corpo a corpo, pode causar dano extra (+1d12 para duas mãos, +1d6 para uma mão) pelo custo de +1d6 de dano de impacto em você mesmo.', NULL),
(84, 6, 'Investida Imprudente', 'Ao fazer uma investida, você pode aumentar sua penalidade na Defesa pela investida para -5 para receber um bônus de +1d12 na rolagem de dano.', NULL),
(85, 6, 'Pele de Aço', 'O bônus de Pele de Ferro aumenta para +4.', 'Pele de Ferro, 8º nível de bárbaro'),
(86, 6, 'Pele de Ferro', 'Você recebe +4 na Defesa, mas apenas se não estiver usando armadura pesada.', NULL),
(87, 6, 'Sangrar dos Inimigos', 'Enquanto em fúria, quando faz um acerto crítico, o inimigo sangra (1d6 dano por rodada).', NULL),
(88, 6, 'Superstição', 'Você odeia magia e recebe +5 em testes de resistência contra elas.', NULL),
(89, 6, 'Totem Espiritual', 'Você soma sua Sabedoria no seu total de pontos de mana.', 'Sab 1, 4º nível de bárbaro'),
(90, 6, 'Vigor Primal', 'Você pode gastar uma ação de movimento e 1 PM para recuperar 1d12 pontos de vida.', NULL),
(142, 10, 'Aspecto da Invernia', 'Você aprende magias de Convocação, Evocação, Arcana ou Divina, de qualquer círculo que possa lançar. Custo +1 PM, RD 5 Frio e +1 dano de gelo.', NULL),
(143, 10, 'Aspecto do Outono', 'Você aprende magias de Necromancia, Arcana ou Divina, de qualquer círculo que possa lançar. Custo +1 PM, -2 em testes de resistência de inimigos.', NULL),
(144, 10, 'Aspecto da Primavera', 'Você aprende magias de Encantamento ou Ilusão, Arcana ou Divina, de qualquer círculo que possa lançar. Custo +1 PM, +2 Carisma.', NULL),
(145, 10, 'Aspecto do Verão', 'Você aprende magias de Transmutação, Arcana ou Divina, de qualquer círculo que possa lançar. Custo +1 PM, +1d6 dano de fogo.', NULL),
(146, 10, 'Aumento de Atributo', 'Você recebe +1 em um atributo. Pode ser escolhido várias vezes.', NULL),
(147, 10, 'Companheiro Animal', 'Você recebe um companheiro animal (parceiro).', 'Car 1, treinado em Adestramento'),
(148, 10, 'Companheiro Animal Aprimorado', 'Seu companheiro animal se torna veterano (+3 Defesa, +1d12 rolagens de dano).', 'Companheiro Animal, 6º nível de druida'),
(149, 10, 'Forma Selvagem', 'Você pode gastar uma ação completa e 3 PM para se transformar em um animal. Você recebe +1d6 em ataques desarmados, +1 na Defesa e +10 em testes de Furtividade.', '6º nível de druida'),
(150, 10, 'Magia Natural', 'Em forma selvagem, você pode lançar magias e empunhar catalisadores.', 'Forma Selvagem'),
(151, 10, 'Presas Afiadas', 'A margem de ameaça de suas armas naturais aumenta em +2.', NULL),
(152, 10, 'Segredos da Natureza', 'Você aprende duas magias de qualquer círculo que possa lançar (devem pertencer às escolas do druida).', NULL),
(153, 10, 'Tranquilidade dos Lagos', 'Você recebe +2 em Vontade. Pode gastar 1 PM para refazer um teste de resistência.', NULL),
(154, 11, 'Agite Antes de Usar', 'Você pode gastar 1 PM para arremessar um item alquímico como ação padrão, causando +1d6 de dano do mesmo tipo.', 'treinado em Ofício (alquimista)'),
(155, 11, 'Ajuste de Mira', 'Você pode gastar uma ação padrão e PM (limitado pela Int) para aprimorar uma arma. Para cada PM, +1 em ataques e rolagens de dano.', 'Balística'),
(156, 11, 'Alquimista de Batalha', 'Você pode preparar uma poção ou veneno com uma ação padrão (pagando o custo).', 'Alquimista Iniciado'),
(157, 11, 'Alquimista Iniciado', 'Você recebe um livro de fórmulas e aprende 3 fórmulas de 1º círculo. A cada nível ímpar, aprende uma fórmula de círculo maior.', 'Int 1, Sab 1, treinado em Ofício (alquimista)'),
(158, 11, 'Ativação Rápida', 'Ativar uma engenhoca exige uma ação padrão, mas você pode pagar 2 PM para ativá-la como ação de movimento.', 'Engenhoqueiro, 7º nível de inventor'),
(159, 11, 'Autômato', 'Você fabrica um autômato (parceiro) que o auxilia.', 'Engenhoqueiro'),
(160, 11, 'Balística', 'Você recebe proficiência com armas marciais de ataque à distância e de fogo. Pode usar Inteligência em vez de Destreza nos testes de ataque.', 'treinado em Pontaria e Ofício (armeiro)'),
(161, 11, 'Blindagem', 'Você pode usar sua Inteligência na Defesa quando usa armadura pesada.', 'Couraceiro, 8º nível de inventor'),
(162, 11, 'Conhecimento de Fórmulas', 'Você aprende três fórmulas de quaisquer círculos que possa aprender.', 'Alquimista Iniciado'),
(163, 11, 'Couraceiro', 'Você recebe proficiência com armaduras pesadas e escudos. Pode usar Inteligência em vez de Destreza na Defesa (mas continua não podendo somar Int na Defesa).', 'treinado em Ofício (armeiro)'),
(164, 11, 'Engenhoqueiro', 'Você pode fabricar engenhocas (itens mágicos).', 'Int 3, treinado em Ofício (engenhoqueiro)'),
(165, 11, 'Farmacêutico', 'Quando usa um item alquímico que cura PV, ele cura +1d6 PV para cada PM gasto.', 'Sab 1, treinado em Ofício (alquimista)'),
(166, 12, 'Acrobata Arcano', 'Quando usa Acrobacia para evitar um ataque, você pode gastar 1 PM para receber +5 no teste.', 'Truque Mágico, 7º nível de ladino'),
(167, 12, 'Assassinar', 'Você pode gastar 3 PM para analisar uma criatura. No próximo turno, seu primeiro Ataque Furtivo contra ela tem os dados de dano dobrados.', '5º nível de ladino'),
(168, 12, 'Aumento de Atributo', 'Você recebe +1 em um atributo. Pode ser escolhido várias vezes.', NULL),
(169, 12, 'Contatos no Submundo', 'Você pode gastar 2 PM para encontrar um contato no submundo, recebendo +5 em Investigação e descontos em itens.', NULL),
(170, 12, 'Emboscar', 'Na primeira rodada de um combate, pode gastar 2 PM para executar uma ação padrão adicional.', 'treinado em Furtividade'),
(171, 12, 'Escapista', 'Você recebe +5 em testes de Acrobacia para escapar de agarrão e efeitos de movimento.', NULL),
(172, 12, 'Fuga Formidável', 'Você pode gastar uma ação completa e 1 PM para analisar um local. Até o fim da cena, recebe +3m em deslocamento e +5 em Acrobacia e Atletismo.', NULL),
(173, 12, 'Gatuno', 'Você recebe +2 em Atletismo. Quando escala, não fica desprevenido e seu deslocamento é reduzido apenas pela metade.', NULL),
(174, 12, 'Ladrão Arcano', 'Quando causa dano com Ataque Furtivo, pode gastar 1 PM para "roubar" uma magia do alvo (deve passar em teste de Vontade).', 'Roubo de Magia, 13º nível de ladino'),
(175, 12, 'Mão na Boca', 'Quando acerta um ataque furtivo contra uma criatura desprevenida, pode tentar agarrá-la como ação livre.', 'treinado em Luta'),
(176, 12, 'Mãos Rápidas', 'Uma vez por rodada, pode gastar 1 PM para fazer um teste de Ladinagem como ação livre.', 'Des 2, treinado em Ladinagem'),
(177, 12, 'Mente Criminosa', 'Você soma sua Inteligência em Ladinagem e Furtividade.', 'Int 1'),
(178, 12, 'Oportunismo', 'Uma vez por rodada, quando um inimigo adjacente sofre dano de um de seus aliados, pode gastar 2 PM para fazer um ataque corpo a corpo contra ele.', '6º nível de ladino'),
(179, 12, 'Rolamento Defensivo', 'Quando sofre dano, pode gastar 2 PM para reduzir esse dano à metade. Se passar em um teste de Reflexos (CD 15), fica caído.', 'treinado em Reflexos'),
(180, 12, 'Roubo de Magia', 'Quando causa dano com Ataque Furtivo, pode gastar 1 PM para roubar 1 PM temporário do alvo.', NULL),
(181, 12, 'Saqueador de Tumbas', 'Você recebe +5 em testes de Investigação para encontrar armadilhas e em testes de resistência contra elas.', 'Sab 2, treinado em Investigação'),
(182, 12, 'Sombra', 'Você recebe +2 em Furtividade, e não sofre penalidade em testes de Furtividade por se mover no mesmo turno.', 'treinado em Furtividade'),
(183, 12, 'Truque Mágico', 'Você aprende e pode lançar uma magia arcana de 1º círculo (atributo-chave Int).', 'Int 1'),
(184, 12, 'Velocidade Ladina', 'Uma vez por rodada, pode gastar 2 PM para realizar uma ação de movimento adicional.', 'Des 2, treinado em Iniciativa'),
(185, 12, 'Veneno Persistente', 'A CD para resistir aos seus venenos aumenta em +2.', 'Veneno Potente, 8º nível de ladino'),
(186, 12, 'Veneno Potente', 'A CD para resistir aos seus venenos aumenta em +2.', 'treinado em Ofício (alquimista)'),
(187, 13, 'Arma Improvisada', 'Você pode gastar uma ação de movimento para procurar uma arma improvisada. Se passar no teste de Percepção (CD 20), encontra uma arma que causa +1d6 de dano.', NULL),
(188, 13, 'Até Acertar', 'Se você errar um ataque desarmado, recebe +2 cumulativo em testes de ataque e rolagens de dano contra esse oponente (até acertar).', NULL),
(189, 13, 'Aumento de Atributo', 'Você recebe +1 em um atributo. Pode ser escolhido várias vezes.', NULL),
(190, 13, 'Braços Calejados', 'Se não estiver usando armadura, soma sua Força na Defesa (limitado pelo seu nível).', NULL),
(191, 13, 'Cabeçada', 'Quando faz um ataque desarmado, pode gastar 2 PM. Se fizer isso, o oponente não pode fazer ataques de oportunidade contra você até seu próximo turno.', NULL),
(192, 13, 'Chave', 'Se estiver agarrando uma criatura, pode gastar 2 PM e uma ação padrão para fazer um teste de manobra (Luta). Se vencer, a criatura fica imobilizada.', 'Int 1, Lutador 4º'),
(193, 13, 'Confiança dos Ringues', 'Quando um inimigo erra um ataque corpo a corpo em você, você recebe 2 PM temporários (cumulativos).', '6º nível de lutador'),
(194, 13, 'Convencido', 'Você recebe +5 em resistência a medo e intimidação.', NULL),
(195, 13, 'Golpe Baixo', 'Quando faz um ataque desarmado, pode gastar 2 PM. O oponente deve fazer um teste de Fortitude (CD For); se falhar, fica atordoado por uma rodada.', NULL),
(196, 13, 'Golpe Imprudente', 'Quando usa o Golpe Relâmpago, pode atacar de forma impulsiva. Seus ataques desarmados recebem +1d6 de dano, mas você sofre -5 na Defesa até o início do seu próximo turno.', NULL),
(197, 13, 'Imobilização', 'Se estiver agarrando uma criatura, pode gastar uma ação completa e 2 PM para imobilizá-la.', 'Chave, 8º nível de lutador'),
(198, 13, 'Língua dos Becos', 'Você pode usar Carisma para testes de Intimidação (em vez de Força).', 'Treinado em Intimidação'),
(199, 13, 'Listado de Chão', 'Você recebe +2 em testes de ataque para agarrar e derrubar. Quando agarrar, pode gastar 1 PM para fazer uma manobra derrubar como ação livre.', NULL),
(200, 13, 'Punho de Adamante', 'Seus ataques desarmados ignoram 10 pontos de RD do alvo.', '8º nível de lutador'),
(201, 13, 'Rasteira', 'Quando faz um ataque desarmado contra uma criatura maior que você, pode gastar 2 PM. Se acertar, a criatura fica caída.', NULL),
(202, 13, 'Sarado', 'Você soma sua Força no seu total de pontos de vida e em Fortitude.', 'For 1'),
(203, 13, 'Sequência Destruidora', 'No início do seu turno, pode gastar 2 PM para dizer um número (no mínimo 2). Seus ataques desarmados recebem +1 bônus cumulativo no dano (ex: +1, +2, +3...).', NULL),
(204, 13, 'Trocação', 'Quando você começa a bater, não para mais. Ao acertar um ataque desarmado, pode gastar PM (até o limite do bônus de Briga) para fazer ataques extras, mas sofre penalidade cumulativa de -5.', '6º nível de lutador'),
(205, 13, 'Trocação Tumultuosa', 'Quando usa a ação agredir para fazer um ataque desarmado, pode gastar 2 PM para atingir todas as criaturas adjacentes (incluindo aliados).', 'Trocação, 8º nível de lutador'),
(206, 13, 'Valentão', 'Você recebe +2 em testes de ataque e rolagens de dano contra oponentes caídos, desprevenidos, flanqueados ou indefesos.', NULL),
(207, 13, 'Voadora', 'Quando faz uma investida desarmada, pode gastar 2 PM. Se fizer isso, causa +1d6 de dano para cada 3m que se deslocar (máx. +5d6).', NULL),
(208, 14, 'Arma Sagrada', 'Quando usa Golpe Divino, o dano de luz aumenta em um passo (d8 vira d10, etc).', 'Devoto de Azgher, Khalmyr, Lena ou Thyatis'),
(209, 14, 'Aumento de Atributo', 'Você recebe +1 em um atributo. Pode ser escolhido várias vezes.', NULL),
(210, 14, 'Aura Ardente', 'Enquanto sua aura estiver ativa, no início de seus turnos, oponentes em alcance curto sofrem dano de luz igual ao seu Carisma.', '10º nível de paladino'),
(211, 14, 'Aura de Cura', 'Enquanto sua aura estiver ativa, no início de seus turnos, você e seus aliados em alcance curto curam PV igual ao seu Carisma.', '6º nível de paladino'),
(212, 14, 'Aura de Invencibilidade', 'Enquanto sua aura estiver ativa, você ignora o primeiro dano que sofrer na cena.', '18º nível de paladino'),
(213, 14, 'Aura Poderosa', 'O raio da sua aura aumenta para 9m.', '6º nível de paladino'),
(214, 14, 'Julgamento Divino: Autoridade', 'Você pode gastar 1 PM para comandar uma criatura (Vontade anula).', NULL),
(215, 14, 'Julgamento Divino: Coragem', 'Você pode gastar 2 PM para inspirar coragem. Você e aliados em alcance curto ficam imunes a medo.', NULL),
(216, 14, 'Julgamento Divino: Iluminação', 'Você pode gastar 2 PM para marcar um inimigo. Quando o acerta, ele recebe 2 PM temporários (uma vez por cena).', NULL),
(217, 14, 'Julgamento Divino: Justiça', 'Você pode gastar 2 PM para marcar um inimigo. A próxima vez que ele causar dano em você ou um aliado, deve fazer um teste de Vontade (CD Car) ou sofre dano de luz igual à metade do dano que causou.', NULL),
(218, 14, 'Julgamento Divino: Libertação', 'Você pode gastar 5 PM para cancelar uma condição negativa (como abalado, paralisado, etc.) que esteja afetando uma criatura em alcance curto.', NULL),
(219, 14, 'Julgamento Divino: Salvação', 'Você pode gastar 2 PM para marcar um inimigo. Você e seus aliados recuperam 5 PV quando o acertam.', NULL),
(220, 14, 'Julgamento Divino: Vindicação', 'Você pode gastar 2 PM para marcar um inimigo. Você e aliados recebem +1d8 em testes de ataque e dano contra ele.', NULL),
(221, 14, 'Julgamento Divino: Zelo', 'Você pode gastar 1 PM para marcar um alvo em alcance longo. Você se move em direção a ele no início de cada turno.', NULL),
(222, 14, 'Orar', 'Você pode aprender magias divinas de 1º círculo (atributo-chave Sabedoria).', 'Sab 1'),
(223, 14, 'Virtude Paladinesca: Caridade', 'O custo de suas habilidades de paladino (como Golpe Divino) que tenham um aliado como alvo é reduzido em -1 PM.', NULL),
(224, 14, 'Virtude Paladinesca: Compaixão', 'Você pode usar Cura pelas Mãos como ação padrão e, para cada PM gasto, curar 2d6+1 PV.', NULL),
(225, 14, 'Virtude Paladinesca: Humildade', 'Na primeira rodada de um combate, pode gastar uma ação completa para rezar e pedir orientação. Você recebe PM temporários igual ao seu Carisma.', NULL),
(226, 14, 'Bênção da Justiça', 'No 5º nível, você pode escolher entre Égide Sagrada ou Montaria Sagrada.', '5º nível de paladino'),
(227, 14, 'Égide Sagrada', 'Você pode gastar 2 PM para receber o bônus de seu escudo na Defesa e Reflexos de aliados adjacentes.', 'Bênção da Justiça'),
(228, 14, 'Montaria Sagrada', 'Você pode gastar 2 PM para invocar uma montaria sagrada (parceiro).', 'Bênção da Justiça');

-- --------------------------------------------------------

--
-- Estrutura da tabela `t20_poderes_divinos`
--

CREATE TABLE IF NOT EXISTS `t20_poderes_divinos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(191) NOT NULL,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=73 ;

--
-- Extraindo dados da tabela `t20_poderes_divinos`
--

INSERT INTO `t20_poderes_divinos` (`id`, `nome`, `descricao`) VALUES
(1, 'Afinidade com a Tormenta', 'Você recebe +10 em testes de resistência contra efeitos da Tormenta, de suas criaturas e de devotos de Aharadak. Além disso, seu primeiro poder da Tormenta não conta para perda de Carisma.'),
(2, 'Almejar o Impossível', 'Quando faz um teste de perícia, um resultado 19 ou mais no dado sempre é um sucesso, não importando o valor a ser alcançado.'),
(3, 'Anfíbio', 'Você pode respirar embaixo d''água e adquire deslocamento de natação igual a seu deslocamento terrestre.'),
(4, 'Apostar com o Trapaceiro', 'Quando faz um teste de perícia, você pode gastar 1 PM para apostar com Hyninn. Você e o mestre rolam 1d20; se o seu resultado for maior, você passa no teste (mesmo que o resultado rolado tenha sido uma falha). Se for menor, falha (mesmo que tenha sido um sucesso). Se empatar, o mestre escolhe se você passa ou falha. Você não pode usar esta habilidade em um teste que já tenha rolado novamente.'),
(5, 'Armas da Ambição', 'Você recebe +1 em testes de ataque e na margem de ameaça com armas nas quais é proficiente.'),
(6, 'Arsenal das Profundezas', 'Você recebe +2 nas rolagens de dano com azagaias, lanças e tridentes e seu multiplicador de crítico com essas armas aumenta em +1.'),
(7, 'Astúcia da Serpente', 'Você recebe +2 em Enganação, Furtividade e Intuição.'),
(8, 'Ataque Piedoso', 'Você pode usar armas corpo a corpo para causar dano não letal sem sofrer a penalidade de –5 no teste de ataque.'),
(9, 'Aura de Medo', 'Você pode gastar 2 PM para gerar uma aura de medo de 9m de raio e duração até o fim da cena. Todos os inimigos que entrarem na aura devem fazer um teste de Vontade (CD Car) ou ficam abalados até o fim da cena. Uma criatura que passe no teste de Vontade fica imune a esta habilidade por um dia.'),
(10, 'Aura de Paz', 'Você pode gastar 2 PM para gerar uma aura de paz com alcance curto e duração de uma cena. Qualquer inimigo dentro da aura que tente fazer uma ação hostil contra você deve fazer um teste de Vontade (CD Car). Se falhar, perderá sua ação. Se passar, fica imune a esta habilidade por um dia.'),
(11, 'Aura Restauradora', 'Efeitos de cura usados por você e seus aliados em alcance curto recuperam +1 PV por dado.'),
(12, 'Bênção do Mana', 'Você recebe +1 PM a cada nível ímpar.'),
(13, 'Carícia Sombria', 'Você pode gastar 1 PM e uma ação padrão para cobrir sua arma com energia negativa e trocar seu dano para trevas (Fortitude CD Sab reduz à metade) e você recupera PV iguais à metade do dano causado. Você pode aprender Toque Vampírico como uma magia divina. Se fizer isso, seu custo diminui em –1 PM.'),
(14, 'Centelha Mágica', 'Escolha uma magia arcana ou divina de 1º círculo. Você aprende e pode lançar essa magia.'),
(15, 'Compreender os Ermos', 'Você recebe +2 em Sobrevivência e pode usar Sabedoria para Adestramento (em vez de Carisma).'),
(16, 'Conhecimento Enciclopédico', 'Você se torna treinado em duas perícias baseadas em Inteligência a sua escolha.'),
(17, 'Conjurar Arma', 'Você pode gastar 1 PM para invocar uma arma corpo a corpo ou de arremesso com a qual seja proficiente. A arma surge em sua mão, fornece +1 em testes de ataque e rolagens de dano, e é considerada mágica.'),
(18, 'Coragem Total', 'Você é imune a efeitos de medo, mágicos ou não. Este poder não anula fobias raciais (como o medo de altura dos minotauros).'),
(19, 'Cura Gentil', 'Você soma seu Carisma aos PV restaurados por seus efeitos mágicos de cura.'),
(20, 'Curandeira Perfeita', 'Você sempre pode escolher 10 em testes de Cura. Além disso, não sofre penalidade por usar esta perícia sem um kit de medicamentos. Se possuir o item, recebe +2 no teste de Cura (ou +5, se ele for aprimorado).'),
(21, 'Dedo Verde', 'Você aprende e pode lançar Controlar Plantas. Caso aprenda novamente esta magia, seu custo diminui em –1 PM.'),
(22, 'Descanso Natural', 'Para você, dormir ao relento conta como condição de descanso confortável.'),
(23, 'Dom da Esperança', 'Você soma sua Sabedoria em seus PV em vez de Constituição, e se torna imune às condições alquebrado, esmorecido e frustrado.'),
(24, 'Dom da Imortalidade', 'Você é imortal. Sempre que morre, não importando o motivo, volta à vida após 3d6 dias. Apenas paladinos podem escolher este poder. Um personagem pode ter Dom da Imortalidade ou Dom da Ressurreição, mas não ambos.'),
(25, 'Dom da Profecia', 'Você pode lançar Augúrio. Caso aprenda novamente esta magia, seu custo diminui em –1 PM. Você também pode gastar 2 PM para receber +2 em um teste.'),
(26, 'Dom da Ressurreição', 'Você pode gastar uma ação completa e todos os PM que possui (mínimo 1 PM) para tocar o corpo de uma criatura morta há menos de um ano e ressuscitá-la. A criatura volta à vida com 1 PV e 0 PM, e perde 1 ponto de Constituição permanentemente.'),
(27, 'Dom da Verdade', 'Você pode pagar 2 PM para receber +5 em testes de Intuição, e em testes de Percepção contra Enganação e Furtividade, até o fim da cena.'),
(28, 'Escamas Dracônicas', 'Você recebe +2 na Defesa e em Fortitude.'),
(29, 'Escudo Mágico', 'Quando lança uma magia, você recebe um bônus na Defesa igual ao círculo da magia lançada até o início do seu próximo turno.'),
(30, 'Espada Justiceira', 'Você pode gastar 1 PM para encantar sua espada (ou outra arma corpo a corpo de corte que esteja empunhando). Ela tem seu dano aumentado em um passo até o fim da cena.'),
(31, 'Espada Solar', 'Você pode gastar 1 PM para fazer uma arma corpo a corpo de corte que esteja empunhando causar +1d6 de dano por fogo até o fim da cena.'),
(32, 'Êxtase da Loucura', 'Toda vez que uma ou mais criaturas falham em um teste de Vontade contra uma de suas habilidades mágicas, você recebe 1 PM temporário cumulativo. Você pode ganhar um máximo de PM temporários por cena desta forma igual a sua Sabedoria.'),
(33, 'Familiar Ofídico', 'Você recebe um familiar cobra (veja a página 38) que não conta em seu limite de parceiros.'),
(34, 'Farsa do Fingidor', 'Você aprende e pode lançar Criar Ilusão. Caso aprenda novamente esta magia, seu custo diminui em –1 PM.'),
(35, 'Fé Guerreira', 'Você pode usar Sabedoria para Guerra (em vez de Inteligência). Além disso, em combate, pode gastar 2 PM para substituir um teste de perícia (exceto testes de ataque) por um teste de Guerra.'),
(36, 'Forma de Macaco', 'Você pode gastar uma ação completa e 2 PM para transformar-se em um macaco. Você adquire tamanho Mínusculo (o que fornece +5 em Furtividade e –5 em testes de manobra) e recebe deslocamento de escalar 9m. Seus equipamentos desaparecem (você perde seus benefícios) e você volta ao normal se suas estatísticas não são alteradas. A transformação dura indefinidamente, mas termina caso você faça um ataque, lance uma magia ou sofra dano.'),
(37, 'Fulgor Solar', 'Você recebe redução de frio e trevas 5. Além disso, quando é alvo de um ataque, pode gastar 1 PM para emitir um clarão solar que deixa o atacante ofuscado por uma rodada.'),
(38, 'Fúria Divina', 'Você pode gastar 2 PM para invocar uma fúria selvagem, tornando-se temível em combate. Até o fim da cena, você recebe +2 em testes de ataque e rolagens de dano corpo a corpo, mas não pode executar nenhuma ação que exija paciência ou concentração (como usar a perícia Furtividade ou lançar magias).'),
(39, 'Golpista Divino', 'Você recebe +2 em Enganação, Jogatina e Ladinagem.'),
(40, 'Habitante do Deserto', 'Você recebe redução de fogo 10 e pode pagar 1 PM para criar água pura e potável suficiente para um odre (ou outro recipiente pequeno).'),
(41, 'Inimigo de Tenebra', 'Seus ataques e habilidades causam +1d6 pontos de dano contra mortos-vivos. Quando você usa um efeito que gera luz, o alcance da iluminação dobra.'),
(42, 'Kiai Divino', 'Uma vez por rodada, quando faz um ataque corpo a corpo, você pode pagar 3 PM. Se acertar o ataque, causa dano máximo, sem necessidade de rolar dados.'),
(43, 'Liberdade Divina', 'Você pode gastar 2 PM para receber imunidade a efeitos de movimento por uma rodada.'),
(44, 'Manto da Penumbra', 'Você aprende e pode lançar Escuridão. Caso aprenda novamente esta magia, seu custo diminui em –1 PM.'),
(45, 'Mente Analítica', 'Você recebe +2 em Intuição, Investigação e Vontade.'),
(46, 'Mente Vazia', 'Você recebe +2 em Iniciativa, Percepção e Vontade.'),
(47, 'Mestre dos Mares', 'Você pode falar com animais aquáticos (como o efeito da magia Voz Divina) e aprende e pode lançar Acalmar Animal, mas só contra criaturas aquáticas. Caso aprenda novamente esta magia, seu custo diminui em –1 PM.'),
(48, 'Olhar Amedrontador', 'Você aprende e pode lançar Amedrontar. Caso aprenda novamente esta magia, seu custo diminui em –1 PM.'),
(49, 'Palavras de Bondade', 'Você aprende e pode lançar Enfeitiçar. Caso aprenda novamente esta magia, seu custo diminui em –1 PM.'),
(50, 'Percepção Temporal', 'Você pode gastar 3 PM para somar sua Sabedoria (limitado por seu nível e não cumulativo com efeitos que somam este atributo) a seus ataques, Defesa e testes de Reflexos até o fim da cena.'),
(51, 'Pesquisa Abençoada', 'Se passar uma hora pesquisando seus livros e anotações, você pode rolar novamente um teste de perícia baseada em Inteligência ou Sabedoria que tenha feito desde a última cena. Se tiver acesso a mais livros, você recebe um bônus no teste: +2 para uma coleção particular ou biblioteca pequena e +5 para a biblioteca de um templo ou universidade.'),
(52, 'Poder Oculto', 'Você pode gastar uma ação de movimento e 2 PM para invocar a força do acaso, e rolar na tabela de Poder Oculto.'),
(53, 'Presas Primordiais', 'Você pode gastar 1 PM para transformar seus dentes em presas afiadas até o fim da cena. Você recebe uma arma natural de mordida (dano 1d6, crítico x2, perfuração). Uma vez por rodada, quando usa a ação agredir, pode gastar 1 PM para fazer um ataque corpo a corpo extra com a mordida.'),
(54, 'Presas Venenosas', 'Você pode gastar uma ação de movimento e 1 PM para envenenar uma arma corpo a corpo que esteja empunhando. Em caso de acerto, a arma causa perda de 1d12 pontos de vida. A arma permanece envenenada até atingir uma criatura ou até o fim da cena, o que acontecer primeiro.'),
(55, 'Rejeição Divina', 'Você recebe resistência a magia divina +5.'),
(56, 'Reparar Injustiça', 'Uma vez por rodada, quando um oponente em alcance curto acertar um ataque em você ou em um de seus aliados, você pode gastar 2 PM para fazer este oponente repetir o ataque, escolhendo o pior entre os dois resultados.'),
(57, 'Sangue de Ferro', 'Você pode pagar 3 PM para receber +2 em rolagens de dano e redução de dano 5 até o fim da cena.'),
(58, 'Sangue Ofídico', 'Você recebe resistência a veneno +5 e a CD para resistir aos seus venenos aumenta em +2.'),
(59, 'Servos do Dragão', 'Você pode gastar uma ação completa e 2 PM para invocar 2d4+1 kobolds capangas em espaços desocupados em alcance curto.'),
(60, 'Sopro do Mar', 'Você pode gastar uma ação padrão e 1 PM para soprar vento e chuva em um cone de 6m. Criaturas na área sofrem 2d6 pontos de dano de frio (Reflexos CD Sab reduz à metade). Você pode aprender Sopro das Uivantes como uma magia divina. Se fizer isso, o custo dela diminui em –1 PM.'),
(61, 'Sorte dos Loucos', 'Você pode pagar 1 PM para rolar novamente um teste recém realizado. Se ainda assim falhar no teste, você perde 1d6 PM.'),
(62, 'Talento Artístico', 'Você recebe +2 em Acrobacia, Atuação e Diplomacia.'),
(63, 'Teurgista Místico', 'Até uma magia de cada círculo que você aprender poderá ser escolhida entre magias divinas (se você for um conjurador arcano) ou entre magias arcanas (se for um conjurador divino). Pré-requisito: habilidade de classe Magias.'),
(64, 'Tradição de Lin-Wu', 'Você considera a katana uma arma simples e, se for proficiente em armas marciais, recebe +1 na margem de ameaça com ela.'),
(65, 'Transmissão da Loucura', 'Você pode lançar Sussurros Insanos (CD Car). Caso aprenda novamente esta magia, seu custo diminui em –1 PM.'),
(66, 'Tropas Duyshidakk', 'Você pode gastar uma ação completa e 2 PM para invocar 1d4+1 goblioides capangas em espaços desocupados em alcance curto.'),
(67, 'Urro Divino', 'Quando faz um ataque ou lança uma magia, você pode pagar 1 PM para somar sua Constituição (mínimo +1) à rolagem de dano desse ataque ou magia.'),
(68, 'Visão nas Trevas', 'Você enxerga perfeitamente no escuro, incluindo em magias de escuridão.'),
(69, 'Voz da Civilização', 'Você está sempre sob efeito de Compreensão.'),
(70, 'Voz da Natureza', 'Você pode falar com animais (como o efeito da magia Voz Divina) e aprende e pode lançar Acalmar Animal, mas só contra animais. Caso aprenda novamente esta magia, seu custo diminai em –1 PM.'),
(71, 'Voz dos Monstros', 'Você conhece os idiomas de todos os monstros inteligentes e pode se comunicar livremente com monstros não inteligentes (Int –4 ou menor), como se estivesse sob efeito da magia Voz Divina.'),
(72, 'Zumbificar', 'Você pode gastar uma ação completa e 3 PM para reanimar o cadáver de uma criatura Pequena ou Média adjacente por um dia. O cadáver funciona como um parceiro iniciante de um tipo a sua escolha entre combatente, fortão ou guardião.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `t20_poderes_gerais`
--

CREATE TABLE IF NOT EXISTS `t20_poderes_gerais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(191) NOT NULL,
  `descricao` text NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `pre_requisito` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_categoria_unique` (`nome`,`categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=68 ;

--
-- Extraindo dados da tabela `t20_poderes_gerais`
--

INSERT INTO `t20_poderes_gerais` (`id`, `nome`, `descricao`, `categoria`, `pre_requisito`) VALUES
(1, 'Acuidade com Arma', 'Quando usa uma arma corpo a corpo leve ou uma arma de arremesso, você pode usar sua Destreza em vez de Força nos testes de ataque e rolagens de dano.', 'Combate', 'Des 1'),
(2, 'Arma Secundária Grande', 'Você pode empunhar uma arma corpo a corpo de uma mão com sua mão inábil (com o poder Estilo de Duas Armas).', 'Combate', 'Estilo de Duas Armas'),
(3, 'Arremesso Potente', 'Quando usa uma arma de arremesso, você pode usar sua Força em vez de Destreza nos testes de ataque. Se você possuir o poder Ataque Poderoso, poderá usá-lo com armas de arremesso.', 'Combate', 'For 1, Estilo de Arremesso'),
(4, 'Arremesso Múltiplo', 'Uma vez por rodada, quando faz um ataque com uma arma de arremesso, você pode gastar 1 PM para fazer um ataque adicional contra o mesmo alvo, arremessando outra arma de arremesso.', 'Combate', 'Des 1, Estilo de Arremesso'),
(5, 'Ataque com Escudo', 'Uma vez por rodada, se estiver empunhando um escudo e fizer a ação agredir, você pode gastar 1 PM para fazer um ataque corpo a corpo extra com o escudo. Esse ataque não pode ser usado com o bônus do Bloqueio com Escudo na Defesa.', 'Combate', 'Estilo de Arma e Escudo'),
(6, 'Ataque Pesado', 'Quando faz um ataque corpo a corpo com uma arma de duas mãos, você pode pagar 1 PM. Se fizer isso e acertar o ataque, além do dano você pode fazer uma manobra derrubar ou empurrar contra o alvo como uma ação livre (use o resultado do ataque como o teste de manobra).', 'Combate', 'Estilo de Duas Mãos'),
(7, 'Ataque Preciso', 'Se estiver empunhando uma arma corpo a corpo em uma das mãos e nada na outra, você recebe +2 na margem de ameaça e +1 no multiplicador de crítico.', 'Combate', 'Estilo de Uma Arma'),
(8, 'Bloqueio com Escudo', 'Quando sofre dano, você pode gastar 1 PM para receber redução de dano igual ao bônus na Defesa que seu escudo fornece contra este dano. Você só pode usar este poder se estiver usando um escudo.', 'Combate', 'Estilo de Arma e Escudo'),
(9, 'Carga de Cavalaria', 'Quando faz uma investida montada, você causa +2d8 pontos de dano. Além disso, pode continuar se movendo depois do ataque, desde que se mova em linha reta e seu movimento máximo ainda seja o dobro do seu deslocamento.', 'Combate', 'Ginete'),
(10, 'Combate Defensivo', 'Quando usa a ação agredir, você pode usar este poder. Se fizer isso, até seu próximo turno, sofre –2 em todos os testes de ataque, mas recebe +5 na Defesa.', 'Combate', 'Int 1'),
(11, 'Derrubar Aprimorado', 'Você recebe +2 em testes de ataque para derrubar. Quando derruba uma criatura com esta manobra, pode gastar 1 PM para fazer um ataque extra contra ela.', 'Combate', NULL),
(12, 'Desarmar Aprimorado', 'Você recebe +2 em testes de ataque para desarmar. Quando desarma uma criatura, pode gastar 1 PM para arremessar a arma dela para longe.', 'Combate', 'Combate Defensivo'),
(13, 'Disparo Preciso', 'Você pode fazer ataques à distância contra oponentes envolvidos em combate corpo a corpo sem sofrer a penalidade de –5 no teste de ataque.', 'Combate', 'Estilo de Disparo ou Estilo de Arremesso'),
(14, 'Disparo Rápido', 'Se estiver empunhando uma arma de disparo que possa recarregar como ação livre e gastar uma ação completa para agredir, pode fazer um ataque adicional com ela. Se fizer isso, sofre –2 em todos os testes de ataque até o seu próximo turno.', 'Combate', 'Estilo de Disparo'),
(15, 'Empunhadura Poderosa', 'Ao usar uma arma feita para uma categoria de tamanho maior que a sua, a penalidade que você sofre nos testes de ataque diminui para –2 (normalmente, –5).', 'Combate', 'For 3'),
(16, 'Encouraçado', 'Se estiver usando uma armadura pesada, você recebe +2 na Defesa. Este bônus aumenta em +2 para cada outro poder que você possua que tenha Encouraçado como pré-requisito.', 'Combate', 'Proficiência com armaduras pesadas'),
(17, 'Esquiva', 'Você recebe +2 na Defesa e Reflexos.', 'Combate', 'Des 1'),
(18, 'Estilo de Arma e Escudo', 'Se estiver usando um escudo, o bônus na Defesa que ele fornece aumenta em +2.', 'Combate', 'Treinado em Luta, proficiência com escudos'),
(19, 'Estilo de Arma Longa', 'Você recebe +2 em testes de ataque com armas alongadas e pode atacar alvos adjacentes com essas armas.', 'Combate', 'For 1, treinado em Luta'),
(20, 'Estilo de Arremesso', 'Você pode sacar armas de arremesso como uma ação livre e recebe +2 nas rolagens de dano com elas. Se também possuir o poder Saque Rápido, também recebe +2 nos testes de ataque com essas armas.', 'Combate', 'Treinado em Pontaria'),
(21, 'Estilo de Disparo', 'Se estiver usando uma arma de disparo, você soma sua Destreza nas rolagens de dano.', 'Combate', 'Treinado em Pontaria'),
(22, 'Estilo de Duas Armas', 'Se estiver empunhando duas armas (e pelo menos uma delas for leve) e fizer a ação agredir, você pode fazer dois ataques, um com cada arma. Se fizer isso, sofre –2 em todos os testes de ataque até o seu próximo turno. Se possuir Ambidestria, em vez disso não sofre penalidade para usá-lo.', 'Combate', 'Des 2, treinado em Luta'),
(23, 'Estilo de Duas Mãos', 'Se estiver usando uma arma corpo a corpo com as duas mãos, você recebe +5 nas rolagens de dano. Este poder não pode ser usado com armas leves.', 'Combate', 'For 2, Treinado em Luta'),
(24, 'Estilo de Uma Arma', 'Se estiver usando uma arma corpo a corpo em uma das mãos e nada na outra, você recebe +2 na Defesa e nos testes de ataque com essa arma (exceto ataques desarmados).', 'Combate', 'Treinado em Luta'),
(25, 'Estilo Desarmado', 'Seus ataques desarmados causam 1d6 pontos de dano e podem causar dano letal ou não letal (sem penalidades).', 'Combate', 'Treinado em Luta'),
(26, 'Fanático', 'Seu deslocamento não é reduzido por usar armaduras pesadas.', 'Combate', '12º nível de personagem, Encouraçado'),
(27, 'Finta Aprimorada', 'Você recebe +2 em testes de Enganação para fintar e pode fintar como uma ação de movimento.', 'Combate', 'Treinado em Enganação'),
(28, 'Foco em Arma', 'Escolha uma arma. Você recebe +2 em testes de ataque com essa arma. Você pode escolher este poder outras vezes para armas diferentes.', 'Combate', 'Proficiência com a arma'),
(29, 'Ginete', 'Você passa automaticamente em testes de Cavalgar para não cair da montaria quando sofre dano. Além disso, não sofre penalidades para atacar à distância ou lançar magias quando montado.', 'Combate', 'Treinado em Cavalgar'),
(30, 'Inexpugnável', 'Se estiver usando uma armadura pesada, você recebe +2 em todos os testes de resistência.', 'Combate', 'Encouraçado, 6º nível de personagem'),
(31, 'Mira Apurada', 'Quando usa a ação mirar, você recebe +2 em testes de ataque e na margem de ameaça com ataques à distância até o fim do turno.', 'Combate', 'Sab 1, Disparo Preciso'),
(32, 'Piqueiro', 'Uma vez por rodada, se estiver empunhando uma arma alongada e um inimigo entrar voluntariamente em seu alcance corpo a corpo, você pode gastar 1 PM para fazer um ataque corpo a corpo contra este oponente com esta arma. Se o oponente tiver se aproximado fazendo uma investida, seu ataque causa dois dados de dano extra do mesmo tipo.', 'Combate', 'Estilo de Arma Longa'),
(33, 'Presença Aterradora', 'Você pode gastar uma ação padrão e 1 PM para assustar todas as criaturas a sua escolha em alcance curto. Veja a perícia Intimidação para as regras de assustar.', 'Combate', NULL),
(34, 'Proficiência', 'Escolha uma proficiência: armas marciais, armas de fogo, armaduras pesadas ou escudos (se for proficiente em armas marciais, você também pode escolher armas exóticas). Você recebe essa proficiência.', 'Combate', NULL),
(35, 'Quebrar Aprimorado', 'Você recebe +2 em testes de ataque para quebrar. Quando reduz os PV de uma arma para 0 ou menos, você pode gastar 1 PM para realizar um ataque extra contra o usuário dela. O ataque adicional usa os mesmos valores de ataque e dano, mas os dados devem ser rolados novamente.', 'Combate', 'Ataque Poderoso'),
(36, 'Reflexos de Combate', 'Você ganha uma ação de movimento extra no seu primeiro turno de cada combate.', 'Combate', 'Des 1'),
(37, 'Saque Rápido', 'Você recebe +2 em Iniciativa e pode sacar ou guardar itens como uma ação livre (em vez de ação de movimento). Além disso, a ação para recarregar armas de disparo diminui em uma categoria.', 'Combate', 'Treinado em Iniciativa'),
(38, 'Trespassar', 'Quando você faz um ataque corpo a corpo e reduz os pontos de vida do alvo para 0 ou menos, pode gastar 1 PM para fazer um ataque adicional contra outra criatura dentro do seu alcance.', 'Combate', 'Ataque Poderoso'),
(39, 'Vitalidade', 'Você recebe +1 PV por nível de personagem e +2 em Fortitude.', 'Combate', 'Con 1'),
(40, 'Acrobático', 'Você pode usar sua Destreza em vez de Força em testes de Atletismo. Além disso, terreno difícil não reduz seu deslocamento nem o impede de realizar investidas.', 'Destino', 'Des 2'),
(41, 'Ao Sabor do Destino', 'Confiando em suas próprias habilidades (ou em sua própria sorte), você abre mão de usar itens mágicos. Sua autoconfiança fornece diversos benefícios, de acordo com seu nível de personagem (6º: +2 perícia, 7º: +1 Defesa, 8º: +1 dano, 9º: +1 atributo, 11º: +2 perícia, 12º: +2 Defesa, 13º: +2 rolagens de dano, 14º: +1 atributo, 16º: +2 perícia, 17º: +2 Defesa, 18º: +3 rolagens de dano, 19º: +1 atributo). Você não pode usar itens mágicos (exceto poções), perde o benefício deste poder até o fim da aventura. Você ainda pode lançar magias, receber magias benéficas ou se beneficiar de itens usados por outros. Pré-requisito: Nível 6º.', 'Destino', 'Nível 6º'),
(42, 'Aparência Inofensiva', 'A dificuldade para resistir aos seus testes de Enganação aumenta em +2.', 'Destino', 'Car 1'),
(43, 'Atlético', 'Você recebe +2 em Atletismo e +3m em seu deslocamento.', 'Destino', 'For 2'),
(44, 'Atraente', 'Você recebe +2 em testes de perícias baseadas em Carisma contra criaturas que possam se sentir fisicamente atraídas por você.', 'Destino', 'Car 1'),
(45, 'Comandar', 'Você pode gastar uma ação de movimento e 1 PM para gritar ordens para seus aliados em alcance médio. Eles recebem +1 em testes de perícia até o fim da cena.', 'Destino', 'Car 1'),
(46, 'Costas Largas', 'Seu limite de carga aumenta em 5 espaços e você pode se beneficiar de um item vestido adicional.', 'Destino', 'Con 1, For 1'),
(47, 'Foco em Perícia', 'Escolha uma perícia. Quando faz um teste dessa perícia, você pode gastar 1 PM para rolar dois dados e usar o melhor resultado. Você pode escolher este poder outras vezes para perícias diferentes. Este poder não pode ser aplicado em Luta e Pontaria (mas veja Foco em Arma).', 'Destino', 'Treinado na perícia escolhida'),
(48, 'Inventário Organizado', 'Você soma sua Inteligência no limite de espaços que pode carregar. Para você, itens muito leves ou pequenos como moedas/documentos ocupam meio espaço, em vez de 1/4.', 'Destino', 'Int 1'),
(49, 'Investigador', 'Você recebe +2 em Investigação e soma sua Inteligência em Intuição.', 'Destino', 'Int 1'),
(50, 'Lobo Solitário', 'Você recebe +1 em testes de perícia e Defesa se estiver sem nenhum aliado em alcance curto. Você não sofre penalidade por usar Cura em si mesmo.', 'Destino', NULL),
(51, 'Medicina', 'Você pode gastar 1 PM para fazer um teste de Cura (CD 15) em uma criatura. Se você passar, ela recupera 1d6 PV, mais 1d6 para cada 5 pontos pelos quais o resultado do teste exceder a CD (2d6 com um resultado 20, 3d6 com um resultado 25 e assim por diante). Você só pode usar este poder uma vez por dia na mesma criatura.', 'Destino', 'Sab 1, treinado em Cura'),
(52, 'Parceiro', 'Você possui um parceiro animal ou humanoide que o acompanha em aventuras. Escolha os detalhes dele, como nome, aparência e personalidade. Em termos de jogo, é um parceiro iniciante de um tipo a sua escolha (veja a página 260).', 'Destino', 'Treinado em Adestramento (parceiro animal) ou Diplomacia (parceiro humanoide), 5º nível de personagem'),
(53, 'Sentidos Aguçados', 'Você recebe +2 em Percepção, não fica desprevenido contra inimigos que não possa ver e, sempre que erra um ataque devido a camuflagem, pode rolar mais uma vez o dado da chance de falha.', 'Destino', 'Sab 1, treinado em Percepção'),
(54, 'Sortudo', 'Você pode gastar 3 PM para rolar novamente um teste recém realizado (apenas uma vez por teste).', 'Destino', NULL),
(55, 'Surto Heroico', 'Uma vez por rodada, você pode gastar 5 PM para realizar uma ação padrão ou de movimento adicional.', 'Destino', NULL),
(56, 'Torcida', 'Você recebe +2 em testes de perícia e Defesa quando tem a torcida a seu favor (entenda-se por “torcida” qualquer número de criaturas inteligentes em alcance médio que não esteja realizando nenhuma ação além de torcer por você).', 'Destino', 'Car 1'),
(57, 'Treinamento em Perícia', 'Você se torna treinado em uma perícia a sua escolha. Você pode escolher este poder outras vezes para perícias diferentes.', 'Destino', NULL),
(58, 'Venefício', 'Quando usa um veneno, você não corre risco de se envenenar acidentalmente. Além disso, a CD para resistir aos seus venenos aumenta em +2.', 'Destino', 'Treinado em Ofício (alquimista)'),
(59, 'Vontade de Ferro', 'Você recebe +1 PM para cada dois níveis de personagem e +2 em Vontade.', 'Destino', 'Sab 1'),
(60, 'Celebrar Ritual', 'Você pode lançar magias como rituais. Isso dobra seu limite de PM, mas muda a execução para 1 hora (ou o dobro, o que for maior) e exige um gasto de T$ 10 por PM gastos (em incensos, oferendas...).', 'Magia', 'Misticismo ou Religião, 8º nível de personagem'),
(61, 'Escrever Pergaminho', 'Você pode usar a perícia Ofício (escriba) para fabricar pergaminhos com magias que conheça. Veja a página 121 para a regra de fabricar itens e as páginas 333 e 341 para as regras de pergaminhos.', 'Magia', 'Habilidade de classe Magias, treinado em Ofício (escriba)'),
(62, 'Foco em Magia', 'Escolha uma magia que possa lançar. Seu custo diminui em –1 PM (cumulativo com outras reduções de custo). Você pode escolher este poder outras vezes para magias diferentes.', 'Magia', NULL),
(63, 'Magia Acelerada', 'Muda a execução da magia para ação livre. Você só pode aplicar este aprimoramento em magias com execução de movimento, padrão ou completa e só pode lançar uma magia como ação livre por rodada.', 'Magia', 'Aprimoramento. Custo: +4 PM. Pré-requisito: lançar magias de 2º círculo.'),
(64, 'Magia Ampliada', 'Aumenta o alcance da magia em um passo (de curto para médio, de médio para longo) ou dobra a área de efeito da magia.', 'Magia', 'Aprimoramento. Custo: +2 PM.'),
(65, 'Magia Discreta', 'Você pode lançar a magia sem gesticular e falar, usando apenas concentração. Isso permite lançar magias com as mãos presas, amordaçado etc. Também permite lançar magias arcanas usando armadura sem teste de Misticismo. Outros personagens só percebem que você lançou uma magia se passarem num teste de Misticismo (CD 20).', 'Magia', 'Aprimoramento. Custo: +2 PM.'),
(66, 'Magia Ilimitada', 'Você pode somar seu atributo-chave no limite de PM que pode gastar numa magia. Por exemplo, um arcanista de 5º nível com Int 4 e este poder pode gastar até 9 PM em cada magia.', 'Magia', NULL),
(67, 'Preparar Poção', 'Você pode usar a perícia Ofício (alquimista) para fabricar poções com magias que conheça de 1º e 2º círculos. Veja a página 121 para a regra de fabricar itens e as páginas 333 e 341 para as regras de poções.', 'Magia', 'Habilidade de classe Magias, treinado em Ofício (alquimista)');

-- --------------------------------------------------------

--
-- Estrutura da tabela `t20_racas`
--

CREATE TABLE IF NOT EXISTS `t20_racas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `ajustes_atributos` text,
  `habilidade_1_nome` varchar(255) DEFAULT NULL,
  `habilidade_1_desc` text,
  `habilidade_2_nome` varchar(255) DEFAULT NULL,
  `habilidade_2_desc` text,
  `habilidade_3_nome` varchar(255) DEFAULT NULL,
  `habilidade_3_desc` text,
  `deslocamento` int(11) DEFAULT '9',
  `tamanho` varchar(50) DEFAULT 'Médio',
  `faz_magia` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=18 ;

--
-- Extraindo dados da tabela `t20_racas`
--

INSERT INTO `t20_racas` (`id`, `nome`, `ajustes_atributos`, `habilidade_1_nome`, `habilidade_1_desc`, `habilidade_2_nome`, `habilidade_2_desc`, `habilidade_3_nome`, `habilidade_3_desc`, `deslocamento`, `tamanho`, `faz_magia`) VALUES
(1, 'Anão', 'CON+2, SAB+1, DES-1', 'Conhecimento das Rochas', 'Você recebe visão no escuro e +2 em testes de Percepção e Sobrevivência realizados no subterrâneo.', 'Devagar e Sempre', 'Seu deslocamento é 6m (em vez de 9m). Porém, seu deslocamento não é reduzido por uso de armadura ou excesso de carga.', 'Duro como Pedra & Tradição de Heredrimm', 'Duro como Pedra: +3 PV no 1º nível e +1 PV por nível seguinte. Tradição de Heredrimm: Você é perito nas armas tradicionais anãs (machados, martelos, marretas e picaretas são armas simples para você). Você recebe +2 em ataques com essas armas.', 6, 'Médio', 0),
(2, 'Dahllan', 'SAB+2, DES+1, INT-1', 'Amiga das Plantas', 'Você pode lançar a magia Controlar Plantas (atributo-chave Sabedoria). Caso aprenda novamente esta magia, seu custo diminui em –1 PM.', 'Armadura de Allihanna', 'Você pode gastar uma ação de movimento e 1 PM para transformar sua pele em casca de árvore, recebendo +2 na Defesa até o fim da cena.', 'Empatia Selvagem', 'Você pode se comunicar com animais por meio de linguagem corporal e vocalizações. Você pode usar Adestramento para mudar atitude e persuasão com animais (veja Diplomacia). Caso receba esta habilidade novamente, recebe +2 em Adestramento.', 9, 'Médio', 0),
(3, 'Elfo', 'INT+2, DES+1, CON-1', 'Graça de Glórienn', 'Seu deslocamento é 12m (em vez de 9m).', 'Sangue Mágico', 'Você recebe +1 ponto de mana por nível.', 'Sentidos Élficos', 'Você recebe visão na penumbra e +2 em Misticismo e Percepção.', 12, 'Médio', 1),
(4, 'Goblin', 'DES+2, INT+1, CAR-1', 'Engenhoso', 'Você não sofre penalidades em testes de perícia por não usar ferramentas. Se usar a ferramenta necessária, recebe +2 no teste de perícia.', 'Espelunqueiro', 'Você recebe visão no escuro e deslocamento de escalada igual ao seu deslocamento terrestre.', 'Peste Esguia & Rato das Ruas', 'Peste Esguia: Tamanho Pequeno, deslocamento 9m. Rato das Ruas: Você recebe +2 em Fortitude e sua recuperação de PV e PM nunca é inferior ao seu nível.', 9, 'Pequeno', 0),
(5, 'Golem', 'FOR+2, CON+1, CAR-1', 'Chassi', 'Seu corpo artificial é resistente, mas rígido. Seu deslocamento é 6m, mas não é reduzido por uso de armadura ou excesso de carga. Você recebe +2 na Defesa, mas possui penalidade de armadura –2. Você leva um dia para vestir ou remover uma armadura.', 'Criatura Artificial', 'Você é uma criatura do tipo construto. Recebe visão no escuro e imunidade a efeitos de cansaço, metabólicos e de veneno. Não precisa respirar, alimentar-se ou dormir, mas não se beneficia de cura mundana ou itens da categoria alimentação. Precisa ficar inerte por 8h para recarregar (recupera PV e PM). A perícia Cura não funciona em você, mas Ofício (artesão) pode ser usada no lugar dela.', 'Propósito de Criação & Fonte Elemental', 'Propósito de Criação: Sem origem, recebe um poder geral. Fonte Elemental: Escolha entre água (frio), ar (eletricidade), fogo (fogo) e terra (ácido). Você é imune a dano desse tipo. Se fosse sofrer dano mágico desse tipo, em vez disso cura PV em quantidade igual à metade do dano.', 6, 'Médio', 0),
(6, 'Humano', 'ATRIBUTOS+1,+1,+1', 'Versátil', 'Você se torna treinado em duas perícias a sua escolha (não precisam ser da sua classe). Você pode trocar uma dessas perícias por um poder geral a sua escolha.', NULL, NULL, NULL, NULL, 9, 'Médio', 1),
(7, 'Hynne', 'DES+2, CAR+1, FOR-1', 'Arremessador', 'Quando faz um ataque à distância com uma funda ou uma arma de arremesso, seu dano aumenta em um passo.', 'Pequeno e Rechonchudo', 'Seu tamanho é Pequeno e seu deslocamento é 6m. Você recebe +2 em Enganação e pode usar Destreza como atributo-chave de Atletismo (em vez de Força).', 'Sorte Salvadora', 'Quando faz um teste de resistência, você pode gastar 1 PM para rolar este teste novamente.', 6, 'Pequeno', 1),
(8, 'Kliren', 'INT+2, CAR+1, FOR-1', 'Híbrido', 'Você se torna treinado em uma perícia a sua escolha (não precisa ser da sua classe).', 'Engenhosidade', 'Quando faz um teste de perícia, você pode gastar 2 PM para somar sua Inteligência no teste. Não funciona em testes de ataque. Se receber esta habilidade novamente, o custo é reduzido em –1 PM.', 'Ossos Frágeis & Vanguardista', 'Ossos Frágeis: Sofre +1 ponto de dano por dado de impacto. Vanguardista: Recebe proficiência em armas de fogo e +2 em Ofício (um qualquer, à sua escolha).', 9, 'Médio', 0),
(9, 'Lefou', 'ATRIBUTOS+1,+1,+1, CAR-1', 'Cria da Tormenta', 'Você é uma criatura do tipo monstro e recebe +5 em testes de resistência contra efeitos causados por lefeu e pela Tormenta.', 'Deformidade', 'Você recebe +2 em duas perícias a sua escolha. Cada um desses bônus conta como um poder da Tormenta (exceto para perda de Carisma). Você pode trocar um desses bônus por um poder da Tormenta a sua escolha (ele também não conta para perda de Carisma).', NULL, NULL, 9, 'Médio', 0),
(10, 'Medusa', 'DES+2, CAR+1', 'Cria de Megalokk', 'Você é uma criatura do tipo monstro e recebe visão no escuro.', 'Natureza Venenosa', 'Você recebe resistência a veneno +5 e pode gastar uma ação de movimento e 1 PM para envenenar uma arma que esteja usando. A arma causa perda de 1d12 PV (veneno). Dura até acertar um ataque ou fim da cena.', 'Olhar Atordoante', 'Você pode gastar uma ação de movimento e 1 PM para forçar uma criatura em alcance curto a fazer um teste de Fortitude (CD Car). Se falhar, fica atordoada por uma rodada (apenas uma vez por cena).', 9, 'Médio', 0),
(11, 'Minotauro', 'FOR+2, CON+1, SAB-1', 'Chifres', 'Você possui uma arma natural de chifres (dano 1d6, crítico x2, perfuração). Uma vez por rodada, quando usa a ação agredir para atacar com outra arma, pode gastar 1 PM para fazer um ataque corpo a corpo extra com os chifres.', 'Couro Rígido', 'Sua pele é dura como a de um touro. Você recebe +1 na Defesa.', 'Faro', 'Você tem olfato apurado. Contra inimigos em alcance curto que não possa ver, você não fica desprevenido e camuflagem total lhe causa apenas 20% de chance de falha.', 9, 'Médio', 0),
(12, 'Osteon', 'ATRIBUTOS+1,+1,+1,-exceto-CON, CON-1', 'Armadura Óssea', 'Você recebe redução de corte, frio e perfuração 5.', 'Memória Póstuma', 'Você se torna treinado em uma perícia (não precisa ser da sua classe) OU recebe um poder geral a sua escolha. Alternativamente, pode ser um osteon de outra raça (escolha uma habilidade dessa raça; se a raça for de tamanho diferente, você possui esse tamanho).', 'Natureza Esquelética & Preço da Não Vida', 'Natureza Esquelética: Morto-vivo, visão no escuro, imunidades (cansaço, metabólico, trevas, veneno), não precisa respirar/comer/dormir. Cura de luz causa dano, trevas cura. Preço da Não Vida: Precisa passar 8h sob estrelas ou subterrâneo para recuperar PV/PM por descanso (imune a condições boas/ruins). Caso contrário, sofre fome.', 9, 'Médio', 0),
(13, 'Qareen', 'CAR+2, INT+1, SAB-1', 'Desejos', 'Se lançar uma magia que alguém tenha pedido desde seu último turno, o custo da magia diminui em –1 PM. Fazer um desejo é ação livre.', 'Resistência Elemental', 'Conforme sua ascendência, você recebe RD 10 a um tipo de dano: frio (água), eletricidade (ar), fogo (fogo), ácido (terra), luz (luz) ou trevas (trevas).', 'Tatuagem Mística', 'Você pode lançar uma magia de 1º círculo a sua escolha (atributo-chave Carisma). Caso aprenda novamente essa magia, seu custo diminui em –1 PM.', 9, 'Médio', 1),
(14, 'Sereia/Tritão', 'ATRIBUTOS+1,+1,+1', 'Canção dos Mares', 'Você pode lançar duas das magias a seguir: Amedrontar, Comando, Despedaçar, Enfeitiçar, Hipnotismo ou Sono (atributo-chave Carisma). Caso aprenda novamente uma dessas magias, seu custo diminui em –1 PM.', 'Mestre do Tridente', 'Para você, o tridente é uma arma simples. Além disso, você recebe +2 em rolagens de dano com azagaias, lanças e tridentes.', 'Transformação Anfíbia', 'Você pode respirar debaixo d’água e possui deslocamento de natação 12m. Fora d’água, tem pernas (deslocamento 9m). Se ficar mais de um dia sem contato com água, não recupera PM com descanso.', 9, 'Médio', 0),
(15, 'Sílfide', 'CAR+2, DES+1, FOR-2', 'Asas de Borboleta', 'Seu tamanho é Minúsculo. Pode pairar a 1,5m do chão (desl. 9m), ignorando terreno difícil e imune a dano por queda (se consciente). Pode gastar 1 PM por rodada para voar com desl. 12m.', 'Espírito da Natureza', 'Você é do tipo espírito, recebe visão na penumbra e pode falar com animais livremente.', 'Magia das Fadas', 'Você pode lançar duas das magias a seguir: Criar Ilusão, Enfeitiçar, Luz (arcana) ou Sono (atributo-chave Carisma). Caso aprenda novamente uma dessas magias, seu custo diminui em –1 PM.', 9, 'Minúsculo', 1),
(16, 'Suraggel', 'SAB+2, CAR+1 (Aggelus); DES+2, INT+1 (Sulfure)', 'Herança Divina', 'Você é uma criatura do tipo espírito e recebe visão no escuro.', 'Luz Sagrada (Aggelus)', 'Você recebe +2 em Diplomacia e Intuição. Pode lançar Luz (divina, Carisma). Custo -1 PM se aprender de novo.', 'Sombras Profanas (Sulfure)', 'Você recebe +2 em Enganação e Furtividade. Pode lançar Escuridão (divina, Inteligência). Custo -1 PM se aprender de novo.', 9, 'Médio', 0),
(17, 'Trog', 'CON+2, FOR+1, INT-1', 'Mau Cheiro', 'Você pode gastar ação padrão e 2 PM para expelir gás fétido. Criaturas em alcance curto (exceto trogs) devem passar em Fortitude (CD Con) ou ficam enjoadas por 1d6 rodadas. Imunidade por um dia se passar.', 'Mordida', 'Arma natural de mordida (dano 1d6, crítico x2, perfuração). Uma vez por rodada, ao usar ação agredir com outra arma, pode gastar 1 PM para ataque extra com mordida.', 'Reptiliano & Sangue Frio', 'Reptiliano: Monstro, visão no escuro, +1 Defesa (se sem armadura/roupa pesada), +5 Furtividade. Sangue Frio: Sofre +1 ponto de dano por dado de dano de frio.', 9, 'Médio', 0);

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
-- Limitadores para a tabela `personagem_t20_inventario`
--
ALTER TABLE `personagem_t20_inventario`
  ADD CONSTRAINT `fk_item_inventario_t20` FOREIGN KEY (`item_id`) REFERENCES `t20_itens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_personagem_inventario_t20` FOREIGN KEY (`personagem_id`) REFERENCES `personagens_t20` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `personagem_t20_magias`
--
ALTER TABLE `personagem_t20_magias`
  ADD CONSTRAINT `fk_magia_id` FOREIGN KEY (`magia_id`) REFERENCES `t20_magias` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_magia_personagem` FOREIGN KEY (`personagem_id`) REFERENCES `personagens_t20` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `personagem_t20_poderes`
--
ALTER TABLE `personagem_t20_poderes`
  ADD CONSTRAINT `fk_personagem_poder_t20` FOREIGN KEY (`personagem_id`) REFERENCES `personagens_t20` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `personagens_op`
--
ALTER TABLE `personagens_op`
  ADD CONSTRAINT `fk_classe` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_origem` FOREIGN KEY (`origem_id`) REFERENCES `origens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_trilha` FOREIGN KEY (`trilha_id`) REFERENCES `trilhas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user_op` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `personagens_op_poderes`
--
ALTER TABLE `personagens_op_poderes`
  ADD CONSTRAINT `fk_poder_personagem` FOREIGN KEY (`personagem_id`) REFERENCES `personagens_op` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `personagens_op_rituais`
--
ALTER TABLE `personagens_op_rituais`
  ADD CONSTRAINT `fk_ritual_id` FOREIGN KEY (`ritual_id`) REFERENCES `rituais_op` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ritual_personagem` FOREIGN KEY (`personagem_id`) REFERENCES `personagens_op` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `personagens_t20`
--
ALTER TABLE `personagens_t20`
  ADD CONSTRAINT `fk_user_t20` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

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
-- Limitadores para a tabela `t20_divindade_poderes`
--
ALTER TABLE `t20_divindade_poderes`
  ADD CONSTRAINT `fk_divindade_link` FOREIGN KEY (`divindade_id`) REFERENCES `t20_divindades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_poder_divino_link` FOREIGN KEY (`poder_divino_id`) REFERENCES `t20_poderes_divinos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `t20_habilidades_classe_auto`
--
ALTER TABLE `t20_habilidades_classe_auto`
  ADD CONSTRAINT `fk_habilidade_da_classe` FOREIGN KEY (`classe_id`) REFERENCES `t20_classes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `t20_poderes_classe`
--
ALTER TABLE `t20_poderes_classe`
  ADD CONSTRAINT `fk_poder_da_classe` FOREIGN KEY (`classe_id`) REFERENCES `t20_classes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `trilhas`
--
ALTER TABLE `trilhas`
  ADD CONSTRAINT `trilhas_ibfk_1` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
