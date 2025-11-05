<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://i0.wp.com/travpromobile.com/wp-content/uploads/TravPROMobile2024_logo.png?fit=2595%2C795&quality=80&ssl=1" width="400" alt="Laravel Logo"></a></p>

# DevToolDashboardV2 — README


---

## Table of Contents

1. [Project overview](#project-overview)
2. [Features](#features)
3. [Tech stack](#tech-stack)
4. [Prerequisites](#prerequisites)
5. [Getting started — step by step (development)](#getting-started---step-by-step-development)
   * Backend (Laravel)
   * Frontend (React)
   * Running both together
6. [Environment variables (examples)](#environment-variables-examples)
7. [Database: migrations & seeding](#database-migrations--seeding)
8. [Authentication & API setup (recommended)](#authentication--api-setup-recommended)
9. [Building for production & deployment tips](#building-for-production--deployment-tips)
10. [Queues, scheduling & background workers](#queues-scheduling--background-workers)
11. [Testing](#testing)
12. [Troubleshooting & common fixes](#troubleshooting--common-fixes)
13. [Best practices & performance tips](#best-practices--performance-tips)
14. [Contributing](#contributing)
15. [License & contact](#license--contact)

---

# Project overview

DevTool Dashboard is an SPA where the React frontend consumes a REST/JSON API served by Laravel. It is designed for developer workflows: logs, metrics, task runners, environment inspectors, lightweight database admin, and integrations with third-party services (e.g., Sentry, Prometheus, Git providers).

This README focuses on practical, step-by-step instructions to run, test and deploy the project — with proven tips to avoid common pitfalls.

---

# Features

* React SPA dashboard (components, routing, auth flows, reusable UI)
* Laravel API with resource controllers
* SPA authentication using Laravel Sanctum (cookie-based)
* Real-time-ish updates via polling or WebSockets (Laravel Echo option)
* DB migrations & seeders for demo/admin data
* Built-in dev utilities: job queue monitor, scheduled tasks, log viewer
* Dockerfile + `docker-compose` (optional) for reproducible environments

---

# Tech stack

* Backend: PHP 8.1+ (Laravel 9/10 recommended)
* Frontend: React (Vite or Create React App — this project uses **Vite**)
* DB: MySQL / PostgreSQL / SQLite (configurable)
* Optional: Redis (caching & queues), Supervisor (queue workers), Nginx
* Tooling: Composer, Node.js / npm (or yarn), Docker (optional)

---

# Prerequisites

Install on your machine (or CI environment):

* PHP 8.1+ (with extensions: pdo, pdo_mysql, mbstring, openssl, json, tokenizer, xml)
* Composer
* Node.js 18+ and npm (or yarn)
* MySQL / PostgreSQL / SQLite
* Redis (recommended for caching/queues)
* Git
* (Optional) Docker & Docker Compose

---

# Getting started — step by step (development)

> Follow these sections in order. Commands assume Unix-like shell (macOS, Linux). Adapt for Windows or Docker.

## 1) Clone the repo

```bash
git clone git@github.com:your-org/devtool-dashboard.git
cd devtool-dashboard
```

## 2) Backend (Laravel) — install & configure

1. Install PHP dependencies:

   ```bash
   cd backend             # or wherever the Laravel app lives
   composer install
   ```
2. Copy env:

   ```bash
   cp .env.example .env
   ```
3. Generate app key:

   ```bash
   php artisan key:generate
   ```
4. Set up environment variables in `.env` (see the **Environment variables** section below).
5. Install frontend build tooling if integrated (Laravel side):

   * If project uses Laravel Vite: ensure `npm install` in project root (frontend + backend might be combined).
6. Create the database and run migrations (see next section).

## 3) Frontend (React) — install & configure

1. Change to frontend directory (if separate):

   ```bash
   cd ../frontend   # or ./resources/js if embedded in Laravel
   npm install
   ```
2. Copy frontend env if exists:

   ```bash
   cp .env.example .env
   ```

   * Example keys: `VITE_API_BASE_URL=http://localhost:8000/api`
3. Start the frontend dev server:

   ```bash
   npm run dev
   ```

   * This runs Vite and serves the SPA at `http://localhost:5173` (default). If using CRA, it'll be `3000`.

## 4) Running the Laravel app (dev)

In the Laravel folder:

```bash
php artisan migrate --seed    # creates DB schema and seeds demo data
php artisan serve --host=127.0.0.1 --port=8000
```

Your API will be at `http://127.0.0.1:8000`.

## 5) Running both together (recommended setup)

* If using Sanctum cookie-based auth, run frontend on a different port and configure CORS + cookie domains:

  * Frontend: `http://localhost:5173`
  * Backend: `http://127.0.0.1:8000`
* Ensure `.env` for Laravel has:

  ```
  SANCTUM_STATEFUL_DOMAINS=localhost:5173
  SESSION_DOMAIN=localhost
  ```

---

# Environment variables (examples)

## Laravel `.env` (important values)

```env
APP_NAME="DevToolDashboardV2"
APP_ENV=local
APP_KEY=base64:...
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=devtool_db
DB_USERNAME=root
DB_PASSWORD=secret

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=redis
SESSION_DRIVER=cookie
SESSION_DOMAIN=localhost

SANCTUM_STATEFUL_DOMAINS=localhost:5173
CORS_ALLOWED_ORIGINS=http://localhost:5173

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## Frontend `.env` (Vite)

```
VITE_API_BASE_URL=http://127.0.0.1:8000/api
VITE_AUTH_BASE_URL=http://127.0.0.1:8000
```

---

# Database: migrations & seeding

1. Run migrations:

   ```bash
   php artisan migrate
   ```
2. Run seeders (creates demo/admin user):

   ```bash
   php artisan db:seed
   ```
3. If you need to reset:

   ```bash
   php artisan migrate:fresh --seed
   ```

**Tip:** Use `--env=testing` and an in-memory DB for fast automated tests.

---

# Authentication & API setup (recommended)

This project recommends **Laravel Sanctum** for SPA authentication.

* Backend:

  * Install and configure Sanctum.
  * Ensure `config/cors.php` allows your frontend origin.
  * Expose endpoints:

    * `POST /sanctum/csrf-cookie` → to initialize cookie
    * `POST /login` → set cookie
    * `POST /logout`, `GET /user` etc.

* Frontend flow (React):

  1. Request `csrf-cookie`: `GET /sanctum/csrf-cookie` (with credentials).
  2. `POST /login` with credentials (fetch with `credentials: 'include'`).
  3. Subsequent API calls: include `credentials: 'include'` to send cookies.

**Important:** Use `credentials: 'include'` for fetch/axios and configure axios defaults:

```js
axios.defaults.withCredentials = true;
axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL;
```

---

# Building for production & deployment tips

## Build steps

1. Build frontend:

   ```bash
   # from frontend
   npm run build      # produces /dist (Vite) or /build
   ```

2. Serve static assets:

   * Option A (single host): Copy frontend `dist` into Laravel `public` or configure Laravel Vite to build into `public/build`.
   * Option B (separate hosts): Serve frontend from a CDN / static host (Netlify, Vercel), and point API calls to Laravel API.

3. Prepare Laravel for production:

   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. Set proper file permissions for `storage` & `bootstrap/cache`.

## Recommended deployment checklist

* Use HTTPS (TLS) — let’s encrypt or managed certs.
* Strong database credentials stored in env (not in repo).
* Use Redis for cache & queues; configure `CACHE_DRIVER=redis`.
* Run queue workers via Supervisor or systemd.
* Set up cron for `php artisan schedule:run` every minute.
* Monitor logs (Sentry / Logstash) and metrics (Prometheus/Grafana).
* Use CI/CD: run tests, lint, build, and deploy artifacts automatically.

---

# Queues, scheduling & background workers

* Queue setup:

  * Use Redis for `QUEUE_CONNECTION=redis`.
  * Start a worker:

    ```
    php artisan queue:work --sleep=3 --tries=3
    ```
  * In production, run with Supervisor config to keep it alive.

* Scheduler:

  * Add `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1` to cron.

---

# Testing

* Backend:

  ```bash
  php artisan test
  ```
* Frontend:

  * If using Jest / Vitest:

    ```bash
    npm run test
    ```
* E2E:

  * Use Cypress or Playwright; a sample script might run migrations, seed, run backend & frontend, then run tests.

---

# Troubleshooting & common fixes

* **CORS / 401 issues**

  * Ensure `SANCTUM_STATEFUL_DOMAINS` contains your frontend host and `SESSION_DOMAIN` is correct.
  * Call `/sanctum/csrf-cookie` before login.

* **`Access-Control-Allow-Origin` errors**

  * Update `config/cors.php` to include front-end origin(s). Clear config cache after change:

    ```bash
    php artisan config:clear
    ```

* **Environment variables not applied**

  * Run `php artisan config:cache` but remember to clear cache when updating `.env` in development: `php artisan config:clear`.

* **Migrations failing**

  * Check DB credentials, host, and that the DB user has privileges.
  * For MySQL socket issues, verify `DB_HOST` and `DB_SOCKET`.

* **Assets 404 in production**

  * Make sure frontend build files are placed where Laravel expects them (or configure proper public path).

---

# Best practices & performance tips

* Use query caching or Redis for heavy-read endpoints.
* Paginate API responses and support cursor-based pagination for large datasets.
* Use eager loading (`with()`) in Eloquent to prevent N+1 queries.
* Serve static assets (JS/CSS) via CDN.
* Leverage HTTP caching headers, and conditional requests (ETag/Last-Modified) where appropriate.
* Keep secrets out of the repo. Use environment vaults (AWS Secrets Manager, GitHub Secrets, Vault).
* Add rate limiting on sensitive endpoints.

---

# Contributing

1. Fork the repo & create a feature branch.
2. Follow code style:

   * PHP: PSR-12
   * JS: Prettier + ESLint rules present in the repo
3. Run tests locally.
4. Open a PR with a clear description and screenshots if UI changes.
5. Add docs for new env vars and migrations.

---

# Useful commands cheat sheet

### Backend

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
php artisan tinker
php artisan config:cache
php artisan route:cache
php artisan queue:work
```

### Frontend

```bash
npm install
npm run dev        # dev server
npm run build      # production build
npm run test
```

---

#  admin credentials (demo)

> After seeding the DB, you can log in with:

* Email: `testuser@example.com`
* Password: `password`
  

---

# License & contact

* License: TravPRO mobile 2025.



