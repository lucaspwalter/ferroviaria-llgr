# 🚆 Sistema de Gerenciamento Ferroviário LLGR

## 📋 Sobre o Projeto

Sistema completo de gerenciamento ferroviário desenvolvido para a atividade SA (Situação de Aprendizagem).

## ✅ Funcionalidades Implementadas

- ✅ Gerenciamento de Sensores
- ✅ Gerenciamento de Trens
- ✅ Gerenciamento de Rotas
- ✅ Gerenciamento de Itinerários
- ✅ Gerenciamento de Alertas
- ✅ Gerenciamento de Manutenções
- ✅ Gerenciamento de Notificações
- ✅ Gerenciamento de Relatórios

## 🚀 Instalação

1. **Instale o XAMPP** (Apache + MySQL + PHP)

2. **Configure o Banco de Dados:**
   - Acesse http://localhost/phpmyadmin
   - Crie o banco `ferrorama`
   - Importe o arquivo `database/schema.sql`

3. **Crie um operador:**
```sql
INSERT INTO operadores (nome, email, senha, cargo, ativo) 
VALUES ('Admin', 'admin@llgr.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', TRUE);
```

4. **Acesse o sistema:**
   - Login: http://localhost/ferroviaria-llgr/operator/php/login.php
   - Email: admin@llgr.com
   - Senha: password

## 📁 Estrutura

```
ferroviaria-llgr/
├── database/schema.sql          # Script do banco de dados
├── operator/
│   ├── css/                     # Estilos
│   ├── js/                      # JavaScript
│   └── php/                     # Páginas PHP (8 módulos)
└── operator-backend/            # APIs PHP (8 backends)
```

## 🔌 APIs Disponíveis

Todas as APIs retornam JSON e aceitam:
- `?acao=cadastrar` (POST)
- `?acao=listar` (GET)
- `?acao=buscar&id={id}` (GET)
- `?acao=atualizar` (POST)
- `?acao=deletar` (POST)

**Endpoints:**
- `operator-backend/sensores-backend.php`
- `operator-backend/trens-backend.php`
- `operator-backend/rotas-backend.php`
- `operator-backend/itinerarios-backend.php`
- `operator-backend/alertas-backend.php`
- `operator-backend/manutencoes-backend.php`
- `operator-backend/notificacoes-backend.php`
- `operator-backend/relatorios-backend.php`

## 💻 Tecnologias

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 7.4+
- **Banco:** MySQL/MariaDB
- **Design:** Responsivo, Mobile First

## 📝 Validações

- ✅ Campos obrigatórios
- ✅ Códigos únicos
- ✅ Formatos de dados
- ✅ Feedback visual
- ✅ Mensagens de erro/sucesso

## 🎨 Design

- Tema escuro moderno
- Paleta: #1A1A1A, #242424, #E50000
- Fontes: Inter e Poppins
- Totalmente responsivo

## 📊 Demonstração

Todas as páginas possuem:
1. Formulário de cadastro com validação
2. Tabela de listagem automática
3. Feedback visual de sucesso/erro
4. Design consistente e responsivo

---

**Desenvolvido para:** Técnico em Desenvolvimento de Sistemas  
**Ano:** 2024
