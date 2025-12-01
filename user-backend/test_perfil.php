<?php
session_start();

// Simular usuário logado para teste
if (!isset($_SESSION['usuario_id'])) {
    echo "Por favor, faça login primeiro para testar o perfil.";
    exit();
}

echo "<h2>Teste de Debug do Perfil</h2>";
echo "<p>Usuario ID: " . $_SESSION['usuario_id'] . "</p>";

// Incluir conexão
require_once __DIR__ . '/conexao.php';

echo "<p>Conexão com banco: OK</p>";

// Testar consulta
$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT id, nome, email, telefone, cpf, data_nascimento, 
               endereco, cidade, estado, created_at as criado_em
        FROM usuarios WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "<p style='color: red;'>Erro ao preparar consulta: " . $conn->error . "</p>";
    exit();
}

$stmt->bind_param("i", $usuario_id);

if (!$stmt->execute()) {
    echo "<p style='color: red;'>Erro ao executar consulta: " . $stmt->error . "</p>";
    exit();
}

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo "<p style='color: green;'>Dados encontrados!</p>";
    echo "<pre>";
    print_r($row);
    echo "</pre>";
} else {
    echo "<p style='color: red;'>Usuário não encontrado no banco!</p>";
}

$stmt->close();
$conn->close();
?>
