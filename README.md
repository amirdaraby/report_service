# Report Service

A Laravel API for scheduled report generation. Users register, create periodic reports (daily/weekly) with keywords, and the system queries Elasticsearch for matching posts, exports them to Excel, and emails the result.

## Table of Contents

- [Stack](#stack)
- [Setup](#setup)
- [Artisan Commands](#artisan-commands)
- [API Endpoints](#api-endpoints)
- [Architecture](#architecture)
- [Interview Answers](#interview-answers)
  - [1. Scaling the System](#1-how-would-you-scale-this-system-when-the-number-of-requests-and-users-increases)
  - [2. Handling Large Elasticsearch Data](#2-if-the-amount-of-user-data-in-a-time-range-becomes-very-large-what-is-your-solution-focused-on-elasticsearch)
  - [3. Multiple Delivery Channels](#3-if-users-need-to-select-one-or-multiple-delivery-channels-what-approach-would-you-suggest)

## Stack

- PHP / Laravel
- PostgreSQL
- Redis
- Elasticsearch
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


# Interview Answers

## 1. How would you scale this system when the number of requests and users increases?

### API Scaling

- Multiple Laravel application instances can be deployed behind a load balancer. The API is stateless, so each instance handles requests independently.

### Asynchronous Processing

- Generating reports, creating Excel files, and sending emails are time-consuming operations. They should not run during the user's request lifecycle.
The API only creates and stores the report configuration. Actual report generation happens asynchronously via queues.

### Database Scaling

- Add indexes for frequently queried columns.
- Large tables can be partitioned by time or other suitable keys.

### Scheduler Concurrency

- To prevent multiple scheduler instances from processing the same report, PostgreSQL row locking is used:
```FOR UPDATE SKIP LOCKED```.
This lets multiple scheduler instances run simultaneously while ensuring each report is dispatched only once.

## 2. If the amount of user data in a time range becomes very large, what is your solution? (Focused on Elasticsearch)

### Time-based Indexing
```
posts-2026-01
posts-2026-02
posts-2026-03
```

This way, queries only hit the relevant time range instead of searching the entire dataset.

### Index Lifecycle Management (ILM)

Elasticsearch ILM manages the lifecycle of indices automatically. Older data can be moved to cheaper storage or deleted based on business requirements.

## 3. If users need to select one or multiple delivery channels, what approach would you suggest?
Use the Strategy pattern with a delivery interface (for example, `ReportDelivery`) that defines how a generated report is delivered.

Each delivery channel (email, Slack, Telegram, webhook, etc.) can have its own implementation of this interface. The report generation flow does not need to know which channel is being used; it only sends the generated report to the selected delivery channels.

Adding a new delivery channel would only require creating a new implementation without changing the existing report generation logic.