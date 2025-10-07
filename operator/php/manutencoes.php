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
                <h2 class="card-title">Cadastrar Nova Manutenção</h2>
                <form id="manutencaoForm" onsubmit="return submitForm('manutencaoForm', '../../operator-backend/manutencoes-backend.php')">
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
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="agendada">Agendada</option>
                                <option value="em_andamento">Em Andamento</option>
                                <option value="concluida">Concluída</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="custo">Custo Estimado (R$)</label>
                            <input type="number" id="custo" name="custo" class="form-control" 
                                   placeholder="0.00" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="responsavel">Responsável</label>
                        <input type="text" id="responsavel" name="responsavel" class="form-control" 
                               placeholder="Nome do técnico ou equipe responsável" maxlength="100">
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
                        <button type="submit" class="btn btn-primary">Salvar Manutenção</button>
                        <button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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
                            </tr>
                        </thead>
                        <tbody id="manutencoesTableBody">
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
            `;
            return tr;
        }
        function loadDataTable() {
            loadData(backendUrl, 'manutencoesTableBody');
        }
    </script>
</body>
</html>