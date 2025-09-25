# Nubank Clone - Backend Laravel + API para App React Native

## Título e Visão Geral

Backend Laravel 12 que expõe a API consumida pelo aplicativo React Native clone do Nubank. O projeto fornece autenticação via tokens do Laravel Sanctum, dados financeiros simulados (conta, cartão e extrato), endpoints REST padronizados e seeders com um usuário demo para testes locais.

## Sumário

- [Stack e Versões](#stack-e-versões)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Configuração do Ambiente](#configuração-do-ambiente)
- [Configuração do .env](#configuração-do-env)
- [Banco de Dados: Migrações e Seeders](#banco-de-dados-migrações-e-seeders)
- [Execução do Backend](#execução-do-backend)
- [Integração com o App React Native](#integração-com-o-app-react-native)
- [Endpoints Principais](#endpoints-principais)
- [Testes Automatizados e Lint](#testes-automatizados-e-lint)
- [Rollback](#rollback)
- [Comandos Úteis](#comandos-úteis)

## Stack e Versões

| Componente | Versão / Requisito | Observações |
|------------|--------------------|-------------|
| PHP        | ^8.2               | Necessário para Laravel 12. |
| Laravel    | ^12.28             | Framework principal. |
| Composer   | 2.6+               | Gerenciamento de dependências PHP. |
| Node.js    | ^20                | Útil para Vite/Tailwind (front web opcional). |
| NPM        | ^10                | Scripts utilitários. |
| SQLite     | Padrão             | Configuração default em `.env.example`. |
| Laravel Sanctum | ^4.2         | Autenticação por token para o app móvel. |

## Estrutura do Projeto

```
app/
├── Enums/
├── Http/
│   ├── Controllers/Api/
│   ├── Requests/Api/
│   └── Resources/
├── Models/
bootstrap/
config/
database/
├── factories/
├── migrations/
└── seeders/
public/
resources/
routes/
├── api.php
└── web.php
tests/
└── Feature/Api/
```

## Configuração do Ambiente

1. Instale PHP 8.2+, Composer 2.6+, Node.js 20+ e NPM 10+.
2. Clone o repositório e acesse a pasta do projeto.
3. Instale as dependências PHP:
   ```bash
   composer install
   ```
4. (Opcional) Instale dependências JavaScript caso utilize os assets via Vite:
   ```bash
   npm install
   ```

## Configuração do .env

1. Copie o arquivo de exemplo e gere a chave da aplicação:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
2. Ajuste as variáveis conforme sua base (SQLite por padrão):
   ```env
   APP_NAME="Nubank Clone"
   APP_URL=http://localhost:8000
   FRONTEND_URL=http://localhost:19006 # URL do Metro/Expo ou outro host do app RN
   SANCTUM_STATEFUL_DOMAINS=localhost,localhost:19006
   ```
3. Para uso com SQLite local, garanta que o arquivo `database/database.sqlite` exista:
   ```bash
   touch database/database.sqlite
   ```

## Banco de Dados: Migrações e Seeders

1. Execute as migrações e carregue os dados demo:
   ```bash
   php artisan migrate --seed
   ```
2. Usuário demo disponível após o seed:
   - **E-mail:** `cliente@nubank.com`
   - **Senha:** `SenhaForte1!`

## Execução do Backend

Servidor de desenvolvimento:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Fila e logs (opcional, conforme `composer.json`):
```bash
composer dev
```

## Integração com o App React Native

1. Configure o cliente HTTP do app para apontar para `http://<IP_DA_MAQUINA>:8000/api`.
2. Use o token Bearer retornado pelo `POST /api/auth/login` em todas as rotas autenticadas.
3. Em ambiente local, exponha o backend na mesma rede do dispositivo/emulador. Para Expo, utilize `FRONTEND_URL` com o host/porta do Metro bundler.
4. Fluxo sugerido no app:
   1. Login usando o usuário seed.
   2. Buscar dados do usuário (`GET /api/users/me`).
   3. Carregar saldo (`GET /api/accounts/me`).
   4. Listar extrato (`GET /api/transactions`).
   5. Exibir cartão (`GET /api/cards/me`).
   6. Efetuar transferências com `POST /api/transfers`.

## Endpoints Principais

### Autenticação

```http
POST /api/auth/login
Body: { "email": "cliente@nubank.com", "password": "SenhaForte1!" }
```
Resposta (200): token Bearer + dados do usuário.

```http
POST /api/auth/logout
Header: Authorization: Bearer <token>
```
Resposta (200): mensagem de sucesso.

### Sessão e Dados

```http
GET /api/users/me
GET /api/accounts/me
GET /api/cards/me
GET /api/transactions?por_pagina=20&tipo=entrada
POST /api/transfers { "valor": 100.5, "descricao": "Transferência", "destinatario": "João" }
```

Todas as rotas acima exigem cabeçalho `Authorization: Bearer <token>`.

## Testes Automatizados e Lint

Execute os testes (unitários + integração):
```bash
php artisan test
```

Rode o lint PHP com Pint:
```bash
./vendor/bin/pint
```

## Rollback

Desfazer migrações e seeds aplicados:
```bash
php artisan migrate:rollback --step=1
```
Repita conforme necessário até retornar ao estado desejado. Para restaurar dados seed, execute novamente `php artisan migrate --seed`.

Para reverter mudanças no working tree:
```bash
git reset --hard HEAD
```

## Comandos Úteis

- Limpar caches: `php artisan optimize:clear`
- Regenerar chaves Sanctum: `php artisan sanctum:prune-expired --hours=48`
- Rodar somente testes de API: `php artisan test --testsuite=Feature --filter=Api`

---

**Observação:** mantenha o padrão de commits em português e siga o Conventional Commits (ex.: `feat: adicionar API financeira`). Atualize este README sempre que novas rotas ou requisitos forem adicionados.
