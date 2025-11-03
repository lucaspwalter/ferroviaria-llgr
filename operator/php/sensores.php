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
            <h1 class="page-title">Gerenciamento de Sensores</h1>
            <div id="alert" class="alert"></div>
            <div class="card">
                <h2 class="card-title" id="formTitle">Cadastrar Novo Sensor</h2>
                <form id="sensorForm" onsubmit="return submitForm('sensorForm', '../../operator-backend/sensores-backend.php')">
                    <input type="hidden" id="id" name="id">
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
                        <button type="submit" class="btn btn-primary" id="submitBtn">Salvar Sensor</button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Cancelar</button>
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
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="sensoresTableBody">
                            <tr><td colspan="7" class="loading">Carregando dados</td></tr>
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
                <td>
                    <button class="btn-action btn-edit" onclick="editSensor(${sensor.id})" title="Editar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteSensor(${sensor.id})" title="Excluir">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </td>
            `;
            return tr;
        }
        
        async function editSensor(id) {
            try {
                const response = await fetch(`${backendUrl}?acao=buscar&id=${id}`);
                const result = await response.json();
                
                if (result.sucesso) {
                    const sensor = result.dados;
                    document.getElementById('id').value = sensor.id;
                    document.getElementById('codigo').value = sensor.codigo;
                    document.getElementById('tipo').value = sensor.tipo;
                    document.getElementById('localizacao').value = sensor.localizacao;
                    document.getElementById('latitude').value = sensor.latitude || '';
                    document.getElementById('longitude').value = sensor.longitude || '';
                    document.getElementById('status').value = sensor.status;
                    document.getElementById('unidade_medida').value = sensor.unidade_medida || '';
                    document.getElementById('observacoes').value = sensor.observacoes || '';
                    
                    document.getElementById('formTitle').textContent = 'Editar Sensor';
                    document.getElementById('submitBtn').textContent = 'Atualizar Sensor';
                    document.getElementById('sensorForm').onsubmit = function() {
                        return submitForm('sensorForm', backendUrl, 'atualizar');
                    };
                    
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showAlert('Erro ao carregar dados do sensor', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showAlert('Erro ao comunicar com o servidor', 'error');
            }
        }
        
        function deleteSensor(id) {
            deleteRecord(id, backendUrl, 'Tem certeza que deseja excluir este sensor?');
        }
        
        function resetForm() {
            document.getElementById('sensorForm').reset();
            document.getElementById('id').value = '';
            document.getElementById('formTitle').textContent = 'Cadastrar Novo Sensor';
            document.getElementById('submitBtn').textContent = 'Salvar Sensor';
            document.getElementById('sensorForm').onsubmit = function() {
                return submitForm('sensorForm', backendUrl);
            };
        }
        function loadDataTable() {
            loadData(backendUrl, 'sensoresTableBody');
        }
        window.addEventListener('DOMContentLoaded', loadDataTable);
    </script>
</body>
</html>