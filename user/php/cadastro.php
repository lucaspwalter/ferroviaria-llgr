<?php
session_start();
$erro = $_SESSION['erro'] ?? '';
$sucesso = $_SESSION['sucesso'] ?? '';
unset($_SESSION['erro'], $_SESSION['sucesso']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastro</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/cadastro.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>
<body>
<header></header>
<main class="login-container">
    <form action="/sa/ferroviaria-llgr/user-backend/cadastro_backend.php" method="POST">
        <h1>Crie sua <span class="conta-vermelho">conta</span></h1>

        <div class="input-box">
            <input name="nome" placeholder="Usuário" type="text" required />
            <i class="bi bi-person-fill"></i>
        </div>

        <div class="input-box">
            <input name="email" placeholder="E-mail" type="email" required />
            <i class="bi bi-envelope"></i>
        </div>

        <div class="input-box">
            <input name="senha" placeholder="Senha" type="password" required />
            <i class="bi bi-lock-fill"></i>
        </div>

        <?php if ($erro): ?>
            <div class="mensagem erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="mensagem sucesso"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <button type="submit" class="login">Cadastre-se aqui!</button>

        <div class="register-link">
            <p>Já tem conta? <a href="login.php">Entrar</a></p>
        </div>
    </form>
</main>
<script src="../js/mobile-navbar.js"></script>
</body>
</html>
