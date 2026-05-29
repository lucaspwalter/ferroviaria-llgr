<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado']);
    exit();
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';
$operador_id = intval($_SESSION['operador_id']);

switch ($acao) {
    case 'buscar':
        buscarPerfil($conn, $operador_id);
        break;
    case 'atualizar':
        atualizarPerfil($conn, $operador_id);
        break;
    case 'atualizar_senha':
        atualizarSenha($conn, $operador_id);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}

function buscarPerfil($conn, $operador_id) {
    $stmt = $conn->prepare("SELECT id, nome, email, telefone, cargo, especialidade FROM operadores WHERE id = ?");
    $stmt->bind_param("i", $operador_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($operador = $result->fetch_assoc()) {
        echo json_encode(['sucesso' => true, 'dados' => $operador]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Operador não encontrado']);
    }
}

function atualizarPerfil($conn, $operador_id) {
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');

    if (strlen($nome) < 3) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Informe um nome válido']);
        return;
    }

    $stmt = $conn->prepare("UPDATE operadores SET nome = ?, telefone = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nome, $telefone, $operador_id);

    if ($stmt->execute()) {
        $_SESSION['operador_nome'] = $nome;
        echo json_encode(['sucesso' => true, 'mensagem' => 'Perfil atualizado com sucesso']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar perfil']);
    }
}

function atualizarSenha($conn, $operador_id) {
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';

    if (strlen($nova_senha) < 8) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'A nova senha deve ter no mínimo 8 caracteres']);
        return;
    }

    $stmt = $conn->prepare("SELECT senha FROM operadores WHERE id = ?");
    $stmt->bind_param("i", $operador_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $operador = $result->fetch_assoc();

    if (!$operador || !password_verify($senha_atual, $operador['senha'])) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Senha atual incorreta']);
        return;
    }

    $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE operadores SET senha = ? WHERE id = ?");
    $stmt->bind_param("si", $hash, $operador_id);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Senha atualizada com sucesso']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar senha']);
    }
}
