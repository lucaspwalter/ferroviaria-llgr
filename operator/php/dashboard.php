<?php
session_start();
if (!isset($_SESSION['operador_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

function contarRegistros($conn, $sql)
{
    $resultado = $conn->query($sql);

    if (!$resultado) {
        return 0;
    }

    $linha = $resultado->fetch_row();

    return (int) ($linha[0] ?? 0);
}

$total_trens_ativos = contarRegistros(
    $conn,
    "SELECT COUNT(*) FROM trens WHERE status = 'operacional' OR status = 'em_rota'"
);
$total_trens_parados = contarRegistros(
    $conn,
    "SELECT COUNT(*) FROM trens WHERE status = 'manutencao' OR status = 'inativo'"
);
$total_alertas_ativos = contarRegistros(
    $conn,
    "SELECT COUNT(*) FROM alertas WHERE status = 'ativo'"
);
$itinerarios_atrasados = contarRegistros(
    $conn,
    "SELECT COUNT(*) FROM itinerarios WHERE status = 'atrasado' AND data_partida = CURDATE()"
);
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - LLGR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/dashboard.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <link rel="stylesheet" href="../css/toast.css" />
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
      <section class="dashboard-section">
        <h1 class="page-title">Dashboard de Desempenho e Visão Geral</h1>
        <div class="welcome-box">
          <p class="welcome-text">
            <strong>Bem-vindo, <?php echo htmlspecialchars($_SESSION['operador_nome']); ?>!</strong><br>
            <small class="welcome-subtitle">Visão geral do sistema ferroviário</small>
          </p>
        </div>
        <div class="kpi-grid">
          <div class="kpi-card">
            <svg class="kpi-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M7 3h10a3 3 0 0 1 3 3v8a4 4 0 0 1-4 4l2 2v1h-2.5l-3-3h-1l-3 3H6v-1l2-2a4 4 0 0 1-4-4V6a3 3 0 0 1 3-3Zm0 2a1 1 0 0 0-1 1v3h12V6a1 1 0 0 0-1-1H7Zm-1 6v3a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-3H6Zm2.5 3.5a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3Zm7 0a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3Z"/></svg>
            <span class="kpi-value" data-count="<?php echo $total_trens_ativos; ?>">0</span>
            <span class="kpi-label">Trens Ativos</span>
          </div>
          <div class="kpi-card">
            <svg class="kpi-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0-20Zm0 3a7 7 0 0 1 4.95 11.95L7.05 7.05A6.96 6.96 0 0 1 12 5Zm0 14a6.96 6.96 0 0 1-4.95-2.05l9.9-9.9A7 7 0 0 1 12 19Z"/></svg>
            <span class="kpi-value status-critical" data-count="<?php echo $total_trens_parados; ?>">0</span>
            <span class="kpi-label">Trens Parados</span>
          </div>
          <div class="kpi-card">
            <svg class="kpi-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2 1 21h22L12 2Zm0 4 7.53 13H4.47L12 6Zm-1 4h2v5h-2v-5Zm0 6h2v2h-2v-2Z"/></svg>
            <span class="kpi-value" data-count="<?php echo $total_alertas_ativos; ?>">0</span>
            <span class="kpi-label">Alertas Ativos</span>
          </div>
          <div class="kpi-card">
            <svg class="kpi-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 10 10h-2a8 8 0 1 1-8-8V2Zm1 5h-2v6l5 3l1-1.73l-4-2.27V7Z"/></svg>
            <span class="kpi-value status-warning" data-count="<?php echo $itinerarios_atrasados; ?>">0</span>
            <span class="kpi-label">Atrasos (Últimas 24h)</span>
          </div>
        </div>
        <div class="charts-grid">
          <div class="card chart-card pie-chart-card">
            <h2 class="card-title">HISTÓRICO DE INCIDENTES RECENTES</h2>
            <div class="chart-container pie-chart-container">
              <canvas id="graficoPizza"></canvas>
              <div class="legenda-chartjs">
                <ul>
                  <li><span class="legenda-color-critico"></span>Crítico</li>
                  <li><span class="legenda-color-urgente"></span>Urgente</li>
                  <li><span class="legenda-color-aviso"></span>Aviso</li>
                  <li><span class="legenda-color-informativo"></span>Informativo</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card chart-card">
            <h2 class="card-title">PASSAGEIROS POR CLASSE</h2>
            <div class="chart-container">
              <canvas id="barChart"></canvas>
            </div>
          </div>
        </div>
      </section>
    </main>
    <script src="../js/mobile-navbar.js"></script>
    <script src="../js/grafico-pizza.js"></script>
    <script src="../js/passageiros-por-classe.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.kpi-value[data-count]').forEach(function(element) {
          const target = parseInt(element.dataset.count, 10) || 0;
          const start = performance.now();
          const duration = 1000;

          function update(now) {
            const progress = Math.min((now - start) / duration, 1);
            element.textContent = Math.round(target * progress);
            if (progress < 1) {
              requestAnimationFrame(update);
            }
          }

          requestAnimationFrame(update);
        });
      });
    </script>
      <script src="../js/toast.js"></script>
</body>
</html>
