<?php
session_start();
require_once __DIR__ . '/../../config/security.php';
if (!isset($_SESSION['operador_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Operador - LLGR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/gerenciamento.css">
    <link rel="stylesheet" href="../css/toast.css">
</head>
<body>
    <header>
        <nav>
            <a class="logo" href="dashboard.php">LLGR</a>
            <div class="mobile-menu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="sensores.php">Sensores</a></li>
                <li><a href="estacoes.php">Estações</a></li>
                <li><a href="trens.php">Trens</a></li>
                <li><a href="rotas.php">Rotas</a></li>
                <li><a href="itinerarios.php">Itinerários</a></li>
                <li><a href="alertas.php">Alertas</a></li>
                <li><a href="manutencoes.php">Manutenções</a></li>
                <li><a href="notificacoes.php">Notificações</a></li>
                <li><a href="relatorios.php">Relatórios</a></li>
                <li><a href="reclamacoes.php">Reclamações</a></li>
                <li><a href="perfil_operador.php">Perfil</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main class="main-content-wrapper">
        <section class="management-section">
            <h1 class="page-title">Perfil do Operador</h1>
            <div class="card">
                <h2 class="card-title">Dados Pessoais</h2>
                <form method="POST" id="perfilForm">
                    <?= csrf_input() ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input type="text" id="telefone" name="telefone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="cargo">Cargo</label>
                            <input type="text" id="cargo" class="form-control" disabled>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Perfil</button>
                </form>
            </div>
            <div class="card">
                <h2 class="card-title">Alterar Senha</h2>
                <form method="POST" id="senhaForm">
                    <?= csrf_input() ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="senha_atual">Senha Atual</label>
                            <input type="password" id="senha_atual" name="senha_atual" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nova_senha">Nova Senha</label>
                            <input type="password" id="nova_senha" name="nova_senha" class="form-control" required minlength="8">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Atualizar Senha</button>
                </form>
            </div>
        </section>
    </main>
    <script src="../js/mobile-navbar.js"></script>
    <script src="../js/toast.js"></script>
    <script>
        const backendUrl = '../../operator/api/perfil_operador.php';

        async function carregarPerfil() {
            const response = await fetch(`${backendUrl}?acao=buscar`);
            const result = await response.json();
            if (result.sucesso) {
                const operador = result.dados;
                document.getElementById('nome').value = operador.nome || '';
                document.getElementById('email').value = operador.email || '';
                document.getElementById('telefone').value = operador.telefone || '';
                document.getElementById('cargo').value = operador.cargo || '';
            } else {
                showToast(result.mensagem || 'Erro ao carregar perfil', 'error');
            }
        }

        document.getElementById('perfilForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            formData.append('acao', 'atualizar');
            const response = await fetch(backendUrl, { method: 'POST', body: formData });
            const result = await response.json();
            showToast(result.mensagem, result.sucesso ? 'success' : 'error');
        });

        document.getElementById('senhaForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            formData.append('acao', 'atualizar_senha');
            const response = await fetch(backendUrl, { method: 'POST', body: formData });
            const result = await response.json();
            showToast(result.mensagem, result.sucesso ? 'success' : 'error');
            if (result.sucesso) {
                this.reset();
            }
        });

        window.addEventListener('DOMContentLoaded', carregarPerfil);
    </script>
</body>
</html>
