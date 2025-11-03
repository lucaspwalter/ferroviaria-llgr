# Sistema de Gerenciamento Ferroviário LLGR

## 📋 Sobre o Projeto

Sistema completo de gerenciamento ferroviário desenvolvido para controle operacional de trens, rotas, sensores, manutenções e mais. O sistema possui interfaces separadas para operadores e usuários finais.

## ✅ Funcionalidades Implementadas - ATIVIDADE COMPLETA

### **Backend PHP - CRUD Completo**

Todos os módulos possuem as 4 operações CRUD implementadas:

#### 1. **Sensores** (`sensores-backend.php`)
- ✅ Cadastrar sensor
- ✅ Listar todos os sensores
- ✅ Buscar sensor por ID
- ✅ Atualizar sensor
- ✅ Deletar sensor

#### 2. **Trens** (`trens-backend.php`)
- ✅ Cadastrar trem
- ✅ Listar todos os trens
- ✅ Buscar trem por ID
- ✅ Atualizar trem
- ✅ Deletar trem

#### 3. **Rotas** (`rotas-backend.php`)
- ✅ Cadastrar rota
- ✅ Listar todas as rotas
- ✅ Buscar rota por ID
- ✅ Atualizar rota
- ✅ Deletar rota

#### 4. **Itinerários** (`itinerarios-backend.php`)
- ✅ Cadastrar itinerário
- ✅ Listar todos os itinerários (com JOIN de rotas e trens)
- ✅ Buscar itinerário por ID
- ✅ Atualizar itinerário
- ✅ Deletar itinerário

#### 5. **Alertas** (`alertas-backend.php`)
- ✅ Cadastrar alerta
- ✅ Listar todos os alertas
- ✅ Buscar alerta por ID
- ✅ Atualizar alerta (com controle de resolução)
- ✅ Deletar alerta

#### 6. **Manutenções** (`manutencoes-backend.php`)
- ✅ Cadastrar manutenção
- ✅ Listar todas as manutenções (com JOIN de trens)
- ✅ Buscar manutenção por ID
- ✅ Atualizar manutenção (com atualização automática de status do trem)
- ✅ Deletar manutenção

#### 7. **Notificações** (`notificacoes-backend.php`)
- ✅ Cadastrar notificação
- ✅ Listar notificações do operador
- ✅ Buscar notificação por ID
- ✅ Marcar como lida
- ✅ Deletar notificação

#### 8. **Relatórios** (`relatorios-backend.php`)
- ✅ Gerar relatório (com dados JSON)
- ✅ Listar todos os relatórios
- ✅ Buscar relatório por ID
- ✅ Deletar relatório
- ✅ Geração automática de dados por tipo de relatório

#### 9. **Reclamações** (`reclamacoes_backend.php`)
- ✅ Responder reclamação
- ✅ Marcar como resolvida
- ✅ Listar reclamações

---

### **Frontend HTML/JavaScript - Interface Completa**

Todas as páginas possuem:

#### ✅ **Formulários de Cadastro**
- Validação de campos obrigatórios
- Mensagens de erro dinâmicas
- Pré-preenchimento automático para edição
- Reset de formulário

#### ✅ **Tabelas de Consulta**
- Carregamento dinâmico via AJAX
- Formatação de dados (datas, moedas, status)
- Badges coloridos por status
- Estados de loading e empty state

#### ✅ **Ações de Edição**
- Botão de editar em cada registro
- Busca do registro por ID
- Preenchimento automático do formulário
- Alteração do título do formulário
- Scroll suave para o topo da página

#### ✅ **Ações de Exclusão**
- Botão de excluir em cada registro
- Mensagem de confirmação
- Exclusão via AJAX
- Atualização automática da tabela

#### ✅ **Feedback Visual**
- Alertas de sucesso/erro
- Loading states
- Animações suaves
- Design responsivo

---

## 📁 Estrutura de Arquivos

```
ferroviaria-llgr/
├── operator/                      # Interface do Operador
│   ├── php/
│   │   ├── sensores.php          ✅ CRUD Completo
│   │   ├── trens.php             ✅ CRUD Completo
│   │   ├── rotas.php             ✅ CRUD Completo
│   │   ├── itinerarios.php       ✅ CRUD Completo
│   │   ├── alertas.php           ✅ CRUD Completo
│   │   ├── manutencoes.php       ✅ CRUD Completo
│   │   ├── notificacoes.php      ✅ CRUD Completo
│   │   ├── relatorios.php        ✅ CRUD Completo
│   │   ├── reclamacoes.php       ✅ Gerenciamento
│   │   ├── dashboard.php
│   │   ├── login.php
│   │   └── logout.php
│   ├── js/
│   │   └── gerenciamento.js      ✅ Funções CRUD reutilizáveis
│   └── css/
│       ├── gerenciamento.css
│       └── navbar.css
├── operator-backend/              # Backend API
│   ├── sensores-backend.php      ✅ CRUD Completo
│   ├── trens-backend.php         ✅ CRUD Completo
│   ├── rotas-backend.php         ✅ CRUD Completo
│   ├── itinerarios-backend.php   ✅ CRUD Completo
│   ├── alertas-backend.php       ✅ CRUD Completo
│   ├── manutencoes-backend.php   ✅ CRUD Completo
│   ├── notificacoes-backend.php  ✅ CRUD Completo
│   ├── relatorios-backend.php    ✅ CRUD Completo
│   └── reclamacoes_backend.php   ✅ Ações
├── user/                          # Interface do Usuário
│   └── php/
│       ├── cadastro.php
│       ├── login.php
│       ├── perfil.php
│       ├── rotas_usuario.php
│       └── notificacoes_usuario.php
└── user-backend/                  # Backend do Usuário
    ├── conexao.php               ✅ Conexão com BD
    ├── cadastro_backend.php
    ├── login_backend.php
    └── perfil_backend.php
```

---

## 🎯 Requisitos da Atividade - STATUS

### ✅ **1. Implementação de Edição**
- [x] Funcionalidade de edição para todos os módulos
- [x] Formulários pré-preenchidos com dados existentes
- [x] Validação de campos antes da atualização
- [x] Atualização no banco de dados via SQL UPDATE
- [x] Feedback visual de sucesso/erro

### ✅ **2. Implementação de Exclusão**
- [x] Funcionalidade de exclusão para todos os módulos
- [x] Confirmação antes da exclusão
- [x] Remoção do banco de dados via SQL DELETE
- [x] Atualização automática da interface
- [x] Tratamento de erros

### ✅ **3. Implementação de Consulta**
- [x] Listagem de todos os dados armazenados
- [x] Dados organizados em tabelas responsivas
- [x] Formatação adequada (datas, moedas, status)
- [x] Recuperação via SQL SELECT com JOINs quando necessário
- [x] Interface clara e responsiva
- [x] Estados de loading e empty

---

## 🔧 Tecnologias Utilizadas

- **Backend**: PHP 7.4+
- **Banco de Dados**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **AJAX**: Fetch API
- **Design**: Responsivo, Mobile-first

---

## 🚀 Como Usar

### Pré-requisitos
1. XAMPP ou WAMP instalado
2. MySQL rodando
3. PHP 7.4 ou superior

### Instalação
1. Clone o repositório na pasta `htdocs` do XAMPP
2. Importe o banco de dados (arquivo SQL necessário)
3. Configure a conexão em `user-backend/conexao.php`
4. Acesse: `http://localhost/ferroviaria-llgr/operator/php/login.php`

### Credenciais de Teste
```
Operador:
Email: operador@llgr.com
Senha: [definida no banco]
```

---

## 📊 Funcionalidades Destacadas

### **Gerenciamento de Sensores**
- Cadastro com código único, tipo, localização GPS
- Status: ativo, inativo, manutenção
- Unidade de medida configurável

### **Gerenciamento de Trens**
- Controle de capacidade, velocidade máxima
- Histórico de km rodados
- Integração com manutenções

### **Gerenciamento de Rotas**
- Origem, destino, distância
- Tempo estimado de viagem
- Paradas intermediárias
- Preço base

### **Gerenciamento de Itinerários**
- Vinculação com rotas e trens
- Controle de horários
- Status: agendado, em andamento, concluído
- Contagem de passageiros

### **Sistema de Alertas**
- Prioridades de 1 a 10
- Tipos: crítico, urgente, aviso, informativo
- Controle de resolução
- Histórico de ações tomadas

### **Controle de Manutenções**
- Tipos: preventiva, corretiva, emergencial
- Controle de custos
- Peças substituídas
- Atualização automática do status do trem

### **Geração de Relatórios**
- Relatórios operacionais, financeiros, manutenção
- Período configurável
- Dados em JSON
- Múltiplos formatos (PDF, Excel, CSV)

---

## ✨ Recursos Adicionais

- **Segurança**: Validação de sessão em todas as páginas
- **Sanitização**: Proteção contra SQL Injection com prepared statements
- **UX**: Feedback visual imediato para todas as ações
- **Responsivo**: Interface adaptada para mobile
- **Performance**: Carregamento assíncrono via AJAX
- **Organização**: Código modular e reutilizável

---

## 📝 Observações Importantes

1. **Todas as funcionalidades de CRUD estão 100% implementadas**
2. **Backend e Frontend totalmente integrados**
3. **Validações client-side e server-side**
4. **Mensagens de erro e sucesso amigáveis**
5. **Confirmação antes de exclusões**
6. **Formulários com reset automático após salvar**
7. **Atualização automática das tabelas**

---

## 👨‍💻 Desenvolvido para

Atividade de Desenvolvimento de Sistemas
**Técnico em Desenvolvimento de Sistemas**

---

## 📫 Suporte

Para dúvidas ou problemas:
- Verifique se o XAMPP está rodando
- Confirme se o banco de dados foi importado corretamente
- Verifique as credenciais em `conexao.php`
- Acesse o console do navegador para erros JavaScript
- Verifique os logs do PHP para erros do servidor

---

**Status da Atividade: ✅ COMPLETA - Todas as funcionalidades implementadas e testadas**