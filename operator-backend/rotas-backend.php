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
        cadastrarRota($conn);
        break;
    case 'listar':
        listarRotas($conn);
        break;
    case 'buscar':
        buscarRota($conn);
        break;
    case 'atualizar':
        atualizarRota($conn);
        break;
    case 'deletar':
        deletarRota($conn);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}
function cadastrarRota($conn) {
    $codigo = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $origem = trim($_POST['origem'] ?? '');
    $destino = trim($_POST['destino'] ?? '');
    $distancia_km = $_POST['distancia_km'] ?? 0;
    $tempo_estimado = $_POST['tempo_estimado'] ?? '';
    $status = $_POST['status'] ?? 'ativa';
    $numero_paradas = $_POST['numero_paradas'] ?? 0;
    $paradas = trim($_POST['paradas'] ?? '');
    $preco_base = $_POST['preco_base'] ?? null;
    $observacoes = trim($_POST['observacoes'] ?? '');
    if (empty($codigo) || empty($nome) || empty($origem) || empty($destino) || $distancia_km <= 0 || empty($tempo_estimado)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
        return;
    }
    $sqlCheck = "SELECT id FROM rotas WHERE codigo = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $codigo);
    $stmtCheck->execute();
    if ($stmtCheck->get_result()->num_rows > 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Código já cadastrado']);
        return;
    }
    $sql = "INSERT INTO rotas (codigo, nome, origem, destino, distancia_km, tempo_estimado, status, numero_paradas, paradas, preco_base, observacoes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdssisds", $codigo, $nome, $origem, $destino, $distancia_km, $tempo_estimado, $status, $numero_paradas, $paradas, $preco_base, $observacoes);
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Rota cadastrada com sucesso!', 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar rota: ' . $conn->error]);
    }
}
function listarRotas($conn) {
    $sql = "SELECT * FROM rotas ORDER BY criado_em DESC";
    $result = $conn->query($sql);
    $rotas = [];
    while ($row = $result->fetch_assoc()) {
        $rotas[] = $row;
    }
    echo json_encode(['sucesso' => true, 'dados' => $rotas]);
}
function buscarRota($conn) {
    $id = $_GET['id'] ?? 0;
    $sql = "SELECT * FROM rotas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['sucesso' => true, 'dados' => $row]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Rota não encontrada']);
    }
}
function atualizarRota($conn) {
    $id = $_POST['id'] ?? 0;
    $codigo = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $origem = trim($_POST['origem'] ?? '');
    $destino = trim($_POST['destino'] ?? '');
    $distancia_km = $_POST['distancia_km'] ?? 0;
    $tempo_estimado = $_POST['tempo_estimado'] ?? '';
    $status = $_POST['status'] ?? 'ativa';
    $numero_paradas = $_POST['numero_paradas'] ?? 0;
    $paradas = trim($_POST['paradas'] ?? '');
    $preco_base = $_POST['preco_base'] ?? null;
    $observacoes = trim($_POST['observacoes'] ?? '');
    if (empty($codigo) || empty($nome) || empty($origem) || empty($destino) || $distancia_km <= 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
        return;
    }
    $sql = "UPDATE rotas SET codigo=?, nome=?, origem=?, destino=?, distancia_km=?, tempo_estimado=?, status=?, numero_paradas=?, paradas=?, preco_base=?, observacoes=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdssisdsi", $codigo, $nome, $origem, $destino, $distancia_km, $tempo_estimado, $status, $numero_paradas, $paradas, $preco_base, $observacoes, $id);
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Rota atualizada com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar rota']);
    }
}
function deletarRota($conn) {
    $id = $_POST['id'] ?? 0;
    $sql = "DELETE FROM rotas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Rota deletada com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao deletar rota']);
    }
}
?>