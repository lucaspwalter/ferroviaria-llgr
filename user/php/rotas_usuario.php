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
    <title>Rotas Disponíveis - LLGR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/rotasUsuario.css">
    <link rel="stylesheet" href="../css/toast.css" />
</head>
<body>
    <header>
        <nav>
            <a class="logo" href="rotas_usuario.php">LLGR</a>
            <div class="mobile-menu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="rotas_usuario.php">Rotas</a></li>
                <li><a href="notificacoes_usuario.php">Notificações</a></li>
                <li><a href="chat.php">Reclame Aqui</a></li>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="logout_usuario.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main class="main-content">
        <h1 class="page-title">Rotas Disponíveis</h1>
        <div class="rota-search">
            <input type="search" id="rotaSearch" placeholder="Buscar por rota, origem ou destino">
        </div>
        <div id="rotasContainer">
            <div class="loading">Carregando rotas...</div>
        </div>
    </main>
    <script src="../js/mobile-navbar.js"></script>
    <script>
        let todasRotas = [];

        async function carregarRotas() {
            const container = document.getElementById('rotasContainer');
            try {
                const response = await fetch('../../user/api/rotas.php?acao=listar_ativas');
                const result = await response.json();
                if (result.sucesso && result.dados.length > 0) {
                    todasRotas = result.dados;
                    renderRotas();
                } else {
                    container.innerHTML = `
                        <div class="empty-state">
                            <p>📍 Nenhuma rota disponível no momento</p>
                            <small>Verifique novamente mais tarde</small>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Erro:', error);
                container.innerHTML = `
                    <div class="empty-state">
                        <p>❌ Erro ao carregar rotas</p>
                        <small>Tente novamente mais tarde</small>
                    </div>
                `;
            }
        }

        function renderRotas() {
            const container = document.getElementById('rotasContainer');
            const busca = document.getElementById('rotaSearch').value.trim().toLowerCase();
            const rotas = todasRotas.filter(rota => {
                const texto = `${rota.nome || ''} ${rota.origem || ''} ${rota.destino || ''}`.toLowerCase();
                return !busca || texto.includes(busca);
            });

            if (rotas.length === 0) {
                container.innerHTML = '<div class="empty-state"><p>Nenhuma rota encontrada</p></div>';
                return;
            }

            container.innerHTML = '<div class="rotas-grid"></div>';
            const grid = container.querySelector('.rotas-grid');
            rotas.forEach(rota => grid.appendChild(criarCardRota(rota)));
        }

        function criarCardRota(rota) {
            const card = document.createElement('div');
            card.className = 'rota-card';
            const statusClass = rota.status === 'ativa' ? 'status-ativa' : 'status-inativa';
            const statusText = rota.status === 'ativa' ? 'Ativa' : 'Inativa';
            card.innerHTML = `
                <div class="rota-header">
                    <span class="rota-codigo">${rota.codigo}</span>
                    <span class="rota-status ${statusClass}">${statusText}</span>
                </div>
                <h2 class="rota-nome">${rota.nome}</h2>
                <div class="rota-trajeto">
                    <span class="trajeto-origem">${rota.origem}</span>
                    <span class="trajeto-arrow">→</span>
                    <span class="trajeto-destino">${rota.destino}</span>
                </div>
                <div class="rota-info">
                    <div class="info-item">
                        <div class="info-label">Distância</div>
                        <div class="info-value">${rota.distancia_km} km</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tempo Estimado</div>
                        <div class="info-value">${rota.tempo_estimado}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Paradas</div>
                        <div class="info-value">0</div>
                    </div>
                    <div class="info-item preco-destaque">
                        <div class="info-label">Preço Base</div>
                        <div class="info-value">${rota.preco_base ? 'R$ ' + parseFloat(rota.preco_base).toFixed(2) : '-'}</div>
                    </div>
                </div>
            `;
            return card;
        }
        document.getElementById('rotaSearch').addEventListener('input', renderRotas);
        window.addEventListener('DOMContentLoaded', carregarRotas);
    </script>
    <script src="../js/toast.js"></script>
</body>
</html>
