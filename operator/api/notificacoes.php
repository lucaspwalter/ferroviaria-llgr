<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/security.php';
require_once __DIR__ . '/../../config/database.php';
if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

switch ($acao) {
    case 'cadastrar':
        cadastrarNotificacao($conn);
        break;
    case 'listar':
        listarNotificacoes($conn);
        break;
    case 'buscar':
        buscarNotificacao($conn);
        break;
    case 'marcar_lida':
        marcarComoLida($conn);
        break;
    case 'deletar':
        deletarNotificacao($conn);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}

function cadastrarNotificacao($conn) {
    try {
        $operador_id = $_POST['operador_id'] ?? null;
        $tipo = $_POST['tipo'] ?? '';
        $titulo = trim($_POST['titulo'] ?? '');
        $mensagem = trim($_POST['mensagem'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $prioridade = $_POST['prioridade'] ?? 'média';
        
        if (empty($tipo) || empty($titulo) || empty($mensagem)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
            return;
        }
        
        $sql = "INSERT INTO notificacoes (operador_id, tipo, titulo, mensagem, link, prioridade) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $operador_id, $tipo, $titulo, $mensagem, $link, $prioridade);
        
        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Notificação cadastrada com sucesso!', 'id' => $stmt->insert_id]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar notificação: ' . $conn->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function listarNotificacoes($conn) {
    try {
        $operador_id = $_SESSION['operador_id'];
        
        $sql = "SELECT * FROM notificacoes 
                WHERE operador_id IS NULL OR operador_id = ? 
                ORDER BY lida ASC, criado_em DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $operador_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $notificacoes = [];
        
        while ($row = $result->fetch_assoc()) {
            $notificacoes[] = $row;
        }
        
        echo json_encode(['sucesso' => true, 'dados' => $notificacoes]);
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function buscarNotificacao($conn) {
    try {
        $id = $_GET['id'] ?? 0;
        
        $sql = "SELECT * FROM notificacoes WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            echo json_encode(['sucesso' => true, 'dados' => $row]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Notificação não encontrada']);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function marcarComoLida($conn) {
    try {
        $id = $_POST['id'] ?? 0;
        $lida_em = date('Y-m-d H:i:s');
        
        $sql = "UPDATE notificacoes SET lida=TRUE, lida_em=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $lida_em, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Notificação marcada como lida!']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao marcar notificação']);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function deletarNotificacao($conn) {
    try {
        $id = $_POST['id'] ?? 0;
        
        $sql = "DELETE FROM notificacoes WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Notificação deletada com sucesso!']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao deletar notificação']);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}
?>
