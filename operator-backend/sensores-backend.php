<?php
session_start();
header('Content-Type: application/json');
include __DIR__ . '/../user-backend/conexao.php';

if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado']);
    exit();
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

switch ($acao) {
    case 'cadastrar':
        cadastrarSensor($conn);
        break;
    case 'listar':
        listarSensores($conn);
        break;
    case 'buscar':
        buscarSensor($conn);
        break;
    case 'atualizar':
        atualizarSensor($conn);
        break;
    case 'deletar':
        deletarSensor($conn);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}

function cadastrarSensor($conn) {
    $codigo = trim($_POST['codigo'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $localizacao = trim($_POST['localizacao'] ?? '');
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $status = $_POST['status'] ?? 'ativo';
    $unidade_medida = trim($_POST['unidade_medida'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');
    
    if (empty($codigo) || empty($tipo) || empty($localizacao)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
        return;
    }
    
    if (strlen($codigo) > 50) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Código deve ter no máximo 50 caracteres']);
        return;
    }
    
    $sqlCheck = "SELECT id FROM sensores WHERE codigo = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $codigo);
    $stmtCheck->execute();
    
    if ($stmtCheck->get_result()->num_rows > 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Código já cadastrado']);
        return;
    }
    
    $sql = "INSERT INTO sensores (codigo, tipo, localizacao, latitude, longitude, status, unidade_medida, observacoes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssddsss", $codigo, $tipo, $localizacao, $latitude, $longitude, $status, $unidade_medida, $observacoes);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Sensor cadastrado com sucesso!', 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar sensor: ' . $conn->error]);
    }
}

function listarSensores($conn) {
    $sql = "SELECT * FROM sensores ORDER BY criado_em DESC";
    $result = $conn->query($sql);
    $sensores = [];
    
    while ($row = $result->fetch_assoc()) {
        $sensores[] = $row;
    }
    
    echo json_encode(['sucesso' => true, 'dados' => $sensores]);
}

function buscarSensor($conn) {
    $id = $_GET['id'] ?? 0;
    
    $sql = "SELECT * FROM sensores WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['sucesso' => true, 'dados' => $row]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Sensor não encontrado']);
    }
}

function atualizarSensor($conn) {
    $id = $_POST['id'] ?? 0;
    $codigo = trim($_POST['codigo'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $localizacao = trim($_POST['localizacao'] ?? '');
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $status = $_POST['status'] ?? 'ativo';
    $unidade_medida = trim($_POST['unidade_medida'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');
    
    if (empty($codigo) || empty($tipo) || empty($localizacao)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
        return;
    }
    
    $sql = "UPDATE sensores 
            SET codigo=?, tipo=?, localizacao=?, latitude=?, longitude=?, status=?, unidade_medida=?, observacoes=? 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssddsssi", $codigo, $tipo, $localizacao, $latitude, $longitude, $status, $unidade_medida, $observacoes, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Sensor atualizado com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar sensor']);
    }
}

function deletarSensor($conn) {
    $id = $_POST['id'] ?? 0;
    
    $sql = "DELETE FROM sensores WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Sensor deletado com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao deletar sensor']);
    }
}
?>
