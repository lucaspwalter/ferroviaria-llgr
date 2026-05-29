<?php
session_start();
$usuario_logado = isset($_SESSION['usuario_id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - LLGR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sobre.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/toast.css" />
</head>
<body>
    <header>
        <nav>
            <a class="logo" href="<?= $usuario_logado ? 'rotas_usuario.php' : '../../index.html' ?>">LLGR</a>
            <div class="mobile-menu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <?php if ($usuario_logado): ?>
                    <li><a href="sobre.php">Sobre</a></li>
                    <li><a href="rotas_usuario.php">Rotas</a></li>
                    <li><a href="notificacoes_usuario.php">Notificações</a></li>
                    <li><a href="chat.php">Reclame Aqui</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                    <li><a href="logout_usuario.php">Sair</a></li>
                <?php else: ?>
                    <li><a href="sobre.php">Sobre</a></li>
                    <li><a href="login.php">Entrar</a></li>
                    <li><a href="cadastro.php">Cadastrar</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="sobre-container">
        <div class="hero-section">
            <div class="hero-content">
                <h1>Sobre o <span class="llgr-red">LLGR</span></h1>
                <p class="subtitle">Sistema de Gestão Ferroviária Inteligente</p>
            </div>
        </div>

        <div class="content-wrapper">
            <section class="info-section">
                <div class="section-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h2>Nosso Objetivo</h2>
                <p>O LLGR (Logística e Logística de Gestão Rodoviária) é um sistema completo de gerenciamento ferroviário desenvolvido para otimizar operações, aumentar a eficiência e garantir a segurança no transporte ferroviário.</p>
                <p>Nossa missão é fornecer uma plataforma integrada que conecte operadores, usuários e sistemas de monitoramento em tempo real, proporcionando uma experiência de gestão ferroviária de classe mundial.</p>
            </section>

            <section class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Dashboard Inteligente</h3>
                    <p>Visualize KPIs, gráficos e métricas operacionais em tempo real para tomada de decisões estratégicas.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <h3>Sensores IoT</h3>
                    <p>Monitoramento contínuo através de sensores de temperatura, pressão, velocidade e vibração.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-train"></i>
                    </div>
                    <h3>Gestão de Frota</h3>
                    <p>Controle completo de trens, incluindo manutenção preventiva e histórico operacional.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <h3>Rotas Otimizadas</h3>
                    <p>Planejamento e gerenciamento de rotas com informações detalhadas de distância, tempo e paradas.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3>Alertas Inteligentes</h3>
                    <p>Sistema de notificações e alertas em tempo real para incidentes críticos e manutenções.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Manutenção</h3>
                    <p>Gestão completa de manutenções preventivas, corretivas e emergenciais da frota.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Itinerários</h3>
                    <p>Programação e controle de viagens com múltiplas rotas e gestão de passageiros.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Relatórios</h3>
                    <p>Geração de relatórios operacionais, financeiros e de incidentes em diversos formatos.</p>
                </div>
            </section>

            <section class="info-section equipe">
                <div class="section-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h2>Equipe de Desenvolvimento</h2>
                <p class="team-intro">Projeto desenvolvido por estudantes dedicados do curso técnico em Desenvolvimento de Sistemas:</p>
                <div class="team-names">
                    <div class="developer-name">Lucas Walter</div>
                    <div class="developer-name">Raul</div>
                    <div class="developer-name">Lucas Rafael</div>
                    <div class="developer-name">Gustavo Cercal</div>
                </div>
            </section>


        </div>

        <footer class="sobre-footer">
            <p>&copy; 2024 LLGR - Sistema de Gestão Ferroviária. Todos os direitos reservados.</p>
            <p class="footer-tech">Desenvolvido com <i class="fas fa-heart"></i> por estudantes de Desenvolvimento de Sistemas</p>
        </footer>
    </main>

    <script src="../js/mobile-navbar.js"></script>
    <script src="../js/toast.js"></script>
</body>
</html>
