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
    <title>Gerenciamento de Rotas - LLGR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
            <h1 class="page-title">Gerenciamento de Rotas</h1>
            <div id="alert" class="alert"></div>
            <div class="card">
                <h2 class="card-title" id="formTitle">Cadastrar Nova Rota</h2>
                <form id="rotaForm" onsubmit="return submitForm('rotaForm', '../../operator-backend/rotas-backend.php')">
                    <input type="hidden" id="id" name="id">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="codigo">Código <span class="required">*</span></label>
                            <input type="text" id="codigo" name="codigo" class="form-control"
                                   placeholder="Ex: ROTA-001" required maxlength="50">
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="nome">Nome da Rota <span class="required">*</span></label>
                            <input type="text" id="nome" name="nome" class="form-control"
                                   placeholder="Ex: Linha Central" required maxlength="150">
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="origem">Origem <span class="required">*</span></label>
                            <input type="text" id="origem" name="origem" class="form-control"
                                   placeholder="Ex: Estação Norte" required maxlength="100">
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="destino">Destino <span class="required">*</span></label>
                            <input type="text" id="destino" name="destino" class="form-control"
                                   placeholder="Ex: Estação Sul" required maxlength="100">
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label for="distancia_km">Distância (km) <span class="required">*</span></label>
                            <input type="number" id="distancia_km" name="distancia_km" class="form-control"
                                   placeholder="45.5" required step="0.01" min="0.01">
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="tempo_estimado">Tempo Estimado <span class="required">*</span></label>
                            <input type="time" id="tempo_estimado" name="tempo_estimado" class="form-control"
                                   required>
                            <div class="error-message"></div>
                            <small class="help-text">Formato: HH:MM</small>
                        </div>
                        <div class="form-group">
                            <label for="preco_base">Preço Base (R$)</label>
                            <input type="number" id="preco_base" name="preco_base" class="form-control"
                                   placeholder="15.00" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="numero_paradas">Número de Paradas</label>
                            <input type="number" id="numero_paradas" name="numero_paradas" class="form-control"
                                   placeholder="0" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="ativa">Ativa</option>
                                <option value="inativa">Inativa</option>
                                <option value="manutencao">Manutenção</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="paradas">Paradas (separadas por vírgula)</label>
                        <textarea id="paradas" name="paradas" class="form-control"
                                  placeholder="Ex: Estação A, Estação B, Estação C"></textarea>
                        <small class="help-text">Liste as paradas intermediárias</small>
                    </div>
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" class="form-control"
                                  placeholder="Informações adicionais sobre a rota"></textarea>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Salvar Rota</button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Cancelar</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2 class="card-title">Rotas Cadastradas</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Origem → Destino</th>
                                <th>Distância</th>
                                <th>Preço</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="rotasTableBody">
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
        const backendUrl = '../../operator-backend/rotas-backend.php';
        
        function createTableRow(rota) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${rota.codigo}</td>
                <td>${rota.nome}</td>
                <td>${rota.origem} → ${rota.destino}</td>
                <td>${rota.distancia_km} km</td>
                <td>${rota.preco_base ? formatCurrency(rota.preco_base) : '-'}</td>
                <td>${getStatusBadge(rota.status)}</td>
                <td>
                    <button class="btn-action btn-edit" onclick="editRota(${rota.id})" title="Editar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteRota(${rota.id})" title="Excluir">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </td>
            `;
            return tr;
        }
        
        async function editRota(id) {
            try {
                const response = await fetch(`${backendUrl}?acao=buscar&id=${id}`);
                const result = await response.json();
                
                if (result.sucesso) {
                    const rota = result.dados;
                    document.getElementById('id').value = rota.id;
                    document.getElementById('codigo').value = rota.codigo;
                    document.getElementById('nome').value = rota.nome;
                    document.getElementById('origem').value = rota.origem;
                    document.getElementById('destino').value = rota.destino;
                    document.getElementById('distancia_km').value = rota.distancia_km;
                    document.getElementById('tempo_estimado').value = rota.tempo_estimado;
                    document.getElementById('preco_base').value = rota.preco_base || '';
                    document.getElementById('numero_paradas').value = rota.numero_paradas || 0;
                    document.getElementById('status').value = rota.status;
                    document.getElementById('paradas').value = rota.paradas || '';
                    document.getElementById('observacoes').value = rota.observacoes || '';
                    
                    document.getElementById('formTitle').textContent = 'Editar Rota';
                    document.getElementById('submitBtn').textContent = 'Atualizar Rota';
                    document.getElementById('rotaForm').onsubmit = function() {
                        return submitForm('rotaForm', backendUrl, 'atualizar');
                    };
                    
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showAlert('Erro ao carregar dados da rota', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showAlert('Erro ao comunicar com o servidor', 'error');
            }
        }
        
        function deleteRota(id) {
            deleteRecord(id, backendUrl, 'Tem certeza que deseja excluir esta rota?');
        }
        
        function resetForm() {
            document.getElementById('rotaForm').reset();
            document.getElementById('id').value = '';
            document.getElementById('formTitle').textContent = 'Cadastrar Nova Rota';
            document.getElementById('submitBtn').textContent = 'Salvar Rota';
            document.getElementById('rotaForm').onsubmit = function() {
                return submitForm('rotaForm', backendUrl);
            };
        }
        
        function loadDataTable() {
            loadData(backendUrl, 'rotasTableBody');
        }
        
        window.addEventListener('DOMContentLoaded', loadDataTable);
    </script>
</body>
</html>