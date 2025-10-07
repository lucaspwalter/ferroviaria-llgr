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
    <title>Gerenciamento de Relatórios - LLGR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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
                <li><a href="notificacoes.php">Notificações</a></li>
                <li><a href="relatorios.php">Relatórios</a></li>
                <li><a href="reclamacoes.php">Reclamações</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main class="main-content-wrapper">
        <section class="management-section">
            <h1 class="page-title">Gerenciamento de Relatórios</h1>
            <div id="alert" class="alert"></div>
            <div class="card">
                <h2 class="card-title">Gerar Novo Relatório</h2>
                <form id="relatorioForm" onsubmit="return submitForm('relatorioForm', '../../operator-backend/relatorios-backend.php')">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tipo">Tipo de Relatório <span class="required">*</span></label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="operacional">Operacional</option>
                                <option value="financeiro">Financeiro</option>
                                <option value="manutencao">Manutenção</option>
                                <option value="incidentes">Incidentes</option>
                                <option value="geral">Geral</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="formato">Formato</label>
                            <select id="formato" name="formato" class="form-control">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Título do Relatório <span class="required">*</span></label>
                        <input type="text" id="titulo" name="titulo" class="form-control" 
                               placeholder="Ex: Relatório Operacional - Outubro 2024" required maxlength="200">
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" class="form-control" 
                                  placeholder="Descreva o objetivo do relatório"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="periodo_inicio">Período - Início <span class="required">*</span></label>
                            <input type="date" id="periodo_inicio" name="periodo_inicio" class="form-control" required>
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="periodo_fim">Período - Fim <span class="required">*</span></label>
                            <input type="date" id="periodo_fim" name="periodo_fim" class="form-control" required>
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                        <button type="reset" class="btn btn-secondary">Limpar Formulário</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2 class="card-title">Relatórios Gerados</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Tipo</th>
                                <th>Período</th>
                                <th>Formato</th>
                                <th>Gerado em</th>
                                <th>Gerado por</th>
                            </tr>
                        </thead>
                        <tbody id="relatoriosTableBody">
                            <tr><td colspan="6" class="loading">Carregando dados</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <script src="../js/mobile-navbar.js"></script>
    <script src="../js/gerenciamento.js"></script>
    <script>
        const backendUrl = '../../operator-backend/relatorios-backend.php';
        function createTableRow(relatorio) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${relatorio.titulo}</td>
                <td><span class="badge badge-info">${relatorio.tipo}</span></td>
                <td>${formatDate(relatorio.periodo_inicio)} até ${formatDate(relatorio.periodo_fim)}</td>
                <td><span class="badge badge-secondary">${relatorio.formato.toUpperCase()}</span></td>
                <td>${formatDateTime(relatorio.criado_em)}</td>
                <td>${relatorio.operador_nome || '-'}</td>
            `;
            return tr;
        }
        function loadDataTable() {
            loadData(backendUrl, 'relatoriosTableBody');
        }
        window.addEventListener('DOMContentLoaded', loadDataTable);
    </script>
</body>
</html>