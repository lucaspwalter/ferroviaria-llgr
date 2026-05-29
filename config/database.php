<?php
$envPath = __DIR__ . '/../.env';
$env = [
    'DB_HOST' => 'localhost',
    'DB_USER' => 'root',
    'DB_PASS' => '',
    'DB_NAME' => 'ferrorama',
];

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);

        if (array_key_exists($key, $env)) {
            $env[$key] = trim($value);
        }
    }
}

$conn = new mysqli($env['DB_HOST'], $env['DB_USER'], $env['DB_PASS'], $env['DB_NAME']);

if ($conn->connect_error) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno de conexão',
    ]);
    exit();
}

$conn->set_charset('utf8mb4');
