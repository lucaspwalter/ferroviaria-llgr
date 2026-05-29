<?php
session_start();
require_once __DIR__ . '/../../config/security.php';
require_once __DIR__ . '/../../config/database.php';
// Verifica se o operador está logado
if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Não autorizado']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? 'listar';

switch ($acao) {
    case 'cadastrar':
    case 'criar':
        criarEstacao();
        break;
    case 'listar':
        listarEstacoes();
        break;
    case 'buscar':
        buscarEstacao();
        break;
    case 'atualizar':
        atualizarEstacao();
        break;
    case 'deletar':
    case 'excluir':
        excluirEstacao();
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}

function criarEstacao() {
    global $conn;
    
    $codigo = $_POST['codigo'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $numero_plataformas = $_POST['numero_plataformas'] ?? 1;
    $capacidade_passageiros = $_POST['capacidade_passageiros'] ?? null;
    $status = $_POST['status'] ?? 'ativa';
    $horario_abertura = $_POST['horario_abertura'] ?? null;
    $horario_fechamento = $_POST['horario_fechamento'] ?? null;
    $servicos = $_POST['servicos'] ?? null;
    $observacoes = $_POST['observacoes'] ?? null;
    
    if (empty($codigo) || empty($nome) || empty($cidade) || empty($estado) || empty($endereco)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Campos obrigatórios não preenchidos']);
        return;
    }
    
    $sql = "INSERT INTO estacoes (codigo, nome, cidade, estado, endereco, latitude, longitude, 
            numero_plataformas, capacidade_passageiros, status, horario_abertura, horario_fechamento, 
            servicos, observacoes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssddiissss", $codigo, $nome, $cidade, $estado, $endereco, $latitude, 
                      $longitude, $numero_plataformas, $capacidade_passageiros, $status, 
                      $horario_abertura, $horario_fechamento, $servicos, $observacoes);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Estação cadastrada com sucesso']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar estação: ' . $stmt->error]);
    }
}

function listarEstacoes() {
    global $conn;
    
    $sql = "SELECT * FROM estacoes ORDER BY nome ASC";
    $result = $conn->query($sql);
    
    $estacoes = [];
    while ($row = $result->fetch_assoc()) {
        $estacoes[] = $row;
    }
    
    echo json_encode(['sucesso' => true, 'dados' => $estacoes]);
}

function buscarEstacao() {
    global $conn;
    
    $id = $_GET['id'] ?? 0;
    
    $sql = "SELECT * FROM estacoes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['sucesso' => true, 'dados' => $row]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Estação não encontrada']);
    }
}

function atualizarEstacao() {
    global $conn;
    
    $id = $_POST['id'] ?? 0;
    $codigo = $_POST['codigo'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $numero_plataformas = $_POST['numero_plataformas'] ?? 1;
    $capacidade_passageiros = $_POST['capacidade_passageiros'] ?? null;
    $status = $_POST['status'] ?? 'ativa';
    $horario_abertura = $_POST['horario_abertura'] ?? null;
    $horario_fechamento = $_POST['horario_fechamento'] ?? null;
    $servicos = $_POST['servicos'] ?? null;
    $observacoes = $_POST['observacoes'] ?? null;
    
    if (empty($id) || empty($codigo) || empty($nome) || empty($cidade) || empty($estado) || empty($endereco)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Campos obrigatórios não preenchidos']);
        return;
    }
    
    $sql = "UPDATE estacoes SET codigo = ?, nome = ?, cidade = ?, estado = ?, endereco = ?, 
            latitude = ?, longitude = ?, numero_plataformas = ?, capacidade_passageiros = ?, 
            status = ?, horario_abertura = ?, horario_fechamento = ?, servicos = ?, observacoes = ? 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssddiissssi", $codigo, $nome, $cidade, $estado, $endereco, $latitude, 
                      $longitude, $numero_plataformas, $capacidade_passageiros, $status, 
                      $horario_abertura, $horario_fechamento, $servicos, $observacoes, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Estação atualizada com sucesso']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar estação: ' . $stmt->error]);
    }
}

function excluirEstacao() {
    global $conn;
    
    $id = $_POST['id'] ?? 0;
    
    if (empty($id)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'ID não fornecido']);
        return;
    }
    
    $sql = "DELETE FROM estacoes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Estação excluída com sucesso']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao excluir estação: ' . $stmt->error]);
    }
}
?>
