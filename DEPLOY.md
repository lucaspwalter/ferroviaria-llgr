# Deploy do LLGR

Este projeto e uma aplicacao PHP + MySQL sem etapa de build. Para portfolio, o caminho mais simples e hospedar em um servidor com Apache, PHP 7.4+ e MySQL/MariaDB, como Hostinger, InfinityFree, 000webhost, cPanel ou uma VPS.

## Requisitos

- PHP 7.4 ou superior, com extensao `mysqli` ativa
- MySQL ou MariaDB
- Apache com suporte a `.htaccess`
- Acesso para importar arquivos `.sql`

## Arquivos que devem ir para o servidor

Envie estes itens para a pasta publica do site, normalmente `public_html`:

- `index.html`
- `.htaccess`
- `config/`
- `operator/`
- `user/`
- `database/`

O arquivo `.env` deve ser criado manualmente no servidor. Nao envie o seu `.env` local para repositorios publicos.

## Configurar o banco

1. Crie um banco MySQL no painel da hospedagem.
2. Crie um usuario e senha para esse banco.
3. Importe o arquivo `database/schema/ferrorama.sql`.
4. Se quiser carregar dados extras de rotas, importe tambem `database/seeds/seed_rotas_extras.sql`.

## Criar o `.env` no servidor

Crie um arquivo chamado `.env` na raiz do projeto, ao lado de `index.html`:

```env
DB_HOST=localhost
DB_USER=usuario_do_banco
DB_PASS=senha_do_banco
DB_NAME=nome_do_banco
```

Em algumas hospedagens, `DB_HOST` pode ser diferente de `localhost`. Use o host informado no painel da hospedagem.

## URLs principais

- Pagina inicial: `/index.html`
- Login do usuario: `/user/pages/login.php`
- Cadastro do usuario: `/user/pages/cadastro.php`
- Login do operador: `/operator/pages/login.php`
- Dashboard do operador: `/operator/pages/dashboard.php`

## Checklist antes de divulgar no portfolio

- O site abre sem erro 500.
- O cadastro de usuario funciona.
- Login de usuario funciona.
- Login de operador funciona.
- As telas que consultam rotas, trens, estacoes e reclamacoes carregam dados.
- O arquivo `.env` nao aparece ao acessar `https://seu-dominio/.env`.
- As pastas `config/` e `database/` nao ficam listaveis no navegador.

## Credenciais de demonstracao

O dump inclui operadores de exemplo. Antes de publicar de forma permanente, troque as senhas desses usuarios pelo painel ou diretamente no banco com hashes gerados por `password_hash`.

Para portfolio, prefira deixar claro que e um ambiente demonstrativo e nao use dados pessoais reais.
