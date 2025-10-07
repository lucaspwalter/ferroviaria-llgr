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
                <h2 class="card-title">Cadastrar Nova Rota</h2>
                <form id="rotaForm" onsubmit="return submitForm('rotaForm', '../../operator-backend/rotas-backend.php')">
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
                        <button type="submit" class="btn btn-primary">Salvar Rota</button>
                        <button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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
                            </tr>
                        </thead>
                        <tbody id="rotasTableBody">
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
            `;
            return tr;
        }
        function loadDataTable() {
            loadData(backendUrl, 'rotasTableBody');
        }
        window.addEventListener('DOMContentLoaded', loadDataTable);
    </script>
</body>
</html>
