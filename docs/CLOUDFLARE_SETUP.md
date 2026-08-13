# Cloudflare Setup & Security hardening Guide

## 1. Domain Activation
1. Sign up for a free account at Cloudflare.
2. Click **Add Site** and input `utilazy.com`.
3. Change domain name servers at your registrar (e.g. Namecheap, GoDaddy) to point to the designated Cloudflare NS entries.

## 2. SSL/TLS Settings
1. Navigate to **SSL/TLS -> Overview**.
2. Select **Full (Strict)** encryption to ensure end-to-end HTTPS protection.

## 3. Cloudflare Turnstile Configuration
1. Navigate to **Turnstile -> Add Site**.
2. Input Site Name and choose **Managed challenge**.
3. Retrieve **Site Key** and **Secret Key**.
4. Update `.env` values or edit Site Settings inside Admin Panel.

## 4. Caching Rules
- Standard pages and tools can be cached.
- **NEVER cache** endpoints: `/dashboard`, `/admin`, `/api/payments/webhook`, `/profile`, `/token-history` to prevent session leaks. Create a cache rule to bypass caching on these paths.
