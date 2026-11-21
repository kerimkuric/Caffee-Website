# Kod Kere

Neighbourhood coffee shop web app for the IBU Web Programming project. Guests browse the menu, reserve a table, order drinks, and leave reviews. Staff manage the catalog, users, and orders.

**Live site:** _to be added in Milestone 5_  
**Swagger:** http://localhost:8080/public/v1/docs/

## What this is

A single-page application (SPA) with a REST backend (FlightPHP) and MySQL. Milestone 3 adds services, FlightPHP CRUD routes, OpenAPI/Swagger, and the shop-location external API. JWT and role middleware come in Milestone 4.

## Entities and relationships

| Entity | Role in the shop |
| --- | --- |
| users | Customers and admins |
| categories | Menu groups |
| products | Drinks and pastry |
| reservations | Table bookings |
| orders | Takeaway / cafe orders |
| reviews | Guest ratings |
| order_items | Products on an order |

ERD: [docs/erd.md](docs/erd.md) and visual [docs/erd.html](docs/erd.html). SQL: [database/kod_kere.sql](database/kod_kere.sql).

## Roles and test accounts

Password for both accounts: `Password123!`

| Role | Access (planned for M4) | Test account |
| --- | --- | --- |
| admin | CRUD on all entities | `admin@kodkere.ba` |
| customer | Own profile, orders, reservations, reviews; view menu | `guest@kodkere.ba` |

## External API

**OpenStreetMap Nominatim** geocodes `Edina Džeke 11, Sarajevo` for the Contact page (`GET /api/location`).

- 5 second timeout
- Failures go to `backend/logs/error.log`
- Successful calls go to `backend/logs/info.log`
- If Nominatim is down, the UI still shows the street address
- Optional **LocationIQ** key in `config.php` (`LOCATIONIQ_API_KEY`). Leave the placeholder to use Nominatim (no key required)

## How to run locally

### 1. Database

1. Start MySQL (XAMPP is fine).
2. Copy `backend/config/config.example.php` to `backend/config/config.php`.
3. Import `database/kod_kere.sql`.

### 2. API

```bash
cd backend
composer install
php -S localhost:8080 router.php
```

- API: http://localhost:8080/api/products
- Swagger: http://localhost:8080/public/v1/docs/ (or http://localhost:8080/)

### 3. Frontend

```bash
cd frontend
php -S localhost:5500
```

Open http://localhost:5500 — the Contact page calls `GET /api/location`.

## Example requests

```bash
curl http://localhost:8080/api/products
curl http://localhost:8080/api/location
curl -X POST http://localhost:8080/api/categories -H "Content-Type: application/json" -d "{\"name\":\"Tea\",\"description\":\"Pot of tea\"}"
```

## Project structure

```
backend/     index.php, routes, services, dao, config, logs, public/v1/docs
database/    kod_kere.sql
frontend/    css, js, views, img, lib
docs/        ERD, AI_LOG.md
```

Layers: **route → service → DAO → MySQL**. Only services call DAO. Only DAO runs SQL.

## AI usage

See [AGENTS.md](AGENTS.md) and [docs/AI_LOG.md](docs/AI_LOG.md).

## Submission

Work is done on branch `milestone3`. Open a PR and add `ibu-web-programming2001` as reviewer (and collaborator). Do not merge the milestone branch into `main`.
