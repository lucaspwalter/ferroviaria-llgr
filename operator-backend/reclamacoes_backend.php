<?php
session_start();
require_once __DIR__ . '/../user-backend/conexao.php';
if (!isset($_SESSION['operador_id'])) {
    $_SESSION['erro'] = "Acesso negado.";
    header("Location: ../operator/php/login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST['acao'] ?? '';
    $operador_id = $_SESSION['operador_id'];
    if ($acao == 'responder') {
        $reclamacao_id = intval($_POST['reclamacao_id']);
        $resposta = trim($_POST['resposta']);
        if (empty($resposta)) {
            $_SESSION['erro'] = "A resposta não pode estar vazia.";
            header("Location: ../operator/php/reclamacoes.php");
            exit();
        }
        $sql = "UPDATE reclamacoes SET resposta = ?, status = 'respondida', respondido_por = ?, respondido_em = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $resposta, $operador_id, $reclamacao_id);
        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Resposta enviada com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao enviar resposta.";
        }
        header("Location: ../operator/php/reclamacoes.php");
        exit();
    }
    if ($acao == 'resolver') {
        $reclamacao_id = intval($_POST['reclamacao_id']);
        $sql = "UPDATE reclamacoes SET status = 'resolvida' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reclamacao_id);
        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Reclamação marcada como resolvida!";
        } else {
            $_SESSION['erro'] = "Erro ao atualizar reclamação.";
        }
        header("Location: ../operator/php/reclamacoes.php");
        exit();
    }
}
$_SESSION['erro'] = "Ação inválida.";
header("Location: ../operator/php/reclamacoes.php");
exit();