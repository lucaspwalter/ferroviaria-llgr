<?php
session_start();
ob_clean();
header('Content-Type: application/json; charset=utf-8');

$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "ferrorama";

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro de conexão com banco de dados']);
    exit();
}

$conn->set_charset("utf8mb4");

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado']);
    exit();
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

switch ($acao) {
    case 'buscar_perfil':
        buscarPerfil($conn);
        break;
    case 'atualizar_perfil':
        atualizarPerfil($conn);
        break;
    case 'atualizar_senha':
        atualizarSenha($conn);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}

function buscarPerfil($conn) {
    $usuario_id = $_SESSION['usuario_id'];
    
    $sql = "SELECT id, nome, email, telefone, cpf, data_nascimento, endereco, cidade, estado, created_at as criado_em 
            FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao preparar consulta']);
        return;
    }
    
    $stmt->bind_param("i", $usuario_id);
    
    if (!$stmt->execute()) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao executar consulta']);
        return;
    }
    
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['sucesso' => true, 'dados' => $row]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não encontrado']);
    }
    
    $stmt->close();
}

function atualizarPerfil($conn) {
    $usuario_id = $_SESSION['usuario_id'];
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? null;
    $endereco = trim($_POST['endereco'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    
    if (empty($nome)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Nome é obrigatório']);
        return;
    }
    
    if (empty($data_nascimento)) {
        $data_nascimento = null;
    }
    
    $sql = "UPDATE usuarios 
            SET nome=?, telefone=?, data_nascimento=?, endereco=?, cidade=?, estado=?, updated_at=NOW() 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao preparar atualização']);
        return;
    }
    
    $stmt->bind_param("ssssssi", $nome, $telefone, $data_nascimento, $endereco, $cidade, $estado, $usuario_id);
    
    if ($stmt->execute()) {
        $_SESSION['usuario_nome'] = $nome;
        echo json_encode(['sucesso' => true, 'mensagem' => 'Perfil atualizado com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar perfil']);
    }
    
    $stmt->close();
}

function atualizarSenha($conn) {
    $usuario_id = $_SESSION['usuario_id'];
    $senha_atual = $_POST['senha_atual'] ?? '';
    $senha_nova = $_POST['senha_nova'] ?? '';
    $senha_confirma = $_POST['senha_confirma'] ?? '';
    
    if (empty($senha_atual) || empty($senha_nova) || empty($senha_confirma)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos']);
        return;
    }
    
    if ($senha_nova !== $senha_confirma) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Nova senha e confirmação não coincidem']);
        return;
    }
    
    if (strlen($senha_nova) < 8) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Nova senha deve ter pelo menos 8 caracteres']);
        return;
    }
    
    $sql = "SELECT senha FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao verificar senha']);
        return;
    }
    
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();
    
    if (!$usuario || !password_verify($senha_atual, $usuario['senha'])) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Senha atual incorreta']);
        return;
    }
    
    $senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
    
    $sql = "UPDATE usuarios SET senha=?, updated_at=NOW() WHERE id=?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar senha']);
        return;
    }
    
    $stmt->bind_param("si", $senha_hash, $usuario_id);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Senha alterada com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao alterar senha']);
    }
    
    $stmt->close();
}

$conn->close();
?>
