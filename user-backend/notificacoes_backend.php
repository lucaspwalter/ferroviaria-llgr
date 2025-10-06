<?php
header('Content-Type: application/json');
include __DIR__ . '/conexao.php';
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
    session_start();
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