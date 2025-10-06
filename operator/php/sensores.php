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
    <title>Gerenciamento de Sensores - LLGR</title>
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
            <h1 class="page-title">Gerenciamento de Sensores</h1>
            <div id="alert" class="alert"></div>
            <div class="card">
                <h2 class="card-title">Cadastrar Novo Sensor</h2>
                <form id="sensorForm" onsubmit="return submitForm('sensorForm', '../../operator-backend/sensores-backend.php')">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="codigo">Código <span class="required">*</span></label>
                            <input type="text" id="codigo" name="codigo" class="form-control" 
                                   placeholder="Ex: SENS-001" required maxlength="50">
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="tipo">Tipo <span class="required">*</span></label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="temperatura">Temperatura</option>
                                <option value="pressao">Pressão</option>
                                <option value="velocidade">Velocidade</option>
                                <option value="proximidade">Proximidade</option>
                                <option value="vibracao">Vibração</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="localizacao">Localização <span class="required">*</span></label>
                        <input type="text" id="localizacao" name="localizacao" class="form-control" 
                               placeholder="Ex: Estação Central - Plataforma 1" required maxlength="200">
                        <div class="error-message"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="number" id="latitude" name="latitude" class="form-control" 
                                   placeholder="-26.3045" step="0.00000001">
                        </div>
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="number" id="longitude" name="longitude" class="form-control" 
                                   placeholder="-48.8487" step="0.00000001">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo</option>
                                <option value="manutencao">Manutenção</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="unidade_medida">Unidade de Medida</label>
                            <input type="text" id="unidade_medida" name="unidade_medida" class="form-control" 
                                   placeholder="Ex: °C, km/h, bar" maxlength="20">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" class="form-control" 
                                  placeholder="Informações adicionais sobre o sensor"></textarea>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">Salvar Sensor</button>
                        <button type="reset" class="btn btn-secondary">Limpar Formulário</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2 class="card-title">Sensores Cadastrados</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Tipo</th>
                                <th>Localização</th>
                                <th>Status</th>
                                <th>Unidade</th>
                                <th>Cadastrado em</th>
                            </tr>
                        </thead>
                        <tbody id="sensoresTableBody">
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
        const backendUrl = '../../operator-backend/sensores-backend.php';
        function createTableRow(sensor) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${sensor.codigo}</td>
                <td>${sensor.tipo}</td>
                <td>${sensor.localizacao}</td>
                <td>${getStatusBadge(sensor.status)}</td>
                <td>${sensor.unidade_medida || '-'}</td>
                <td>${formatDateTime(sensor.criado_em)}</td>
            `;
            return tr;
        }
        function loadDataTable() {
            loadData(backendUrl, 'sensoresTableBody');
        }
        window.addEventListener('DOMContentLoaded', loadDataTable);
    </script>
</body>
</html>