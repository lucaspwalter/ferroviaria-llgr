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
            <h1 class="page-title">Gerenciamento de Alertas</h1>
            <div id="alert" class="alert"></div>
            <div class="card">
                <h2 class="card-title" id="formTitle">Cadastrar Novo Alerta</h2>
                <form id="alertaForm" onsubmit="return submitForm('alertaForm', '../../operator-backend/alertas-backend.php')">
                    <input type="hidden" id="id" name="id">
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
                    <div class="form-group">
                        <label for="acao_tomada">Ação Tomada</label>
                        <textarea id="acao_tomada" name="acao_tomada" class="form-control" 
                                  placeholder="Descreva as ações tomadas para resolver o alerta"></textarea>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Salvar Alerta</button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Cancelar</button>
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
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="alertasTableBody">
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
                <td>
                    <button class="btn-action btn-edit" onclick="editAlerta(${alerta.id})" title="Editar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteAlerta(${alerta.id})" title="Excluir">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </td>
            `;
            return tr;
        }
        
        async function editAlerta(id) {
            try {
                const response = await fetch(`${backendUrl}?acao=buscar&id=${id}`);
                const result = await response.json();
                
                if (result.sucesso) {
                    const alerta = result.dados;
                    document.getElementById('id').value = alerta.id;
                    document.getElementById('tipo').value = alerta.tipo;
                    document.getElementById('origem').value = alerta.origem;
                    document.getElementById('prioridade').value = alerta.prioridade;
                    document.getElementById('status').value = alerta.status;
                    document.getElementById('titulo').value = alerta.titulo;
                    document.getElementById('descricao').value = alerta.descricao;
                    document.getElementById('localizacao').value = alerta.localizacao || '';
                    document.getElementById('acao_tomada').value = alerta.acao_tomada || '';
                    
                    document.getElementById('formTitle').textContent = 'Editar Alerta';
                    document.getElementById('submitBtn').textContent = 'Atualizar Alerta';
                    document.getElementById('alertaForm').onsubmit = function() {
                        return submitForm('alertaForm', backendUrl, 'atualizar');
                    };
                    
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showAlert('Erro ao carregar dados do alerta', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showAlert('Erro ao comunicar com o servidor', 'error');
            }
        }
        
        function deleteAlerta(id) {
            deleteRecord(id, backendUrl, 'Tem certeza que deseja excluir este alerta?');
        }
        
        function resetForm() {
            document.getElementById('alertaForm').reset();
            document.getElementById('id').value = '';
            document.getElementById('formTitle').textContent = 'Cadastrar Novo Alerta';
            document.getElementById('submitBtn').textContent = 'Salvar Alerta';
            document.getElementById('alertaForm').onsubmit = function() {
                return submitForm('alertaForm', backendUrl);
            };
        }
        
        function loadDataTable() {
            loadData(backendUrl, 'alertasTableBody');
        }
        
        window.addEventListener('DOMContentLoaded', loadDataTable);
    </script>
</body>
</html>