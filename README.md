# Kod Kere

Neighbourhood coffee shop web app for the IBU Web Programming project. Guests browse the menu, reserve a table, order drinks, and leave reviews. Staff manage the catalog, users, and orders.

**Live site:** _to be added in Milestone 5_  
**Swagger:** _to be added in Milestone 3_

## What this is

A single-page application (SPA) with a REST backend (FlightPHP) and MySQL. Milestone 2 adds the database schema, sample data, and the DAO layer. The frontend is still static; routes and services come in Milestone 3.

## Entities and relationships

Six entities plus `order_items`:

| Entity | Role in the shop |
| --- | --- |
| users | Customers and admins |
| categories | Menu groups |
| products | Drinks and pastry |
| reservations | Table bookings |
| orders | Takeaway / cafe orders |
| reviews | Guest ratings |
| order_items | Products on an order |

A user places orders and reservations and writes reviews. Categories group products. Orders contain products through `order_items`.

ERD: [docs/erd.md](docs/erd.md) and visual [docs/erd.html](docs/erd.html). SQL: [database/kod_kere.sql](database/kod_kere.sql).

## Roles and test accounts

Password for both accounts: `Password123!`

| Role | Access (planned) | Test account |
| --- | --- | --- |
| admin | CRUD on all entities | `admin@kodkere.ba` |
| customer | Own profile, orders, reservations, reviews; view menu | `guest@kodkere.ba` |

Passwords are stored as bcrypt hashes. JWT is issued at login (Milestone 4).

## External API

The course requires at least one third-party API from Milestone 3. Weather is not part of this shop. Milestone 3 will add an API that fits the contact/location or menu flow, with timeout and error handling.

## How to run locally

### Frontend (Milestone 1+)

The SPA loads HTML fragments with `fetch`, so it must be served over HTTP (not `file://`).

```bash
cd frontend
php -S localhost:5500
```

Then open http://localhost:5500

### Database and DAO (Milestone 2)

1. Start MySQL (XAMPP is fine).
2. Copy `backend/config/config.example.php` to `backend/config/config.php` and set `DB_USER` / `DB_PASSWORD` if they are not `root` / empty.
3. Import the schema:

```bash
C:\xampp\mysql\bin\mysql.exe -u root < database/kod_kere.sql
```

Or import `database/kod_kere.sql` in phpMyAdmin.

4. Optional DAO check (needs a working `config.php`):

```bash
php backend/dao_test.php
```

### Later (Milestones 3–5)

1. Install FlightPHP with Composer in `backend/`.
2. Run the API and point the frontend at it.

## Project structure

```
backend/     dao, config, (routes/services/middleware later)
database/    kod_kere.sql
frontend/    css, js, views, img, lib
docs/        ERD, AI_LOG.md
```

Template: Koppee (HTML Codex / ThemeWagon), restyled as Kod Kere.

DAO pattern matches the course reading: `BaseDao` holds the PDO connection and shared CRUD; each table has a child class (`UserDao`, `ProductDao`, …) that passes the table name and adds extra queries.

## AI usage

See [AGENTS.md](AGENTS.md) and [docs/AI_LOG.md](docs/AI_LOG.md). Both are updated every milestone.

## Submission

Work is done on branch `milestone2`. Open a PR and add `ibu-web-programming2001` as reviewer (and collaborator). Do not merge the milestone branch into `main`.
