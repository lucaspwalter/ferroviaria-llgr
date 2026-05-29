<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../php/login.php");
    exit();
}

require_once __DIR__ . '/../../config/security.php';
require_csrf_token('../php/login.php');
require_once __DIR__ . '/../../config/database.php';

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$senha = $_POST['senha'] ?? '';
if (!$email) {
    $_SESSION['erro'] = "E-mail ou senha incorretos.";
    header("Location: ../php/login.php");
    exit();
}
if (strlen($senha) < 8) {
    $_SESSION['erro'] = "E-mail ou senha incorretos.";
    header("Location: ../php/login.php");
    exit();
}
$sql = "SELECT id, nome, senha, cargo FROM operadores WHERE email = ? AND ativo = TRUE";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $operador = $result->fetch_assoc();
    if (password_verify($senha, $operador['senha'])) {
        session_regenerate_id(true);
        unset($_SESSION['usuario_id'], $_SESSION['usuario_nome']);
        $_SESSION['operador_id'] = $operador['id'];
        $_SESSION['operador_nome'] = $operador['nome'];
        $_SESSION['operador_cargo'] = $operador['cargo'];
        header("Location: ../php/dashboard.php");
        exit();
    } else {
        $_SESSION['erro'] = "E-mail ou senha incorretos.";
        header("Location: ../php/login.php");
        exit();
    }
} else {
    $_SESSION['erro'] = "E-mail ou senha incorretos.";
    header("Location: ../php/login.php");
    exit();
}
?>
