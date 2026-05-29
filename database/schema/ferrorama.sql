-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/12/2025 às 01:56
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ferrorama`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alertas`
--

CREATE TABLE `alertas` (
  `id` int(11) NOT NULL,
  `tipo` enum('crítico','urgente','aviso','informativo') NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` text NOT NULL,
  `origem` enum('sensor','sistema','operador','trem','rota') NOT NULL,
  `origem_id` int(11) DEFAULT NULL,
  `prioridade` int(11) DEFAULT 1,
  `status` enum('ativo','resolvido','em_análise','ignorado') DEFAULT 'ativo',
  `resolvido_por` int(11) DEFAULT NULL,
  `resolvido_em` datetime DEFAULT NULL,
  `localizacao` varchar(200) DEFAULT NULL,
  `acao_tomada` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `alertas`
--

INSERT INTO `alertas` (`id`, `tipo`, `titulo`, `descricao`, `origem`, `origem_id`, `prioridade`, `status`, `resolvido_por`, `resolvido_em`, `localizacao`, `acao_tomada`, `criado_em`, `atualizado_em`) VALUES
(1, 'aviso', 'Sensor de temperatura elevada', 'Sensor SENS-001 registrou 45 graus', 'sensor', NULL, 1, 'ativo', NULL, NULL, NULL, NULL, '2025-10-04 23:27:57', '2025-10-04 23:27:57'),
(2, 'informativo', 'Manutencao programada', 'TREM-003 entrara em manutencao preventiva', 'sistema', NULL, 1, 'ativo', NULL, NULL, NULL, NULL, '2025-10-04 23:27:57', '2025-10-04 23:27:57');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estacoes`
--

CREATE TABLE `estacoes` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `endereco` varchar(200) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `numero_plataformas` int(11) DEFAULT 1,
  `capacidade_passageiros` int(11) DEFAULT NULL,
  `status` enum('ativa','inativa','manutencao','reforma') DEFAULT 'ativa',
  `horario_abertura` time DEFAULT NULL,
  `horario_fechamento` time DEFAULT NULL,
  `servicos` text DEFAULT NULL COMMENT 'Serviços disponíveis na estação',
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `estacoes`
--

INSERT INTO `estacoes` (`id`, `codigo`, `nome`, `cidade`, `estado`, `endereco`, `latitude`, `longitude`, `numero_plataformas`, `capacidade_passageiros`, `status`, `horario_abertura`, `horario_fechamento`, `servicos`, `observacoes`, `criado_em`, `atualizado_em`) VALUES
(1, 'EST-001', 'Estação Central', 'Joinville', 'SC', 'Rua XV de Novembro, 100 - Centro', NULL, NULL, 4, 500, 'ativa', NULL, NULL, 'Wi-Fi, Banheiros, Lanchonete, Estacionamento', NULL, '2025-12-01 00:55:54', '2025-12-01 00:55:54'),
(2, 'EST-002', 'Estação Norte', 'Joinville', 'SC', 'Avenida Santos Dumont, 2500 - Zona Norte', NULL, NULL, 2, 300, 'ativa', NULL, NULL, 'Wi-Fi, Banheiros', NULL, '2025-12-01 00:55:54', '2025-12-01 00:55:54'),
(3, 'EST-003', 'Estação Sul', 'Joinville', 'SC', 'Rua Blumenau, 500 - Zona Sul', NULL, NULL, 3, 400, 'ativa', NULL, NULL, 'Wi-Fi, Banheiros, Estacionamento', NULL, '2025-12-01 00:55:54', '2025-12-01 00:55:54');

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_solicitacoes`
--

CREATE TABLE `historico_solicitacoes` (
  `id` int(11) NOT NULL,
  `solicitacao_id` int(11) NOT NULL,
  `status_anterior` varchar(50) DEFAULT NULL,
  `status_novo` varchar(50) NOT NULL,
  `operador_id` int(11) NOT NULL,
  `observacao` text DEFAULT NULL,
  `data_alteracao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itinerarios`
--

CREATE TABLE `itinerarios` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `rota_id` int(11) NOT NULL,
  `trem_id` int(11) DEFAULT NULL,
  `data_partida` date NOT NULL,
  `hora_partida` time NOT NULL,
  `hora_chegada_prevista` time NOT NULL,
  `status` enum('agendado','em_andamento','concluído','cancelado','atrasado') DEFAULT 'agendado',
  `passageiros_embarcados` int(11) DEFAULT 0,
  `atraso_minutos` int(11) DEFAULT 0,
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `manutencoes`
--

CREATE TABLE `manutencoes` (
  `id` int(11) NOT NULL,
  `trem_id` int(11) NOT NULL,
  `tipo` enum('preventiva','corretiva','emergencial','revisão') NOT NULL,
  `descricao` text NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim_prevista` date NOT NULL,
  `data_fim_real` date DEFAULT NULL,
  `status` enum('agendada','em_andamento','concluída','cancelada') DEFAULT 'agendada',
  `custo` decimal(10,2) DEFAULT NULL,
  `responsavel` varchar(100) DEFAULT NULL,
  `pecas_substituidas` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `operador_id` int(11) DEFAULT NULL,
  `tipo` enum('alerta','lembrete','aviso','sistema') NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `mensagem` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `prioridade` enum('baixa','média','alta') DEFAULT 'média',
  `lida` tinyint(1) DEFAULT 0,
  `lida_em` datetime DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `operadores`
--

CREATE TABLE `operadores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `cargo` enum('Administrador','Operador','Manutenção','Supervisor') DEFAULT 'Operador',
  `especialidade` varchar(100) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultima_atividade` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `operadores`
--

INSERT INTO `operadores` (`id`, `nome`, `email`, `telefone`, `senha`, `cargo`, `especialidade`, `ativo`, `data_cadastro`, `ultima_atividade`) VALUES
(1, 'operador', 'operador@gmail.com', NULL, '$2y$10$5WacqWrEnk6/OL8ROjjDSeUOshMyt2QFPJWQUkYQ40Q.nUz36q6J6', 'Operador', NULL, 1, '2025-10-01 15:01:59', '2025-10-25 17:30:03'),
(2, 'Admin Sistema', 'admin@llgr.com', NULL, '$2y$10$5WacqWrEnk6/OL8ROjjDSeUOshMyt2QFPJWQUkYQ40Q.nUz36q6J6', 'Administrador', NULL, 1, '2025-10-14 04:34:01', NULL),
(3, 'João Manutenção', 'manutencao@llgr.com', NULL, '$2y$10$5WacqWrEnk6/OL8ROjjDSeUOshMyt2QFPJWQUkYQ40Q.nUz36q6J6', 'Manutenção', NULL, 1, '2025-10-14 04:34:01', NULL),
(4, 'Maria Supervisora', 'supervisor@llgr.com', NULL, '$2y$10$5WacqWrEnk6/OL8ROjjDSeUOshMyt2QFPJWQUkYQ40Q.nUz36q6J6', 'Supervisor', NULL, 1, '2025-10-14 04:34:01', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `reclamacoes`
--

CREATE TABLE `reclamacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `resposta` text DEFAULT NULL,
  `status` enum('pendente','respondida','resolvida') DEFAULT 'pendente',
  `respondido_por` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `respondido_em` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `reclamacoes`
--

INSERT INTO `reclamacoes` (`id`, `usuario_id`, `mensagem`, `resposta`, `status`, `respondido_por`, `created_at`, `respondido_em`) VALUES
(1, 1, 'Nao consigo acessar as rotas', 'Problema resolvido', 'resolvida', 1, '2025-10-06 05:22:27', '2025-10-06 05:24:02'),
(2, 1, 'oi', 'problema resolvido', 'respondida', 1, '2025-10-25 17:29:56', '2025-10-25 17:30:17');

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorios`
--

CREATE TABLE `relatorios` (
  `id` int(11) NOT NULL,
  `tipo` enum('operacional','financeiro','manutenção','incidentes','geral') NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL,
  `periodo_inicio` date NOT NULL,
  `periodo_fim` date NOT NULL,
  `gerado_por` int(11) NOT NULL,
  `formato` enum('pdf','excel','csv') DEFAULT 'pdf',
  `caminho_arquivo` varchar(255) DEFAULT NULL,
  `dados_json` text DEFAULT NULL,
  `status` enum('gerado','processando','erro') DEFAULT 'gerado',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rotas`
--

CREATE TABLE `rotas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `origem` varchar(100) NOT NULL,
  `destino` varchar(100) NOT NULL,
  `distancia_km` decimal(8,2) NOT NULL,
  `tempo_estimado` time NOT NULL,
  `status` enum('ativa','inativa','manutenção') DEFAULT 'ativa',
  `preco_base` decimal(10,2) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `rota_paradas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rota_id` int(11) NOT NULL,
  `estacao_id` int(11) DEFAULT NULL,
  `nome_parada` varchar(150) NOT NULL,
  `ordem` int(11) NOT NULL,
  `tempo_parada_minutos` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_rota_id` (`rota_id`),
  KEY `idx_ordem` (`ordem`),
  CONSTRAINT `fk_parada_rota` FOREIGN KEY (`rota_id`) REFERENCES `rotas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_parada_estacao` FOREIGN KEY (`estacao_id`) REFERENCES `estacoes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `rotas`
--

INSERT INTO `rotas` (`id`, `codigo`, `nome`, `origem`, `destino`, `distancia_km`, `tempo_estimado`, `status`, `preco_base`, `observacoes`, `criado_em`, `atualizado_em`) VALUES
(1, 'ROTA-001', 'Linha Central', 'Estacao Norte', 'Estacao Sul', 45.50, '00:45:00', 'ativa', 15.00, NULL, '2025-10-04 23:27:57', '2025-10-04 23:27:57'),
(2, 'ROTA-002', 'Linha Expressa', 'Terminal A', 'Terminal B', 80.00, '01:20:00', 'ativa', 25.00, NULL, '2025-10-04 23:27:57', '2025-10-04 23:27:57'),
(3, 'ROTA-004', 'Linha Litorânea - Joinville a Florianópolis', 'Estação Central de Joinville', 'Estação Metropolitana de Florianópolis', 180.50, '14:30:00', 'ativa', 65.00, 'trem regional com trajeto costeiro, operando diariamente das 06:00 às 22:00.', '2025-10-04 23:46:25', '2025-10-04 23:46:25');

INSERT INTO `rota_paradas` (`rota_id`, `nome_parada`, `ordem`) VALUES
(3, 'Estação Araquari', 1),
(3, 'Estação Barra Velha', 2),
(3, 'Estação Itajaí', 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `sensores`
--

CREATE TABLE `sensores` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `tipo` enum('temperatura','pressao','velocidade','proximidade','vibracao') NOT NULL,
  `localizacao` varchar(200) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `status` enum('ativo','inativo','manutencao') DEFAULT 'ativo',
  `ultima_leitura` datetime DEFAULT NULL,
  `valor_atual` decimal(10,2) DEFAULT NULL,
  `unidade_medida` varchar(20) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `sensores`
--

INSERT INTO `sensores` (`id`, `codigo`, `tipo`, `localizacao`, `latitude`, `longitude`, `status`, `ultima_leitura`, `valor_atual`, `unidade_medida`, `observacoes`, `criado_em`, `atualizado_em`) VALUES
(1, 'SENS-001', 'temperatura', 'Estacao Central - Plataforma 1', NULL, NULL, 'ativo', NULL, NULL, 'C', NULL, '2025-10-04 23:27:57', '2025-10-04 23:27:57'),
(2, 'SENS-002', 'velocidade', 'Km 15 - Linha Principal', NULL, NULL, 'ativo', NULL, NULL, 'km/h', NULL, '2025-10-04 23:27:57', '2025-10-04 23:27:57'),
(3, 'SENS-003', 'pressao', 'Trilho Norte - Secao A', NULL, NULL, 'ativo', NULL, NULL, 'bar', NULL, '2025-10-04 23:27:57', '2025-10-04 23:27:57');

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacoes_manutencao`
--

CREATE TABLE `solicitacoes_manutencao` (
  `id` int(11) NOT NULL,
  `trem_id` int(11) NOT NULL,
  `solicitante_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` text NOT NULL,
  `prioridade` enum('baixa','média','alta','crítica') DEFAULT 'média',
  `tipo_problema` enum('mecânico','elétrico','estrutural','limpeza','outros') NOT NULL,
  `status` enum('pendente','em_análise','em_andamento','aguardando_peças','concluída','cancelada') DEFAULT 'pendente',
  `responsavel_manutencao_id` int(11) DEFAULT NULL,
  `data_solicitacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_inicio_atendimento` datetime DEFAULT NULL,
  `data_conclusao` datetime DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `solucao` text DEFAULT NULL,
  `pecas_utilizadas` text DEFAULT NULL,
  `custo_real` decimal(10,2) DEFAULT NULL,
  `tempo_parada_minutos` int(11) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `trens`
--

CREATE TABLE `trens` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `fabricante` varchar(100) DEFAULT NULL,
  `ano_fabricacao` year(4) DEFAULT NULL,
  `capacidade_passageiros` int(11) NOT NULL,
  `velocidade_maxima` decimal(6,2) DEFAULT NULL,
  `status` enum('operacional','manutenção','inativo','em_rota') DEFAULT 'operacional',
  `km_rodados` decimal(10,2) DEFAULT 0.00,
  `ultima_manutencao` date DEFAULT NULL,
  `proxima_manutencao` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `trens`
--

INSERT INTO `trens` (`id`, `codigo`, `modelo`, `fabricante`, `ano_fabricacao`, `capacidade_passageiros`, `velocidade_maxima`, `status`, `km_rodados`, `ultima_manutencao`, `proxima_manutencao`, `observacoes`, `criado_em`, `atualizado_em`) VALUES
(1, 'TREM-001', 'Serie 5000', 'RailTech', '2020', 300, 120.00, 'operacional', 0.00, NULL, NULL, NULL, '2025-10-04 23:27:57', '2025-10-04 23:27:57'),
(2, 'TREM-002', 'Express 3000', 'TrainCorp', '2019', 250, 150.00, 'operacional', 0.00, NULL, NULL, NULL, '2025-10-04 23:27:57', '2025-10-04 23:27:57'),
(3, 'TREM-003', 'Metro X1', 'UrbanRail', '2021', 400, 100.00, 'manutenção', 0.00, NULL, NULL, NULL, '2025-10-04 23:27:57', '2025-10-04 23:27:57');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `telefone`, `cpf`, `data_nascimento`, `endereco`, `cidade`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Lucas Pereira Walter', 'lukas.lukas.walter@gmail.com', '$2y$10$oJQlQ2xpP/7XO2bqcavQjOsrR0JjDaL9s1.d1I8I/RGA2tTW2A9ba', '(47) 99155-0977', '11841582905', '2007-12-22', 'Rua das Bromelias, 374', 'Joinville', 'SC', '2025-10-05 19:43:33', '2025-12-01 00:35:53');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alertas`
--
ALTER TABLE `alertas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resolvido_por` (`resolvido_por`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_prioridade` (`prioridade`),
  ADD KEY `idx_criado_em` (`criado_em`);

--
-- Índices de tabela `estacoes`
--
ALTER TABLE `estacoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `idx_codigo` (`codigo`),
  ADD KEY `idx_cidade` (`cidade`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_status` (`status`);

--
-- Índices de tabela `historico_solicitacoes`
--
ALTER TABLE `historico_solicitacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_solicitacao` (`solicitacao_id`),
  ADD KEY `idx_operador` (`operador_id`);

--
-- Índices de tabela `itinerarios`
--
ALTER TABLE `itinerarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `rota_id` (`rota_id`),
  ADD KEY `trem_id` (`trem_id`),
  ADD KEY `idx_codigo` (`codigo`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_data_partida` (`data_partida`);

--
-- Índices de tabela `manutencoes`
--
ALTER TABLE `manutencoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trem_id` (`trem_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_data_inicio` (`data_inicio`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_operador_id` (`operador_id`),
  ADD KEY `idx_lida` (`lida`),
  ADD KEY `idx_prioridade` (`prioridade`),
  ADD KEY `idx_criado_em` (`criado_em`);

--
-- Índices de tabela `operadores`
--
ALTER TABLE `operadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `reclamacoes`
--
ALTER TABLE `reclamacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `respondido_por` (`respondido_por`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created` (`created_at`);

--
-- Índices de tabela `relatorios`
--
ALTER TABLE `relatorios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_gerado_por` (`gerado_por`),
  ADD KEY `idx_criado_em` (`criado_em`);

--
-- Índices de tabela `rotas`
--
ALTER TABLE `rotas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `idx_codigo` (`codigo`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_origem_destino` (`origem`,`destino`);

--
-- Índices de tabela `sensores`
--
ALTER TABLE `sensores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `idx_codigo` (`codigo`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_tipo` (`tipo`);

--
-- Índices de tabela `solicitacoes_manutencao`
--
ALTER TABLE `solicitacoes_manutencao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trem_id` (`trem_id`),
  ADD KEY `idx_solicitante` (`solicitante_id`),
  ADD KEY `idx_responsavel` (`responsavel_manutencao_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_prioridade` (`prioridade`);

--
-- Índices de tabela `trens`
--
ALTER TABLE `trens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `idx_codigo` (`codigo`),
  ADD KEY `idx_status` (`status`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alertas`
--
ALTER TABLE `alertas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `estacoes`
--
ALTER TABLE `estacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `historico_solicitacoes`
--
ALTER TABLE `historico_solicitacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `itinerarios`
--
ALTER TABLE `itinerarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `manutencoes`
--
ALTER TABLE `manutencoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `operadores`
--
ALTER TABLE `operadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `reclamacoes`
--
ALTER TABLE `reclamacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `relatorios`
--
ALTER TABLE `relatorios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `rotas`
--
ALTER TABLE `rotas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `sensores`
--
ALTER TABLE `sensores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `solicitacoes_manutencao`
--
ALTER TABLE `solicitacoes_manutencao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `trens`
--
ALTER TABLE `trens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `alertas_ibfk_1` FOREIGN KEY (`resolvido_por`) REFERENCES `operadores` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `historico_solicitacoes`
--
ALTER TABLE `historico_solicitacoes`
  ADD CONSTRAINT `fk_historico_operador` FOREIGN KEY (`operador_id`) REFERENCES `operadores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_historico_solicitacao` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacoes_manutencao` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `itinerarios`
--
ALTER TABLE `itinerarios`
  ADD CONSTRAINT `itinerarios_ibfk_1` FOREIGN KEY (`rota_id`) REFERENCES `rotas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itinerarios_ibfk_2` FOREIGN KEY (`trem_id`) REFERENCES `trens` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `manutencoes`
--
ALTER TABLE `manutencoes`
  ADD CONSTRAINT `manutencoes_ibfk_1` FOREIGN KEY (`trem_id`) REFERENCES `trens` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`operador_id`) REFERENCES `operadores` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `reclamacoes`
--
ALTER TABLE `reclamacoes`
  ADD CONSTRAINT `reclamacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reclamacoes_ibfk_2` FOREIGN KEY (`respondido_por`) REFERENCES `operadores` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `relatorios`
--
ALTER TABLE `relatorios`
  ADD CONSTRAINT `relatorios_ibfk_1` FOREIGN KEY (`gerado_por`) REFERENCES `operadores` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `solicitacoes_manutencao`
--
ALTER TABLE `solicitacoes_manutencao`
  ADD CONSTRAINT `fk_solicitacao_responsavel` FOREIGN KEY (`responsavel_manutencao_id`) REFERENCES `operadores` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_solicitacao_solicitante` FOREIGN KEY (`solicitante_id`) REFERENCES `operadores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_solicitacao_trem` FOREIGN KEY (`trem_id`) REFERENCES `trens` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
