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
        cadastrarManutencao($conn);
        break;
    case 'listar':
        listarManutencoes($conn);
        break;
    case 'buscar':
        buscarManutencao($conn);
        break;
    case 'atualizar':
        atualizarManutencao($conn);
        break;
    case 'deletar':
        deletarManutencao($conn);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}
function cadastrarManutencao($conn) {
    $trem_id = $_POST['trem_id'] ?? 0;
    $tipo = $_POST['tipo'] ?? '';
    $descricao = trim($_POST['descricao'] ?? '');
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_fim_prevista = $_POST['data_fim_prevista'] ?? '';
    $status = $_POST['status'] ?? 'agendada';
    $custo = $_POST['custo'] ?? null;
    $responsavel = trim($_POST['responsavel'] ?? '');
    $pecas_substituidas = trim($_POST['pecas_substituidas'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');
    if ($trem_id <= 0 || empty($tipo) || empty($descricao) || empty($data_inicio) || empty($data_fim_prevista)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
        return;
    }
    $sql = "INSERT INTO manutencoes (trem_id, tipo, descricao, data_inicio, data_fim_prevista, status, custo, responsavel, pecas_substituidas, observacoes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssdsss", $trem_id, $tipo, $descricao, $data_inicio, $data_fim_prevista, $status, $custo, $responsavel, $pecas_substituidas, $observacoes);
    if ($stmt->execute()) {
        if ($status === 'em_andamento') {
            $sqlUpdate = "UPDATE trens SET status='manutencao' WHERE id=?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("i", $trem_id);
            $stmtUpdate->execute();
        }
        echo json_encode(['sucesso' => true, 'mensagem' => 'Manutenção cadastrada com sucesso!', 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar manutenção: ' . $conn->error]);
    }
}
function listarManutencoes($conn) {
    $sql = "SELECT m.*, t.codigo as trem_codigo, t.modelo as trem_modelo 
            FROM manutencoes m 
            LEFT JOIN trens t ON m.trem_id = t.id 
            ORDER BY m.data_inicio DESC";
    $result = $conn->query($sql);
    $manutencoes = [];
    while ($row = $result->fetch_assoc()) {
        $manutencoes[] = $row;
    }
    echo json_encode(['sucesso' => true, 'dados' => $manutencoes]);
}
function buscarManutencao($conn) {
    $id = $_GET['id'] ?? 0;
    $sql = "SELECT m.*, t.codigo as trem_codigo, t.modelo as trem_modelo 
            FROM manutencoes m 
            LEFT JOIN trens t ON m.trem_id = t.id 
            WHERE m.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['sucesso' => true, 'dados' => $row]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Manutenção não encontrada']);
    }
}
function atualizarManutencao($conn) {
    $id = $_POST['id'] ?? 0;
    $trem_id = $_POST['trem_id'] ?? 0;
    $tipo = $_POST['tipo'] ?? '';
    $descricao = trim($_POST['descricao'] ?? '');
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_fim_prevista = $_POST['data_fim_prevista'] ?? '';
    $data_fim_real = $_POST['data_fim_real'] ?? null;
    $status = $_POST['status'] ?? 'agendada';
    $custo = $_POST['custo'] ?? null;
    $responsavel = trim($_POST['responsavel'] ?? '');
    $pecas_substituidas = trim($_POST['pecas_substituidas'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');
    if ($trem_id <= 0 || empty($tipo) || empty($descricao) || empty($data_inicio)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
        return;
    }
    $sql = "UPDATE manutencoes SET trem_id=?, tipo=?, descricao=?, data_inicio=?, data_fim_prevista=?, data_fim_real=?, status=?, custo=?, responsavel=?, pecas_substituidas=?, observacoes=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssdssssi", $trem_id, $tipo, $descricao, $data_inicio, $data_fim_prevista, $data_fim_real, $status, $custo, $responsavel, $pecas_substituidas, $observacoes, $id);
    if ($stmt->execute()) {
        $novo_status_trem = 'operacional';
        if ($status === 'em_andamento') {
            $novo_status_trem = 'manutencao';
        } elseif ($status === 'concluida') {
            $novo_status_trem = 'operacional';
            $sqlUpdateTrem = "UPDATE trens SET status=?, ultima_manutencao=? WHERE id=?";
            $stmtUpdateTrem = $conn->prepare($sqlUpdateTrem);
            $data_hoje = date('Y-m-d');
            $stmtUpdateTrem->bind_param("ssi", $novo_status_trem, $data_hoje, $trem_id);
            $stmtUpdateTrem->execute();
        }
        echo json_encode(['sucesso' => true, 'mensagem' => 'Manutenção atualizada com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar manutenção']);
    }
}
function deletarManutencao($conn) {
    $id = $_POST['id'] ?? 0;
    $sql = "DELETE FROM manutencoes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Manutenção deletada com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao deletar manutenção']);
    }
}
?>