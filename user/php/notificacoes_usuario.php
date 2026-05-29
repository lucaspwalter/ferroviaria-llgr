<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <title>Notificações - LLGR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/notificacoesU.css" />
      <link rel="stylesheet" href="../css/toast.css" />
</head>
  <body>
    <div class="app-bg">
      <div class="notificacoes-container">
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
        <div class="notificacoes-lista" id="notificacoes-lista">
          <div class="loading-container">
            Carregando notificações...
          </div>
        </div>
        <div class="reclamacao">
          Faça sua <a href="chat.php" class="botao">reclamação</a> aqui
        </div>
      </div>
    </div>
    <script src="../js/mobile-navbar.js"></script>
    <script>
        async function carregarNotificacoes() {
            const container = document.getElementById('notificacoes-lista');
            try {
                const response = await fetch('../api/notificacoes.php?acao=listar_publicas');
                const result = await response.json();
                if (result.sucesso && result.dados.length > 0) {
                    container.innerHTML = '';
                    result.dados.forEach(notificacao => {
                        const notifDiv = criarNotificacao(notificacao);
                        container.appendChild(notifDiv);
                    });
                } else {
                    container.innerHTML = `
                        <div class="empty-notificacoes">
                            <p>📭 Nenhuma notificação</p>
                            <small>Não há notificações no momento</small>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Erro:', error);
                container.innerHTML = `
                    <div class="error-notificacoes">
                        <p>❌ Erro ao carregar notificações</p>
                    </div>
                `;
            }
        }
        function criarNotificacao(notif) {
            const div = document.createElement('div');
            div.className = 'notificacao-item' + (notif.lida ? ' lida' : '');
            const icone = {
                'alerta': '⚠️',
                'lembrete': '⏰',
                'aviso': 'ℹ️',
                'sistema': '🔧'
            }[notif.tipo] || '📌';
            const prioridadeClass = {
                'alta': 'prioridade-alta',
                'media': 'prioridade-media',
                'baixa': 'prioridade-baixa'
            }[notif.prioridade] || '';
            div.innerHTML = `
                <div class="notificacao-header ${prioridadeClass}">
                    <span class="notificacao-icone">${icone}</span>
                    <div class="notificacao-info">
                        <strong class="notificacao-titulo">${escapeHTML(notif.titulo)}</strong>
                        <p class="notificacao-mensagem">${escapeHTML(notif.mensagem)}</p>
                        <small class="notificacao-data">${formatarDataHora(notif.criado_em)}</small>
                    </div>
                    ${notif.lida ? '<span class="status-lida">✓</span>' : '<span class="status-nova">●</span>'}
                </div>
            `;
            return div;
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
        function formatarDataHora(data) {
            if (!data) return '-';
            const d = new Date(data);
            const agora = new Date();
            const diff = agora - d;
            const minutos = Math.floor(diff / 60000);
            const horas = Math.floor(diff / 3600000);
            const dias = Math.floor(diff / 86400000);
            if (minutos < 1) return 'Agora mesmo';
            if (minutos < 60) return `Há ${minutos} min`;
            if (horas < 24) return `Há ${horas}h`;
            if (dias < 7) return `Há ${dias}d`;
            return d.toLocaleDateString('pt-BR');
        }
        window.addEventListener('DOMContentLoaded', carregarNotificacoes);
    </script>
      <script src="../js/toast.js"></script>
</body>
</html>
