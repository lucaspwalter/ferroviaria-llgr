LLGR - Sistema de Gestão Ferroviária

Título do Projeto
LLGR - Logística e Logística de Gestão Rodoviária

Sistema Integrado de Gerenciamento Ferroviário

Objetivo do Projeto
O LLGR é um sistema web desenvolvido para digitalizar e otimizar a gestão de operações ferroviárias. O objetivo principal é fornecer uma plataforma que conecte operadores e usuários, permitindo o controle completo de trens, rotas, estações, sensores, manutenções e itinerários.

O sistema visa aumentar a eficiência operacional, garantir a segurança no transporte e melhorar a experiência dos usuários através de uma interface moderna e intuitiva.

Contexto
Este sistema foi desenvolvido como projeto final integrador do curso técnico em Desenvolvimento de Sistemas. O projeto demonstra a aplicação prática de conceitos fundamentais da área:

- Desenvolvimento Web Frontend e Backend
- Banco de Dados Relacional
- Programação Orientada a Objetos
- Interface e Experiência do Usuário
- Arquitetura de Software
- Gestão de Projetos

O LLGR simula um ambiente real de gestão ferroviária, abordando desde o cadastro básico de recursos até funcionalidades avançadas como monitoramento por sensores e geração de relatórios gerenciais.

Funcionalidades Principais

Sistema de Autenticação
O sistema possui dois tipos de usuários com diferentes níveis de acesso:

Operadores: Responsáveis pela gestão completa do sistema, incluindo cadastros, monitoramento e geração de relatórios.

Usuários: Podem consultar rotas disponíveis, visualizar notificações e realizar reclamações.

Dashboard Operacional
Painel principal com visão geral do sistema, exibindo:
- Número de trens ativos e parados
- Índice de pontualidade média
- Quantidade de atrasos nas últimas 24 horas
- Gráficos de incidentes por gravidade
- Distribuição de passageiros por classe

Módulos de Gerenciamento

Sensores
Cadastro e monitoramento de sensores IoT instalados na infraestrutura ferroviária. Tipos de sensores: temperatura, pressão, velocidade, proximidade e vibração. Cada sensor possui localização, status e unidade de medida.

Estações
Gerenciamento completo de estações ferroviárias com informações de localização, capacidade, número de plataformas, horários de funcionamento e serviços disponíveis.

Trens
Controle da frota incluindo dados do modelo, fabricante, ano de fabricação, capacidade de passageiros, velocidade máxima, quilometragem rodada e status operacional.

Rotas
Cadastro de trajetos entre estações, contendo origem, destino, distância em quilômetros, tempo estimado de viagem, preço base e pontos de parada.

Itinerários
Programação de viagens específicas, vinculando rotas a trens, com definição de datas, horários de partida e chegada, e controle de passageiros embarcados.

Manutenções
Gestão de manutenções da frota, diferenciando entre preventivas, corretivas, emergenciais e revisões. Controla custos, responsáveis, peças substituídas e datas de execução.

Alertas
Sistema de alertas classificados por criticidade (crítico, urgente, aviso, informativo), vinculados a sensores ou gerados pelo sistema. Permite acompanhamento de resolução e ações tomadas.

Notificações
Envio de avisos e lembretes para usuários sobre eventos relacionados a trens, rotas, estações ou sistema.

Relatórios
Geração de relatórios operacionais, financeiros e de incidentes, com possibilidade de exportação em diferentes formatos e filtros por período.

Área do Usuário

Consulta de Rotas
Visualização de rotas disponíveis com informações detalhadas de origem, destino, tempo de viagem e preço.

Notificações
Recebimento e visualização de avisos enviados pelos operadores.

Sistema de Reclamações
Canal direto para abertura de chamados e reclamações, com acompanhamento de respostas dos operadores e status de resolução.

Perfil Pessoal
Gerenciamento de dados pessoais com possibilidade de edição de informações cadastrais.

Tecnologias Utilizadas

Frontend
- HTML5: estruturação semântica das páginas
- CSS3: estilização e design responsivo
- JavaScript: interatividade e comunicação com o servidor
- Chart.js: criação de gráficos dinâmicos
- Font Awesome: biblioteca de ícones
- Google Fonts: tipografia Poppins

Backend
- PHP 7.4+: lógica de negócios e processamento de dados
- MySQL: banco de dados relacional

Servidor
- Apache (XAMPP): servidor web para desenvolvimento
- phpMyAdmin: administração do banco de dados

Padrões e Práticas
- Arquitetura MVC: separação de responsabilidades
- API REST: comunicação via JSON
- Responsive Design: adaptação para diferentes dispositivos
- Prepared Statements: prevenção de SQL Injection
- Password Hashing: criptografia de senhas com bcrypt

Equipe de Desenvolvimento

Lucas Walter
Raul
Lucas Rafael
Gustavo Cercal


Curso: Técnico em Desenvolvimento de Sistemas
Ano: 2025

Estrutura do Repositório

```
ferroviaria-llgr/
│
├── index.html                         Página inicial
├── README.md                          Este arquivo
├── DEPLOY.md                          Guia para publicar em hospedagem PHP/MySQL
├── LICENSE                            Licença MIT
│
├── config/                            Configurações e helpers globais
│   ├── database.php                   Conexão com o banco de dados
│   └── security.php                   Helpers de segurança e CSRF
│
├── database/                          Arquivos de banco de dados
│   ├── schema/                        Estrutura completa do banco
│   │   └── ferrorama.sql
│   ├── migrations/                    Alterações incrementais de schema
│   │   └── migration_rota_paradas.sql
│   └── seeds/                         Dados extras para popular o banco
│       └── seed_rotas_extras.sql
│
├── operator/                          Área do Operador
│   ├── pages/                           Páginas do operador
│   │   ├── dashboard.php              Painel principal
│   │   ├── sensores.php               Gerenciar sensores
│   │   ├── estacoes.php               Gerenciar estações
│   │   ├── trens.php                  Gerenciar trens
│   │   ├── rotas.php                  Gerenciar rotas
│   │   ├── itinerarios.php            Gerenciar itinerários
│   │   ├── alertas.php                Gerenciar alertas
│   │   ├── notificacoes.php           Gerenciar notificações
│   │   ├── manutencoes.php            Gerenciar manutenções
│   │   ├── relatorios.php             Gerenciar relatórios
│   │   ├── reclamacoes.php            Gerenciar reclamações
│   │   ├── perfil_operador.php        Perfil do operador
│   │   └── login.php                  Login do operador
│   │
│   ├── api/                           APIs do operador
│   ├── css/                           Estilos do operador
│   ├── js/                            Scripts do operador
│   └── img/                           Imagens
│
├── user/                              Área do Usuário
│   ├── pages/                           Páginas do usuário
│   │   ├── cadastro.php               Cadastro de usuário
│   │   ├── login.php                  Login do usuário
│   │   ├── perfil.php                 Visualizar perfil
│   │   ├── editar_perfil.php          Editar perfil
│   │   ├── rotas_usuario.php          Consultar rotas
│   │   ├── notificacoes_usuario.php   Ver notificações
│   │   ├── chat.php                   Sistema de reclamações
│   │   └── sobre.php                  Sobre o sistema
│   │
│   ├── api/                           APIs do usuário
│   ├── css/                           Estilos do usuário
│   ├── js/                            Scripts do usuário
│   └── img/                           Imagens
```

Publicação

Para colocar o projeto no ar, use uma hospedagem com Apache, PHP 7.4+ e MySQL/MariaDB. O passo a passo completo esta em `DEPLOY.md`, incluindo importacao do banco, criacao do arquivo `.env` e checklist de verificacao.

Licença

Este projeto foi desenvolvido para fins educacionais como parte do curso técnico em Desenvolvimento de Sistemas.

MIT License

Copyright (c) 2024 Lucas Walter, Raul, Lucas Rafael, Gustavo Cercal

É concedida permissão, gratuitamente, a qualquer pessoa que obtenha uma cópia deste software e dos arquivos de documentação associados, para usar, copiar, modificar, mesclar, publicar, distribuir, sublicenciar e/ou vender cópias do software, desde que o aviso de copyright acima e este aviso de permissão sejam incluídos em todas as cópias ou partes substanciais do software.

O software é fornecido "como está", sem garantia de qualquer tipo, expressa ou implícita.
