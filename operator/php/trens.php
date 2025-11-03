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
    <title>Gerenciamento de Trens - LLGR</title>
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
            <h1 class="page-title">Gerenciamento de Trens</h1>
            <div id="alert" class="alert"></div>
            <div class="card">
                <h2 class="card-title" id="formTitle">Cadastrar Novo Trem</h2>
                <form id="tremForm" onsubmit="return submitForm('tremForm', '../../operator-backend/trens-backend.php')">
                    <input type="hidden" id="id" name="id">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="codigo">Código <span class="required">*</span></label>
                            <input type="text" id="codigo" name="codigo" class="form-control" 
                                   placeholder="Ex: TREM-001" required maxlength="50">
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="modelo">Modelo <span class="required">*</span></label>
                            <input type="text" id="modelo" name="modelo" class="form-control" 
                                   placeholder="Ex: Serie 5000" required maxlength="100">
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fabricante">Fabricante</label>
                            <input type="text" id="fabricante" name="fabricante" class="form-control" 
                                   placeholder="Ex: RailTech" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="ano_fabricacao">Ano de Fabricação</label>
                            <input type="number" id="ano_fabricacao" name="ano_fabricacao" class="form-control" 
                                   placeholder="2020" min="1900" max="2100">
                        </div>
                    </div>
                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label for="capacidade_passageiros">Capacidade <span class="required">*</span></label>
                            <input type="number" id="capacidade_passageiros" name="capacidade_passageiros" 
                                   class="form-control" placeholder="300" required min="1">
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="velocidade_maxima">Velocidade Máx (km/h)</label>
                            <input type="number" id="velocidade_maxima" name="velocidade_maxima" 
                                   class="form-control" placeholder="120" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="km_rodados">Km Rodados</label>
                            <input type="number" id="km_rodados" name="km_rodados" class="form-control" 
                                   placeholder="0" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="operacional">Operacional</option>
                                <option value="manutencao">Manutenção</option>
                                <option value="inativo">Inativo</option>
                                <option value="em_rota">Em Rota</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" class="form-control" 
                                  placeholder="Informações adicionais sobre o trem"></textarea>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Salvar Trem</button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Cancelar</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2 class="card-title">Trens Cadastrados</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Modelo</th>
                                <th>Fabricante</th>
                                <th>Capacidade</th>
                                <th>Vel. Máx</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="trensTableBody">
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
        const backendUrl = '../../operator-backend/trens-backend.php';
        
        function createTableRow(trem) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${trem.codigo}</td>
                <td>${trem.modelo}</td>
                <td>${trem.fabricante || '-'}</td>
                <td>${trem.capacidade_passageiros} passageiros</td>
                <td>${trem.velocidade_maxima ? trem.velocidade_maxima + ' km/h' : '-'}</td>
                <td>${getStatusBadge(trem.status)}</td>
                <td>
                    <button class="btn-action btn-edit" onclick="editTrem(${trem.id})" title="Editar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteTrem(${trem.id})" title="Excluir">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </td>
            `;
            return tr;
        }
        
        async function editTrem(id) {
            try {
                const response = await fetch(`${backendUrl}?acao=buscar&id=${id}`);
                const result = await response.json();
                
                if (result.sucesso) {
                    const trem = result.dados;
                    document.getElementById('id').value = trem.id;
                    document.getElementById('codigo').value = trem.codigo;
                    document.getElementById('modelo').value = trem.modelo;
                    document.getElementById('fabricante').value = trem.fabricante || '';
                    document.getElementById('ano_fabricacao').value = trem.ano_fabricacao || '';
                    document.getElementById('capacidade_passageiros').value = trem.capacidade_passageiros;
                    document.getElementById('velocidade_maxima').value = trem.velocidade_maxima || '';
                    document.getElementById('km_rodados').value = trem.km_rodados || 0;
                    document.getElementById('status').value = trem.status;
                    document.getElementById('observacoes').value = trem.observacoes || '';
                    
                    document.getElementById('formTitle').textContent = 'Editar Trem';
                    document.getElementById('submitBtn').textContent = 'Atualizar Trem';
                    document.getElementById('tremForm').onsubmit = function() {
                        return submitForm('tremForm', backendUrl, 'atualizar');
                    };
                    
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showAlert('Erro ao carregar dados do trem', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showAlert('Erro ao comunicar com o servidor', 'error');
            }
        }
        
        function deleteTrem(id) {
            deleteRecord(id, backendUrl, 'Tem certeza que deseja excluir este trem?');
        }
        
        function resetForm() {
            document.getElementById('tremForm').reset();
            document.getElementById('id').value = '';
            document.getElementById('formTitle').textContent = 'Cadastrar Novo Trem';
            document.getElementById('submitBtn').textContent = 'Salvar Trem';
            document.getElementById('tremForm').onsubmit = function() {
                return submitForm('tremForm', backendUrl);
            };
        }
        
        function loadDataTable() {
            loadData(backendUrl, 'trensTableBody');
        }
        
        window.addEventListener('DOMContentLoaded', loadDataTable);
    </script>
</body>
</html>