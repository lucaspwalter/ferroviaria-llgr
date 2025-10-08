<?php
session_start();
header('Content-Type: application/json');
include __DIR__ . '/../user-backend/conexao.php';

if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado']);
    exit();
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

switch ($acao) {
    case 'cadastrar':
        cadastrarRelatorio($conn);
        break;
    case 'listar':
        listarRelatorios($conn);
        break;
    case 'buscar':
        buscarRelatorio($conn);
        break;
    case 'gerar':
        gerarRelatorio($conn);
        break;
    case 'deletar':
        deletarRelatorio($conn);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}

function cadastrarRelatorio($conn) {
    $tipo = $_POST['tipo'] ?? '';
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $periodo_inicio = $_POST['periodo_inicio'] ?? '';
    $periodo_fim = $_POST['periodo_fim'] ?? '';
    $formato = $_POST['formato'] ?? 'pdf';
    $gerado_por = $_SESSION['operador_id'];
    
    if (empty($tipo) || empty($titulo) || empty($periodo_inicio) || empty($periodo_fim)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
        return;
    }
    
    $dados_json = gerarDadosRelatorio($conn, $tipo, $periodo_inicio, $periodo_fim);
    
    $sql = "INSERT INTO relatorios (tipo, titulo, descricao, periodo_inicio, periodo_fim, 
            gerado_por, formato, dados_json, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'gerado')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssisss", $tipo, $titulo, $descricao, $periodo_inicio, $periodo_fim, 
                      $gerado_por, $formato, $dados_json);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Relatório cadastrado com sucesso!', 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar relatório: ' . $conn->error]);
    }
}

function gerarDadosRelatorio($conn, $tipo, $periodo_inicio, $periodo_fim) {
    $dados = [];
    
    switch ($tipo) {
        case 'operacional':
            $sql = "SELECT COUNT(*) as total FROM itinerarios WHERE data_partida BETWEEN ? AND ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $periodo_inicio, $periodo_fim);
            $stmt->execute();
            $dados['total_itinerarios'] = $stmt->get_result()->fetch_assoc()['total'];
            
            $sql = "SELECT status, COUNT(*) as total FROM itinerarios 
                    WHERE data_partida BETWEEN ? AND ? GROUP BY status";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $periodo_inicio, $periodo_fim);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados['por_status'] = [];
            while ($row = $result->fetch_assoc()) {
                $dados['por_status'][] = $row;
            }
            break;
            
        case 'manutencao':
            $sql = "SELECT COUNT(*) as total FROM manutencoes WHERE data_inicio BETWEEN ? AND ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $periodo_inicio, $periodo_fim);
            $stmt->execute();
            $dados['total_manutencoes'] = $stmt->get_result()->fetch_assoc()['total'];
            
            $sql = "SELECT SUM(custo) as total_custo FROM manutencoes WHERE data_inicio BETWEEN ? AND ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $periodo_inicio, $periodo_fim);
            $stmt->execute();
            $dados['custo_total'] = $stmt->get_result()->fetch_assoc()['total_custo'];
            break;
            
        case 'incidentes':
            $sql = "SELECT COUNT(*) as total FROM alertas WHERE criado_em BETWEEN ? AND ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $periodo_inicio, $periodo_fim);
            $stmt->execute();
            $dados['total_alertas'] = $stmt->get_result()->fetch_assoc()['total'];
            
            $sql = "SELECT tipo, COUNT(*) as total FROM alertas 
                    WHERE criado_em BETWEEN ? AND ? GROUP BY tipo";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $periodo_inicio, $periodo_fim);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados['por_tipo'] = [];
            while ($row = $result->fetch_assoc()) {
                $dados['por_tipo'][] = $row;
            }
            break;
            
        case 'geral':
            $dados['trens_ativos'] = $conn->query("SELECT COUNT(*) as total FROM trens WHERE status='operacional'")->fetch_assoc()['total'];
            $dados['rotas_ativas'] = $conn->query("SELECT COUNT(*) as total FROM rotas WHERE status='ativa'")->fetch_assoc()['total'];
            $dados['sensores_ativos'] = $conn->query("SELECT COUNT(*) as total FROM sensores WHERE status='ativo'")->fetch_assoc()['total'];
            break;
    }
    
    return json_encode($dados);
}

function listarRelatorios($conn) {
    $sql = "SELECT r.*, o.nome as operador_nome 
            FROM relatorios r 
            LEFT JOIN operadores o ON r.gerado_por = o.id 
            ORDER BY r.criado_em DESC";
    $result = $conn->query($sql);
    $relatorios = [];
    
    while ($row = $result->fetch_assoc()) {
        $relatorios[] = $row;
    }
    
    echo json_encode(['sucesso' => true, 'dados' => $relatorios]);
}

function buscarRelatorio($conn) {
    $id = $_GET['id'] ?? 0;
    
    $sql = "SELECT r.*, o.nome as operador_nome 
            FROM relatorios r 
            LEFT JOIN operadores o ON r.gerado_por = o.id 
            WHERE r.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['sucesso' => true, 'dados' => $row]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Relatório não encontrado']);
    }
}

function gerarRelatorio($conn) {
    cadastrarRelatorio($conn);
}

function deletarRelatorio($conn) {
    $id = $_POST['id'] ?? 0;
    
    $sql = "DELETE FROM relatorios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Relatório deletado com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao deletar relatório']);
    }
}
?>
