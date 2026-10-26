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
