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
    <title>Gerenciamento de Itinerários - LLGR</title>
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
            <h1 class="page-title">Gerenciamento de Itinerários</h1>
            <div id="alert" class="alert"></div>
            <div class="card">
                <h2 class="card-title" id="formTitle">Cadastrar Novo Itinerário</h2>
                <form id="itinerarioForm" onsubmit="return submitForm('itinerarioForm', '../../operator-backend/itinerarios-backend.php')">
                    <input type="hidden" id="id" name="id">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="codigo">Código <span class="required">*</span></label>
                            <input type="text" id="codigo" name="codigo" class="form-control" 
                                   placeholder="Ex: ITIN-001" required maxlength="50">
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="rota_id">Rota <span class="required">*</span></label>
                            <select id="rota_id" name="rota_id" class="form-control" required>
                                <option value="">Selecione...</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="trem_id">Trem</label>
                            <select id="trem_id" name="trem_id" class="form-control">
                                <option value="">Selecione...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data_partida">Data de Partida <span class="required">*</span></label>
                            <input type="date" id="data_partida" name="data_partida" class="form-control" required>
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label for="hora_partida">Hora de Partida <span class="required">*</span></label>
                            <input type="time" id="hora_partida" name="hora_partida" class="form-control" required>
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="hora_chegada_prevista">Hora de Chegada <span class="required">*</span></label>
                            <input type="time" id="hora_chegada_prevista" name="hora_chegada_prevista" class="form-control" required>
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="passageiros_embarcados">Passageiros</label>
                            <input type="number" id="passageiros_embarcados" name="passageiros_embarcados" 
                                   class="form-control" placeholder="0" min="0" value="0">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="agendado">Agendado</option>
                                <option value="em_andamento">Em Andamento</option>
                                <option value="concluido">Concluído</option>
                                <option value="cancelado">Cancelado</option>
                                <option value="atrasado">Atrasado</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" class="form-control" 
                                  placeholder="Informações adicionais sobre o itinerário"></textarea>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Salvar Itinerário</button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Cancelar</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2 class="card-title">Itinerários Cadastrados</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Rota</th>
                                <th>Trem</th>
                                <th>Data</th>
                                <th>Partida</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="itinerariosTableBody">
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
        const backendUrl = '../../operator-backend/itinerarios-backend.php';
        
        window.addEventListener('DOMContentLoaded', function() {
            loadSelect('../../operator-backend/rotas-backend.php', 'rota_id', 'id', 'nome');
            loadSelect('../../operator-backend/trens-backend.php', 'trem_id', 'id', 'codigo');
            loadDataTable();
        });
        
        function createTableRow(itinerario) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${itinerario.codigo}</td>
                <td>${itinerario.rota_nome || '-'}</td>
                <td>${itinerario.trem_codigo || '-'}</td>
                <td>${formatDate(itinerario.data_partida)}</td>
                <td>${itinerario.hora_partida}</td>
                <td>${getStatusBadge(itinerario.status)}</td>
                <td>
                    <button class="btn-action btn-edit" onclick="editItinerario(${itinerario.id})" title="Editar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteItinerario(${itinerario.id})" title="Excluir">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </td>
            `;
            return tr;
        }
        
        async function editItinerario(id) {
            try {
                const response = await fetch(`${backendUrl}?acao=buscar&id=${id}`);
                const result = await response.json();
                
                if (result.sucesso) {
                    const itinerario = result.dados;
                    document.getElementById('id').value = itinerario.id;
                    document.getElementById('codigo').value = itinerario.codigo;
                    document.getElementById('rota_id').value = itinerario.rota_id;
                    document.getElementById('trem_id').value = itinerario.trem_id || '';
                    document.getElementById('data_partida').value = itinerario.data_partida;
                    document.getElementById('hora_partida').value = itinerario.hora_partida;
                    document.getElementById('hora_chegada_prevista').value = itinerario.hora_chegada_prevista;
                    document.getElementById('passageiros_embarcados').value = itinerario.passageiros_embarcados || 0;
                    document.getElementById('status').value = itinerario.status;
                    document.getElementById('observacoes').value = itinerario.observacoes || '';
                    
                    document.getElementById('formTitle').textContent = 'Editar Itinerário';
                    document.getElementById('submitBtn').textContent = 'Atualizar Itinerário';
                    document.getElementById('itinerarioForm').onsubmit = function() {
                        return submitForm('itinerarioForm', backendUrl, 'atualizar');
                    };
                    
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showAlert('Erro ao carregar dados do itinerário', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showAlert('Erro ao comunicar com o servidor', 'error');
            }
        }
        
        function deleteItinerario(id) {
            deleteRecord(id, backendUrl, 'Tem certeza que deseja excluir este itinerário?');
        }
        
        function resetForm() {
            document.getElementById('itinerarioForm').reset();
            document.getElementById('id').value = '';
            document.getElementById('formTitle').textContent = 'Cadastrar Novo Itinerário';
            document.getElementById('submitBtn').textContent = 'Salvar Itinerário';
            document.getElementById('itinerarioForm').onsubmit = function() {
                return submitForm('itinerarioForm', backendUrl);
            };
        }
        
        function loadDataTable() {
            loadData(backendUrl, 'itinerariosTableBody');
        }
    </script>
</body>
</html>