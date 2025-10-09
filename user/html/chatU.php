<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../php/login.php");
    exit();
}
require_once __DIR__ . '/../../user-backend/conexao.php';
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT r.*, u.nome as usuario_nome
        FROM reclamacoes r
        INNER JOIN usuarios u ON r.usuario_id = u.id
        WHERE r.usuario_id = ?
        ORDER BY r.created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$reclamacoes = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Reclame Aqui - LLGR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/chatU.css">
</head>
<body>
    <div class="chat-container">
        <header>
            <nav>
                <a class="logo" href="telainicialU.html">LLGR</a>
                <div class="mobile-menu">
                    <div class="line1"></div>
                    <div class="line2"></div>
                    <div class="line3"></div>
                </div>
                <ul class="nav-list">
                    <li><a href="../php/rotas_usuario.php">Rotas</a></li>
                    <li><a href="../php/notificacoes_usuario.php">Notificações</a></li>
                    <li><a href="chatU.php">Reclame Aqui</a></li>
                    <li><a href="../php/perfil.php">Perfil</a></li>
                    <li><a href="../php/logout_usuario.php">Sair</a></li>
                </ul>
            </nav>
        </header>
        <div class="chat-messages" id="chat-messages">
            <?php if (empty($reclamacoes)): ?>
                <div class="empty-state">
                    <p>Nenhuma reclamação ainda. Envie sua primeira mensagem!</p>
                </div>
            <?php else: ?>
                <?php foreach ($reclamacoes as $rec): ?>
                    <div class="msg user">
                        <div class="msg-content"><?= htmlspecialchars($rec['mensagem']) ?></div>
                        <div class="msg-time"><?= date('d/m/Y H:i', strtotime($rec['created_at'])) ?></div>
                        <div class="msg-status status-<?= $rec['status'] ?>">
                            <?= $rec['status'] == 'pendente' ? 'Aguardando resposta' : ($rec['status'] == 'respondida' ? 'Respondida' : 'Resolvida') ?>
                        </div>
                    </div>
                    <?php if ($rec['resposta']): ?>
                        <div class="msg bot">
                            <div class="msg-content"><?= htmlspecialchars($rec['resposta']) ?></div>
                            <div class="msg-time"><?= $rec['respondido_em'] ? date('d/m/Y H:i', strtotime($rec['respondido_em'])) : '' ?></div>
                            <div class="msg-author">Equipe LLGR</div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <form class="chat-input-area" id="chat-form" method="POST" action="../../user-backend/reclamacoes_backend.php">
            <input type="hidden" name="acao" value="enviar">
            <input
                type="text"
                name="mensagem"
                id="chat-input"
                placeholder="Digite sua reclamação..."
                required
                maxlength="1000"
            >
            <button type="submit">Enviar</button>
        </form>
    </div>
    <script src="../js/mobile-navbar.js"></script>
    <script>
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    </script>
</body>
</html>
