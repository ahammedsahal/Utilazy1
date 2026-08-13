-- Utilazy Database Schema

CREATE DATABASE IF NOT EXISTS `utilazy` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `utilazy`;

-- 1. categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `icon` VARCHAR(255) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `google_id` VARCHAR(255) NULL DEFAULT NULL,
  `apple_id` VARCHAR(255) NULL DEFAULT NULL,
  `unlimited_tokens` TINYINT NOT NULL DEFAULT 0,
  `status` VARCHAR(50) NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. user_sessions
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `session_identifier` VARCHAR(255) NOT NULL UNIQUE,
  `ip_hash` VARCHAR(255) NOT NULL,
  `user_agent` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. tools
CREATE TABLE IF NOT EXISTS `tools` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `category_id` INT NOT NULL,
  `icon` VARCHAR(255) NOT NULL,
  `access_type` VARCHAR(50) NOT NULL DEFAULT 'free',
  `token_cost` INT NOT NULL DEFAULT 0,
  `free_limit` INT NOT NULL DEFAULT 0,
  `enabled` TINYINT NOT NULL DEFAULT 1,
  `featured` TINYINT NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. token_transactions
CREATE TABLE IF NOT EXISTS `token_transactions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `amount` INT NOT NULL,
  `transaction_type` VARCHAR(50) NOT NULL,
  `reference_type` VARCHAR(100) NULL DEFAULT NULL,
  `reference_id` INT NULL DEFAULT NULL,
  `description` TEXT NOT NULL,
  `balance_after` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. token_packages
CREATE TABLE IF NOT EXISTS `token_packages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `tokens` INT NOT NULL,
  `bonus_tokens` INT NOT NULL DEFAULT 0,
  `price` DECIMAL(10,2) NOT NULL,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'USD',
  `lemon_product_id` VARCHAR(255) NULL DEFAULT NULL,
  `lemon_variant_id` VARCHAR(255) NULL DEFAULT NULL,
  `active` TINYINT NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `provider` VARCHAR(50) NOT NULL,
  `provider_order_id` VARCHAR(255) NOT NULL,
  `provider_transaction_id` VARCHAR(255) NOT NULL UNIQUE,
  `amount` DECIMAL(10,2) NOT NULL,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'USD',
  `status` VARCHAR(50) NOT NULL,
  `metadata` JSON NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. coupons
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `type` VARCHAR(50) NOT NULL,
  `value` DECIMAL(10,2) NOT NULL,
  `bonus_tokens` INT NOT NULL DEFAULT 0,
  `usage_limit` INT NULL DEFAULT NULL,
  `per_user_limit` INT NULL DEFAULT NULL,
  `minimum_purchase` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `starts_at` TIMESTAMP NULL DEFAULT NULL,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `active` TINYINT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. coupon_redemptions
CREATE TABLE IF NOT EXISTS `coupon_redemptions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `coupon_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `payment_id` INT NULL DEFAULT NULL,
  `redeemed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. shortened_urls
CREATE TABLE IF NOT EXISTS `shortened_urls` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL DEFAULT NULL,
  `original_url` TEXT NOT NULL,
  `short_code` VARCHAR(50) NOT NULL UNIQUE,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `active` TINYINT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. custom_urls
CREATE TABLE IF NOT EXISTS `custom_urls` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL DEFAULT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `destination_url` TEXT NOT NULL,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `active` TINYINT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. url_clicks
CREATE TABLE IF NOT EXISTS `url_clicks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `short_url_id` INT NULL DEFAULT NULL,
  `custom_url_id` INT NULL DEFAULT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country` VARCHAR(100) NOT NULL DEFAULT 'Unknown',
  `device` VARCHAR(100) NOT NULL DEFAULT 'Unknown',
  `browser` VARCHAR(100) NOT NULL DEFAULT 'Unknown',
  `referrer` VARCHAR(255) NOT NULL DEFAULT 'Direct',
  FOREIGN KEY (`short_url_id`) REFERENCES `shortened_urls` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`custom_url_id`) REFERENCES `custom_urls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. invoices
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `invoice_number` VARCHAR(100) NOT NULL UNIQUE,
  `invoice_data` JSON NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. tool_usage
CREATE TABLE IF NOT EXISTS `tool_usage` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `tool_id` INT NOT NULL,
  `token_cost` INT NOT NULL DEFAULT 0,
  `metadata` JSON NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. monthly_claims
CREATE TABLE IF NOT EXISTS `monthly_claims` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `month` VARCHAR(7) NOT NULL,
  `tokens` INT NOT NULL,
  `claimed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `user_month_unique` (`user_id`, `month`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. login_attempts
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL DEFAULT NULL,
  `email_hash` VARCHAR(255) NOT NULL,
  `success` TINYINT NOT NULL,
  `ip_hash` VARCHAR(255) NOT NULL,
  `user_agent` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. audit_logs
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT NOT NULL,
  `action` VARCHAR(255) NOT NULL,
  `entity_type` VARCHAR(100) NOT NULL,
  `entity_id` INT NULL DEFAULT NULL,
  `metadata` JSON NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. site_settings
CREATE TABLE IF NOT EXISTS `site_settings` (
  `key` VARCHAR(255) NOT NULL PRIMARY KEY,
  `value` TEXT NULL,
  `type` VARCHAR(50) NOT NULL DEFAULT 'text',
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. code_injections
CREATE TABLE IF NOT EXISTS `code_injections` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `location` VARCHAR(50) NOT NULL,
  `code` TEXT NOT NULL,
  `enabled` TINYINT NOT NULL DEFAULT 0,
  `updated_by` INT NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. design_tokens
CREATE TABLE IF NOT EXISTS `design_tokens` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `token_key` VARCHAR(100) NOT NULL,
  `token_value` VARCHAR(255) NOT NULL,
  `scope` VARCHAR(50) NOT NULL DEFAULT 'light',
  `updated_by` INT NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `key_scope_unique` (`token_key`, `scope`),
  FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. ig_giveaway_draws
CREATE TABLE IF NOT EXISTS `ig_giveaway_draws` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `instagram_post_id` VARCHAR(255) NOT NULL,
  `winner_count` INT NOT NULL DEFAULT 1,
  `keyword_rule` JSON NOT NULL,
  `min_mentions` INT NOT NULL DEFAULT 0,
  `one_entry_per_user` TINYINT NOT NULL DEFAULT 1,
  `excluded_usernames` JSON NOT NULL,
  `date_range_start` TIMESTAMP NULL DEFAULT NULL,
  `date_range_end` TIMESTAMP NULL DEFAULT NULL,
  `eligible_count` INT NOT NULL DEFAULT 0,
  `disqualified_count` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. ig_giveaway_entries
CREATE TABLE IF NOT EXISTS `ig_giveaway_entries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `draw_id` INT NOT NULL,
  `instagram_username` VARCHAR(255) NOT NULL,
  `comment_text` TEXT NOT NULL,
  `mention_count` INT NOT NULL DEFAULT 0,
  `matched_keywords` JSON NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `disquality_reason` VARCHAR(255) NULL DEFAULT NULL,
  `is_winner` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`draw_id`) REFERENCES `ig_giveaway_draws` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;