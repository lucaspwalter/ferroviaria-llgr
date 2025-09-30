<?php
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    // Validações
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "E-mail inválido.";
        header("Location: ../TELA USUARIO/html/cadastro.php");
        exit();
    }

    if (strlen($senha) < 8 || !preg_match('/[A-Z]/', $senha) || !preg_match('/[0-9]/', $senha)) {
        $_SESSION['erro'] = "A senha deve ter no mínimo 8 caracteres, incluindo letra maiúscula e número.";
        header("Location: ../TELA USUARIO/html/cadastro.php");
        exit();
    }

    // Verificar duplicidade
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['erro'] = "E-mail já cadastrado.";
        header("Location: ../TELA USUARIO/html/cadastro.php");
        exit();
    }

    // Salvar no banco
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senhaHash);

    if ($stmt->execute()) {
        $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Agora você pode entrar.";
        header("Location: ../TELA USUARIO/html/cadastro.php"); // volta para a mesma página
        exit();
    } else {
        $_SESSION['erro'] = "Erro ao cadastrar.";
        header("Location: ../TELA USUARIO/html/cadastro.php");
        exit();
    }
}
?>
