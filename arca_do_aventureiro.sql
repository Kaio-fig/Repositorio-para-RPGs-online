-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 15-Set-2025 às 21:32
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
  `nome` varchar(50) NOT NULL,
  `pv_inicial_formula` varchar(50) NOT NULL,
  `pv_por_nex_formula` varchar(50) NOT NULL,
  `pe_inicial_formula` varchar(50) NOT NULL,
  `pe_por_nex_formula` varchar(50) NOT NULL,
  `san_inicial` int(11) NOT NULL,
  `san_por_nex` int(11) NOT NULL,
  `pericias_fixas` varchar(255) NOT NULL,
  `pericias_bonus_formula` varchar(100) NOT NULL,
  `proficiencias` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `classes`
--

INSERT INTO `classes` (`id`, `nome`, `pv_inicial_formula`, `pv_por_nex_formula`, `pe_inicial_formula`, `pe_por_nex_formula`, `san_inicial`, `san_por_nex`, `pericias_fixas`, `pericias_bonus_formula`, `proficiencias`) VALUES
(1, 'Combatente', '20+VIG', '4+VIG', '2+PRE', '2+PRE', 12, 3, 'Luta ou Pontaria; Fortitude ou Reflexos', 'Intelecto +1', 'Armas simples, armas táticas e proteções leves'),
(2, 'Especialista', '16+VIG', '3+VIG', '3+PRE', '3+PRE', 16, 4, 'Nenhuma fixa', 'Intelecto +7', 'Armas simples e proteções leves'),
(3, 'Ocultista', '12+VIG', '2+VIG', '4+PRE', '4+PRE', 20, 5, 'Ocultismo e Vontade', 'Intelecto +3', 'Armas simples');

-- --------------------------------------------------------

--
-- Estrutura da tabela `op_armas`
--

CREATE TABLE IF NOT EXISTS `op_armas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `dano` varchar(20) NOT NULL,
  `crit` varchar(20) NOT NULL,
  `categoria` int(11) NOT NULL DEFAULT '0',
  `espaco` int(11) NOT NULL DEFAULT '1',
  `tipo` varchar(20) DEFAULT NULL,
  `alcance` varchar(20) DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Extraindo dados da tabela `op_armas`
--

INSERT INTO `op_armas` (`id`, `nome`, `dano`, `crit`, `categoria`, `espaco`, `tipo`, `alcance`, `descricao`) VALUES
(1, 'Faca', '1d4', '19', 0, 1, 'C', 'Curto', 'Arma corpo a corpo leve'),
(2, 'Pistola', '1d12', '18', 1, 1, 'B', 'Curto', 'Arma de fogo leve'),
(3, 'Revolver', '2d6', '19×3', 1, 1, 'B', 'Curto', 'Arma de fogo leve'),
(4, 'Fuzil de caça', '2d8', '19×3', 1, 2, 'B', 'Médio', 'Arma de fogo duas mãos'),
(5, 'Espada', '1d8/1d10', '19', 1, 1, 'C', '-', 'Arma corpo a corpo uma mão'),
(6, 'Machado', '1d8', '×3', 1, 1, 'C', '-', 'Arma corpo a corpo uma mão'),
(7, 'Submetralhadora', '2d6', '19/x3', 1, 1, 'B', 'Curto', 'Arma de fogo automática'),
(8, 'Espingarda', '4d6', '×3', 1, 2, 'B', 'Curto', 'Arma de fogo duas mãos'),
(9, 'Fuzil de assalto', '2d10', '19/x3', 2, 2, 'B', 'Médio', 'Arma de fogo tática'),
(10, 'Fuzil de precisão', '2d10', '19/x3', 3, 2, 'B', 'Longo', 'Arma de fogo de longo alcance');

-- --------------------------------------------------------

--
-- Estrutura da tabela `op_gerais`
--

CREATE TABLE IF NOT EXISTS `op_gerais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `bonus` varchar(100) DEFAULT NULL,
  `categoria` int(11) NOT NULL DEFAULT '0',
  `espaco` int(11) NOT NULL DEFAULT '1',
  `tipo` varchar(50) DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `op_gerais`
--

INSERT INTO `op_gerais` (`id`, `nome`, `bonus`, `categoria`, `espaco`, `tipo`, `descricao`) VALUES
(1, 'Kit Médico', '+5 em testes de Medicina', 0, 1, 'Utensílio', 'Equipamento médico para primeiros socorros'),
(2, 'Lanterna', 'Iluminação em área média', 0, 1, 'Ferramenta', 'Fonte de luz portátil'),
(3, 'Rádio Comunicador', 'Comunicação em até 1km', 0, 1, 'Comunicação', 'Dispositivo de comunicação por rádio'),
(4, 'Binóculos', '+2 em Percepção à distância', 0, 1, 'Utensílio', 'Dispositivo óptico para visão à distância'),
(5, 'Corda', '15m de corda resistente', 0, 1, 'Utensílio', 'Corda de nylon para escalada e amarração'),
(6, 'Máscara de gás', 'Proteção contra gases', 0, 1, 'Proteção', 'Máscara de proteção respiratória'),
(7, 'Granada de Fragmentação', '4d6 de dano em área', 1, 1, 'Explosivo', 'Explosivo de fragmentação para múltiplos alvos'),
(8, 'Granada de Fumaça', 'Cria área de cobertura', 0, 1, 'Explosivo', 'Granada que libera fumaça para ocultação');

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
  `tipo_item` enum('arma','protecao','geral','paranormal') NOT NULL,
  `efeito` text NOT NULL,
  `categoria_extra` int(11) NOT NULL DEFAULT '0',
  `descricao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Extraindo dados da tabela `op_modificacoes`
--

INSERT INTO `op_modificacoes` (`id`, `nome`, `tipo_item`, `efeito`, `categoria_extra`, `descricao`) VALUES
(1, 'Certeira', 'arma', '+2 em testes de ataque', 1, 'Modificação que melhora a precisão da arma'),
(2, 'Cruel', 'arma', '+2 em rolagens de dano', 1, 'Modificação que aumenta o dano causado'),
(3, 'Discreta', 'arma', '+5 em testes para ser ocultada e reduz o espaço em -1', 0, 'Modificação que torna a arma mais fácil de ocultar'),
(4, 'Perigosa', 'arma', '+2 em margem de ameaça', 1, 'Modificação que aumenta a chance de acerto crítico'),
(5, 'Alongada', 'arma', '+2 em testes de ataque', 1, 'Modificação para armas de fogo que aumenta o alcance'),
(6, 'Calibre Grosso', 'arma', 'Aumenta o dano em mais um dado do mesmo tipo', 1, 'Modificação que aumenta o calibre da arma'),
(7, 'Antibombas', 'protecao', '+5 em testes de resistência contra efeitos de área', 1, 'Modificação que oferece proteção contra explosões'),
(8, 'Blindada', 'protecao', 'Aumenta RD para 5 e o espaço em +1', 1, 'Modificação que aumenta a resistência a dano'),
(9, 'Discreta', 'protecao', '+5 em testes de ocultar e reduz o espaço em -1', 0, 'Modificação que torna a proteção mais fácil de ocultar'),
(10, 'Reforçada', 'protecao', 'Aumenta a Defesa em +2 e o espaço em +1', 1, 'Modificação que aumenta a proteção oferecida'),
(11, 'Amaldiçoada', 'paranormal', 'Adiciona efeito paranormal ao item', 2, 'Modificação que imbui o item com energia paranormal'),
(12, 'Potencializada', 'paranormal', 'Aumenta a potência do efeito paranormal', 1, 'Modificação que amplifica os efeitos paranormais');

-- --------------------------------------------------------

--
-- Estrutura da tabela `op_paranormal`
--

CREATE TABLE IF NOT EXISTS `op_paranormal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `efeito` text NOT NULL,
  `categoria` int(11) NOT NULL DEFAULT '0',
  `espaco` int(11) NOT NULL DEFAULT '1',
  `elemento` varchar(20) DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `op_paranormal`
--

INSERT INTO `op_paranormal` (`id`, `nome`, `efeito`, `categoria`, `espaco`, `elemento`, `descricao`) VALUES
(1, 'Amuleto de Proteção', 'Fornece +2 em Defesa', 1, 1, 'Conhecimento', 'Amuleto que oferece proteção contra ataques'),
(2, 'Anel do Elo Mental', 'Permite comunicação telepática', 2, 1, 'Conhecimento', 'Par de anéis que conecta mentalmente os usuários'),
(3, 'Pérola de Sangue', 'Fornece +5 em testes físicos temporariamente', 2, 1, 'Sangue', 'Esfera que injeta adrenalina no usuário'),
(4, 'Máscara das Sombras', 'Permite teletransporte entre sombras', 3, 1, 'Morte', 'Máscara que concede habilidades de manipulação de sombras'),
(5, 'Coração Pulsante', 'Reduz dano pela metade uma vez por cena', 2, 1, 'Sangue', 'Coração humano preservado que pulsa com energia de Sangue'),
(6, 'Frasco de Vitalidade', 'Armazena PV para recuperação posterior', 1, 1, 'Sangue', 'Frasco que pode armazenar sangue para uso posterior');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `op_protecoes`
--

CREATE TABLE IF NOT EXISTS `op_protecoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `defesa` varchar(20) NOT NULL,
  `categoria` int(11) NOT NULL DEFAULT '0',
  `espaco` int(11) NOT NULL DEFAULT '1',
  `tipo` varchar(50) DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `op_protecoes`
--

INSERT INTO `op_protecoes` (`id`, `nome`, `defesa`, `categoria`, `espaco`, `tipo`, `descricao`) VALUES
(1, 'Leve', '+5', 1, 2, 'Armadura', 'Proteção leve que permite boa mobilidade'),
(2, 'Pesada', '+10', 2, 5, 'Armadura', 'Proteção pesada que oferece maior defesa mas reduz mobilidade'),
(3, 'Escudo', '+2', 1, 2, 'Escudo', 'Proteção adicional que pode ser empunhada');

-- --------------------------------------------------------

--
-- Estrutura da tabela `origens`
--

CREATE TABLE IF NOT EXISTS `origens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `poder_base` text NOT NULL,
  `pericia1` varchar(100) NOT NULL,
  `pericia2` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=27 ;

--
-- Extraindo dados da tabela `origens`
--

INSERT INTO `origens` (`id`, `nome`, `poder_base`, `pericia1`, `pericia2`) VALUES
(1, 'Acadêmico', 'Saber é Poder: Você pode gastar 2 PE para receber +5 em um teste que use Intelecto.', 'Ciências', 'Investigação'),
(2, 'Agente de Saúde', 'Técnica Medicinal: Sempre que cura um personagem, você adiciona seu Intelecto no total de PV curados.', 'Intuição', 'Medicina'),
(3, 'Amnésico', 'Vislumbres do Passado: Uma vez por sessão, faça um teste de Intelecto (DT 10) para reconhecer pessoas ou lugares familiares. Se passar, recebe 1d4 PE temporários e uma informação útil.', 'À escolha do mestre', 'À escolha do mestre'),
(4, 'Artista', 'Magnum Opus: Uma vez por missão, pode determinar que um personagem envolvido em uma cena de interação o reconheça. Recebe +5 em testes de Presença e perícias baseadas em Presença contra ele.', 'Artes', 'Enganação'),
(5, 'Atleta', '110%: Quando faz um teste de perícia usando Força ou Agilidade (exceto Luta e Pontaria), pode gastar 2 PE para receber +5 no teste.', 'Acrobacia', 'Atletismo'),
(6, 'Chef', 'Ingrediente Secreto: Em cenas de interlúdio, quando cozinha um prato especial, você e aliados que comerem recebem o benefício de dois pratos. Benefícios se acumulam se repetidos.', 'Fortitude', 'Profissão (cozinheiro)'),
(7, 'Criminoso', 'O Crime Compensa: No final de uma missão, escolha um item encontrado. Na próxima missão, pode incluí-lo no inventário sem contar no limite de itens.', 'Crime', 'Furtividade'),
(8, 'Cultista Arrependido', 'Traços do Outro Lado: Você possui um poder paranormal à sua escolha, mas começa o jogo com metade da Sanidade inicial da sua classe.', 'Ocultismo', 'Religião'),
(9, 'Desgarrado', 'Calejado: Você recebe +1 PV para cada 5% de NEX.', 'Fortitude', 'Sobrevivência'),
(10, 'Engenheiro', 'Ferramenta Favorita: Um item de sua escolha (não arma) conta como uma categoria abaixo para fins de uso. Pode reduzir o custo de categoria do item.', 'Profissão', 'Tecnologia'),
(11, 'Executivo', 'Processo Otimizado: Você recebe +5 em testes de Diplomacia para negociações e pode gastar 1 PE para reduzir pela metade o tempo de um teste estendido.', 'Diplomacia', 'Profissão'),
(12, 'Investigador', 'Faro para Pistas: Uma vez por cena, quando fizer um teste para procurar pistas, pode gastar 1 PE para receber +5 no teste.', 'Investigação', 'Percepção'),
(13, 'Lutador', 'Mão Pesada: Quando usa Força para testes de ataque desarmado ou com armas simples, pode gastar 1 PE para causar +1d6 de dano.', 'Luta', 'Reflexos'),
(14, 'Magnata', 'Patrocinador da Ordem: Seu limite de crédito é sempre considerado um acima do atual.', 'Diplomacia', 'Pilotagem'),
(15, 'Mercenário', 'Posição de Combate: Ao rolar Iniciativa, pode gastar 1 PE para rolar novamente e ficar com o melhor resultado.', 'Iniciativa', 'Intimidação'),
(16, 'Militar', 'Para Bellum: Recebe +2 em rolagens de dano com armas de fogo.', 'Pontaria', 'Tática'),
(17, 'Operário', 'Ferramenta de Trabalho: Você recebe +5 em testes de Força para manipular objetos pesados. Pode improvisar armas de categoria inferior usando ferramentas.', 'Fortitude', 'Profissão'),
(18, 'Policial', 'Patrulha: Você recebe +2 em Defesa.', 'Percepção', 'Pontaria'),
(19, 'Religioso', 'Acalentar: Você recebe +5 em testes de Religião para acalmar. Quando acalma uma pessoa, ela recupera 1d6 + sua Presença em Sanidade.', 'Religião', 'Vontade'),
(20, 'Servidor Público', 'Espírito Cívico: Sempre que faz um teste para ajudar, pode gastar 1 PE para aumentar o bônus concedido em +2.', 'Intuição', 'Vontade'),
(21, 'Teórico da Conspiração', 'Eu Já Sabia: Você não se abala tanto com entidades anômalas. Recebe resistência a dano mental igual ao seu Intelecto.', 'Investigação', 'Ocultismo'),
(22, 'T.I.', 'Motor de Busca: Sempre que tiver acesso à internet, pode gastar 2 PE para substituir um teste de perícia qualquer por um de Tecnologia.', 'Investigação', 'Tecnologia'),
(23, 'Trabalhador Rural', 'Desbravador: Quando faz um teste de Adestramento ou Sobrevivência, pode gastar 2 PE para receber +5 no teste. Você não sofre penalidade em deslocamento por terreno difícil.', 'Adestramento', 'Sobrevivência'),
(24, 'Trambiqueiro', 'Impostor: Uma vez por cena, pode gastar 2 PE para substituir um teste de perícia qualquer por um teste de Enganação.', 'Crime', 'Enganação'),
(25, 'Universitário', 'Dedicação: Você recebe +1 PE e mais 1 PE adicional a cada NEX ímpar (15%, 25%...). Seu limite de PE por turno aumenta em 1 (máx. 2 em NEX 5%, máx. 3 em NEX 10% e assim por diante).', 'Atualidades', 'Investigação'),
(26, 'Vítima', 'Cicatrizes Psicológicas: Você recebe +1 de Sanidade para cada 5% de NEX.', 'Reflexos', 'Vontade');

-- --------------------------------------------------------

--
-- Estrutura da tabela `personagens`
--

CREATE TABLE IF NOT EXISTS `personagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `sistema` varchar(50) NOT NULL,
  `nivel` int(11) NOT NULL DEFAULT '1',
  `imagem` varchar(255) DEFAULT 'default.jpg',
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  ADD CONSTRAINT `op_personagem_itens_ibfk_1` FOREIGN KEY (`personagem_id`) REFERENCES `personagens` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `personagens`
--
ALTER TABLE `personagens`
  ADD CONSTRAINT `personagens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
