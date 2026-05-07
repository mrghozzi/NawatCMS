# Agents Reference: Nawat CMS

## Purpose
This file is the execution reference for agents working on Nawat CMS. It summarizes the active project rules, architecture, implementation conventions, and current foundation state.

## Communication Protocol
- Codebase content must use English for naming, variables, database identifiers, comments, classes, files, and technical copy.
- Conversation with the user must be in Arabic.
- Final outputs should explain changes in Arabic while keeping code and implementation terms in English where needed.

## Project Identity
- Product name: Nawat CMS.
- Core framework: Laravel 12 inside `nw-includes`.
- Architecture goal: WordPress-style root structure optimized for shared hosting.
- License target: MIT.
- Design system: SuperDesign-based admin UI and installer UI.

## Directory Rules
- `index.php`: root front controller that bootstraps Laravel from `nw-includes`.
- `.htaccess`: root rewrite/security rules for Apache shared hosting.
- `nw-admin/`: admin views and public admin assets.
  - `nw-admin/views/`: Blade views registered under the `admin` namespace.
  - `nw-admin/assets/`: CSS, JavaScript, and future static admin assets.
- `nw-content/`: user-controlled content.
  - `themes/`: front-end themes.
  - `plugins/`: modular extensions.
  - `uploads/`: media uploads; files are ignored by git except placeholders.
  - `languages/`: future i18n files.
- `nw-includes/`: Laravel engine.
  - Contains `app/`, `bootstrap/`, `config/`, `database/`, `routes/`, `storage/`, `vendor/`.

## Current Foundation State
- Laravel 12 has been initialized in `nw-includes`.
- Root `index.php` boots `nw-includes/vendor/autoload.php` and `nw-includes/bootstrap/app.php`.
- Laravel `base_path()` remains `nw-includes`.
- Laravel `public_path()` is configured to the project root so `/nw-admin/assets/...` and `/nw-content/...` resolve from the shared-hosting web root.
- `/install` is registered through `nw-includes/routes/install.php`.
- The first installer view exists at `nw-admin/views/install/index.blade.php`.
- Installer styling exists at `nw-admin/assets/css/install.css`.
- SuperDesign context exists in `.superdesign/`.

## Service Providers
- `AppServiceProvider`
  - Registers `NawatPathService`.
- `AdminServiceProvider`
  - Registers the `admin` view namespace.
  - Shares `AdminAssetService` with admin views as `$adminAssets`.
- `ThemeServiceProvider`
  - Binds `ThemeRepositoryInterface` to `FilesystemThemeRepository`.
  - Registers `ThemeService`.

## Implementation Standards
- Use PHP 8.2+ strict types.
- Follow PSR-12 and Laravel Pint formatting.
- Prefer Service-Repository Pattern for domain behavior that is not simple controller glue.
- Keep admin UI assets inside `nw-admin/assets`.
- Keep admin views inside `nw-admin/views`.
- Keep Laravel application code inside `nw-includes/app`.
- Do not move Composer dependencies outside `nw-includes/vendor`.
- Do not commit `.env`, generated caches, database SQLite files, `vendor`, or uploaded media.
- Preserve unrelated user changes in the working tree.

## UI Standards
- Use SuperDesign context before substantial UI work.
- Admin and installer UI should be functional, restrained, responsive, and operational rather than marketing-heavy.
- Use panels, tables, navigation/stepper patterns, buttons, and dense readable layouts.
- Keep cards and buttons at 8px border radius or less unless a future design system explicitly changes this.
- Avoid decorative gradients, orbs, and unrelated landing-page sections.
- Ensure bilingual visible copy is intentional; code identifiers remain English.

## Verification Commands
Run commands from `nw-includes` with XAMPP PHP on PATH:

```powershell
$env:PATH='E:\xampp\php;' + $env:PATH
php artisan route:list --path=install
php artisan test
./vendor/bin/pint --test
```

Apache smoke checks from the project root:

```powershell
Invoke-WebRequest -UseBasicParsing -Uri http://localhost/NawatCMS/install
Invoke-WebRequest -UseBasicParsing -Uri http://localhost/NawatCMS/nw-includes/composer.json
```

Expected results:
- `/install` returns `200`.
- Direct access to `nw-includes/composer.json` returns `403`.

## Canonical Source
`AGENTS_INSTRUCTIONS.md` remains the original blueprint. This `Agents.md` file is the practical execution reference and should be updated when implementation rules or foundation decisions change.
