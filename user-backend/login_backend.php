<?php
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    $sql = "SELECT id, senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $_SESSION['erro'] = "E-mail ou senha incorretos.";
        header("Location: ../TELA USUARIO/html/login.php");
        exit();
    }

    $stmt->bind_result($id, $senhaHash);
    $stmt->fetch();

    if (password_verify($senha, $senhaHash)) {
        $_SESSION['usuario_id'] = $id;
        header("Location: ../TELA USUARIO/html/telainicialU.html"); 
        exit();
    } else {
        $_SESSION['erro'] = "E-mail ou senha incorretos.";
        header("Location: ../TELA USUARIO/html/login.php");
        exit();
    }
}
?>
