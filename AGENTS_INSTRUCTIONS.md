# Technical Blueprint: Nawat CMS (WordPress-Style Architecture)

## 1. Project Identity & Vision
- **Name:** Nawat CMS
- **Framework:** Laravel 12 (Core Engine)
- **Design System:** Must be based on **/superdesign** (Principles, Components, and Layouts).
- **Goal:** A modular CMS with a directory structure inspired by WordPress, optimized for shared hosting.
- **License:** MIT

## 2. ⚠️ Language & Communication Protocol
- **Codebase:** All code (Naming, Variables, Database, Comments) must be in **English**.
- **Interaction:** The Agent must discuss, explain, and consult with the User in **Arabic**.
- **Output:** English Code / Arabic Conversation.

## 3. Directory Structure (The "Nawat" Hierarchy)
The project must be restructured to run from the root, mimicking the WordPress layout:

### A. Root Directory
- `index.php`: Main entry point (Bootstraps Laravel).
- `.gitignore`: Configured to exclude sensitive and dependency files.
- `/nw-admin/`: Admin Dashboard (Assets & Views based on **/superdesign**).
- `/nw-content/`: User-controlled data.
    - `/themes/`: Front-end styles (Must support **/superdesign** integration).
    - `/plugins/`: Modular extensions.
    - `/uploads/`: Media storage (Excluded from Git).
    - `/languages/`: i18n files.
- `/nw-includes/`: The "Engine" folder (Laravel Core).
    - Contains: `app/`, `bootstrap/`, `config/`, `database/`, `routes/`, `vendor/`, `storage/`.

## 4. UI/UX Design Requirements (/superdesign)
- **Admin UI:** All administrative interfaces in `/nw-admin/` must strictly adhere to the **/superdesign** framework/design-system.
- **Components:** Use SuperDesign's buttons, cards, tables, and navigation patterns.
- **Responsive:** Mobile-first design following the typography and spacing of SuperDesign.

## 5. Architectural Requirements
### 1. Decoupled Admin UI
- Use a dedicated View Namespace for admin: `view()->addNamespace('admin', base_path('nw-admin/views'))`.
- All admin assets (CSS/JS) must be stored in `/nw-admin/assets/`.

### 2. Path Mapping & Hosting
- Modify `index.php` in the root to point to `nw-includes/bootstrap/app.php`.
- Reconfigure `public_path()` and `base_path()` to ensure assets and storage are linked correctly within the `nw-` structure.

### 3. Installer & Updater (/install)
- Graphical installer for server checks, `.env` setup, and DB migrations.
- UI must be styled using **/superdesign**.

### 4. Git Configuration (.gitignore)
- Create a `.gitignore` file in the root to exclude:
    - `.env` and sensitive credentials.
    - `/nw-includes/vendor/` and `/nw-includes/storage/` (except placeholders).
    - `/nw-content/uploads/` files.
    - Node modules and compiled assets (unless specified).

## 6. Immediate Task: Phase 1 (Foundation)
1. Initialize Laravel 12 inside `nw-includes`.
2. Map the root `index.php` and create the `.gitignore` file.
3. Setup the UI scaffolding in `/nw-admin/` using **/superdesign**.
4. Implement the `ThemeServiceProvider` and `AdminServiceProvider`.
5. Create a basic `/install` route with a SuperDesign-styled welcome page.

## 7. Coding Standards
- PSR-12, Strict Type Hinting (PHP 8.2+), and Service-Repository Pattern.