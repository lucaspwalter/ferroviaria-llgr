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
                <form id="sensorForm">
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
                            <tr><td colspan="7" class="loading">Carregando dados...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <script src="../js/mobile-navbar.js"></script>
    <script>
        // URL do backend - CAMINHO ABSOLUTO
        const BACKEND_URL = '/ferroviaria-llgr/operator-backend/sensores-backend.php';
        
        // Função para mostrar alertas
        function showAlert(message, type = 'success') {
            const alertDiv = document.getElementById('alert');
            alertDiv.className = `alert alert-${type} show`;
            alertDiv.textContent = message;
            setTimeout(() => alertDiv.classList.remove('show'), 5000);
        }
        
        // Função para formatar data
        function formatDateTime(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleString('pt-BR');
        }
        
        // Função para criar badge de status
        function getStatusBadge(status) {
            const colors = {
                'ativo': 'success',
                'inativo': 'secondary',
                'manutencao': 'warning'
            };
            return `<span class="badge badge-${colors[status] || 'secondary'}">${status}</span>`;
        }
        
        // Criar linha da tabela
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
                    <button class="btn-action btn-edit" onclick="editSensor(${sensor.id})" title="Editar">✏️</button>
                    <button class="btn-action btn-delete" onclick="deleteSensor(${sensor.id})" title="Excluir">🗑️</button>
                </td>
            `;
            return tr;
        }
        
        // Carregar sensores
        async function loadSensores() {
            const tbody = document.getElementById('sensoresTableBody');
            tbody.innerHTML = '<tr><td colspan="7" class="loading">Carregando...</td></tr>';
            
            try {
                const response = await fetch(BACKEND_URL + '?acao=listar');
                const result = await response.json();
                
                if (result.sucesso) {
                    tbody.innerHTML = '';
                    if (result.dados.length > 0) {
                        result.dados.forEach(sensor => {
                            tbody.appendChild(createTableRow(sensor));
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Nenhum sensor cadastrado</td></tr>';
                    }
                } else {
                    tbody.innerHTML = '<tr><td colspan="7" class="error">Erro ao carregar: ' + result.mensagem + '</td></tr>';
                }
            } catch (error) {
                console.error('Erro:', error);
                tbody.innerHTML = '<tr><td colspan="7" class="error">Erro ao carregar dados</td></tr>';
            }
        }
        
        // Editar sensor
        async function editSensor(id) {
            try {
                const response = await fetch(BACKEND_URL + '?acao=buscar&id=' + id);
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
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showAlert('Erro ao carregar sensor', 'error');
                }
            } catch (error) {
                showAlert('Erro ao comunicar com servidor', 'error');
            }
        }
        
        // Deletar sensor
        async function deleteSensor(id) {
            if (!confirm('Tem certeza que deseja excluir este sensor?')) return;
            
            const formData = new FormData();
            formData.append('acao', 'deletar');
            formData.append('id', id);
            
            try {
                const response = await fetch(BACKEND_URL, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                if (result.sucesso) {
                    showAlert(result.mensagem, 'success');
                    loadSensores();
                } else {
                    showAlert(result.mensagem, 'error');
                }
            } catch (error) {
                showAlert('Erro ao excluir', 'error');
            }
        }
        
        // Resetar formulário
        function resetForm() {
            document.getElementById('sensorForm').reset();
            document.getElementById('id').value = '';
            document.getElementById('formTitle').textContent = 'Cadastrar Novo Sensor';
            document.getElementById('submitBtn').textContent = 'Salvar Sensor';
        }
        
        // Submit do formulário
        document.getElementById('sensorForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const id = document.getElementById('id').value;
            formData.append('acao', id ? 'atualizar' : 'cadastrar');
            
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'Salvando...';
            
            try {
                const response = await fetch(BACKEND_URL, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.sucesso) {
                    showAlert(result.mensagem, 'success');
                    resetForm();
                    loadSensores();
                } else {
                    showAlert(result.mensagem, 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showAlert('Erro ao salvar: ' + error.message, 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = id ? 'Atualizar Sensor' : 'Salvar Sensor';
            }
        });
        
        // Carregar ao iniciar
        window.addEventListener('DOMContentLoaded', loadSensores);
    </script>
</body>
</html>
