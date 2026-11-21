# Kod Kere — AI assistant context

Kod Kere is an individual university web-programming project: a coffee shop SPA in Sarajevo with a FlightPHP REST backend and MySQL.

## Technology stack

- Frontend: HTML, CSS, JavaScript only. Koppee Bootstrap 4 template, customized. No mixing of PHP into the frontend.
- Backend: pure PHP, FlightPHP for the REST API, PDO with prepared statements.
- Database: MySQL, at least 5 entities with CRUD.
- Auth: JWT. Roles: `admin` and `customer`. JWT middleware is Milestone 4.
- Docs: OpenAPI / Swagger at `backend/public/v1/docs/`.
- External API: OpenStreetMap Nominatim (optional LocationIQ key) on `GET /api/location`, shown on the Contact page.

## Architecture rules

- Keep frontend and backend separate. Views must not call the database.
- From Milestone 5, only frontend service files may perform AJAX. Do not put `fetch` / `$.ajax` inside HTML views then.
- Backend layers: routes → services → DAO. Services are the only layer that calls DAO.
- SQL lives only in DAO classes. Do not write queries in routes or services.
- Every entity DAO extends `BaseDao` and inherits the PDO connection plus `getAll`, `getById`, `insert`, `update`, `delete`.
- Always use prepared statements for values. Table/column names are never taken from user input.
- Validation and constraints live in services (`AppException` with 400/404).
- Middleware is registered once in FlightPHP (auth, roles, request logging, error handling). Do not copy that logic into every route. Auth middleware is Milestone 4.
- Secrets live in `backend/config/config.php`. Commit `config.example.php` only.
- Write info logs to `backend/logs/info.log` and errors to `backend/logs/error.log`.
- Do not merge `milestone*` branches into `main`. The course reviewer merges after the PR.

## Folder structure

```
backend/routes, services, dao, middleware, config, logs, public/v1/docs
database/     kod_kere.sql
frontend/css, js, views, img, lib
docs/          ERD, AI_LOG.md
AGENTS.md
README.md
```

## Naming

- PHP classes: `UserDao`, `UserService`, `AuthMiddleware`.
- Tables: lowercase plural (`users`, `products`, `order_items`).
- Frontend views: `frontend/views/*.html`.
- Hash routes: `#/home`, `#/login`, `#/products`, …
- API routes: `/api/users`, `/api/products`, `/api/location`, …

## Milestone 3 scope (current)

Services, FlightPHP CRUD for all entities, OpenAPI/Swagger, external location API. No JWT yet.

## Course reviewer

GitHub user `ibu-web-programming2001` must be collaborator and PR reviewer. One branch per milestone: `milestone1` … `milestone5`.
