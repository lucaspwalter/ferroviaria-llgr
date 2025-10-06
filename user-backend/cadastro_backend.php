<?php
session_start();
require_once __DIR__ . "/conexao.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $nome = trim($_POST["nome"]);
        $email = trim($_POST["email"]);
        $senha = $_POST["senha"];
        $confirmar_senha = $_POST["confirmar_senha"];
        $cpf = isset($_POST["cpf"]) ? preg_replace('/[^0-9]/', '', trim($_POST["cpf"])) : null;
        $telefone = isset($_POST["telefone"]) ? trim($_POST["telefone"]) : null;
        $data_nascimento = !empty($_POST["data_nascimento"]) ? $_POST["data_nascimento"] : null;
        $endereco = isset($_POST["endereco"]) ? trim($_POST["endereco"]) : null;
        $cidade = isset($_POST["cidade"]) ? trim($_POST["cidade"]) : null;
        $estado = isset($_POST["estado"]) ? trim($_POST["estado"]) : null;
        if (strlen($nome) < 3 || strlen($nome) > 100) {
            throw new Exception("O nome deve ter entre 3 e 100 caracteres.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("E-mail inválido.");
        }
        if ($senha !== $confirmar_senha) {
            throw new Exception("As senhas não coincidem.");
        }
        if (strlen($senha) < 8) {
            throw new Exception("A senha deve ter no mínimo 8 caracteres.");
        }
        if (!preg_match('/[A-Z]/', $senha)) {
            throw new Exception("A senha deve conter pelo menos uma letra maiúscula.");
        }
        if (!preg_match('/[0-9]/', $senha)) {
            throw new Exception("A senha deve conter pelo menos um número.");
        }
        if ($cpf && !empty($cpf)) {
            if (strlen($cpf) !== 11) {
                throw new Exception("CPF deve ter 11 dígitos.");
            }
            if (!validarCPF($cpf)) {
                throw new Exception("CPF inválido.");
            }
            $sql = "SELECT id FROM usuarios WHERE cpf = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $cpf);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                throw new Exception("Este CPF já está cadastrado.");
            }
            $stmt->close();
        }
        if ($telefone && !empty($telefone)) {
            $telefone_limpo = preg_replace('/[^0-9]/', '', $telefone);
            if (strlen($telefone_limpo) < 10 || strlen($telefone_limpo) > 11) {
                throw new Exception("Telefone inválido. Use o formato (00) 00000-0000");
            }
        }
        $estados_validos = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 
                           'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 
                           'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
        if ($estado && !empty($estado) && !in_array($estado, $estados_validos)) {
            throw new Exception("Estado inválido.");
        }
        $sql = "SELECT id FROM usuarios WHERE LOWER(email) = LOWER(?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            throw new Exception("Este e-mail já está cadastrado.");
        }
        $stmt->close();
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (
            nome, email, senha, cpf, telefone, data_nascimento, 
            endereco, cidade, estado, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erro ao preparar cadastro: " . $conn->error);
        }
        $stmt->bind_param(
            "sssssssss",
            $nome,
            $email,
            $senhaHash,
            $cpf,
            $telefone,
            $data_nascimento,
            $endereco,
            $cidade,
            $estado
        );
        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça login para continuar.";
            header("Location: /ferroviaria-llgr/user/php/login.php");
            exit();
        } else {
            throw new Exception("Erro ao cadastrar: " . $stmt->error);
        }
    } catch (Exception $e) {
        $_SESSION['erro'] = $e->getMessage();
        header("Location: /ferroviaria-llgr/user/php/cadastro.php");
        exit();
    }
} else {
    $_SESSION['erro'] = "Método de requisição inválido.";
    header("Location: /ferroviaria-llgr/user/php/cadastro.php");
    exit();
}
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11) {
        return false;
    }
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += intval($cpf[$i]) * (10 - $i);
    }
    $resto = $soma % 11;
    $digito1 = ($resto < 2) ? 0 : 11 - $resto;
    if (intval($cpf[9]) !== $digito1) {
        return false;
    }
    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += intval($cpf[$i]) * (11 - $i);
    }
    $resto = $soma % 11;
    $digito2 = ($resto < 2) ? 0 : 11 - $resto;
    if (intval($cpf[10]) !== $digito2) {
        return false;
    }
    return true;
}
?>