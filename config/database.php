<?php
if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
}

$env = [
    'DB_HOST' => getenv('DB_HOST') ?: 'localhost',
    'DB_USER' => getenv('DB_USER') ?: 'root',
    'DB_PASS' => getenv('DB_PASS') ?: '',
    'DB_NAME' => getenv('DB_NAME') ?: 'ferrorama',
];

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
