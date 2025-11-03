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
    <title>Gerenciamento de Manutenções - LLGR</title>
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
            <h1 class="page-title">Gerenciamento de Manutenções</h1>
            <div id="alert" class="alert"></div>
            <div class="card">
                <h2 class="card-title" id="formTitle">Cadastrar Nova Manutenção</h2>
                <form id="manutencaoForm" onsubmit="return submitForm('manutencaoForm', '../../operator-backend/manutencoes-backend.php')">
                    <input type="hidden" id="id" name="id">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="trem_id">Trem <span class="required">*</span></label>
                            <select id="trem_id" name="trem_id" class="form-control" required>
                                <option value="">Selecione...</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="tipo">Tipo <span class="required">*</span></label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="preventiva">Preventiva</option>
                                <option value="corretiva">Corretiva</option>
                                <option value="emergencial">Emergencial</option>
                                <option value="revisao">Revisão</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição <span class="required">*</span></label>
                        <textarea id="descricao" name="descricao" class="form-control" 
                                  placeholder="Descreva os serviços a serem realizados" required></textarea>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="data_inicio">Data de Início <span class="required">*</span></label>
                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" required>
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="data_fim_prevista">Data Fim Prevista <span class="required">*</span></label>
                            <input type="date" id="data_fim_prevista" name="data_fim_prevista" class="form-control" required>
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="data_fim_real">Data Fim Real</label>
                            <input type="date" id="data_fim_real" name="data_fim_real" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="agendada">Agendada</option>
                                <option value="em_andamento">Em Andamento</option>
                                <option value="concluida">Concluída</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="custo">Custo Estimado (R$)</label>
                            <input type="number" id="custo" name="custo" class="form-control" 
                                   placeholder="0.00" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="responsavel">Responsável</label>
                            <input type="text" id="responsavel" name="responsavel" class="form-control" 
                                   placeholder="Nome do técnico ou equipe responsável" maxlength="100">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pecas_substituidas">Peças Substituídas</label>
                        <textarea id="pecas_substituidas" name="pecas_substituidas" class="form-control" 
                                  placeholder="Liste as peças que foram ou serão substituídas"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" class="form-control" 
                                  placeholder="Informações adicionais sobre a manutenção"></textarea>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Salvar Manutenção</button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Cancelar</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2 class="card-title">Manutenções Cadastradas</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Trem</th>
                                <th>Tipo</th>
                                <th>Data Início</th>
                                <th>Data Fim</th>
                                <th>Status</th>
                                <th>Custo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="manutencoesTableBody">
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
        const backendUrl = '../../operator-backend/manutencoes-backend.php';
        
        window.addEventListener('DOMContentLoaded', function() {
            loadSelect('../../operator-backend/trens-backend.php', 'trem_id', 'id', 'codigo');
            loadDataTable();
        });
        
        function createTableRow(manutencao) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${manutencao.trem_codigo || '-'}</td>
                <td><span class="badge badge-info">${manutencao.tipo}</span></td>
                <td>${formatDate(manutencao.data_inicio)}</td>
                <td>${formatDate(manutencao.data_fim_prevista)}</td>
                <td>${getStatusBadge(manutencao.status)}</td>
                <td>${manutencao.custo ? formatCurrency(manutencao.custo) : '-'}</td>
                <td>
                    <button class="btn-action btn-edit" onclick="editManutencao(${manutencao.id})" title="Editar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteManutencao(${manutencao.id})" title="Excluir">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </td>
            `;
            return tr;
        }
        
        async function editManutencao(id) {
            try {
                const response = await fetch(`${backendUrl}?acao=buscar&id=${id}`);
                const result = await response.json();
                
                if (result.sucesso) {
                    const manutencao = result.dados;
                    document.getElementById('id').value = manutencao.id;
                    document.getElementById('trem_id').value = manutencao.trem_id;
                    document.getElementById('tipo').value = manutencao.tipo;
                    document.getElementById('descricao').value = manutencao.descricao;
                    document.getElementById('data_inicio').value = manutencao.data_inicio;
                    document.getElementById('data_fim_prevista').value = manutencao.data_fim_prevista;
                    document.getElementById('data_fim_real').value = manutencao.data_fim_real || '';
                    document.getElementById('status').value = manutencao.status;
                    document.getElementById('custo').value = manutencao.custo || '';
                    document.getElementById('responsavel').value = manutencao.responsavel || '';
                    document.getElementById('pecas_substituidas').value = manutencao.pecas_substituidas || '';
                    document.getElementById('observacoes').value = manutencao.observacoes || '';
                    
                    document.getElementById('formTitle').textContent = 'Editar Manutenção';
                    document.getElementById('submitBtn').textContent = 'Atualizar Manutenção';
                    document.getElementById('manutencaoForm').onsubmit = function() {
                        return submitForm('manutencaoForm', backendUrl, 'atualizar');
                    };
                    
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showAlert('Erro ao carregar dados da manutenção', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showAlert('Erro ao comunicar com o servidor', 'error');
            }
        }
        
        function deleteManutencao(id) {
            deleteRecord(id, backendUrl, 'Tem certeza que deseja excluir esta manutenção?');
        }
        
        function resetForm() {
            document.getElementById('manutencaoForm').reset();
            document.getElementById('id').value = '';
            document.getElementById('formTitle').textContent = 'Cadastrar Nova Manutenção';
            document.getElementById('submitBtn').textContent = 'Salvar Manutenção';
            document.getElementById('manutencaoForm').onsubmit = function() {
                return submitForm('manutencaoForm', backendUrl);
            };
        }
        
        function loadDataTable() {
            loadData(backendUrl, 'manutencoesTableBody');
        }
    </script>
</body>
</html>