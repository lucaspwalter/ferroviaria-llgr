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
    <title>Gerenciamento de Alertas - LLGR</title>
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
                <li><a href="notificacoes.php">Notificações</a></li>
                <li><a href="relatorios.php">Relatórios</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main class="main-content-wrapper">
        <section class="management-section">
            <h1 class="page-title">Gerenciamento de Alertas</h1>
            <div id="alert" class="alert"></div>
            <div class="card">
                <h2 class="card-title">Cadastrar Novo Alerta</h2>
                <form id="alertaForm" onsubmit="return submitForm('alertaForm', '../../operator-backend/alertas-backend.php')">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tipo">Tipo <span class="required">*</span></label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="critico">Crítico</option>
                                <option value="urgente">Urgente</option>
                                <option value="aviso">Aviso</option>
                                <option value="informativo">Informativo</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="origem">Origem <span class="required">*</span></label>
                            <select id="origem" name="origem" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="sensor">Sensor</option>
                                <option value="sistema">Sistema</option>
                                <option value="operador">Operador</option>
                                <option value="trem">Trem</option>
                                <option value="rota">Rota</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="prioridade">Prioridade (1-10)</label>
                            <input type="number" id="prioridade" name="prioridade" class="form-control" 
                                   placeholder="1" min="1" max="10" value="1">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="ativo">Ativo</option>
                                <option value="em_analise">Em Análise</option>
                                <option value="resolvido">Resolvido</option>
                                <option value="ignorado">Ignorado</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Título <span class="required">*</span></label>
                        <input type="text" id="titulo" name="titulo" class="form-control" 
                               placeholder="Ex: Sensor de temperatura elevada" required maxlength="200">
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição <span class="required">*</span></label>
                        <textarea id="descricao" name="descricao" class="form-control" 
                                  placeholder="Descreva o alerta detalhadamente" required></textarea>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="localizacao">Localização</label>
                        <input type="text" id="localizacao" name="localizacao" class="form-control" 
                               placeholder="Ex: Km 15 - Linha Principal" maxlength="200">
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">Salvar Alerta</button>
                        <button type="reset" class="btn btn-secondary">Limpar Formulário</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2 class="card-title">Alertas Cadastrados</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Título</th>
                                <th>Origem</th>
                                <th>Prioridade</th>
                                <th>Status</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody id="alertasTableBody">
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
        const backendUrl = '../../operator-backend/alertas-backend.php';
        function createTableRow(alerta) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${getStatusBadge(alerta.tipo)}</td>
                <td>${alerta.titulo}</td>
                <td>${alerta.origem}</td>
                <td><span class="badge badge-info">${alerta.prioridade}</span></td>
                <td>${getStatusBadge(alerta.status)}</td>
                <td>${formatDateTime(alerta.criado_em)}</td>
            `;
            return tr;
        }
        function loadDataTable() {
            loadData(backendUrl, 'alertasTableBody');
        }
        window.addEventListener('DOMContentLoaded', loadDataTable);
    </script>
</body>
</html>