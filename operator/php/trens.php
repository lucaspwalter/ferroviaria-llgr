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
                <h2 class="card-title">Cadastrar Novo Trem</h2>
                <form id="tremForm" onsubmit="return submitForm('tremForm', '../../operator-backend/trens-backend.php')">
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
                        <button type="submit" class="btn btn-primary">Salvar Trem</button>
                        <button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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
                            </tr>
                        </thead>
                        <tbody id="trensTableBody">
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
            `;
            return tr;
        }
        function loadDataTable() {
            loadData(backendUrl, 'trensTableBody');
        }
        window.addEventListener('DOMContentLoaded', loadDataTable);
    </script>
</body>
</html>