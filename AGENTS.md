# AGENTS.md

## Cursor Cloud specific instructions

This is a standard **Laravel 12** application using PHP 8.3, Composer, Node.js/npm, and SQLite.

### System dependencies (pre-installed in VM snapshot)

- **PHP 8.3** (from `ppa:ondrej/php`) with extensions: mbstring, xml, curl, sqlite3, zip, bcmath, intl, opcache, dom, fileinfo, tokenizer
- **Composer** (installed to `/usr/local/bin/composer`)
- **Node.js 22** and **npm** (pre-installed)
- **SQLite3**

### Quick reference

| Task | Command |
|---|---|
| Install PHP deps | `composer install` |
| Install JS deps | `npm install` |
| Setup (first time) | `composer run setup` |
| Dev server (all-in-one) | `composer run dev` |
| PHP server only | `php artisan serve` |
| Vite dev server only | `npm run dev` |
| Run tests | `php artisan test` |
| Lint (code style) | `./vendor/bin/pint --test` |
| Fix code style | `./vendor/bin/pint` |
| Build frontend | `npm run build` |

### Non-obvious caveats

- The `.env` file is **not** committed. If missing, copy from `.env.example` and run `php artisan key:generate`.
- The SQLite database file at `database/database.sqlite` must exist before running migrations. Create it with `touch database/database.sqlite` if absent.
- `composer run dev` starts 4 concurrent processes (PHP server, queue worker, log tailer, Vite). It's the recommended way to run the full dev environment.
- Tests use in-memory SQLite (`:memory:`) configured in `phpunit.xml`, so no database file is needed for tests.
- The `composer run setup` script is idempotent and handles the full first-time setup (deps, `.env`, key generation, migrations, frontend build).
