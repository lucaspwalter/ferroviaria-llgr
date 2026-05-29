<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado']);
    exit();
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

switch ($acao) {
    case 'cadastrar':
        cadastrarAlerta($conn);
        break;
    case 'listar':
        listarAlertas($conn);
        break;
    case 'buscar':
        buscarAlerta($conn);
        break;
    case 'atualizar':
        atualizarAlerta($conn);
        break;
    case 'deletar':
        deletarAlerta($conn);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}

function cadastrarAlerta($conn) {
    try {
        $tipo = $_POST['tipo'] ?? '';
        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $origem = $_POST['origem'] ?? '';
        $origem_id = $_POST['origem_id'] ?? null;
        $prioridade = $_POST['prioridade'] ?? 1;
        $status = $_POST['status'] ?? 'ativo';
        $localizacao = trim($_POST['localizacao'] ?? '');
        
        if (empty($tipo) || empty($titulo) || empty($descricao) || empty($origem)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
            return;
        }
        
        $sql = "INSERT INTO alertas (tipo, titulo, descricao, origem, origem_id, prioridade, status, localizacao) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiiss", $tipo, $titulo, $descricao, $origem, $origem_id, $prioridade, $status, $localizacao);
        
        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Alerta cadastrado com sucesso!', 'id' => $stmt->insert_id]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar alerta: ' . $conn->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function listarAlertas($conn) {
    try {
        $sql = "SELECT * FROM alertas ORDER BY prioridade DESC, criado_em DESC";
        $result = $conn->query($sql);
        $alertas = [];
        
        while ($row = $result->fetch_assoc()) {
            $alertas[] = $row;
        }
        
        echo json_encode(['sucesso' => true, 'dados' => $alertas]);
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function buscarAlerta($conn) {
    try {
        $id = $_GET['id'] ?? 0;
        
        $sql = "SELECT * FROM alertas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            echo json_encode(['sucesso' => true, 'dados' => $row]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Alerta não encontrado']);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function atualizarAlerta($conn) {
    try {
        $id = $_POST['id'] ?? 0;
        $tipo = $_POST['tipo'] ?? '';
        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $origem = $_POST['origem'] ?? '';
        $origem_id = $_POST['origem_id'] ?? null;
        $prioridade = $_POST['prioridade'] ?? 1;
        $status = $_POST['status'] ?? 'ativo';
        $localizacao = trim($_POST['localizacao'] ?? '');
        $acao_tomada = trim($_POST['acao_tomada'] ?? '');
        
        if (empty($tipo) || empty($titulo) || empty($descricao)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
            return;
        }
        
        $resolvido_por = null;
        $resolvido_em = null;
        
        if ($status === 'resolvido') {
            $resolvido_por = $_SESSION['operador_id'];
            $resolvido_em = date('Y-m-d H:i:s');
        }
        
        $sql = "UPDATE alertas 
                SET tipo=?, titulo=?, descricao=?, origem=?, origem_id=?, prioridade=?, status=?, 
                    localizacao=?, acao_tomada=?, resolvido_por=?, resolvido_em=? 
                WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiisssisi", $tipo, $titulo, $descricao, $origem, $origem_id, $prioridade, 
                          $status, $localizacao, $acao_tomada, $resolvido_por, $resolvido_em, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Alerta atualizado com sucesso!']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar alerta']);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function deletarAlerta($conn) {
    try {
        $id = $_POST['id'] ?? 0;
        
        $sql = "DELETE FROM alertas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Alerta deletado com sucesso!']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao deletar alerta']);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}
?>
