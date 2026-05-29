<?php
session_start();
require_once __DIR__ . '/../../config/security.php';
$erro = $_SESSION['erro'] ?? '';
$sucesso = $_SESSION['sucesso'] ?? '';
$usuarioLogado = isset($_SESSION['usuario_id']);
unset($_SESSION['erro'], $_SESSION['sucesso']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Operador - LLGR</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/login.css" />
</head>
<body>
<header></header>
<main class="login-container">
    <form action="../../operator/api/login.php" method="POST">
        <?= csrf_input() ?>
        <h1>Login do Operador</h1>
        <div class="input-box">
            <input name="email" placeholder="E-mail" type="email" required />
        </div>
        <div class="input-box">
            <input name="senha" placeholder="Senha" type="password" required />
        </div>
        <?php if ($erro): ?>
            <div class="mensagem erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="mensagem sucesso"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>
        <button type="submit" class="login">Entrar</button>
        <a class="secondary-login-link" href="../../index.html">Voltar para escolha de acesso</a>
        <a class="secondary-login-link" href="../../user/pages/login.php">Entrar como usuário</a>
        <?php if ($usuarioLogado): ?>
            <a class="logout-user-link" href="../../user/pages/logout_usuario.php">Sair da conta de usuário</a>
        <?php endif; ?>
    </form>
</main>
</body>
</html>
