<?php
session_start();
if (!isset($_SESSION['operador_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Notificações - LLGR</title>
    <link rel="preconnect" href="https:
    <link rel="preconnect" href="https:
    <link href="https:
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/gerenciamento.css">
</head>
<body>
    <header>
        <nav>
            <a class="logo" href="dashboard.php">LLGR</a>
            <div class="mobile-menu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="sensores.php">Sensores</a></li>
                <li><a href="trens.php">Trens</a></li>
                <li><a href="rotas.php">Rotas</a></li>
                <li><a href="itinerarios.php">Itinerários</a></li>
                <li><a href="alertas.php">Alertas</a></li>
                <li><a href="manutencoes.php">Manutenções</a></li>
                <li><a href="../../index.html">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main class="main-content-wrapper">
        <section class="management-section">
            <h1 class="page-title">Gerenciamento de Notificações</h1>
            <div id="alert" class="alert"></div>
            <div class="card">
                <h2 class="card-title">Cadastrar Nova Notificação</h2>
                <form id="notificacaoForm" onsubmit="return submitForm('notificacaoForm', '../../operator-backend/notificacoes-backend.php')">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tipo">Tipo <span class="required">*</span></label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="alerta">Alerta</option>
                                <option value="lembrete">Lembrete</option>
                                <option value="aviso">Aviso</option>
                                <option value="sistema">Sistema</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="prioridade">Prioridade</label>
                            <select id="prioridade" name="prioridade" class="form-control">
                                <option value="baixa">Baixa</option>
                                <option value="media" selected>Média</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Título <span class="required">*</span></label>
                        <input type="text" id="titulo" name="titulo" class="form-control" 
                               placeholder="Ex: Lembrete de manutenção" required maxlength="200">
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="mensagem">Mensagem <span class="required">*</span></label>
                        <textarea id="mensagem" name="mensagem" class="form-control" 
                                  placeholder="Digite a mensagem da notificação" required></textarea>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="link">Link (opcional)</label>
                        <input type="url" id="link" name="link" class="form-control" 
                               placeholder="https:
                        <small class="help-text">URL de redirecionamento ao clicar na notificação</small>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">Enviar Notificação</button>
                        <button type="reset" class="btn btn-secondary">Limpar Formulário</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2 class="card-title">Minhas Notificações</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Título</th>
                                <th>Prioridade</th>
                                <th>Status</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody id="notificacoesTableBody">
                            <tr><td colspan="5" class="loading">Carregando dados</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <script src="../js/mobile-navbar.js"></script>
    <script src="../js/gerenciamento.js"></script>
    <script>
        const backendUrl = '../../operator-backend/notificacoes-backend.php';
        function createTableRow(notificacao) {
            const tr = document.createElement('tr');
            const statusBadge = notificacao.lida ? 
                '<span class="badge badge-secondary">Lida</span>' : 
                '<span class="badge badge-success">Não lida</span>';
            tr.innerHTML = `
                <td><span class="badge badge-info">${notificacao.tipo}</span></td>
                <td>${notificacao.titulo}</td>
                <td><span class="badge badge-${notificacao.prioridade === 'alta' ? 'danger' : (notificacao.prioridade === 'média' ? 'warning' : 'secondary')}">${notificacao.prioridade}</span></td>
                <td>${statusBadge}</td>
                <td>${formatDateTime(notificacao.criado_em)}</td>
            `;
            return tr;
        }
        function loadDataTable() {
            loadData(backendUrl, 'notificacoesTableBody');
        }
        window.addEventListener('DOMContentLoaded', loadDataTable);
    </script>
</body>
</html>