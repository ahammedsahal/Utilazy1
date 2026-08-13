# cPanel A to Z Deployment Guide

## 1. Prerequisites
- cPanel Host running PHP 8.2+ with `pdo_mysql`, `curl`, `mbstring`, `zip`, `openssl`, and `gd` modules enabled.
- MySQL/MariaDB database access.
- SSL Certificate activated.

## 2. Setup Database
1. Inside cPanel, navigate to **MySQL Database Wizard**.
2. Create database `utilazy`.
3. Create database user and assign to database with all privileges.
4. Navigate to **phpMyAdmin**, select `utilazy` database, click **Import**, and choose `database/schema.sql`, then import `database/seed.sql`.

## 3. Upload Application
1. Pack repository into a ZIP file (excluding `.env`, `node_modules/`, `vendor/` to save space).
2. Inside cPanel, navigate to **File Manager**, select `public_html`, and upload the ZIP.
3. Extract files directly into `public_html`.
4. Verify root `.htaccess` and `public/.htaccess` files exist.

## 4. Configure Production Environment
1. Create a `.env` file in `public_html` matching `.env.example` settings.
2. Fill database credentials:
   ```ini
   DB_HOST=localhost
   DB_NAME=utilazy
   DB_USER=your_db_user
   DB_PASS=your_db_password
   ```
3. Generate secure long secrets for `APP_SECRET` and `SESSION_SECRET`.
4. Set `APP_ENV=production` and `APP_DEBUG=false`.

## 5. Enable HTTPS Redirect
Navigate to **Domains** in cPanel and enable **Force HTTPS Redirect** to ensure secure cookies only.
