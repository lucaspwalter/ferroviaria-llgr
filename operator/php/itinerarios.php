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
                <h2 class="card-title">Cadastrar Novo Itinerário</h2>
                <form id="itinerarioForm" onsubmit="return submitForm('itinerarioForm', '../../operator-backend/itinerarios-backend.php')">
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
                        <button type="submit" class="btn btn-primary">Salvar Itinerário</button>
                        <button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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
                            </tr>
                        </thead>
                        <tbody id="itinerariosTableBody">
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
            `;
            return tr;
        }
        function loadDataTable() {
            loadData(backendUrl, 'itinerariosTableBody');
        }
    </script>
</body>
</html>