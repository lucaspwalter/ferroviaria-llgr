# Ferroviária LLGR

## O que é

Ferroviária LLGR é um sistema web para digitalizar e otimizar a gestão de operações ferroviárias, conectando operadores e usuários com controle completo de trens, rotas, estações, sensores, manutenções e itinerários.

- Portfólio: https://lucaspwalter.github.io/portfolio/
- Repositório: https://github.com/lucaspwalter/ferroviaria-llgr

## Como funciona

O sistema é dividido em dois módulos principais:

- Módulo de operadores: gerencia trens, rotas, estações, sensores e manutenções.
- Módulo de usuários: consulta itinerários e acompanha operações em tempo real.

## Tecnologias

- PHP
- MySQL
- Docker

## Como rodar localmente

Com Docker instalado:

```bash
git clone https://github.com/lucaspwalter/ferroviaria-llgr.git
cd ferroviaria-llgr
docker compose up --build
```

Acesse `http://localhost:8000`. O MySQL e o schema são configurados automaticamente.

Instruções manuais para Linux, macOS e Windows também estão no portfólio:

https://lucaspwalter.github.io/portfolio/setup-ferroviaria.html

## Estrutura do projeto

```text
ferroviaria-llgr/
- config/
  - database.php
  - security.php
- database/
  - migrations/
  - schema/
  - seeds/
- operator/
  - api/
  - css/
  - img/
  - js/
  - pages/
- user/
  - api/
  - css/
  - img/
  - js/
  - pages/
- DEPLOY.md
- Dockerfile
- index.html
- LICENSE
- Procfile
- README.md
- nixpacks.toml
```
