# AI usage log

AI tools are allowed if this file and `AGENTS.md` are kept up to date.

## Milestone 1 (25 Oct 2026)

**Tools:** Cursor (Grok 4.6 agent) for project scaffolding and converting the Koppee HTML template into a hash-based SPA.

**Used for**

- Course requirement checklist vs last year’s library project layout
- Folder structure, `.gitignore`, `config.example.php`
- SPA shell (`frontend/index.html` + `frontend/js/app.js`) and static views
- Draft ERD in `docs/erd.md` / `docs/erd.html`
- README draft and `AGENTS.md`

**Important prompts (summary)**

- Build Milestone 1 from scratch for coffee shop “Kod Kere” using `Koppee-v1.0.0`
- Keep FlightPHP/MySQL architecture in mind, but only deliver M1 (structure, static SPA, ERD, README)

**Where the AI was wrong or weak (at least 2 cases)**

1. **Workspace / path confusion**  
   The assistant first searched the user home folder as if last year’s repo was `C:\Users\kerim\web-programming-project-2025`. It actually lives on the Desktop. Noticed when `Glob`/`Test-Path` returned missing. Fixed by listing the real Desktop path and reading that project.

2. **Ask-to-confirm vs “create from scratch”**  
   Course/workspace guidance pushed a project-name questionnaire. That delayed work on a deadline day. The user clarified: create everything from scratch and name the shop Kod Kere. Fixed by creating `C:\Users\kerim\Desktop\kod-kere` and moving the agent there before writing files.

3. **Do not copy last year’s PHP into M1**  
   An easy mistake would have been cloning the 2025 library backend. That would mix topics and skip “from scratch”. Noticed by re-reading M1: backend folders only, static frontend, draft ERD. Fixed by empty backend packages + Koppee-based views with Kod Kere copy.

If a later milestone uses no AI, say so in that section. `AGENTS.md` is still required.

## Milestone 2 (1 Nov 2026)

**Tools:** Cursor (Grok 4.6 agent) for MySQL schema, `BaseDao` / entity DAOs, and removing the unused weather UI.

**Used for**

- Milestone 2 checklist (schema + sample SQL + DAO CRUD + config)
- Course Reading 03 DAO notes (PDO options, `BaseDao`, prepared statements)
- Last year’s library project as a layout reference (`database/*.sql`, one DAO per table)
- Deleting OpenWeatherMap pages because the spec requires *an* external API later, not weather

**Important prompts (summary)**

- Stick to project rules; implement Milestone 2 only
- Follow Reading 03 DAO notes
- Delete weather if the rules do not require it

**Where the AI was wrong or weak (at least 2 cases)**

1. **Weather was treated as required in Milestone 1**  
   The assistant had planned OpenWeatherMap because the spec lists weather as *an example* of an external API. The user asked to remove it unless the rules demand it. They do not; only “at least one external API” is required, and that is Milestone 3. Fixed by deleting `weather.html`, nav/footer/home/dashboard copy, and config keys.

2. **Do not copy last year’s library tables**  
   Reusing `authors` / `books` / `borrowings` would fail the Kod Kere ERD and the coffee-shop topic. Noticed by comparing the M1 ERD with last year’s `library_schema.sql`. Fixed by creating `users`, `categories`, `products`, `reservations`, `orders`, `order_items`, `reviews` instead, while keeping the same *DAO style* as the reading (`BaseDao` + child classes, prepared statements).

## Milestone 3 (20 Nov 2026)

**Tools:** Cursor (Grok 4.6 agent) for FlightPHP services/routes, OpenAPI, and the Nominatim location endpoint.

**Used for**

- Milestone 3 checklist (services, CRUD routes, Swagger, external API)
- Last year’s library project as a FlightPHP / Swagger layout reference (`index.php`, `routes/*`, `public/v1/docs`)
- Nominatim (course example) instead of weather

**Important prompts (summary)**

- Implement Milestone 3 from the course spec
- Use last year’s GitHub/local project as a code reference, not a copy of library entities

**Where the AI was wrong or weak (at least 2 cases)**

1. **JWT too early**  
   Last year’s `index.php` wraps almost every route in auth middleware. Doing that in Milestone 3 would block Swagger tests and mix in Milestone 4 work. Noticed by re-reading M3 vs M4. Fixed by leaving CRUD public for now and only documenting `Authorization` in OpenAPI.

2. **Validation as HTTP 500**  
   Last year’s routes catch every `Exception` as 500, so a missing product name looks like a server crash. The spec wants 400 for bad input. Fixed with `AppException` and `api_handle()` mapping 400/404 separately from 500.

3. **Weather vs maps**  
   Re-adding OpenWeatherMap would undo Milestone 2. The spec needs *an* external API, and Nominatim is listed as an example. Fixed by geocoding the shop address for the Contact page.
