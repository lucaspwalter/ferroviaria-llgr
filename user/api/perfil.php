<?php
session_start();

// Limpar qualquer output anterior
if (ob_get_level()) {
    ob_end_clean();
}

// Garantir que retornamos JSON
header('Content-Type: application/json; charset=utf-8');

// Desabilitar exibição de erros (mas manter log)
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Incluir conexão
require_once __DIR__ . '/../../config/database.php';
// Verificar autenticação
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'sucesso' => false, 
        'mensagem' => 'Usuário não autenticado'
    ]);
    exit();
}

// Pegar ação
$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

// Roteamento
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
        echo json_encode([
            'sucesso' => false, 
            'mensagem' => 'Ação inválida'
        ]);
}

function buscarPerfil($conn) {
    try {
        $usuario_id = $_SESSION['usuario_id'];

        $sql = "SELECT id, nome, email, telefone, cpf, data_nascimento, 
                       endereco, cidade, estado, created_at as criado_em
                FROM usuarios WHERE id = ?";
        
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception('Erro ao preparar consulta');
        }

        $stmt->bind_param("i", $usuario_id);

        if (!$stmt->execute()) {
            throw new Exception('Erro ao executar consulta');
        }

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                'sucesso' => true, 
                'dados' => $row
            ]);
        } else {
            echo json_encode([
                'sucesso' => false, 
                'mensagem' => 'Usuário não encontrado'
            ]);
        }

        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'sucesso' => false, 
            'mensagem' => 'Erro: ' . $e->getMessage()
        ]);
    }
}

function atualizarPerfil($conn) {
    try {
        $usuario_id = $_SESSION['usuario_id'];
        $nome = trim($_POST['nome'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $data_nascimento = $_POST['data_nascimento'] ?? null;
        $endereco = trim($_POST['endereco'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $estado = trim($_POST['estado'] ?? '');

        if (empty($nome)) {
            throw new Exception('Nome é obrigatório');
        }

        if (empty($data_nascimento)) {
            $data_nascimento = null;
        }

        $sql = "UPDATE usuarios
                SET nome=?, telefone=?, data_nascimento=?, endereco=?, cidade=?, estado=?, updated_at=NOW()
                WHERE id=?";
        
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception('Erro ao preparar atualização');
        }

        $stmt->bind_param("ssssssi", $nome, $telefone, $data_nascimento, $endereco, $cidade, $estado, $usuario_id);

        if ($stmt->execute()) {
            $_SESSION['usuario_nome'] = $nome;
            echo json_encode([
                'sucesso' => true, 
                'mensagem' => 'Perfil atualizado com sucesso!'
            ]);
        } else {
            throw new Exception('Erro ao atualizar perfil');
        }

        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'sucesso' => false, 
            'mensagem' => $e->getMessage()
        ]);
    }
}

function atualizarSenha($conn) {
    try {
        $usuario_id = $_SESSION['usuario_id'];
        $senha_atual = $_POST['senha_atual'] ?? '';
        $senha_nova = $_POST['senha_nova'] ?? '';
        $senha_confirma = $_POST['senha_confirma'] ?? '';

        if (empty($senha_atual) || empty($senha_nova) || empty($senha_confirma)) {
            throw new Exception('Preencha todos os campos');
        }

        if ($senha_nova !== $senha_confirma) {
            throw new Exception('Nova senha e confirmação não coincidem');
        }

        if (strlen($senha_nova) < 8) {
            throw new Exception('Nova senha deve ter pelo menos 8 caracteres');
        }

        $sql = "SELECT senha FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception('Erro ao verificar senha');
        }

        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();

        if (!$usuario || !password_verify($senha_atual, $usuario['senha'])) {
            throw new Exception('Senha atual incorreta');
        }

        $senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios SET senha=?, updated_at=NOW() WHERE id=?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception('Erro ao atualizar senha');
        }

        $stmt->bind_param("si", $senha_hash, $usuario_id);

        if ($stmt->execute()) {
            echo json_encode([
                'sucesso' => true, 
                'mensagem' => 'Senha alterada com sucesso!'
            ]);
        } else {
            throw new Exception('Erro ao alterar senha');
        }

        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'sucesso' => false, 
            'mensagem' => $e->getMessage()
        ]);
    }
}

$conn->close();
?>
