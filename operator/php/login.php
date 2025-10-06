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
    <title>Login Operador</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/login.css" />
</head>
<body>
<header></header>
<main class="login-container">
    <form action="../../operator-backend/login-backend.php" method="POST">
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
    </form>
</main>
</body>
</html>