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
    $sql = "SELECT
                r.*,
                COUNT(rp.id) AS total_paradas,
                GROUP_CONCAT(rp.nome_parada ORDER BY rp.ordem SEPARATOR '||') AS paradas
            FROM rotas r
            LEFT JOIN rota_paradas rp ON rp.rota_id = r.id
            WHERE r.status = 'ativa'
            GROUP BY r.id
            ORDER BY r.nome ASC";
    $result = $conn->query($sql);
    $rotas = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['paradas'] = $row['paradas'] ? explode('||', $row['paradas']) : [];
            $rotas[] = $row;
        }
    }
    echo json_encode(['sucesso' => true, 'dados' => $rotas]);
}
function buscarRota($conn) {
    $id = $_GET['id'] ?? 0;
    $sql = "SELECT
                r.*,
                COUNT(rp.id) AS total_paradas,
                GROUP_CONCAT(rp.nome_parada ORDER BY rp.ordem SEPARATOR '||') AS paradas
            FROM rotas r
            LEFT JOIN rota_paradas rp ON rp.rota_id = r.id
            WHERE r.id = ? AND r.status = 'ativa'
            GROUP BY r.id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $row['paradas'] = $row['paradas'] ? explode('||', $row['paradas']) : [];
        echo json_encode(['sucesso' => true, 'dados' => $row]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Rota não encontrada']);
    }
}
?>
