<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/login.php");
    exit();
}

require_once __DIR__ . '/../../config/security.php';
require_csrf_token('../pages/login.php');
require_once __DIR__ . '/../../config/database.php';

$email = filter_var($_POST["email"] ?? '', FILTER_VALIDATE_EMAIL);
$senha = $_POST["senha"] ?? '';

if (!$email) {
    $_SESSION['erro'] = "E-mail ou senha incorretos.";
    header("Location: ../pages/login.php");
    exit();
}

$sql = "SELECT id, senha, nome FROM usuarios WHERE LOWER(email) = LOWER(?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    $_SESSION['erro'] = "E-mail ou senha incorretos.";
    header("Location: ../pages/login.php");
    exit();
}

$usuario = $resultado->fetch_assoc();

if (password_verify($senha, $usuario['senha'])) {
    session_regenerate_id(true);
    unset($_SESSION['operador_id'], $_SESSION['operador_nome'], $_SESSION['operador_cargo']);
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    header("Location: ../pages/rotas_usuario.php");
    exit();
}

$_SESSION['erro'] = "E-mail ou senha incorretos.";
header("Location: ../pages/login.php");
exit();
?>
