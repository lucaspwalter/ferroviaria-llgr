<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/security.php';
require_once __DIR__ . '/../../config/database.php';
if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

switch ($acao) {
    case 'cadastrar':
        cadastrarItinerario($conn);
        break;
    case 'listar':
        listarItinerarios($conn);
        break;
    case 'buscar':
        buscarItinerario($conn);
        break;
    case 'atualizar':
        atualizarItinerario($conn);
        break;
    case 'deletar':
        deletarItinerario($conn);
        break;
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}

function cadastrarItinerario($conn) {
    try {
        $codigo = trim($_POST['codigo'] ?? '');
        $rota_id = $_POST['rota_id'] ?? 0;
        $trem_id = $_POST['trem_id'] ?? null;
        $data_partida = $_POST['data_partida'] ?? '';
        $hora_partida = $_POST['hora_partida'] ?? '';
        $hora_chegada_prevista = $_POST['hora_chegada_prevista'] ?? '';
        $status = $_POST['status'] ?? 'agendado';
        $passageiros_embarcados = $_POST['passageiros_embarcados'] ?? 0;
        $observacoes = trim($_POST['observacoes'] ?? '');
        
        if (empty($codigo) || $rota_id <= 0 || empty($data_partida) || empty($hora_partida) || empty($hora_chegada_prevista)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
            return;
        }
        
        $sqlCheck = "SELECT id FROM itinerarios WHERE codigo = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $codigo);
        $stmtCheck->execute();
        
        if ($stmtCheck->get_result()->num_rows > 0) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Código já cadastrado']);
            return;
        }
        
        $sql = "INSERT INTO itinerarios (codigo, rota_id, trem_id, data_partida, hora_partida, 
                hora_chegada_prevista, status, passageiros_embarcados, observacoes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siissssis", $codigo, $rota_id, $trem_id, $data_partida, $hora_partida, 
                          $hora_chegada_prevista, $status, $passageiros_embarcados, $observacoes);
        
        if ($stmt->execute()) {
            atualizarStatusTremItinerario($conn, $trem_id, $status);
            echo json_encode(['sucesso' => true, 'mensagem' => 'Itinerário cadastrado com sucesso!', 'id' => $stmt->insert_id]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar itinerário: ' . $conn->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function listarItinerarios($conn) {
    try {
        $sql = "SELECT i.*, r.nome as rota_nome, r.origem, r.destino, t.codigo as trem_codigo 
                FROM itinerarios i 
                LEFT JOIN rotas r ON i.rota_id = r.id 
                LEFT JOIN trens t ON i.trem_id = t.id 
                ORDER BY i.data_partida DESC, i.hora_partida DESC";
        $result = $conn->query($sql);
        $itinerarios = [];
        
        while ($row = $result->fetch_assoc()) {
            $itinerarios[] = $row;
        }
        
        echo json_encode(['sucesso' => true, 'dados' => $itinerarios]);
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function buscarItinerario($conn) {
    try {
        $id = $_GET['id'] ?? 0;
        
        $sql = "SELECT i.*, r.nome as rota_nome, t.codigo as trem_codigo 
                FROM itinerarios i 
                LEFT JOIN rotas r ON i.rota_id = r.id 
                LEFT JOIN trens t ON i.trem_id = t.id 
                WHERE i.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            echo json_encode(['sucesso' => true, 'dados' => $row]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Itinerário não encontrado']);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function atualizarItinerario($conn) {
    try {
        $id = $_POST['id'] ?? 0;
        $codigo = trim($_POST['codigo'] ?? '');
        $rota_id = $_POST['rota_id'] ?? 0;
        $trem_id = $_POST['trem_id'] ?? null;
        $data_partida = $_POST['data_partida'] ?? '';
        $hora_partida = $_POST['hora_partida'] ?? '';
        $hora_chegada_prevista = $_POST['hora_chegada_prevista'] ?? '';
        $status = $_POST['status'] ?? 'agendado';
        $passageiros_embarcados = $_POST['passageiros_embarcados'] ?? 0;
        $observacoes = trim($_POST['observacoes'] ?? '');
        
        if (empty($codigo) || $rota_id <= 0 || empty($data_partida) || empty($hora_partida)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios']);
            return;
        }
        
        $sql = "UPDATE itinerarios 
                SET codigo=?, rota_id=?, trem_id=?, data_partida=?, hora_partida=?, 
                    hora_chegada_prevista=?, status=?, passageiros_embarcados=?, observacoes=? 
                WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siissssisi", $codigo, $rota_id, $trem_id, $data_partida, $hora_partida, 
                          $hora_chegada_prevista, $status, $passageiros_embarcados, $observacoes, $id);
        
        if ($stmt->execute()) {
            atualizarStatusTremItinerario($conn, $trem_id, $status);
            echo json_encode(['sucesso' => true, 'mensagem' => 'Itinerário atualizado com sucesso!']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar itinerário']);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function deletarItinerario($conn) {
    try {
        $id = $_POST['id'] ?? 0;
        
        $sql = "DELETE FROM itinerarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Itinerário deletado com sucesso!']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao deletar itinerário']);
        }
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno: ' . $e->getMessage()]);
    }
}

function atualizarStatusTremItinerario($conn, $trem_id, $status) {
    if (empty($trem_id)) {
        return;
    }

    if ($status === 'em_andamento') {
        $novo_status = 'em_rota';
    } elseif ($status === 'concluído' || $status === 'cancelado') {
        $novo_status = 'operacional';
    } else {
        return;
    }

    $stmt = $conn->prepare("UPDATE trens SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $novo_status, $trem_id);
    $stmt->execute();
}
?>
