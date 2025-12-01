<?php
session_start();
header('Content-Type: application/json');

// Testar conexão com banco
try {
    include __DIR__ . '/../user-backend/conexao.php';
    
    $response = [
        'status' => 'ok',
        'session_active' => isset($_SESSION['operador_id']),
        'operador_id' => $_SESSION['operador_id'] ?? null,
        'db_connected' => isset($conn) && $conn->ping(),
        'post_data' => $_POST,
        'get_data' => $_GET,
        'server_time' => date('Y-m-d H:i:s')
    ];
    
    // Testar query simples
    if (isset($conn)) {
        $result = $conn->query("SELECT COUNT(*) as total FROM sensores");
        if ($result) {
            $row = $result->fetch_assoc();
            $response['sensores_count'] = $row['total'];
        }
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>
