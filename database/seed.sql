-- Utilazy Database Seed Data
USE `utilazy`;

-- 1. Insert Default Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `sort_order`) VALUES
(1, 'Text Tools', 'text-tools', 'Format, convert, and manipulate text content.', '✏️', 1),
(2, 'PDF Tools', 'pdf-tools', 'Edit, compress, split, and merge PDF files.', '📄', 2),
(3, 'Image Tools', 'image-tools', 'Compress, resize, and convert images.', '🖼️', 3),
(4, 'Developer Tools', 'developer-tools', 'Format code, test regex, and encode formats.', '💻', 4),
(5, 'Productivity', 'productivity', 'Calculators, counters, and notepad utilities.', '⚡', 5),
(6, 'Conversion', 'conversion', 'Convert markdown, JSON, CSV, and XML formats.', '🔄', 6),
(7, 'Random Tools', 'random-tools', 'Generate random numbers, UUIDs, or spin a wheel.', '🎲', 7),
(8, 'URL Tools', 'url-tools', 'Shorten URLs and track custom redirects.', '🔗', 8),
(9, 'Business Tools', 'business-tools', 'Generate invoices, split bills, and manage giveaways.', '💼', 9);

-- 2. Insert Default Tools
INSERT INTO `tools` (`slug`, `name`, `description`, `category_id`, `icon`, `access_type`, `token_cost`, `free_limit`, `enabled`, `featured`, `sort_order`) VALUES
('instagram-comment-picker', 'Instagram Comment Picker', 'Run giveaways, filter comments, and pick winners using official Meta APIs.', 9, '📸', 'token_required', 15, 0, 1, 1, 1),
('invoice-generator', 'Invoice Generator', 'Generate professional invoices with custom logo, tax, and PDF downloads.', 9, '🧾', 'free', 0, 0, 1, 1, 2),
('url-shortener', 'URL Shortener', 'Shorten links, generate QR codes, and view detailed click metrics.', 8, '🔗', 'free', 0, 3, 1, 1, 1),
('custom-url-shortener', 'Custom URL Shortener', 'Create editable custom slug links like utilazy.com/go/custom.', 8, '🎯', 'free', 0, 1, 1, 1, 2),
('case-converter', 'Case Converter', 'Convert text between UPPERCASE, lowercase, CamelCase, snake_case, etc.', 1, '🔠', 'free', 0, 0, 1, 0, 1),
('word-to-html', 'Word to HTML', 'Convert formatted text or DOCX documents to clean HTML code.', 1, '🌐', 'free', 0, 0, 1, 0, 2),
('html-to-word', 'HTML to Word', 'Convert HTML templates or web copy to standard Word DOCX files.', 1, '📝', 'free', 0, 0, 1, 0, 3),
('word-to-whatsapp', 'Word to WhatsApp Formatting', 'Convert text into bold, italic, and bulleted WhatsApp message style.', 1, '💬', 'free', 0, 0, 1, 0, 4),
('character-word-counter', 'Character & Word Counter', 'Analyze word, sentence, line counts, and estimate reading/speaking times.', 1, '📊', 'free', 0, 0, 1, 0, 5),
('pdf-editor', 'PDF Editor', 'Draw, reorder pages, highlight, sign, and annotate PDF documents.', 2, '🖊️', 'token_required', 20, 0, 1, 1, 1),
('pdf-compressor', 'PDF Compressor', 'Compress PDF document file sizes client-side.', 2, '🗜️', 'token_required', 10, 0, 1, 0, 2),
('pdf-to-images', 'PDF to Images', 'Extract all pages of a PDF document as PNG images.', 2, '🖼️', 'free', 0, 0, 1, 0, 3),
('images-to-pdf', 'Images to PDF', 'Compile multiple images together into a single organized PDF file.', 2, '📦', 'free', 0, 0, 1, 0, 4),
('pdf-merge-split', 'PDF Merge & Split', 'Combine multiple PDF files or extract pages into a new PDF.', 2, '✂️', 'free', 0, 0, 1, 0, 5),
('image-compressor', 'Image Compressor', 'Compress JPG, PNG, and WebP images instantly to perfect sizes.', 3, '📉', 'free', 0, 0, 1, 1, 1),
('image-format-converter', 'Image Format Converter', 'Convert between JPG, PNG, and WebP format variants.', 3, '🔄', 'free', 0, 0, 1, 0, 2),
('image-resizer', 'Image Resizer', 'Resize custom dimensions of image files.', 3, '📐', 'free', 0, 0, 1, 0, 3),
('image-cropper', 'Image Cropper', 'Crop image dimensions with pre-defined aspect ratios.', 3, '✂️', 'free', 0, 0, 1, 0, 4),
('image-rotator', 'Image Rotator', 'Rotate and flip image orientation.', 3, '🔄', 'free', 0, 0, 1, 0, 5),
('metadata-viewer-remover', 'EXIF Metadata Remover', 'View and wipe privacy-sensitive EXIF geolocation tags from images.', 3, '🕵️', 'free', 0, 0, 1, 0, 6),
('encrypted-note', 'Encrypted Note', 'Create secure cipher messages or AES encrypted notes with passcode protection.', 4, '🔐', 'free', 0, 0, 1, 1, 1),
('json-formatter', 'JSON Formatter & Validator', 'Prettify, validate, minify, and check syntax of JSON structures.', 4, '🗄️', 'free', 0, 0, 1, 0, 2),
('sql-formatter', 'SQL Query Formatter', 'Clean and format messy SQL query statements for readability.', 4, '🛢️', 'free', 0, 0, 1, 0, 3),
('code-formatter', 'HTML/CSS/JS Beautifier', 'Format web languages with neat indentation.', 4, '🎨', 'free', 0, 0, 1, 0, 4),
('regex-tester', 'Regular Expression Tester', 'Test and debug regex matches against inputs.', 4, '🔍', 'free', 0, 0, 1, 0, 5),
('color-converter', 'Color Format Converter', 'Convert hex, rgb, and hsl color values.', 4, '🎨', 'free', 0, 0, 1, 0, 6),
('unit-converter-calculator', 'Unit Converter & Calculators', 'Convert length, temperature, speed, area, and perform scientific calculations.', 5, '🧮', 'free', 0, 0, 1, 1, 1),
('notepad', 'Simple Notepad', 'Write, draft, auto-save text client-side, and print easily.', 5, '🗒️', 'free', 0, 0, 1, 0, 2),
('business-days-calculator', 'Business Days Calculator', 'Calculate business days between dates excluding holidays.', 5, '📅', 'free', 0, 0, 1, 0, 3),
('timezone-converter', 'Timezone Converter & Clock', 'Compare local times across various international zones.', 5, '⏰', 'free', 0, 0, 1, 0, 4),
('markdown-html', 'Markdown ↔ HTML Converter', 'Seamlessly convert between rich Markdown formatting and raw HTML tags.', 6, '📝', 'free', 0, 0, 1, 0, 1),
('json-csv', 'JSON ↔ CSV Converter', 'Convert structured JSON schemas into tabular comma-separated values.', 6, '📊', 'free', 0, 0, 1, 0, 2),
('xml-formatter', 'XML Formatter', 'Prettify and clean structured XML documents.', 6, '📁', 'free', 0, 0, 1, 0, 3),
('xml-json', 'XML ↔ JSON Converter', 'Translate nested XML tags into JSON keys and values.', 6, '🔌', 'free', 0, 0, 1, 0, 4),
('yaml-json', 'YAML ↔ JSON Converter', 'Parse structured YAML configs into portable JSON arrays.', 6, '📜', 'free', 0, 0, 1, 0, 5),
('base64-encode-decode', 'Base64 Encoder & Decoder', 'Securely decode strings or files from portable base64 formatting.', 6, '🔏', 'free', 0, 0, 1, 0, 6),
('url-encode-decode', 'URL Encoder & Decoder', 'Sanitize and convert query parameters or URL-encoded path segments.', 6, '⛓️', 'free', 0, 0, 1, 0, 7),
('html-encode-decode', 'HTML Entity Encoder', 'Encode raw HTML tags into safe code entities to display on web.', 6, '🛡️', 'free', 0, 0, 1, 0, 8),
('unicode-ascii-hex-binary', 'Binary/Hex/Unicode Converter', 'Map text characters to binary, octal, hex codes, or ASCII values.', 6, '🔢', 'free', 0, 0, 1, 0, 9),
('timestamp-converter', 'Unix Timestamp Converter', 'Convert raw seconds epoch timestamps to human dates.', 6, '⏳', 'free', 0, 0, 1, 0, 10),
('spin-wheel', 'Random Spin Wheel', 'Add options to spin the wheel for giveaways, names, or choices.', 7, '🎡', 'free', 0, 0, 1, 1, 1),
('uuid-generator', 'Random UUID Generator', 'Generate secure UUID v4 tokens individually or in batches.', 7, '🔑', 'free', 0, 0, 1, 0, 2);

-- 3. Insert Default Token Packages
INSERT INTO `token_packages` (`id`, `name`, `tokens`, `bonus_tokens`, `price`, `currency`, `lemon_product_id`, `lemon_variant_id`, `active`, `sort_order`) VALUES
(1, 'Starter Pack', 500, 0, 4.99, 'USD', 'prod_starter', 'var_starter', 1, 1),
(2, 'Popular Pack', 1500, 100, 9.99, 'USD', 'prod_popular', 'var_popular', 1, 2),
(3, 'Pro Pack', 4000, 500, 19.99, 'USD', 'prod_pro', 'var_pro', 1, 3),
(4, 'Power Pack', 10000, 2000, 39.99, 'USD', 'prod_power', 'var_power', 1, 4);

-- 4. Insert Default Design Tokens (Ember & Ink Spec)
INSERT INTO `design_tokens` (`token_key`, `token_value`, `scope`) VALUES
('color-bg', '#FAF9F6', 'light'),
('color-surface', '#FFFFFF', 'light'),
('color-surface-alt', '#F1EFE9', 'light'),
('color-border', '#E7E3DA', 'light'),
('color-ink-900', '#14120F', 'light'),
('color-ink-600', '#4A463E', 'light'),
('color-ink-400', '#8A8578', 'light'),
('color-ember-500', '#FF5A36', 'light'),
('color-ember-600', '#E04321', 'light'),
('color-ember-100', '#FFE4DA', 'light'),
('color-teal-500', '#16B8A6', 'light'),
('color-teal-100', '#DAF6F1', 'light'),
('color-amber-400', '#FFC857', 'light'),
('color-amber-100', '#FFF3D6', 'light'),
('color-danger-500', '#E5484D', 'light'),
('color-success-500', '#2FA36B', 'light'),
('color-bg', '#121110', 'dark'),
('color-surface', '#191815', 'dark'),
('color-surface-alt', '#201F1B', 'dark'),
('color-border', '#2E2C27', 'dark'),
('color-ink-900', '#F6F3EC', 'dark'),
('color-ink-600', '#C9C4B7', 'dark'),
('color-ink-400', '#8C8778', 'dark'),
('color-ember-500', '#FF6B47', 'dark'),
('color-ember-600', '#FF8A66', 'dark'),
('color-ember-100', '#3A2016', 'dark'),
('color-teal-500', '#2DD4C0', 'dark'),
('color-teal-100', '#103631', 'dark'),
('color-amber-400', '#FFD273', 'dark'),
('color-amber-100', '#3A2E11', 'dark'),
('color-danger-500', '#E5484D', 'dark'),
('color-success-500', '#2FA36B', 'dark');

-- 5. Insert Default Site Settings
INSERT INTO `site_settings` (`key`, `value`, `type`) VALUES
('site_name', 'Utilazy', 'text'),
('site_tagline', 'Smart Tools for Everyday Tasks', 'text'),
('first_user_free_tokens', '100', 'number'),
('monthly_free_tokens', '50', 'number'),
('monthly_free_tokens_enabled', '1', 'boolean'),
('turnstile_enabled', '1', 'boolean'),
('turnstile_site_key', '1x00000000000000000000AA', 'text'),
('turnstile_secret_key', '1x00000000000000000000000000000000', 'text'),
('lemon_store_id', '12345', 'text'),
('lemon_api_key', 'mock_api_key', 'text'),
('lemon_webhook_secret', 'mock_signing_secret', 'text'),
('google_client_id', 'mock_google_id', 'text'),
('google_client_secret', 'mock_google_secret', 'text'),
('apple_client_id', 'mock_apple_id', 'text'),
('apple_team_id', 'mock_apple_team', 'text'),
('apple_key_id', 'mock_apple_key', 'text'),
('adblock_detector_enabled', '1', 'boolean'),
('adblock_detector_text', 'Please disable your ad blocker to continue using premium features on Utilazy.', 'text'),
('adblock_detector_enforcement', '0', 'boolean'),
('adblock_detector_continue_visible', '1', 'boolean'),
('maintenance_mode', '0', 'boolean');

-- 6. Insert Default Admin User
-- Password is 'admin123'
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `email_verified_at`, `unlimited_tokens`, `status`) VALUES
(1, 'Super Admin', 'admin@utilazy.com', '$2y$10$gP77V8P8Xp64e7HWe81T1OSDqL2f8sN2f9f8G6Fp/Zt6v7v7u8u8u', CURRENT_TIMESTAMP, 1, 'active');