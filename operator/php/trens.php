<?php
session_start();
require_once __DIR__ . '/../../config/security.php';
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
    <link rel="stylesheet" href="../css/toast.css" />
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
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="sensores.php">Sensores</a></li>
                <li><a href="estacoes.php">Estações</a></li>
                <li><a href="trens.php">Trens</a></li>
                <li><a href="rotas.php">Rotas</a></li>
                <li><a href="itinerarios.php">Itinerários</a></li>
                <li><a href="alertas.php">Alertas</a></li>
                <li><a href="manutencoes.php">Manutenções</a></li>
                <li><a href="notificacoes.php">Notificações</a></li>
                <li><a href="relatorios.php">Relatórios</a></li>
                <li><a href="reclamacoes.php">Reclamações</a></li>
                <li><a href="perfil_operador.php">Perfil</a></li>
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
                <form method="POST" id="tremForm">
                    <?= csrf_input() ?>
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
                <div class="table-toolbar">
                    <div class="search-box">
                        <input type="search" id="searchInput" placeholder="Buscar por código ou modelo">
                    </div>
                    <select id="statusFilter">
                        <option value="">Todos os status</option>
                        <option value="operacional">Operacional</option>
                        <option value="manutencao">Manutenção</option>
                        <option value="inativo">Inativo</option>
                        <option value="em_rota">Em Rota</option>
                    </select>
                </div>
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
                            <tr><td colspan="7" class="loading">Carregando dados...</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="pagination" id="trensPagination"></div>
            </div>
        </section>
    </main>
    <script src="../js/mobile-navbar.js"></script>
    <script>
        const BACKEND_URL = '../../operator/api/trens.php';
        let todosTrens = [];
        let paginaAtual = 1;
        const itensPorPagina = 10;
        
        
        function getStatusBadge(status) {
            const colors = {
                'operacional': 'success',
                'manutencao': 'warning',
                'inativo': 'secondary',
                'em_rota': 'info'
            };
            return `<span class="badge badge-${colors[status] || 'secondary'}">${escapeHTML(status)}</span>`;
        }

        function escapeHTML(value) {
            return String(value ?? '').replace(/[&<>"']/g, char => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char]));
        }

        function getCsrfToken() {
            const input = document.querySelector('input[name="csrf_token"]');
            return input ? input.value : '';
        }
        
        function createTableRow(trem) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${escapeHTML(trem.codigo)}</td>
                <td>${escapeHTML(trem.modelo)}</td>
                <td>${escapeHTML(trem.fabricante || '-')}</td>
                <td>${trem.capacidade_passageiros} passageiros</td>
                <td>${trem.velocidade_maxima ? trem.velocidade_maxima + ' km/h' : '-'}</td>
                <td>${getStatusBadge(trem.status)}</td>
                <td>
                    <button class="btn-action btn-edit" onclick="editTrem(${trem.id})" title="Editar">✏️</button>
                    <button class="btn-action btn-delete" onclick="deleteTrem(${trem.id})" title="Excluir">🗑️</button>
                </td>
            `;
            return tr;
        }

        function getTrensFiltrados() {
            const busca = document.getElementById('searchInput').value.trim().toLowerCase();
            const status = document.getElementById('statusFilter').value;
            return todosTrens.filter(trem => {
                const texto = `${trem.codigo || ''} ${trem.modelo || ''}`.toLowerCase();
                const combinaBusca = !busca || texto.includes(busca);
                const combinaStatus = !status || trem.status === status;
                return combinaBusca && combinaStatus;
            });
        }

        function renderPagination(totalItens) {
            const pagination = document.getElementById('trensPagination');
            const totalPaginas = Math.max(1, Math.ceil(totalItens / itensPorPagina));
            if (paginaAtual > totalPaginas) paginaAtual = totalPaginas;
            pagination.innerHTML = '';
            for (let i = 1; i <= totalPaginas; i++) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `page-btn ${i === paginaAtual ? 'active' : ''}`;
                button.textContent = i;
                button.addEventListener('click', () => {
                    paginaAtual = i;
                    renderTrens();
                });
                pagination.appendChild(button);
            }
        }

        function renderTrens() {
            const tbody = document.getElementById('trensTableBody');
            const filtrados = getTrensFiltrados();
            const inicio = (paginaAtual - 1) * itensPorPagina;
            const pagina = filtrados.slice(inicio, inicio + itensPorPagina);
            tbody.innerHTML = '';
            if (pagina.length > 0) {
                pagina.forEach(trem => tbody.appendChild(createTableRow(trem)));
            } else {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Nenhum trem encontrado</td></tr>';
            }
            renderPagination(filtrados.length);
        }
        
        async function loadTrens() {
            const tbody = document.getElementById('trensTableBody');
            tbody.innerHTML = '<tr><td colspan="7" class="loading">Carregando...</td></tr>';
            
            try {
                const response = await fetch(BACKEND_URL + '?acao=listar');
                const result = await response.json();
                
                if (result.sucesso) {
                    todosTrens = result.dados;
                    renderTrens();
                } else {
                    tbody.innerHTML = '<tr><td colspan="7" class="error">Erro: ' + result.mensagem + '</td></tr>';
                }
            } catch (error) {
                console.error('Erro:', error);
                tbody.innerHTML = '<tr><td colspan="7" class="error">Erro ao carregar</td></tr>';
            }
        }
        
        async function editTrem(id) {
            try {
                const response = await fetch(BACKEND_URL + '?acao=buscar&id=' + id);
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
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showToast('Erro ao carregar trem', 'error');
                }
            } catch (error) {
                showToast('Erro ao comunicar com servidor', 'error');
            }
        }
        
        async function deleteTrem(id) {
            if (!confirm('Tem certeza que deseja excluir este trem?')) return;
            
            const formData = new FormData();
            formData.append('acao', 'deletar');
            formData.append('id', id);
            formData.append('csrf_token', getCsrfToken());
            
            try {
                const response = await fetch(BACKEND_URL, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                if (result.sucesso) {
                    showToast(result.mensagem, 'success');
                    loadTrens();
                } else {
                    showToast(result.mensagem, 'error');
                }
            } catch (error) {
                showToast('Erro ao excluir', 'error');
            }
        }
        
        function resetForm() {
            document.getElementById('tremForm').reset();
            document.getElementById('id').value = '';
            document.getElementById('formTitle').textContent = 'Cadastrar Novo Trem';
            document.getElementById('submitBtn').textContent = 'Salvar Trem';
        }
        
        document.getElementById('tremForm').addEventListener('submit', async function(e) {
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
                    showToast(result.mensagem, 'success');
                    resetForm();
                    loadTrens();
                } else {
                    showToast(result.mensagem, 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showToast('Erro ao salvar: ' + error.message, 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = id ? 'Atualizar Trem' : 'Salvar Trem';
            }
        });
        
        document.getElementById('searchInput').addEventListener('input', () => {
            paginaAtual = 1;
            renderTrens();
        });
        document.getElementById('statusFilter').addEventListener('change', () => {
            paginaAtual = 1;
            renderTrens();
        });
        window.addEventListener('DOMContentLoaded', loadTrens);
    </script>
    <script src="../js/toast.js"></script>
</body>
</html>
