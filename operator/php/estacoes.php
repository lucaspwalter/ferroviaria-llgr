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
    <title>Gerenciamento de Estações - LLGR</title>
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
                <li><a href="estacoes.php">Estações</a></li>
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
            <h1 class="page-title">Gerenciamento de Estações</h1>
            <div id="alert" class="alert"></div>
            <div class="card">
                <h2 class="card-title" id="formTitle">Cadastrar Nova Estação</h2>
                <form id="estacaoForm" onsubmit="return submitForm('estacaoForm', '../../operator-backend/estacoes-backend.php')">
                    <input type="hidden" id="id" name="id">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="codigo">Código <span class="required">*</span></label>
                            <input type="text" id="codigo" name="codigo" class="form-control" 
                                   placeholder="Ex: EST-001" required maxlength="50">
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="nome">Nome da Estação <span class="required">*</span></label>
                            <input type="text" id="nome" name="nome" class="form-control" 
                                   placeholder="Ex: Estação Central" required maxlength="150">
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cidade">Cidade <span class="required">*</span></label>
                            <input type="text" id="cidade" name="cidade" class="form-control" 
                                   placeholder="Ex: Joinville" required maxlength="100">
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado <span class="required">*</span></label>
                            <select id="estado" name="estado" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="AC">AC</option>
                                <option value="AL">AL</option>
                                <option value="AP">AP</option>
                                <option value="AM">AM</option>
                                <option value="BA">BA</option>
                                <option value="CE">CE</option>
                                <option value="DF">DF</option>
                                <option value="ES">ES</option>
                                <option value="GO">GO</option>
                                <option value="MA">MA</option>
                                <option value="MT">MT</option>
                                <option value="MS">MS</option>
                                <option value="MG">MG</option>
                                <option value="PA">PA</option>
                                <option value="PB">PB</option>
                                <option value="PR">PR</option>
                                <option value="PE">PE</option>
                                <option value="PI">PI</option>
                                <option value="RJ">RJ</option>
                                <option value="RN">RN</option>
                                <option value="RS">RS</option>
                                <option value="RO">RO</option>
                                <option value="RR">RR</option>
                                <option value="SC">SC</option>
                                <option value="SP">SP</option>
                                <option value="SE">SE</option>
                                <option value="TO">TO</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço Completo <span class="required">*</span></label>
                        <input type="text" id="endereco" name="endereco" class="form-control" 
                               placeholder="Ex: Rua Principal, 123 - Centro" required maxlength="200">
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
                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label for="numero_plataformas">Plataformas</label>
                            <input type="number" id="numero_plataformas" name="numero_plataformas" 
                                   class="form-control" placeholder="4" min="1" value="1">
                        </div>
                        <div class="form-group">
                            <label for="capacidade_passageiros">Capacidade</label>
                            <input type="number" id="capacidade_passageiros" name="capacidade_passageiros" 
                                   class="form-control" placeholder="500" min="1">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="ativa">Ativa</option>
                                <option value="inativa">Inativa</option>
                                <option value="manutencao">Manutenção</option>
                                <option value="reforma">Reforma</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="horario_abertura">Horário de Abertura</label>
                            <input type="time" id="horario_abertura" name="horario_abertura" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="horario_fechamento">Horário de Fechamento</label>
                            <input type="time" id="horario_fechamento" name="horario_fechamento" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="servicos">Serviços Disponíveis</label>
                        <textarea id="servicos" name="servicos" class="form-control" 
                                  placeholder="Ex: Banheiros, Lanchonete, Wi-Fi, Estacionamento"></textarea>
                        <small class="help-text">Liste os serviços disponíveis na estação</small>
                    </div>
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" class="form-control" 
                                  placeholder="Informações adicionais sobre a estação"></textarea>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Salvar Estação</button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Cancelar</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2 class="card-title">Estações Cadastradas</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Cidade/Estado</th>
                                <th>Plataformas</th>
                                <th>Capacidade</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="estacoesTableBody">
                            <tr><td colspan="7" class="loading">Carregando dados...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <script src="../js/mobile-navbar.js"></script>
    <script src="../js/gerenciamento.js"></script>
    <script>
        const backendUrl = '../../operator-backend/estacoes-backend.php';
        
        function createTableRow(estacao) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${estacao.codigo}</td>
                <td>${estacao.nome}</td>
                <td>${estacao.cidade} - ${estacao.estado}</td>
                <td>${estacao.numero_plataformas || '-'}</td>
                <td>${estacao.capacidade_passageiros ? estacao.capacidade_passageiros + ' passageiros' : '-'}</td>
                <td>${getStatusBadge(estacao.status)}</td>
                <td>
                    <button class="btn-action btn-edit" onclick="editEstacao(${estacao.id})" title="Editar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteEstacao(${estacao.id})" title="Excluir">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </td>
            `;
            return tr;
        }
        
        async function editEstacao(id) {
            try {
                const response = await fetch(`${backendUrl}?acao=buscar&id=${id}`);
                const result = await response.json();
                
                if (result.sucesso) {
                    const estacao = result.dados;
                    document.getElementById('id').value = estacao.id;
                    document.getElementById('codigo').value = estacao.codigo;
                    document.getElementById('nome').value = estacao.nome;
                    document.getElementById('cidade').value = estacao.cidade;
                    document.getElementById('estado').value = estacao.estado;
                    document.getElementById('endereco').value = estacao.endereco;
                    document.getElementById('latitude').value = estacao.latitude || '';
                    document.getElementById('longitude').value = estacao.longitude || '';
                    document.getElementById('numero_plataformas').value = estacao.numero_plataformas || 1;
                    document.getElementById('capacidade_passageiros').value = estacao.capacidade_passageiros || '';
                    document.getElementById('status').value = estacao.status;
                    document.getElementById('horario_abertura').value = estacao.horario_abertura || '';
                    document.getElementById('horario_fechamento').value = estacao.horario_fechamento || '';
                    document.getElementById('servicos').value = estacao.servicos || '';
                    document.getElementById('observacoes').value = estacao.observacoes || '';
                    
                    document.getElementById('formTitle').textContent = 'Editar Estação';
                    document.getElementById('submitBtn').textContent = 'Atualizar Estação';
                    document.getElementById('estacaoForm').onsubmit = function() {
                        return submitForm('estacaoForm', backendUrl, 'atualizar');
                    };
                    
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showAlert('Erro ao carregar dados da estação', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showAlert('Erro ao comunicar com o servidor', 'error');
            }
        }
        
        function deleteEstacao(id) {
            deleteRecord(id, backendUrl, 'Tem certeza que deseja excluir esta estação?');
        }
        
        function resetForm() {
            document.getElementById('estacaoForm').reset();
            document.getElementById('id').value = '';
            document.getElementById('formTitle').textContent = 'Cadastrar Nova Estação';
            document.getElementById('submitBtn').textContent = 'Salvar Estação';
            document.getElementById('estacaoForm').onsubmit = function() {
                return submitForm('estacaoForm', backendUrl);
            };
        }
        
        function loadDataTable() {
            loadData(backendUrl, 'estacoesTableBody');
        }
        
        window.addEventListener('DOMContentLoaded', loadDataTable);
    </script>
</body>
</html>
