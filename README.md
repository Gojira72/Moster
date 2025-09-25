# <PREENCHER>

## Título e Visão Geral

<PREENCHER> — descrição breve do propósito da aplicação e das principais funcionalidades oferecidas.

## Sumário

- [Stack e Versões](#stack-e-versões)
- [Como Identificar a Stack](#como-identificar-a-stack)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Pré-requisitos por Sistema Operacional](#pré-requisitos-por-sistema-operacional)
- [Clonagem e Configuração (Passo a Passo)](#clonagem-e-configuração-passo-a-passo)
- [Configuração do .env](#configuração-do-env)
- [Banco de Dados: Migrações e Seeders](#banco-de-dados-migrações-e-seeders)
- [Front-end (Vite / Node)](#front-end-vite--node)
- [Execução da Aplicação](#execução-da-aplicação)
- [Opcional: Docker / Laravel Sail](#opcional-docker--laravel-sail)
- [Tarefas Agendadas e Filas](#tarefas-agendadas-e-filas)
- [Testes Automatizados](#testes-automatizados)
- [Comandos Úteis](#comandos-úteis)
- [Solução de Problemas (FAQ)](#solução-de-problemas-faq)
- [Licença](#licença)
- [Checklist para Adaptação](#checklist-para-adaptação)
- [Pendências de Configuração](#pendências-de-configuração)

## Stack e Versões

| Componente | Versão / Requisito | Observações |
|------------|--------------------|-------------|
| PHP        | ^8.2               | Confirmado em `composer.json`. |
| Laravel    | ^12.0              | Framework principal (`laravel/framework`). |
| Composer   | 2.6+ (recomendado) | Necessário para gerenciar dependências PHP. |
| Node.js    | ^20 (LTS recomendada) | Compatível com Vite 7 e Tailwind 4. |
| NPM        | ^10                | Ou Yarn/Pnpm, se preferir. |
| Vite       | ^7.0.4             | Configurado em `vite.config.js`. |
| Banco de Dados | SQLite (padrão), suporte a MySQL/PostgreSQL | Ajustável via `.env`. |
| Redis      | Opcional (phpredis) | Configurado em `.env.example`. |
| Laravel Sail | ^1.41 (dev)      | Disponível para ambiente Docker opcional. |

## Como Identificar a Stack

Execute os comandos abaixo para confirmar versões instaladas:

```bash
php -v
php artisan --version
composer -V
composer show laravel/framework
node -v
npm -v
php artisan about
```

## Estrutura do Projeto

Estrutura base (resumo):

```
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
vite.config.js
```

Adapte conforme as necessidades específicas do projeto.

## Pré-requisitos por Sistema Operacional

### Windows

1. Instale o [WSL2](https://learn.microsoft.com/windows/wsl/install) com Ubuntu 22.04+ (recomendado para consistência).
2. Dentro do WSL:
   ```bash
   sudo apt update && sudo apt upgrade -y
   sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip php8.2-intl php8.2-bcmath php8.2-sqlite3 php8.2-mysql php8.2-pgsql unzip git curl build-essential
   sudo apt install -y redis-server
   ```
3. Instale o Composer:
   ```bash
   curl -sS https://getcomposer.org/installer -o composer-setup.php
   php composer-setup.php --install-dir=/usr/local/bin --filename=composer
   rm composer-setup.php
   composer -V
   ```
4. Instale Node.js LTS via [nvm](https://github.com/nvm-sh/nvm):
   ```bash
   curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
   source ~/.nvm/nvm.sh
   nvm install --lts
   node -v && npm -v
   ```
5. Banco de dados: utilize o serviço do WSL (MySQL/PostgreSQL) ou bancos hospedados externamente.

> Alternativa nativa: instale [PHP para Windows](https://windows.php.net/download/), [Composer](https://getcomposer.org/download/), [Git](https://git-scm.com/download/win) e [Node.js LTS](https://nodejs.org/en/download). Garanta extensões equivalentes.

### Ubuntu/Debian

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip php8.2-intl php8.2-bcmath php8.2-sqlite3 php8.2-mysql php8.2-pgsql unzip git curl build-essential
sudo apt install -y redis-server
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
composer -V
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install -y nodejs
node -v && npm -v
```

Instale e configure MySQL/PostgreSQL conforme a necessidade.

### macOS

1. Instale o [Homebrew](https://brew.sh/).
2. Pacotes principais:
   ```bash
   brew update
   brew install php composer git redis
   brew install mysql # ou brew install postgresql
   brew install node
   ```
3. Inicie serviços conforme necessário:
   ```bash
   brew services start mysql
   brew services start redis
   ```
4. Verifique versões:
   ```bash
   php -v
   composer -V
   node -v && npm -v
   ```

## Clonagem e Configuração (Passo a Passo)

```bash
git clone <PREENCHER_URL_DO_REPO>
cd <PREENCHER_DIRETORIO>
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate # Ajuste para o banco utilizado
npm install
```

Ajuste permissões (Linux/macOS):

```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```

## Configuração do .env

| Variável | Descrição | Exemplo (MySQL) | Exemplo (PostgreSQL) |
|----------|-----------|-----------------|----------------------|
| `APP_NAME` | Nome da aplicação | `APP_NAME="<PREENCHER>"` | `APP_NAME="<PREENCHER>"` |
| `APP_ENV` | Ambiente de execução | `local` | `local` |
| `APP_KEY` | Chave de criptografia | Gerado via `php artisan key:generate` | Igual |
| `APP_DEBUG` | Debug habilitado | `true` em dev | `false` em prod |
| `APP_URL` | URL pública | `http://localhost` | `http://localhost` |
| `DB_CONNECTION` | Driver do banco | `mysql` | `pgsql` |
| `DB_HOST` | Host do banco | `127.0.0.1` | `127.0.0.1` |
| `DB_PORT` | Porta | `3306` | `5432` |
| `DB_DATABASE` | Nome do banco | `laravel` | `laravel` |
| `DB_USERNAME` | Usuário | `root` | `postgres` |
| `DB_PASSWORD` | Senha | `<senha>` | `<senha>` |
| `CACHE_STORE` | Driver de cache | `database` ou `redis` | `database` ou `redis` |
| `QUEUE_CONNECTION` | Driver de filas | `database`, `redis`, `sqs`... | `database`, `redis`, `sqs`... |
| `SESSION_DRIVER` | Armazenamento de sessão | `database` | `database` |
| `REDIS_HOST` | Host Redis | `127.0.0.1` | `127.0.0.1` |

Observações:

- Para SQLite, use `DB_CONNECTION=sqlite` e deixe os demais campos comentados.
- Em produção, defina `APP_ENV=production` e `APP_DEBUG=false`.
- Se utilizar Sail, as variáveis de banco são geradas automaticamente em `.env` com prefixo `DB_` apontando para os containers.

## Banco de Dados: Migrações e Seeders

```bash
php artisan migrate
php artisan db:seed
php artisan migrate --seed
php artisan migrate:fresh --seed
```

Utilize factories/seeders localmente para popular dados iniciais. Em produção, execute apenas o necessário.

## Front-end (Vite / Node)

```bash
npm install
npm run dev      # watcher com HMR
npm run build    # bundle otimizado para produção
```

Caso prefira Yarn ou Pnpm, adapte os comandos (`yarn dev`, `pnpm dev`).

## Execução da Aplicação

### Ambiente de Desenvolvimento

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Aplicação acessível em `http://localhost:8000`. Para servir filas e logs simultaneamente, utilize os scripts definidos em `composer.json`:

```bash
composer run dev
```

### Ambiente de Produção

- Configure um servidor web (Nginx ou Apache) apontando para a pasta `public/`.
- Defina `APP_ENV=production` e `APP_DEBUG=false`.
- Execute `php artisan config:cache`, `php artisan route:cache` e `php artisan view:cache` após o deploy.
- Garanta que `php artisan storage:link` seja executado uma vez para expor os arquivos de `storage/app/public`.

## Opcional: Docker / Laravel Sail

1. Instale dependências básicas:
   ```bash
   composer install
   cp .env.example .env
   ```
2. Habilite o Sail (primeira execução cria o binário):
   ```bash
   php artisan sail:install
   ./vendor/bin/sail up -d
   ```
3. Comandos dentro do Sail:
   ```bash
   ./vendor/bin/sail php artisan key:generate
   ./vendor/bin/sail php artisan migrate --seed
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run dev   # ou npm run build
   ./vendor/bin/sail artisan queue:work
   ```
4. Para encerrar:
   ```bash
   ./vendor/bin/sail down
   ```

Ajuste serviços (`mysql`, `pgsql`, `redis`) conforme seleção no `sail:install`.

## Tarefas Agendadas e Filas

### Scheduler

Adicione ao crontab do usuário que executa o PHP:

```bash
* * * * * cd /caminho/para/<PREENCHER_DIRETORIO> && php artisan schedule:run >> /dev/null 2>&1
```

### Filas

Durante o desenvolvimento:

```bash
php artisan queue:work --tries=3 --backoff=5
```

Produção (sugestão com Supervisor):

```ini
[program:<PREENCHER>_queue]
process_name=%(program_name)s_%(process_num)02d
command=php /caminho/para/<PREENCHER_DIRETORIO>/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/<PREENCHER>_queue.log
```

## Testes Automatizados

```bash
php artisan test
# ou
vendor/bin/phpunit
```

Garanta que novos recursos possuam cobertura de testes.

## Comandos Úteis

```bash
php artisan optimize
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan storage:link
php artisan tinker
```

## Solução de Problemas (FAQ)

| Erro | Causa Provável | Solução |
|------|----------------|---------|
| `Application key set successfully.` ausente | `APP_KEY` não gerado | Execute `php artisan key:generate`. |
| `SQLSTATE[HY000] [2002] Connection refused` | Banco indisponível | Verifique `DB_HOST`, porta, firewall e se o serviço está ativo. |
| `Target class [Seeder] does not exist.` | Seeder não carregado | Execute `composer dump-autoload` e recompile, depois `php artisan db:seed`. |
| Assets não atualizam | Cache do Vite | Execute `npm run dev -- --force` ou limpe o cache do navegador. |
| `VITE_*` indefinido | Variáveis não expostas | Garanta `VITE_` prefixo no `.env` e reinicie `npm run dev`. |
| Permissões de escrita | Usuário sem acesso | Ajuste permissões de `storage/` e `bootstrap/cache/`. |

## Licença

Este projeto está licenciado sob os termos MIT, conforme definido em `composer.json`. Ajuste se necessário.

## Checklist para Adaptação

- [ ] Atualizar nome e descrição do projeto.
- [ ] Confirmar URL oficial do repositório.
- [ ] Validar banco de dados padrão e credenciais de ambientes.
- [ ] Definir serviços opcionais (Redis, Horizon, Telescope, etc.).
- [ ] Documentar variáveis adicionais específicas do domínio.
- [ ] Incluir instruções de CI/CD, pipelines e estratégias de deploy.
- [ ] Revisar políticas de segurança, backup e monitoramento.
- [ ] Adicionar referências a issues/milestones associadas ao projeto.

## Pendências de Configuração

- Nome oficial do projeto (`<PREENCHER>`).
- Descrição resumida (`<PREENCHER>`).
- URL do repositório (`<PREENCHER_URL_DO_REPO>`).
- Banco de dados preferencial para produção (`<PREENCHER>`).
- Serviços adicionais habilitados (ex.: Horizon, Passport, etc.).
