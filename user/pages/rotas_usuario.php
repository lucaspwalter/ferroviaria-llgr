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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        <section class="routes-hero">
            <div>
                <span class="section-kicker">Malha ferroviária LLGR</span>
                <h1 class="page-title">Rotas disponíveis</h1>
                <p class="page-subtitle">Consulte linhas ativas, valores, tempo estimado e paradas intermediárias.</p>
            </div>
            <div class="routes-summary" aria-label="Resumo das rotas">
                <strong id="totalRotas">0</strong>
                <span>rotas ativas</span>
            </div>
        </section>

        <section class="routes-toolbar" aria-label="Filtros de rotas">
            <label class="search-field" for="rotaSearch">
                <span>Buscar</span>
                <input type="search" id="rotaSearch" placeholder="Digite rota, origem, destino, código ou parada">
            </label>
            <button type="button" class="clear-search" id="limparBusca">Limpar</button>
        </section>

        <div class="routes-meta" id="routesMeta">Carregando rotas...</div>
        <div id="rotasContainer" aria-live="polite">
            <div class="loading">Carregando rotas...</div>
        </div>
    </main>
    <script src="../js/mobile-navbar.js"></script>
    <script>
        let todasRotas = [];

        async function carregarRotas() {
            const container = document.getElementById('rotasContainer');
            try {
                const response = await fetch('../api/rotas.php?acao=listar_ativas');
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
            const meta = document.getElementById('routesMeta');
            const busca = document.getElementById('rotaSearch').value.trim().toLowerCase();
            const rotas = todasRotas.filter(rota => {
                const paradas = Array.isArray(rota.paradas) ? rota.paradas.join(' ') : '';
                const texto = `${rota.codigo || ''} ${rota.nome || ''} ${rota.origem || ''} ${rota.destino || ''} ${paradas}`.toLowerCase();
                return !busca || texto.includes(busca);
            });

            document.getElementById('totalRotas').textContent = todasRotas.length;
            meta.textContent = busca
                ? `${rotas.length} rota${rotas.length === 1 ? '' : 's'} encontrada${rotas.length === 1 ? '' : 's'} para "${document.getElementById('rotaSearch').value.trim()}"`
                : `${rotas.length} rota${rotas.length === 1 ? '' : 's'} ativa${rotas.length === 1 ? '' : 's'} no momento`;

            if (rotas.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <h2>Nenhuma rota encontrada</h2>
                        <p>Revise a busca ou limpe o filtro para ver todas as rotas disponíveis.</p>
                    </div>
                `;
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
            const paradas = Array.isArray(rota.paradas) ? rota.paradas : [];
            const totalParadas = Number(rota.total_paradas || paradas.length || 0);
            const preco = rota.preco_base ? Number(rota.preco_base).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }) : 'Sob consulta';
            card.innerHTML = `
                <div class="rota-header">
                    <span class="rota-codigo">${escapeHTML(rota.codigo)}</span>
                    <span class="rota-status ${statusClass}">${statusText}</span>
                </div>
                <h2 class="rota-nome">${escapeHTML(rota.nome)}</h2>
                ${rota.observacoes ? `<p class="rota-descricao">${escapeHTML(rota.observacoes)}</p>` : ''}
                <div class="rota-trajeto">
                    <span class="trajeto-origem">${escapeHTML(rota.origem)}</span>
                    <span class="trajeto-arrow">→</span>
                    <span class="trajeto-destino">${escapeHTML(rota.destino)}</span>
                </div>
                <div class="rota-info">
                    <div class="info-item">
                        <div class="info-label">Distância</div>
                        <div class="info-value">${rota.distancia_km} km</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tempo Estimado</div>
                        <div class="info-value">${formatarTempo(rota.tempo_estimado)}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Paradas</div>
                        <div class="info-value">${totalParadas}</div>
                    </div>
                    <div class="info-item preco-destaque">
                        <div class="info-label">Preço Base</div>
                        <div class="info-value">${preco}</div>
                    </div>
                </div>
                ${paradas.length ? `
                    <div class="rota-paradas">
                        <div class="paradas-label">Paradas principais</div>
                        <div class="paradas-lista">${paradas.map(parada => `<span>${escapeHTML(parada)}</span>`).join('')}</div>
                    </div>
                ` : ''}
            `;
            return card;
        }

        function escapeHTML(value) {
            return String(value ?? '').replace(/[&<>"']/g, char => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char]));
        }

        function formatarTempo(tempo) {
            if (!tempo) return '-';
            const [horas, minutos] = tempo.split(':');
            const h = Number(horas);
            const m = Number(minutos);
            if (h > 0 && m > 0) return `${h}h ${m}min`;
            if (h > 0) return `${h}h`;
            return `${m}min`;
        }

        document.getElementById('rotaSearch').addEventListener('input', renderRotas);
        document.getElementById('limparBusca').addEventListener('click', () => {
            document.getElementById('rotaSearch').value = '';
            renderRotas();
            document.getElementById('rotaSearch').focus();
        });
        window.addEventListener('DOMContentLoaded', carregarRotas);
    </script>
    <script src="../js/toast.js"></script>
</body>
</html>
