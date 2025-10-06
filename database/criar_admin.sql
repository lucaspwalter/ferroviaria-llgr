-- =============================================
-- CRIAR USUÁRIO ADMINISTRADOR
-- Execute este script no phpMyAdmin após criar o banco
-- =============================================

USE ferrorama;

-- Criar operador administrador
-- Email: admin@llgr.com
-- Senha: password
INSERT INTO operadores (nome, email, senha, cargo, ativo) 
VALUES ('Admin Sistema', 'admin@llgr.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', TRUE);

-- Verificar se foi criado
SELECT * FROM operadores WHERE email = 'admin@llgr.com';

-- =============================================
-- CREDENCIAIS DE LOGIN:
-- Email: admin@llgr.com
-- Senha: password
-- =============================================
