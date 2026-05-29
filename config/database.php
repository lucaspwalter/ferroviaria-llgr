<?php
if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
}

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

mysqli_report(MYSQLI_REPORT_OFF);

$conn = @new mysqli($env['DB_HOST'], $env['DB_USER'], $env['DB_PASS'], $env['DB_NAME']);

if ($conn->connect_error) {
    if (session_status() === PHP_SESSION_ACTIVE && isset($_SERVER['SCRIPT_NAME'])) {
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);

        if (substr($script, -strlen('/user/api/cadastro.php')) === '/user/api/cadastro.php') {
            $_SESSION['erro'] = 'Nao foi possivel conectar ao banco de dados. Confira as credenciais no arquivo .env.';
            header('Location: ../pages/cadastro.php');
            exit();
        }

        if (substr($script, -strlen('/user/api/login.php')) === '/user/api/login.php') {
            $_SESSION['erro'] = 'Nao foi possivel conectar ao banco de dados. Confira as credenciais no arquivo .env.';
            header('Location: ../pages/login.php');
            exit();
        }
    }

    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Nao foi possivel conectar ao banco de dados.',
    ]);
    exit();
}

$conn->set_charset('utf8mb4');
