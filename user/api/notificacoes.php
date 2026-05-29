<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/security.php';
require_once __DIR__ . '/../../config/database.php';
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
}
$acao = $_GET['acao'] ?? '';
switch ($acao) {
    case 'listar_publicas':
        listarNotificacoesPublicas($conn);
        break;
    case 'marcar_lida':
        marcarComoLida($conn);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}

function listarNotificacoesPublicas($conn) {
    $sql = "SELECT * FROM notificacoes 
            WHERE operador_id IS NULL OR tipo = 'sistema'
            ORDER BY lida ASC, criado_em DESC 
            LIMIT 50";
    $result = $conn->query($sql);
    $notificacoes = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $notificacoes[] = $row;
        }
    }
    echo json_encode(['sucesso' => true, 'dados' => $notificacoes]);
}
function marcarComoLida($conn) {
    $id = $_POST['id'] ?? 0;
    $lida_em = date('Y-m-d H:i:s');
    $sql = "UPDATE notificacoes SET lida=TRUE, lida_em=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $lida_em, $id);
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Notificação marcada como lida']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao marcar notificação']);
    }
}
?>
