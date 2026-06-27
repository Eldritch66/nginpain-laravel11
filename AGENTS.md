# AGENTS.md

## Stack
- Laravel 11, PHP ^8.2, MySQL (env) / SQLite (default), Vite + Tailwind CSS 3.x, PHPUnit 10

## Commands

| Action | Command |
|---|---|
| Dev server | `php artisan serve` + `npm run dev` (Vite) |
| Build assets | `npm run build` |
| Run all tests | `phpunit` or `./vendor/bin/phpunit` |
| Run single test | `phpunit --filter test_name` |
| Format code | `./vendor/bin/pint` |
| Migrate | `php artisan migrate` |
| Fresh migrate + seed | `php artisan migrate:fresh --seed` |
| Tinker (REPL) | `php artisan tinker` |

## Architecture
- **Routes**: All routes in `routes/web.php` use closures (no controllers yet). No API routes.
- **Views**: Blade templates in `resources/views/` (beranda, tentang, and account/*).
- **Frontend**: `resources/css/app.css` (Tailwind directives), `resources/js/app.js` (referenced by Vite but file does not exist).
- **Auth**: Default Laravel `User` model + migration, but no auth scaffolding or login routes.
- **No JS framework** (no Vue/React/Alpine) — just Vite + Tailwind.

## Testing
- Feature tests extend `Tests\TestCase` (Laravel HTTP test case with `$this->get()` etc.).
- Unit tests extend `PHPUnit\Framework\TestCase` directly (no Laravel app booted).
- DB defaults to SQLite in testing (`phpunit.xml`), migrations available.
- `RefreshDatabase` trait is not used by default.

## Code style
- PSR-4: `App\` → `app/`, `Database\Factories\` → `database/factories/`, `Tests\` → `tests/`
- `.editorconfig`: 4-space indent, LF line endings, UTF-8.
- Laravel Pint (`laravel/pint`) is the formatter — run `./vendor/bin/pint` before committing.

## DB & env
- Default config (`config/database.php`) uses SQLite at `database/database.sqlite`.
- Active `.env` overrides to MySQL (root, no password, `laravel` database).
- Session, cache, and queue all use `database` driver.
