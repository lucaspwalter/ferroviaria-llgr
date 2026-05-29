<?php
session_start();
require_once __DIR__ . '/../../config/security.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../php/login.php");
    exit();
}
require_once __DIR__ . '/../../config/database.php';
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
    <link rel="stylesheet" href="../css/toast.css" />
</head>
<body>
    <div class="chat-container">
        <header>
            <nav>
                <a class="logo" href="rotas_usuario.php">LLGR</a>
                <div class="mobile-menu">
                    <div class="line1"></div>
                    <div class="line2"></div>
                    <div class="line3"></div>
                </div>
                <ul class="nav-list">
                    <li><a href="../php/sobre.php">Sobre</a></li>
                    <li><a href="../php/rotas_usuario.php">Rotas</a></li>
                    <li><a href="../php/notificacoes_usuario.php">Notificações</a></li>
                    <li><a href="chat.php">Reclame Aqui</a></li>
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
                        <div class="msg-status status-<?= htmlspecialchars($rec['status']) ?>">
                            <?= $rec['status'] == 'pendente' ? 'Aguardando resposta' : ($rec['status'] == 'respondida' ? 'Respondida' : 'Resolvida') ?>
                        </div>
                    </div>
                    <?php if ($rec['resposta']): ?>
                        <div class="msg bot">
                            <div class="msg-content"><?= htmlspecialchars($rec['resposta']) ?></div>
                            <div class="msg-time"><?= $rec['respondido_em'] ? htmlspecialchars(date('d/m/Y H:i', strtotime($rec['respondido_em']))) : '' ?></div>
                            <div class="msg-author">Equipe LLGR</div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <form class="chat-input-area" id="chat-form" method="POST" action="../api/reclamacoes.php">
            <?= csrf_input() ?>
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
    <script src="../js/toast.js"></script>
</body>
</html>
