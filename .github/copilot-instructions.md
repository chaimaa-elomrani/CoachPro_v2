<!-- .github/copilot-instructions.md -->
# CoachPro_v2 — Copilot instructions for AI coding agents

Purpose
- Help AI agents be productive in this repository by documenting the project's layout, discovered conventions, and concrete next steps for common tasks.

Big picture
- This is a minimal PHP MVC-style application skeleton (directories: `config/`, `core/`, `controllers/`, `models/`, `views/`, `public/`). Many implementation files are currently empty; confirm missing pieces with the maintainer before large changes.
- The intended runtime is a PHP web app served from the `public/` directory as the document root.

Key files and locations (examples)
- Configuration: `config/database.php` — database credentials and connection setup.
- Framework core: `core/` — bootstrap, base `Controller`/`Model`, router (directory is present but currently empty).
- Application logic: `controllers/` and `models/` — place controller classes and data models here.
- Presentation: `views/` — PHP/HTML templates.
- Web root: `public/` — static assets and front controller.
- Project description: [README.md](README.md)

Conventions and patterns (discoverable)
- MVC separation: controllers handle requests, models handle data, views render output. Place files in the corresponding directory.
- DB config centralization: edits to `config/database.php` control DB behavior — prefer changing credentials there rather than scattering connection code.
- Minimal bootstrap expected in `public/index.php` (front controller) to load `core/` classes and dispatch to controllers.

Developer workflows (how to run / debug)
- Local dev using Laragon (this workspace uses Windows): set the project root or `public/` as the document root in Laragon and start the site.
- Or with PHP's built-in server (from repository root):
```
php -S localhost:8000 -t public
```
- If a `core/` router or bootstrap is missing, ask the maintainer whether `public/index.php` is provided elsewhere or should be created.

What AI agents should do first
- Do not assume missing code — open and inspect `core/`, `controllers/`, `models/`, and `public/` before changing behavior.
- If directories are empty, propose skeleton implementations and confirm with the user. Example minimal steps:
  - Add `public/index.php` front controller that requires a small bootstrap in `core/bootstrap.php`.
  - Add a base `core/Controller.php` and `core/Model.php` with simple loader logic.

Safe-change rules (always follow)
- Ask before adding or removing database credentials from `config/database.php`.
- Avoid altering project structure without maintainer approval (this repo is a skeleton; intended layout matters).
- For any runtime or deployment change (web root, server config), confirm target environment (Laragon vs production) first.

Searchable hints for humans and agents
- Look at [config/database.php](config/database.php) first for DB settings.
- If tests or CI appear later add instructions here; currently none were found.

If something is unclear
- Ask the repository owner whether this is a custom micro-framework or an adaptation of an existing framework. Mention any missing core files you plan to scaffold.

End
<!-- End of file -->