# API Agenda Profissional

![tests](https://github.com/juiehelenba/api-agenda-profissional/actions/workflows/tests.yml/badge.svg)

API REST de agenda para **uma profissional**: cadastro, login JWT e CRUD de horários.

Não é um Google Calendar. O ponto do repo é mostrar API + auth + regra de negócio + testes + CI.

## O que a API faz

- A profissional se cadastra e recebe um JWT (1 hora).
- Com o token, marca horários (cliente, data, início, fim).
- Só vê e edita **os horários dela**.
- Dois horários no mesmo dia **não podem se cruzar** (422).
- A lista (`GET /api/horarios`) vem **paginada** (15 por página: `data`, `links`, `meta`).
- Login aceita no máximo **5 tentativas por minuto** (429 se passar).

| Código | Significado |
|--------|-------------|
| 401 | Sem token ou token inválido |
| 403 | Token ok, mas o horário é de outra pessoa |
| 422 | Dado inválido ou conflito de horário |

## Stack

Laravel 13 · PHP 8.4 · SQLite · JWT (HS256, `firebase/php-jwt`) · PHPUnit · GitHub Actions

Documentação das rotas: [`docs/openapi.yaml`](docs/openapi.yaml)

## Como rodar

```bash
git clone https://github.com/juiehelenba/api-agenda-profissional.git
cd api-agenda-profissional
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

App em `http://localhost:8000`.

```bash
php artisan test
```

## Rotas

Todas começam com `/api`. As de horário exigem header `Authorization: Bearer {token}`.

| Método | Rota | Auth |
|--------|------|------|
| POST | `/api/register` | não |
| POST | `/api/login` | não (máx. 5/min) |
| GET | `/api/me` | sim |
| GET | `/api/horarios` | sim |
| POST | `/api/horarios` | sim |
| GET | `/api/horarios/{id}` | sim |
| PUT | `/api/horarios/{id}` | sim |
| DELETE | `/api/horarios/{id}` | sim |

Exemplo de cadastro:

```bash
curl -X POST http://localhost:8000/api/register ^
  -H "Content-Type: application/json" ^
  -d "{\"name\":\"Ana\",\"email\":\"ana@example.com\",\"password\":\"password1\"}"
```

## O que este repo prova

Auth JWT, isolamento por usuário (Policy), conflito de horário, CRUD testado (inclui PUT/DELETE), Form Request, API Resource, throttle no login e CI no GitHub.
