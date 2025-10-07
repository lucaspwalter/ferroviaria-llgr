<?php
session_start();
if (!isset($_SESSION['operador_id'])) {
    header("Location: login.php");
    exit();
}
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
    <link rel="stylesheet" href="../css/dashboard-extra.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="sensores.php">Sensores</a></li>
          <li><a href="trens.php">Trens</a></li>
          <li><a href="rotas.php">Rotas</a></li>
          <li><a href="itinerarios.php">Itinerários</a></li>
          <li><a href="alertas.php">Alertas</a></li>
          <li><a href="manutencoes.php">Manutenções</a></li>
          <li><a href="notificacoes.php">Notificações</a></li>
          <li><a href="relatorios.php">Relatórios</a></li>
          <li><a href="reclamacoes.php">Reclamações</a></li>
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
            <span class="kpi-value">12</span>
            <span class="kpi-label">Trens Ativos</span>
          </div>
          <div class="kpi-card">
            <span class="kpi-value status-critical">2</span>
            <span class="kpi-label">Trens Parados</span>
          </div>
          <div class="kpi-card">
            <span class="kpi-value">98%</span>
            <span class="kpi-label">Pontualidade Média</span>
          </div>
          <div class="kpi-card">
            <span class="kpi-value status-warning">3</span>
            <span class="kpi-label">Atrasos (Últimas 24h)</span>
          </div>
        </div>
        <div class="card chart-card">
          <h2 class="card-title">HISTÓRICO DE INCIDENTES RECENTES</h2>
          <div class="chart-container">
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
      </section>
    </main>
    <script src="../js/mobile-navbar.js"></script>
    <script src="../js/grafico-pizza.js"></script>
    <script src="../js/passageiros-por-classe.js"></script>
  </body>
</html>
