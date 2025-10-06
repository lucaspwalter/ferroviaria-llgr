<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - LLGR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/verperfil.css">
    <link rel="stylesheet" href="../css/perfil-extra.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <a class="logo" href="../html/telainicialU.html">LLGR</a>
            <div class="mobile-menu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <li><a href="../html/telainicialU.html">Início</a></li>
                <li><a href="rotas_usuario.php">Rotas</a></li>
                <li><a href="notificacoes_usuario.php">Notificações</a></li>
                <li><a href="../html/chatU.php">Reclame Aqui</a></li>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="logout_usuario.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <div class="profile-view-container">
        <div class="profile-view-box">
            <h2>Meu Perfil <span class="llgr-red">LLGR</span></h2>
            <div id="loading" class="perfil-loading">
                Carregando perfil...
            </div>
            <div id="perfilContainer" class="perfil-container-hidden">
                <div class="profile-info-group">
                    <div class="info-label">Nome:</div>
                    <div class="info-value" id="nome">-</div>
                </div>
                <div class="profile-info-group">
                    <div class="info-label">Email:</div>
                    <div class="info-value" id="email">-</div>
                </div>
                <div class="profile-info-group">
                    <div class="info-label">Telefone:</div>
                    <div class="info-value" id="telefone">-</div>
                </div>
                <div class="profile-info-group">
                    <div class="info-label">CPF:</div>
                    <div class="info-value" id="cpf">-</div>
                </div>
                <div class="profile-info-group">
                    <div class="info-label">Data de Nascimento:</div>
                    <div class="info-value" id="data_nascimento">-</div>
                </div>
                <div class="profile-info-group">
                    <div class="info-label">Endereço:</div>
                    <div class="info-value" id="endereco">-</div>
                </div>
                <div class="profile-info-group">
                    <div class="info-label">Cidade/Estado:</div>
                    <div class="info-value" id="cidade_estado">-</div>
                </div>
                <div class="profile-info-group">
                    <div class="info-label">Membro desde:</div>
                    <div class="info-value" id="criado_em">-</div>
                </div>
                <button type="button" class="btn-edit" onclick="window.location.href='editar_perfil.php'">
                    Editar Perfil
                </button>
                <div class="profile-links">
                    <a href="rotas_usuario.php">Voltar às Rotas</a>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/mobile-navbar.js"></script>
    <script>
        async function carregarPerfil() {
            const loading = document.getElementById('loading');
            const container = document.getElementById('perfilContainer');
            try {
                const response = await fetch('../../user-backend/perfil_backend.php?acao=buscar_perfil');
                const text = await response.text();
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    loading.innerHTML = 'Erro: Resposta invalida do servidor';
                    return;
                }
                if (result.sucesso) {
                    const dados = result.dados;
                    document.getElementById('nome').textContent = dados.nome || '-';
                    document.getElementById('email').textContent = dados.email || '-';
                    document.getElementById('telefone').textContent = dados.telefone || 'Não informado';
                    document.getElementById('cpf').textContent = dados.cpf || 'Não informado';
                    document.getElementById('data_nascimento').textContent = dados.data_nascimento ? formatarData(dados.data_nascimento) : 'Não informado';
                    document.getElementById('endereco').textContent = dados.endereco || 'Não informado';
                    const cidadeEstado = dados.cidade && dados.estado ? dados.cidade + ' - ' + dados.estado : 'Não informado';
                    document.getElementById('cidade_estado').textContent = cidadeEstado;
                    document.getElementById('criado_em').textContent = formatarDataHora(dados.criado_em);
                    loading.classList.add('perfil-container-hidden');
                    container.classList.remove('perfil-container-hidden');
                    container.classList.add('perfil-container-visible');
                } else {
                    loading.innerHTML = 'Erro ao carregar perfil';
                }
            } catch (error) {
                loading.innerHTML = 'Erro ao comunicar com servidor';
            }
        }
        function formatarData(data) {
            if (!data) return '-';
            const d = new Date(data + 'T00:00:00');
            return d.toLocaleDateString('pt-BR');
        }
        function formatarDataHora(data) {
            if (!data) return '-';
            const d = new Date(data);
            return d.toLocaleString('pt-BR');
        }
        window.addEventListener('DOMContentLoaded', carregarPerfil);
    </script>
</body>
</html>
