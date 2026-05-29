<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$senha = $_POST['senha'] ?? '';
if (!$email) {
    $_SESSION['erro'] = "E-mail inválido!";
    header("Location: ../php/login.php");
    exit();
}
if (strlen($senha) < 8) {
    $_SESSION['erro'] = "A senha deve ter pelo menos 8 caracteres!";
    header("Location: ../php/login.php");
    exit();
}
$sql = "SELECT * FROM operadores WHERE email = ? AND ativo = TRUE";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $operador = $result->fetch_assoc();
    if (password_verify($senha, $operador['senha'])) {
        session_regenerate_id(true);
        $_SESSION['operador_id'] = $operador['id'];
        $_SESSION['operador_nome'] = $operador['nome'];
        $_SESSION['operador_cargo'] = $operador['cargo'];
        header("Location: ../php/dashboard.php");
        exit();
    } else {
        $_SESSION['erro'] = "Senha incorreta!";
        header("Location: ../php/login.php");
        exit();
    }
} else {
    $_SESSION['erro'] = "Operador não encontrado ou inativo!";
    header("Location: ../php/login.php");
    exit();
}
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>
