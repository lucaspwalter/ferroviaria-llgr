<?php
session_start();
require_once __DIR__ . '/conexao.php';
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['erro'] = "Você precisa estar logado.";
    header("Location: ../user/php/login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST['acao'] ?? '';
    if ($acao == 'enviar') {
        $usuario_id = $_SESSION['usuario_id'];
        $mensagem = trim($_POST['mensagem']);
        if (empty($mensagem)) {
            $_SESSION['erro'] = "A mensagem não pode estar vazia.";
            header("Location: ../user/html/chatU.php");
            exit();
        }
        if (strlen($mensagem) > 1000) {
            $_SESSION['erro'] = "A mensagem é muito longa (máximo 1000 caracteres).";
            header("Location: ../user/html/chatU.php");
            exit();
        }
        $sql = "INSERT INTO reclamacoes (usuario_id, mensagem) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $usuario_id, $mensagem);
        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Reclamação enviada com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao enviar reclamação.";
        }
        header("Location: ../user/html/chatU.php");
        exit();
    }
}
$_SESSION['erro'] = "Ação inválida.";
header("Location: ../user/html/chatU.php");
exit();