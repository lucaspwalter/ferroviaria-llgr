# Ferroviária LLGR

A web platform for managing railway operations, infrastructure, and itineraries.

## Overview

Ferroviária LLGR is a web system designed to digitize and optimize railway operations management, connecting operators and users with full control over trains, routes, stations, sensors, maintenance, and itineraries.

- Portfolio: https://lucaspwalter.github.io/portfolio/
- Repository: https://github.com/lucaspwalter/ferroviaria-llgr

## Features

The system is divided into two main modules:

- Operator module: manages trains, routes, stations, sensors, and maintenance.
- User module: searches itineraries and monitors operations in real time.

## Tech Stack

- PHP
- MySQL
- Docker

## Getting Started

With Docker installed:

```bash
git clone https://github.com/lucaspwalter/ferroviaria-llgr.git
cd ferroviaria-llgr
docker compose up --build
```

Open `http://localhost:8000`. MySQL and its schema are configured automatically.

Manual instructions for Linux, macOS, and Windows are also available in the portfolio:

https://lucaspwalter.github.io/portfolio/setup-ferroviaria.html

## Project Structure

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

## License

Licensed under the MIT License. See `LICENSE`.
