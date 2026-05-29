<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado']);
    exit();
}
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
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
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
