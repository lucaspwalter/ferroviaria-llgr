<?php
header('Content-Type: application/json');
include __DIR__ . '/conexao.php';
$acao = $_GET['acao'] ?? '';
switch ($acao) {
    case 'listar_ativas':
        listarRotasAtivas($conn);
        break;
    case 'buscar':
        buscarRota($conn);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}
function listarRotasAtivas($conn) {
    $sql = "SELECT * FROM rotas WHERE status = 'ativa' ORDER BY nome ASC";
    $result = $conn->query($sql);
    $rotas = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rotas[] = $row;
        }
    }
    echo json_encode(['sucesso' => true, 'dados' => $rotas]);
}
function buscarRota($conn) {
    $id = $_GET['id'] ?? 0;
    $sql = "SELECT * FROM rotas WHERE id = ? AND status = 'ativa'";
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
?>