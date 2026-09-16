# Report Service

A Laravel API for scheduled report generation. Users register, create periodic reports (daily/weekly) with keywords, and the system queries Elasticsearch for matching posts, exports them to Excel, and emails the result.

## Stack

- PHP 8.3+ / Laravel 11
- PostgreSQL 15
- Redis 7
- Elasticsearch 9.5
- Sanctum (API token auth)
- Maatwebsite Excel (export)
- Docker / Docker Compose

## Setup

```bash
cp .env.example .env
make setup
docker compose exec php_fpm php artisan elasticsearch:create-posts-index
```

### What `make setup` does

1. Starts all containers (`docker compose up -d --build`)
2. Initializes Elasticsearch security (kibana_system password, role, user)
3. Generates an Elasticsearch API key and writes it to `.env`
4. Restarts queue worker and scheduler
5. Generates `APP_KEY` if missing
6. Runs database migrations

### Seed data

```bash
# Generate fake posts in Elasticsearch
docker compose exec php_fpm php artisan elasticsearch:generate-posts --count=1000 --days=30

# Or import from a JSON file
docker compose exec php_fpm php artisan elasticsearch:import-posts seed_data.json
```

## Artisan Commands

| Command | Description |
|---------|-------------|
| `elasticsearch:create-posts-index` | Create the Elasticsearch posts index with mappings |
| `elasticsearch:generate-posts` | Generate fake posts (options: `--count`, `--days`) |
| `elasticsearch:import-posts {path}` | Import posts from a JSON file |
| `elasticsearch:get-api-key` | Generate ES API key and save to `.env` |
| `reports:dispatch` | Dispatch due periodic reports (runs every minute via scheduler) |

## API Endpoints

Base URL: `http://localhost/api/v1`

All responses follow the format:

```json
{
  "data": {},
  "message": "string"
}
```

### Authentication

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `POST` | `/auth/register` | Register a new user | No |
| `POST` | `/auth/login` | Login and receive a token | No |
| `POST` | `/auth/logout` | Revoke current token | Yes |

### Users

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `GET` | `/users/me` | Get authenticated user | Yes |

### Reports

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `POST` | `/reports` | Create a new report | Yes |
| `GET` | `/reports` | List paginated reports | Yes |


## Enums

| Frequency | Value | Description |
|-----------|-------|-------------|
| DAILY | 1 | Report runs every day |
| WEEKLY | 2 | Report runs every week |

| Status | Value | Description |
|--------|-------|-------------|
| INACTIVE | 0 | Report is disabled |
| ACTIVE | 1 | Report is active |

## Architecture

```
app/
├── Application/          # Business logic layer
│   ├── Contracts/        # Interfaces (repositories, export, delivery)
│   ├── DTOs/             # Immutable data transfer objects
│   └── Services/         # Application services
├── Console/Commands/     # Artisan commands
├── Enums/                # Backed enums
├── Exceptions/           # Domain exceptions
├── Exports/              # Maatwebsite Excel exports
├── Http/
│   ├── Controllers/      # Thin controllers
│   ├── Helpers/          # Response helpers
│   ├── Middleware/        # EnsureJsonResponse
│   └── Requests/         # Form request validation
├── Infrastructure/       # Adapters
│   ├── Eloquent/         # Repository implementations
│   ├── Elasticsearch/    # ES client, indexing, queries
│   ├── Exports/          # Excel exporter
│   └── Mail/             # Email delivery
├── Jobs/                 # Queue jobs
├── Mail/                 # Mailables
├── Models/               # Eloquent models
└── Paginator/            # Custom pagination
```

The architecture follows **Clean Architecture** with a clear separation:

- **Application layer** — business logic, contracts (interfaces), DTOs
- **Infrastructure layer** — concrete implementations (Eloquent, Elasticsearch, Mail, Excel)
- **Http layer** — controllers, requests, middleware (presentation only)