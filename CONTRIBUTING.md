# Contributing to Hospital Management

Thank you for your interest in improving **Hospital Management**!
This guide explains how to propose changes, file bugs, and submit pull requests in a way that keeps the project healthy and easy to maintain.

> Tech stack: **Laravel (PHP)** backend, **Vue 3** frontend, **Vite** build system, role-based access, typical Laravel app structure.
> Primary repo: `esteham/hospital-management`.

---

## Code of Conduct

By participating, you agree to uphold our values of respect and inclusion.
If the repository doesn’t yet include one, please propose a `CODE_OF_CONDUCT.md` (Contributor Covenant).

---

## How can I contribute?

### 1) Report bugs / request features

* **Search first** to avoid duplicates.
* Open a **GitHub Issue** with:

  * Clear title
  * Steps to reproduce (for bugs)
  * Expected vs actual behavior
  * Screenshots or logs (if relevant)
  * Environment (OS, PHP/Node versions, browser, DB)

### 2) Submit improvements

* Small changes (docs/typos) → open a PR directly.
* Bigger changes → open an Issue first to discuss the scope/design.

---

## Development setup

### Prerequisites

* **PHP** ≥ 8.2, **Composer**
* **Node.js** ≥ 20 (npm / pnpm / yarn)
* **MySQL/MariaDB** (or **SQLite** for quick local dev)
* **Git**

### Clone & install

```bash
git clone https://github.com/esteham/hospital-management.git
cd hospital-management

# PHP deps
composer install

# JS deps
npm install    # or: pnpm install / yarn
```

### Environment

```bash
cp .env.example .env
php artisan key:generate
```

---
## 📦 Environment (.env) quick sample

```dotenv
APP_NAME="Hospital Management"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# DB
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hospital_mgmt
DB_USERNAME=root
DB_PASSWORD=

# Vite (optional for HMR over network)
VITE_DEV_SERVER_HOST=localhost
```

---

* Set DB credentials in `.env` (or use SQLite: set `DB_CONNECTION=sqlite` and create `database/database.sqlite`).
* (Optional) Configure mail, cache, queues as needed.

### Migrate & seed

```bash
php artisan migrate --seed
```

> Seeds should create at least: roles/permissions, an admin user, and demo data when available.

### Run locally

```bash
# Backend
php artisan serve

# Frontend (Vite dev server with HMR)
npm run dev     # or: pnpm dev / yarn dev
```

---

## Branching & workflow

* Base branch: **`main`**
* Create a feature branch from `main`:

  * `feat/<short-summary>` (new feature)
  * `fix/<short-summary>` (bug fix)
  * `chore/<short-summary>` (build/tools/infra)
  * `docs/<short-summary>` (documentation only)
* Keep PRs focused and small; large PRs should be split logically.

---

## Commit style

Follow **Conventional Commits**:

```
feat(auth): add OTP login
fix(appointments): correct overlapping slot check
docs(readme): add setup steps
chore(ci): run tests on PHP 8.3
```

---

## Coding standards

### PHP (Laravel)

* Style: **PSR-12**, format with **Laravel Pint**

  ```bash
  ./vendor/bin/pint
  ```
* Static analysis (recommended): **PHPStan** level 6–8 (if configured)

  ```bash
  ./vendor/bin/phpstan analyse
  ```
* Architecture:

  * Prefer **Form Requests**, **Policies**, **Resource classes**
  * Controllers stay thin; move business logic to **Services/Actions**
  * Use **Eloquent Scopes** & **Repositories** where it improves clarity
  * Keep env-specific logic out of code (use `.env`)

### Vue 3 + Vite

* Composition API (script setup preferred)
* Lint & format:

  ```bash
  npm run lint
  npm run format
  ```

  (ESLint + Prettier recommended; keep imports ordered)
* State: Pinia (if used) over ad-hoc event buses
* Components:

  * Small, focused, props/events typed (TS if enabled)
  * Avoid global side effects; keep UI state local or in store
  * Reusable UI primitives live under `components/` with docs in comments

### Security & data

* Validate all input with **Form Requests**
* Authorize sensitive actions with **Policies/Gates**
* Never commit secrets; use `.env` and `.env.example`
* Escape user content in Blade/Vue; sanitize risky HTML

---

## Testing

* Prefer **feature tests** for endpoints & flows; **unit tests** for pure logic
* Suggested tools: **PHPUnit** or **Pest**
* Frontend: add tests if a framework is present (Vitest) for critical logic

Common commands:

```bash
php artisan test           # or: vendor/bin/pest
npm run test               # if frontend tests are configured
```

Target: meaningful coverage of core modules (auth, roles, appointments, billing).

---

## Database & migrations

* Each schema change requires a **migration**.
* Seed minimal reference data; keep heavy demo data optional behind a flag/command.
* Avoid destructive migrations in PRs without a clear migration path.

---

## Performance

* Use **eager loading** to avoid N+1 queries.
* Cache expensive reads where appropriate.
* Keep asset bundles lean; code-split heavy routes.

---

## Accessibility & UX

* Ensure keyboard navigation and sensible focus order.
* Use semantic HTML; provide labels/alt text.
* Maintain consistent spacing, typography, and states (hover/active/disabled).

---

## Pull request checklist

Before opening a PR, please ensure:

* [ ] Issue linked (if applicable) with context & screenshots
* [ ] Branch is up-to-date with `main` and rebased (no merge commits)
* [ ] Linted & formatted (PHP + JS)
* [ ] All tests pass locally (`php artisan test` and `npm run test` if present)
* [ ] Added/updated tests for new behavior
* [ ] Updated docs (`README`, config comments, API docs) if needed
* [ ] No secrets or environment-specific values committed

**PR Template (paste in description):**

```markdown
## Summary
Short explanation of what and why.

## Changes
- …

## Screenshots / Loom
(If UI changes)

## How to test
1. …
2. …
3. …

## Related issues
Closes #123

## Checklist
- [ ] Lint/format pass
- [ ] Tests added/updated
- [ ] Migrations safe (if any)
- [ ] Docs updated (if needed)
```

---

## Release notes & changelog

* Use clear, user-facing PR titles.
* Tag notable changes with `feat:` / `fix:` for auto-generated changelog tools.

---

## Security disclosures

Please **do not** open public issues for vulnerabilities.
Email the maintainer or open a private security report (if Security tab is enabled). A `SECURITY.md` file is welcome.

---

## Licensing

By submitting a contribution, you agree it will be licensed under the repository’s license. If a CLA becomes necessary, contributors will be notified in the PR.

---

## Getting help

* Open a GitHub Issue with the **question** label.
* Share error logs, steps, and environment details.

---

**Thank you for contributing!** Your time and effort help improve patient, doctor, appointment, billing, and inventory workflows for everyone.