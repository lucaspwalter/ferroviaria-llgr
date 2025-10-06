-- =============================================
-- SISTEMA DE GERENCIAMENTO FERROVIÁRIO LLGR
-- Script de Criação do Banco de Dados - VERSÃO CORRIGIDA
-- =============================================

CREATE DATABASE IF NOT EXISTS ferrorama CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ferrorama;

-- Tabela de Operadores
CREATE TABLE IF NOT EXISTS operadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    cargo VARCHAR(50) NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABELA: SENSORES
CREATE TABLE IF NOT EXISTS sensores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    tipo ENUM('temperatura', 'pressao', 'velocidade', 'proximidade', 'vibracao') NOT NULL,
    localizacao VARCHAR(200) NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    status ENUM('ativo', 'inativo', 'manutencao') DEFAULT 'ativo',
    ultima_leitura DATETIME,
    valor_atual DECIMAL(10, 2),
    unidade_medida VARCHAR(20),
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_codigo (codigo),
    INDEX idx_status (status),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABELA: TRENS
CREATE TABLE IF NOT EXISTS trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    modelo VARCHAR(100) NOT NULL,
    fabricante VARCHAR(100),
    ano_fabricacao YEAR,
    capacidade_passageiros INT NOT NULL,
    velocidade_maxima DECIMAL(6, 2),
    status ENUM('operacional', 'manutencao', 'inativo', 'em_rota') DEFAULT 'operacional',
    km_rodados DECIMAL(10, 2) DEFAULT 0,
    ultima_manutencao DATE,
    proxima_manutencao DATE,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_codigo (codigo),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABELA: ROTAS
CREATE TABLE IF NOT EXISTS rotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    nome VARCHAR(150) NOT NULL,
    origem VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL,
    distancia_km DECIMAL(8, 2) NOT NULL,
    tempo_estimado TIME NOT NULL,
    status ENUM('ativa', 'inativa', 'manutencao') DEFAULT 'ativa',
    numero_paradas INT DEFAULT 0,
    paradas TEXT,
    preco_base DECIMAL(10, 2),
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_codigo (codigo),
    INDEX idx_status (status),
    INDEX idx_origem_destino (origem, destino)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABELA: ITINERÁRIOS
CREATE TABLE IF NOT EXISTS itinerarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    rota_id INT NOT NULL,
    trem_id INT,
    data_partida DATE NOT NULL,
    hora_partida TIME NOT NULL,
    hora_chegada_prevista TIME NOT NULL,
    status ENUM('agendado', 'em_andamento', 'concluido', 'cancelado', 'atrasado') DEFAULT 'agendado',
    passageiros_embarcados INT DEFAULT 0,
    atraso_minutos INT DEFAULT 0,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (rota_id) REFERENCES rotas(id) ON DELETE CASCADE,
    FOREIGN KEY (trem_id) REFERENCES trens(id) ON DELETE SET NULL,
    INDEX idx_codigo (codigo),
    INDEX idx_status (status),
    INDEX idx_data_partida (data_partida)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABELA: ALERTAS
CREATE TABLE IF NOT EXISTS alertas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('critico', 'urgente', 'aviso', 'informativo') NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT NOT NULL,
    origem ENUM('sensor', 'sistema', 'operador', 'trem', 'rota') NOT NULL,
    origem_id INT,
    prioridade INT DEFAULT 1,
    status ENUM('ativo', 'resolvido', 'em_analise', 'ignorado') DEFAULT 'ativo',
    resolvido_por INT,
    resolvido_em DATETIME,
    localizacao VARCHAR(200),
    acao_tomada TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (resolvido_por) REFERENCES operadores(id) ON DELETE SET NULL,
    INDEX idx_tipo (tipo),
    INDEX idx_status (status),
    INDEX idx_prioridade (prioridade),
    INDEX idx_criado_em (criado_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABELA: MANUTENÇÕES
CREATE TABLE IF NOT EXISTS manutencoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trem_id INT NOT NULL,
    tipo ENUM('preventiva', 'corretiva', 'emergencial', 'revisao') NOT NULL,
    descricao TEXT NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim_prevista DATE NOT NULL,
    data_fim_real DATE,
    status ENUM('agendada', 'em_andamento', 'concluida', 'cancelada') DEFAULT 'agendada',
    custo DECIMAL(10, 2),
    responsavel VARCHAR(100),
    pecas_substituidas TEXT,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (trem_id) REFERENCES trens(id) ON DELETE CASCADE,
    INDEX idx_trem_id (trem_id),
    INDEX idx_status (status),
    INDEX idx_tipo (tipo),
    INDEX idx_data_inicio (data_inicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABELA: NOTIFICAÇÕES
CREATE TABLE IF NOT EXISTS notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    operador_id INT,
    tipo ENUM('alerta', 'lembrete', 'aviso', 'sistema') NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    mensagem TEXT NOT NULL,
    link VARCHAR(255),
    prioridade ENUM('baixa', 'media', 'alta') DEFAULT 'media',
    lida BOOLEAN DEFAULT FALSE,
    lida_em DATETIME,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operador_id) REFERENCES operadores(id) ON DELETE CASCADE,
    INDEX idx_operador_id (operador_id),
    INDEX idx_lida (lida),
    INDEX idx_prioridade (prioridade),
    INDEX idx_criado_em (criado_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABELA: RELATÓRIOS
CREATE TABLE IF NOT EXISTS relatorios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('operacional', 'financeiro', 'manutencao', 'incidentes', 'geral') NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    periodo_inicio DATE NOT NULL,
    periodo_fim DATE NOT NULL,
    gerado_por INT NOT NULL,
    formato ENUM('pdf', 'excel', 'csv') DEFAULT 'pdf',
    caminho_arquivo VARCHAR(255),
    dados_json TEXT,
    status ENUM('gerado', 'processando', 'erro') DEFAULT 'gerado',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gerado_por) REFERENCES operadores(id) ON DELETE CASCADE,
    INDEX idx_tipo (tipo),
    INDEX idx_gerado_por (gerado_por),
    INDEX idx_criado_em (criado_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- DADOS DE EXEMPLO
-- =============================================

-- Inserir sensores (SEM ACENTOS)
INSERT INTO sensores (codigo, tipo, localizacao, status, unidade_medida) VALUES
('SENS-001', 'temperatura', 'Estacao Central - Plataforma 1', 'ativo', 'C'),
('SENS-002', 'velocidade', 'Km 15 - Linha Principal', 'ativo', 'km/h'),
('SENS-003', 'pressao', 'Trilho Norte - Secao A', 'ativo', 'bar');

-- Inserir trens
INSERT INTO trens (codigo, modelo, fabricante, ano_fabricacao, capacidade_passageiros, velocidade_maxima, status) VALUES
('TREM-001', 'Serie 5000', 'RailTech', 2020, 300, 120.00, 'operacional'),
('TREM-002', 'Express 3000', 'TrainCorp', 2019, 250, 150.00, 'operacional'),
('TREM-003', 'Metro X1', 'UrbanRail', 2021, 400, 100.00, 'manutencao');

-- Inserir rotas
INSERT INTO rotas (codigo, nome, origem, destino, distancia_km, tempo_estimado, status, preco_base) VALUES
('ROTA-001', 'Linha Central', 'Estacao Norte', 'Estacao Sul', 45.50, '00:45:00', 'ativa', 15.00),
('ROTA-002', 'Linha Expressa', 'Terminal A', 'Terminal B', 80.00, '01:20:00', 'ativa', 25.00);

-- Inserir alertas
INSERT INTO alertas (tipo, titulo, descricao, origem, status) VALUES
('aviso', 'Sensor de temperatura elevada', 'Sensor SENS-001 registrou 45 graus', 'sensor', 'ativo'),
('informativo', 'Manutencao programada', 'TREM-003 entrara em manutencao preventiva', 'sistema', 'ativo');

-- =============================================
-- FIM DO SCRIPT
-- =============================================
