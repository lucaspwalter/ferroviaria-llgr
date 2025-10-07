<?php
session_start();
if (!isset($_SESSION['operador_id'])) {
    header("Location: login.php");
    exit();
}
require_once __DIR__ . '/../../user-backend/conexao.php';
$filtro = $_GET['filtro'] ?? 'todas';

$sql = "SELECT r.*, u.nome as usuario_nome, u.email as usuario_email 
        FROM reclamacoes r 
        INNER JOIN usuarios u ON r.usuario_id = u.id ";

if ($filtro == 'pendentes') {
    $sql .= "WHERE r.status = 'pendente' ";
}

$sql .= "ORDER BY r.created_at DESC";

$result = $conn->query($sql);
$reclamacoes = $result->fetch_all(MYSQLI_ASSOC);
$erro = $_SESSION['erro'] ?? '';
$sucesso = $_SESSION['sucesso'] ?? '';
unset($_SESSION['erro'], $_SESSION['sucesso']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Reclamações - LLGR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/gerenciamento.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <main class="main-container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-comments"></i> Gerenciar Reclamações
            </h1>
            <p class="page-subtitle">Visualize e responda reclamações dos usuários</p>
        </div>
        <?php if ($erro): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($sucesso) ?>
            </div>
        <?php endif; ?>
        <div class="filters">
            <a href="?filtro=todas" class="filter-btn <?= $filtro == 'todas' ? 'active' : '' ?>">
                Todas
            </a>
            <a href="?filtro=pendentes" class="filter-btn <?= $filtro == 'pendentes' ? 'active' : '' ?>">
                Pendentes
            </a>
        </div>
        <div class="reclamacoes-grid">
            <?php if (empty($reclamacoes)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Nenhuma reclamação encontrada</p>
                </div>
            <?php else: ?>
                <?php foreach ($reclamacoes as $rec): ?>
                    <div class="reclamacao-card status-<?= $rec['status'] ?>">
                        <div class="card-header">
                            <div class="user-info">
                                <i class="fas fa-user-circle"></i>
                                <div>
                                    <strong><?= htmlspecialchars($rec['usuario_nome']) ?></strong>
                                    <small><?= htmlspecialchars($rec['usuario_email']) ?></small>
                                </div>
                            </div>
                            <span class="badge badge-<?= $rec['status'] ?>">
                                <?= $rec['status'] == 'pendente' ? 'Pendente' : ($rec['status'] == 'respondida' ? 'Respondida' : 'Resolvida') ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mensagem-usuario">
                                <strong>Reclamação:</strong>
                                <p><?= nl2br(htmlspecialchars($rec['mensagem'])) ?></p>
                                <small class="timestamp">
                                    <i class="fas fa-clock"></i>
                                    <?= date('d/m/Y H:i', strtotime($rec['created_at'])) ?>
                                </small>
                            </div>
                            <?php if ($rec['resposta']): ?>
                                <div class="resposta-operador">
                                    <strong>Resposta:</strong>
                                    <p><?= nl2br(htmlspecialchars($rec['resposta'])) ?></p>
                                    <small class="timestamp">
                                        <i class="fas fa-clock"></i>
                                        <?= $rec['respondido_em'] ? date('d/m/Y H:i', strtotime($rec['respondido_em'])) : '' ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <?php if ($rec['status'] == 'pendente'): ?>
                                <button class="btn btn-primary" onclick="abrirModal(<?= $rec['id'] ?>)">
                                    <i class="fas fa-reply"></i> Responder
                                </button>
                            <?php elseif ($rec['status'] == 'respondida'): ?>
                                <button class="btn btn-success" onclick="marcarResolvida(<?= $rec['id'] ?>)">
                                    <i class="fas fa-check"></i> Marcar como Resolvida
                                </button>
                            <?php else: ?>
                                <span class="resolved-badge">
                                    <i class="fas fa-check-circle"></i> Resolvida
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    <div id="modalResposta" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharModal()">&times;</span>
            <h2><i class="fas fa-reply"></i> Responder Reclamação</h2>
            <form method="POST" action="../../operator-backend/reclamacoes_backend.php">
                <input type="hidden" name="acao" value="responder">
                <input type="hidden" name="reclamacao_id" id="reclamacao_id">
                <textarea 
                    name="resposta" 
                    placeholder="Digite sua resposta..."
                    required
                    rows="6"
                ></textarea>
                <div class="modal-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Resposta
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="fecharModal()">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="../js/mobile-navbar.js"></script>
    <script>
        function abrirModal(id) {
            document.getElementById('reclamacao_id').value = id;
            document.getElementById('modalResposta').style.display = 'flex';
        }
        function fecharModal() {
            document.getElementById('modalResposta').style.display = 'none';
        }
        function marcarResolvida(id) {
            if (confirm('Deseja marcar esta reclamação como resolvida?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '../../operator-backend/reclamacoes_backend.php';
                const acaoInput = document.createElement('input');
                acaoInput.type = 'hidden';
                acaoInput.name = 'acao';
                acaoInput.value = 'resolver';
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'reclamacao_id';
                idInput.value = id;
                form.appendChild(acaoInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
        window.onclick = function(event) {
            const modal = document.getElementById('modalResposta');
            if (event.target == modal) {
                fecharModal();
            }
        }
    </script>
</body>
</html>
