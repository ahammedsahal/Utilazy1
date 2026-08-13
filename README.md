# Utilazy — Smart Tools for Everyday Tasks

A premium, fast, responsive online utility toolbox called **Utilazy** (utilazy.com) featuring conversion, PDF document properties edit/compress, text formatting, URL shortening/redirection with analytics, and custom giveaways pickers. Fully styled under the premium, database-driven **"Ember & Ink"** visual design identity.

## 🛠️ Tech Stack & Architecture
- **Backend:** PHP 8.2+ with PDO database connection.
- **Frontend:** HTML5, CSS3, ES6 Vanilla JS, styled with Tailwind CSS grid structures and color-variable design tokens.
- **Database:** MySQL / MariaDB (dual-driver ready).
- **Integrations:** Meta Graph OAuth APIs, Google Sign-In, Sign in with Apple, Lemon Squeezy Payments, Cloudflare Turnstile.
- **Security:** CSRF protection, HttpOnly/Secure session tracking, rate limits, Turnstile validations, prepared SQL statements.

## 📁 Repository Directory Structure
```text
utilazy/
├── public/
│   ├── index.php
│   ├── assets/
│   ├── uploads/
│   └── robots.txt
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Services/
│   ├── Middleware/
│   ├── Helpers/
│   └── Tools/
├── config/
├── database/
│   ├── schema.sql
│   └── seed.sql
├── resources/
│   └── design-tokens/
├── routes/
├── storage/
├── views/
├── tests/
├── cron/
└── docs/
```

## 🚀 Getting Started Locally
1. Run `composer install` (or verify PSR-4 autoloader: `composer dump-autoload`).
2. Copy `.env.example` to `.env` and fill out local database credentials.
3. Import the schemas:
   ```bash
   mysql -u root -p utilazy < database/schema.sql
   mysql -u root -p utilazy < database/seed.sql
   ```
4. Start a local server:
   ```bash
   php -S localhost:8000 -t public
   ```
5. Navigate to `http://localhost:8000`. Login using Super Admin: `admin@utilazy.com` / `admin123`.

## 📖 Deployment & Integration Guides
All guides are located in the `docs/` folder:
- **cPanel Deployment:** `docs/DEPLOYMENT_CPANEL.md`
- **Cloudflare setup:** `docs/CLOUDFLARE_SETUP.md`
- **Lemon Squeezy integration:** `docs/LEMON_SQUEEZY_SETUP.md`
- **Google OAuth setup:** `docs/GOOGLE_OAUTH_SETUP.md`
- **Apple Sign-In setup:** `docs/APPLE_LOGIN_SETUP.md`
- **Adding new tools:** `docs/ADDING_NEW_TOOLS.md`
