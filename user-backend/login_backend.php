<?php
session_start();
require_once __DIR__ . "/conexao.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];
    $sql = "SELECT id, senha FROM usuarios WHERE LOWER(email) = LOWER(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 0) {
        $_SESSION['erro'] = "E-mail ou senha incorretos.";
        header("Location: /ferroviaria-llgr/user/php/login.php");
        exit();
    }
    $usuario = $resultado->fetch_assoc();
    if (password_verify($senha, $usuario['senha'])) {
        $sql_nome = "SELECT nome FROM usuarios WHERE id = ?";
        $stmt_nome = $conn->prepare($sql_nome);
        $stmt_nome->bind_param("i", $usuario['id']);
        $stmt_nome->execute();
        $result_nome = $stmt_nome->get_result();
        $dados_usuario = $result_nome->fetch_assoc();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $dados_usuario['nome'];
        header("Location: /ferroviaria-llgr/user/html/telainicialU.html");
        exit();
    } else {
        $_SESSION['erro'] = "E-mail ou senha incorretos.";
        header("Location: /ferroviaria-llgr/user/php/login.php");
        exit();
    }
}
?>