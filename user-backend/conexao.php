<?php
$servidor = "localhost";
$usuario = "root";
$senha = "root";
$banco = "ferrorama";

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
