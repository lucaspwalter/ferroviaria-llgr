<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0); // Não exibir erros no output JSON

require_once __DIR__ . '/../../config/database.php';
if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado']);
    exit();
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

switch ($acao) {
    case 'cadastrar':
        cadastrarTrem($conn);
        break;
    case 'listar':
        listarTrens($conn);
        break;
    case 'buscar':
        buscarTrem($conn);
        break;
    case 'atualizar':
        atualizarTrem($conn);
        break;
    case 'deletar':
        deletarTrem($conn);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}

function cadastrarTrem($conn) {
    $codigo = trim($_POST['codigo'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $fabricante = trim($_POST['fabricante'] ?? '');
    $ano_fabricacao = $_POST['ano_fabricacao'] ?? null;
    $capacidade_passageiros = $_POST['capacidade_passageiros'] ?? 0;
    $velocidade_maxima = $_POST['velocidade_maxima'] ?? null;
    $status = $_POST['status'] ?? 'operacional';
    $km_rodados = $_POST['km_rodados'] ?? 0;
    $observacoes = trim($_POST['observacoes'] ?? '');
    
    if (empty($codigo) || empty($modelo) || $capacidade_passageiros <= 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
        return;
    }
    
    $sqlCheck = "SELECT id FROM trens WHERE codigo = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $codigo);
    $stmtCheck->execute();
    
    if ($stmtCheck->get_result()->num_rows > 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Código já cadastrado']);
        return;
    }
    
    $sql = "INSERT INTO trens (codigo, modelo, fabricante, ano_fabricacao, capacidade_passageiros, 
            velocidade_maxima, status, km_rodados, observacoes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssidsdds", $codigo, $modelo, $fabricante, $ano_fabricacao, $capacidade_passageiros, 
                      $velocidade_maxima, $status, $km_rodados, $observacoes);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Trem cadastrado com sucesso!', 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar trem: ' . $conn->error]);
    }
}

function listarTrens($conn) {
    $sql = "SELECT * FROM trens ORDER BY criado_em DESC";
    $result = $conn->query($sql);
    $trens = [];
    
    while ($row = $result->fetch_assoc()) {
        $trens[] = $row;
    }
    
    echo json_encode(['sucesso' => true, 'dados' => $trens]);
}

function buscarTrem($conn) {
    $id = $_GET['id'] ?? 0;
    
    $sql = "SELECT * FROM trens WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['sucesso' => true, 'dados' => $row]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Trem não encontrado']);
    }
}

function atualizarTrem($conn) {
    $id = $_POST['id'] ?? 0;
    $codigo = trim($_POST['codigo'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $fabricante = trim($_POST['fabricante'] ?? '');
    $ano_fabricacao = !empty($_POST['ano_fabricacao']) ? $_POST['ano_fabricacao'] : null;
    $capacidade_passageiros = $_POST['capacidade_passageiros'] ?? 0;
    $velocidade_maxima = !empty($_POST['velocidade_maxima']) ? $_POST['velocidade_maxima'] : null;
    $status = $_POST['status'] ?? 'operacional';
    $km_rodados = !empty($_POST['km_rodados']) ? $_POST['km_rodados'] : 0;
    $observacoes = trim($_POST['observacoes'] ?? '');
    
    if (empty($id)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'ID não informado']);
        return;
    }
    
    if (empty($codigo) || empty($modelo) || $capacidade_passageiros <= 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
        return;
    }
    
    $sql = "UPDATE trens 
            SET codigo=?, modelo=?, fabricante=?, ano_fabricacao=?, capacidade_passageiros=?, 
                velocidade_maxima=?, status=?, km_rodados=?, observacoes=?, atualizado_em=NOW() 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiddsdsi", $codigo, $modelo, $fabricante, $ano_fabricacao, $capacidade_passageiros, 
                      $velocidade_maxima, $status, $km_rodados, $observacoes, $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Trem atualizado com sucesso!']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhuma alteração foi feita']);
        }
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar trem: ' . $stmt->error]);
    }
}

function deletarTrem($conn) {
    $id = $_POST['id'] ?? 0;
    
    $sql = "DELETE FROM trens WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Trem deletado com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao deletar trem']);
    }
}
?>
