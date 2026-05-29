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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre o Sistema - LLGR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../../user/css/sobre.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    <main class="sobre-container">
        <div class="hero-section">
            <div class="hero-content">
                <h1>Sistema <span class="llgr-red">LLGR</span></h1>
                <p class="subtitle">Plataforma de Gestão Ferroviária - Área do Operador</p>
            </div>
        </div>

        <div class="content-wrapper">
            <section class="info-section">
                <div class="section-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['operador_nome']); ?>!</h2>
                <p>Você está utilizando a plataforma LLGR - Sistema de Gestão Ferroviária, uma solução completa desenvolvida para operadores e administradores gerenciarem de forma eficiente todas as operações ferroviárias.</p>
                <p>Esta interface administrativa fornece acesso a ferramentas avançadas de monitoramento, controle e análise de dados em tempo real.</p>
            </section>

            <section class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3>Dashboard Completo</h3>
                    <p>Visualize métricas, KPIs e gráficos de desempenho operacional em tempo real.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <h3>Gestão de Sensores</h3>
                    <p>Cadastre e monitore sensores IoT distribuídos pela rede ferroviária.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-train"></i>
                    </div>
                    <h3>Controle de Trens</h3>
                    <p>Gerencie toda a frota, incluindo especificações técnicas e status operacional.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Gerenciamento de Rotas</h3>
                    <p>Cadastre rotas com informações detalhadas de origem, destino e paradas.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>Itinerários</h3>
                    <p>Programe viagens associando trens, rotas, horários e capacidade de passageiros.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3>Sistema de Alertas</h3>
                    <p>Receba e gerencie alertas críticos, urgentes e informativos do sistema.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <h3>Manutenções</h3>
                    <p>Controle manutenções preventivas, corretivas e emergenciais da frota.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3>Notificações</h3>
                    <p>Envie notificações para usuários sobre alterações em rotas, horários e serviços.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Relatórios</h3>
                    <p>Gere relatórios detalhados em diversos formatos (PDF, Excel, CSV).</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Atendimento ao Cliente</h3>
                    <p>Gerencie reclamações e solicitações dos usuários do sistema.</p>
                </div>
            </section>

            <section class="info-section tecnologias">
                <div class="section-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h2>Arquitetura do Sistema</h2>
                <p>O sistema LLGR é construído com arquitetura moderna e escalável:</p>
                <div class="context-list">
                    <li><i class="fas fa-check"></i> <strong>Frontend:</strong> HTML5, CSS3, JavaScript vanilla com design responsivo</li>
                    <li><i class="fas fa-check"></i> <strong>Backend:</strong> PHP 7.4+ com arquitetura MVC</li>
                    <li><i class="fas fa-check"></i> <strong>Banco de Dados:</strong> MySQL 8.0 com relacionamentos otimizados</li>
                    <li><i class="fas fa-check"></i> <strong>Visualização:</strong> Chart.js para gráficos interativos</li>
                    <li><i class="fas fa-check"></i> <strong>Segurança:</strong> Password hashing, prepared statements, session management</li>
                    <li><i class="fas fa-check"></i> <strong>API REST:</strong> Endpoints JSON para comunicação assíncrona</li>
                </div>
            </section>

            <section class="info-section">
                <div class="section-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>Segurança e Privacidade</h2>
                <p>O sistema implementa múltiplas camadas de segurança para proteger dados sensíveis:</p>
                <ul class="context-list">
                    <li><i class="fas fa-lock"></i> Autenticação baseada em sessões com timeout automático</li>
                    <li><i class="fas fa-lock"></i> Senhas criptografadas com bcrypt (algoritmo seguro)</li>
                    <li><i class="fas fa-lock"></i> Proteção contra SQL Injection usando prepared statements</li>
                    <li><i class="fas fa-lock"></i> Validação de dados no frontend e backend</li>
                    <li><i class="fas fa-lock"></i> Controle de acesso baseado em perfis (operador/usuário)</li>
                </ul>
            </section>

            <section class="info-section contexto">
                <div class="section-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h2>Contexto Acadêmico</h2>
                <p>Este projeto foi desenvolvido como trabalho final integrador do Curso Técnico em Desenvolvimento de Sistemas, aplicando conhecimentos de:</p>
                <div class="tech-grid">
                    <div class="tech-item">
                        <i class="fas fa-code"></i>
                        <span>Programação Web</span>
                    </div>
                    <div class="tech-item">
                        <i class="fas fa-database"></i>
                        <span>Banco de Dados</span>
                    </div>
                    <div class="tech-item">
                        <i class="fas fa-paint-brush"></i>
                        <span>Design UI/UX</span>
                    </div>
                    <div class="tech-item">
                        <i class="fas fa-project-diagram"></i>
                        <span>Análise de Sistemas</span>
                    </div>
                    <div class="tech-item">
                        <i class="fas fa-sitemap"></i>
                        <span>Arquitetura MVC</span>
                    </div>
                    <div class="tech-item">
                        <i class="fas fa-tasks"></i>
                        <span>Gestão de Projetos</span>
                    </div>
                </div>
            </section>

            <section class="info-section">
                <div class="section-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h2>Dicas de Uso</h2>
                <ul class="context-list">
                    <li><i class="fas fa-mouse-pointer"></i> Use o menu lateral para navegar entre os módulos do sistema</li>
                    <li><i class="fas fa-mobile-alt"></i> O sistema é totalmente responsivo - funciona em desktop, tablet e mobile</li>
                    <li><i class="fas fa-search"></i> Utilize os filtros nas tabelas para encontrar registros específicos</li>
                    <li><i class="fas fa-download"></i> Exporte relatórios em diferentes formatos conforme sua necessidade</li>
                    <li><i class="fas fa-sync"></i> Os dados do dashboard são atualizados automaticamente</li>
                    <li><i class="fas fa-question-circle"></i> Em caso de dúvidas, consulte a documentação ou entre em contato com o suporte</li>
                </ul>
            </section>

            <section class="info-section contato">
                <div class="section-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h2>Suporte Técnico</h2>
                <p>Para questões técnicas, sugestões de melhorias ou relato de problemas, entre em contato com a equipe de desenvolvimento.</p>
                <div class="contact-buttons">
                    <a href="dashboard.php" class="btn-contact">
                        <i class="fas fa-chart-line"></i> Voltar ao Dashboard
                    </a>
                    <a href="reclamacoes.php" class="btn-secondary">
                        <i class="fas fa-ticket-alt"></i> Central de Suporte
                    </a>
                </div>
            </section>
        </div>

        <footer class="sobre-footer">
            <p>&copy; 2024 LLGR - Sistema de Gestão Ferroviária. Todos os direitos reservados.</p>
            <p class="footer-tech">Versão 1.0.0 | Desenvolvido com <i class="fas fa-heart"></i> por estudantes de Desenvolvimento de Sistemas</p>
        </footer>
    </main>

    <script src="../js/mobile-navbar.js"></script>
    <script src="../js/toast.js"></script>
</body>
</html>
