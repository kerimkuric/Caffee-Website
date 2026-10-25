# Kod Kere

Neighbourhood coffee shop web app for the IBU Web Programming project. Guests browse the menu, reserve a table, order drinks, and leave reviews. Staff manage the catalog, users, and orders.

**Live site:** _to be added in Milestone 5_  
**Swagger:** _to be added in Milestone 3_

## What this is

A single-page application (SPA) with a REST backend (FlightPHP) and MySQL. Milestone 1 is the folder layout, a static Koppee-based frontend, and a draft ERD. The backend is not connected yet.

## Entities and relationships

Six entities (plus `order_items` as order lines):

| Entity | Role in the shop |
| --- | --- |
| users | Customers and admins |
| categories | Menu groups |
| products | Drinks and pastry |
| reservations | Table bookings |
| orders | Takeaway / cafe orders |
| reviews | Guest ratings |

A user places orders and reservations and writes reviews. Categories group products. Orders contain products through `order_items`.

Draft ERD: [docs/erd.md](docs/erd.md) and visual [docs/erd.html](docs/erd.html).

## Roles and test accounts

| Role | Access (planned) | Test account (later) |
| --- | --- | --- |
| admin | CRUD on all entities | `admin@kodkere.ba` / _set in M2 sample SQL_ |
| customer | Own profile, orders, reservations, reviews; view menu | `guest@kodkere.ba` / _set in M2 sample SQL_ |

Passwords will be hashed. JWT is issued at login (Milestone 4).

## External API

**OpenWeatherMap** for Sarajevo. The UI already has a **Weather** page and a home-page card. Milestone 3 will call the API from PHP, suggest a hot or iced drink, log failures, and keep the shop working if weather is down.

## How to view Milestone 1 locally

The SPA loads HTML fragments with `fetch`, so it must be served over HTTP (not `file://`).

```bash
cd frontend
php -S localhost:5500
```

Then open http://localhost:5500

Optional: open `docs/erd.html` for the diagram.

### Later (Milestones 2–5)

1. Copy `backend/config/config.example.php` to `backend/config/config.php` and set database, JWT secret, and API keys.
2. Import the `.sql` file (Milestone 2) into MySQL.
3. Install FlightPHP with Composer in `backend/`.
4. Run the API and point the frontend at it.

## Project structure

```
backend/     routes, services, dao, middleware, config, logs
frontend/    css, js, views, img, lib
docs/        ERD, AI_LOG.md
```

Template: Koppee (HTML Codex / ThemeWagon), restyled as Kod Kere.

## AI usage

See [AGENTS.md](AGENTS.md) and [docs/AI_LOG.md](docs/AI_LOG.md). Both are updated every milestone.

## Submission

Work is done on branch `milestone1`. Open a PR and add `ibu-web-programming2001` as reviewer (and collaborator). Do not merge the milestone branch into `main`.
