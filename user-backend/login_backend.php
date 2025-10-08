<?php
session_start();
require_once __DIR__ . "/conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];
    
    $sql = "SELECT id, senha, nome FROM usuarios WHERE LOWER(email) = LOWER(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 0) {
        $_SESSION['erro'] = "E-mail ou senha incorretos.";
        header("Location: ../user/php/login.php");
        exit();
    }
    
    $usuario = $resultado->fetch_assoc();
    
    if (password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: ../user/php/rotas_usuario.php");
        exit();
    } else {
        $_SESSION['erro'] = "E-mail ou senha incorretos.";
        header("Location: ../user/php/login.php");
        exit();
    }
}
?>
