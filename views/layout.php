<?php
// Global Master Layout for Utilazy - Smart Tools for Everyday Tasks
// Fully implements the "Ember & Ink" visual identity color tokens, typography, and motion.

// Fallbacks for tokens if not set in DB
$bg = $designTokensLight['color-bg'] ?? '#FAF9F6';
$surface = $designTokensLight['color-surface'] ?? '#FFFFFF';
$surfaceAlt = $designTokensLight['color-surface-alt'] ?? '#F1EFE9';
$border = $designTokensLight['color-border'] ?? '#E7E3DA';
$ink900 = $designTokensLight['color-ink-900'] ?? '#14120F';
$ink600 = $designTokensLight['color-ink-600'] ?? '#4A463E';
$ink400 = $designTokensLight['color-ink-400'] ?? '#8A8578';
$ember500 = $designTokensLight['color-ember-500'] ?? '#FF5A36';
$ember600 = $designTokensLight['color-ember-600'] ?? '#E04321';
$ember100 = $designTokensLight['color-ember-100'] ?? '#FFE4DA';
$teal500 = $designTokensLight['color-teal-500'] ?? '#16B8A6';
$teal100 = $designTokensLight['color-teal-100'] ?? '#DAF6F1';
$amber400 = $designTokensLight['color-amber-400'] ?? '#FFC857';
$amber100 = $designTokensLight['color-amber-100'] ?? '#FFF3D6';
$danger500 = $designTokensLight['color-danger-500'] ?? '#E5484D';
$success500 = $designTokensLight['color-success-500'] ?? '#2FA36B';

// Dark Mode Tokens
$bgDark = $designTokensDark['color-bg'] ?? '#121110';
$surfaceDark = $designTokensDark['color-surface'] ?? '#191815';
$surfaceAltDark = $designTokensDark['color-surface-alt'] ?? '#201F1B';
$borderDark = $designTokensDark['color-border'] ?? '#2E2C27';
$ink900Dark = $designTokensDark['color-ink-900'] ?? '#F6F3EC';
$ink600Dark = $designTokensDark['color-ink-600'] ?? '#C9C4B7';
$ink400Dark = $designTokensDark['color-ink-400'] ?? '#8C8778';
$ember500Dark = $designTokensDark['color-ember-500'] ?? '#FF6B47';
$ember600Dark = $designTokensDark['color-ember-600'] ?? '#FF8A66';
$ember100Dark = $designTokensDark['color-ember-100'] ?? '#3A2016';
$teal500Dark = $designTokensDark['color-teal-500'] ?? '#2DD4C0';
$teal100Dark = $designTokensDark['color-teal-100'] ?? '#103631';
$amber400Dark = $designTokensDark['color-amber-400'] ?? '#FFD273';
$amber100Dark = $designTokensDark['color-amber-100'] ?? '#3A2E11';
$danger500Dark = $designTokensDark['color-danger-500'] ?? '#E5484D';
$success500Dark = $designTokensDark['color-success-500'] ?? '#2FA36B';

$flashError = $_SESSION['flash_error'] ?? null;
$flashSuccess = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_error']);
unset($_SESSION['flash_success']);

// Adblock Setting
$adblockEnabled = $settings['adblock_detector_enabled'] ?? '1';
$adblockText = $settings['adblock_detector_text'] ?? 'Please disable your ad blocker to support Utilazy.';
$adblockEnforce = $settings['adblock_detector_enforcement'] ?? '0';
$adblockContinue = $settings['adblock_detector_continue_visible'] ?? '1';
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? $site_name . " — " . $site_tagline) ?></title>

    <!-- Tailwind CSS CDN for swift utilities & grids -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts for typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Satoshi:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Cloudflare Turnstile Client Script -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <style>
        /* CSS Variables fully mapped to DB Design Tokens (Section 2.1) */
        :root {
            --color-bg: <?= $bg ?>;
            --color-surface: <?= $surface ?>;
            --color-surface-alt: <?= $surfaceAlt ?>;
            --color-border: <?= $border ?>;
            --color-ink-900: <?= $ink900 ?>;
            --color-ink-600: <?= $ink600 ?>;
            --color-ink-400: <?= $ink400 ?>;

            --color-ember-500: <?= $ember500 ?>;
            --color-ember-600: <?= $ember600 ?>;
            --color-ember-100: <?= $ember100 ?>;

            --color-teal-500: <?= $teal500 ?>;
            --color-teal-100: <?= $teal100 ?>;

            --color-amber-400: <?= $amber400 ?>;
            --color-amber-100: <?= $amber100 ?>;

            --color-danger-500: <?= $danger500 ?>;
            --color-success-500: <?= $success500 ?>;
        }

        .dark {
            --color-bg: <?= $bgDark ?>;
            --color-surface: <?= $surfaceDark ?>;
            --color-surface-alt: <?= $surfaceAltDark ?>;
            --color-border: <?= $borderDark ?>;
            --color-ink-900: <?= $ink900Dark ?>;
            --color-ink-600: <?= $ink600Dark ?>;
            --color-ink-400: <?= $ink400Dark ?>;

            --color-ember-500: <?= $ember500Dark ?>;
            --color-ember-600: <?= $ember600Dark ?>;
            --color-ember-100: <?= $ember100Dark ?>;

            --color-teal-500: <?= $teal500Dark ?>;
            --color-teal-100: <?= $teal100Dark ?>;

            --color-amber-400: <?= $amber400Dark ?>;
            --color-amber-100: <?= $amber100Dark ?>;
        }

        body {
            background-color: var(--color-bg);
            color: var(--color-ink-900);
            font-family: 'Inter', 'Satoshi', sans-serif;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Satoshi', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Radius mappings (Section 2.3) */
        .radius-card { border-radius: 20px; }
        .radius-btn { border-radius: 12px; }
        .radius-pill { border-radius: 999px; }

        /* Elevation (Section 2.3) */
        .shadow-warm {
            shadow: 0 8px 24px -8px rgba(20, 18, 15, 0.12);
        }

        .code-font {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Micro-interactions (Section 2.3) */
        .btn-ember {
            background-color: var(--color-ember-500);
            color: #FFFFFF;
            transition: all 150ms ease-out;
        }
        .btn-ember:hover {
            background-color: var(--color-ember-600);
            transform: translateY(-1px);
        }
        .btn-ember:active {
            transform: scale(0.98);
        }

        .btn-secondary {
            border: 1px solid var(--color-ink-900);
            background-color: transparent;
            color: var(--color-ink-900);
            transition: all 150ms ease-out;
        }
        .btn-secondary:hover {
            background-color: var(--color-surface-alt);
        }

        .input-custom {
            border: 1px solid var(--color-border);
            background-color: var(--color-surface);
            color: var(--color-ink-900);
            border-radius: 12px;
            transition: all 150ms ease;
        }
        .input-custom:focus {
            outline: none;
            border-color: var(--color-ember-500);
            box-shadow: 0 0 0 2px var(--color-ember-100);
        }

        /* Mega Dropdown Animation (Section 69-A) */
        #tools-dropdown {
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 150ms cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nav-tools-trigger:hover #tools-dropdown,
        #tools-dropdown:hover {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
    </style>

    <!-- Header Custom Code Injection (Section 46) -->
    <?= $headInjections ?>
</head>
<body class="h-full flex flex-col">

    <!-- Header Navigation (Section 6-A & 69-A) -->
    <header class="sticky top-0 z-50 border-b border-[var(--color-border)] bg-[var(--color-surface)]/95 backdrop-blur shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Brand Wordmark -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <span class="text-2xl font-black tracking-tight flex items-center">
                            Utilazy<span class="text-[var(--color-ember-500)] ml-0.5">•</span>
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center space-x-6">
                    <!-- Tools Hover Trigger (Section 69-A) -->
                    <div class="relative nav-tools-trigger py-4">
                        <button class="flex items-center gap-1 font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] transition-colors focus:outline-none">
                            Tools
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <!-- MEGA DROPDOWN PANEL (Section 6-A / 69-A: live registry-driven) -->
                        <div id="tools-dropdown" class="absolute left-1/2 -translate-x-1/2 mt-2 w-[850px] bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-xl p-6 z-50 grid grid-cols-3 gap-6">
                            <?php
                            $count = 0;
                            foreach ($categories as $cat):
                                if (empty($cat['tools'])) continue;
                                $count++;
                                if ($count > 9) break; // limit to grid
                            ?>
                                <div>
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-[var(--color-ink-400)] border-b border-[var(--color-border)] pb-2 mb-2 flex items-center gap-1">
                                        <span><?= $cat['icon'] ?></span> <?= htmlspecialchars($cat['name']) ?>
                                    </h4>
                                    <ul class="space-y-1.5">
                                        <?php
                                        $tools_shown = 0;
                                        foreach ($cat['tools'] as $tl):
                                            $tools_shown++;
                                            if ($tools_shown > 5) {
                                                echo '<li><a href="/tools" class="text-xs text-[var(--color-ember-500)] font-medium hover:underline">View all...</a></li>';
                                                break;
                                            }
                                        ?>
                                            <li>
                                                <a href="/tools/<?= $tl['slug'] ?>" class="group flex items-center justify-between text-xs font-medium text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] py-0.5 transition-colors">
                                                    <span class="flex items-center gap-1">
                                                        <span><?= $tl['icon'] ?></span>
                                                        <span><?= htmlspecialchars($tl['name']) ?></span>
                                                    </span>
                                                    <?php if ($tl['access_type'] === 'free'): ?>
                                                        <span class="text-[9px] bg-[var(--color-amber-100)] text-[var(--color-ink-900)] px-1.5 py-0.5 rounded-full font-bold">Free</span>
                                                    <?php else: ?>
                                                        <span class="text-[9px] bg-[var(--color-teal-100)] text-[var(--color-teal-500)] px-1.5 py-0.5 rounded-full font-bold"><?= $tl['token_cost'] ?>T</span>
                                                    <?php endif; ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                            <div class="col-span-3 border-t border-[var(--color-border)] pt-4 mt-2 flex items-center justify-between">
                                <span class="text-xs text-[var(--color-ink-400)]">Search all online conversion, productivity, and document tools instantly.</span>
                                <a href="/tools" class="text-xs font-bold text-[var(--color-ember-500)] hover:text-[var(--color-ember-600)] flex items-center gap-1">
                                    View All Tools <span>→</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="/categories" class="font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] transition-colors">Categories</a>
                    <a href="/pricing" class="font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] transition-colors">Pricing</a>
                    <a href="/how-it-works" class="font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] transition-colors">How It Works</a>
                </nav>

                <!-- Right Side Buttons & Auth status -->
                <div class="flex items-center space-x-3">
                    <!-- Light/Dark Mode Selector -->
                    <button onclick="toggleDarkMode()" class="p-2 text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] hover:bg-[var(--color-surface-alt)] rounded-full transition-all focus:outline-none" title="Toggle Theme">
                        <svg id="sun-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m2.828 0l-.707-.707m12.828-12.828l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
                        <svg id="moon-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <?php if ($user): ?>
                        <!-- Logged-in Menu -->
                        <div class="flex items-center gap-3">
                            <!-- Token Balance Badge (Section 36) -->
                            <a href="/token-history" class="flex items-center gap-1.5 px-3 py-1.5 bg-[var(--color-teal-100)] text-[var(--color-teal-500)] rounded-full font-bold text-xs hover:scale-105 transition-transform" title="Token History">
                                <span>🪙</span>
                                <span>
                                    <?php if ($user['unlimited_tokens'] == 1): ?>
                                        Unlimited
                                    <?php else: ?>
                                        <?= number_format(\App\Services\TokenService::getBalance($user['id'])) ?> Credits
                                    <?php endif; ?>
                                </span>
                            </a>

                            <!-- Admin dashboard link -->
                            <?php if ($isAdmin): ?>
                                <a href="/admin" class="text-xs font-bold text-[var(--color-ember-500)] border border-[var(--color-ember-500)] px-3 py-1.5 rounded-full hover:bg-[var(--color-ember-100)] transition-colors">Admin</a>
                            <?php endif; ?>

                            <!-- Account Avatar / Menu Link -->
                            <a href="/dashboard" class="flex items-center gap-2 p-1 text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] transition-colors" title="My Dashboard">
                                <span class="w-8 h-8 rounded-full bg-[var(--color-surface-alt)] border border-[var(--color-border)] flex items-center justify-center font-bold text-sm text-[var(--color-ember-500)]">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </span>
                            </a>

                            <a href="/logout" class="text-xs font-medium text-[var(--color-ink-400)] hover:text-[var(--color-danger-500)] transition-colors">Logout</a>
                        </div>
                    <?php else: ?>
                        <!-- Guest Auth buttons -->
                        <a href="/login" class="text-sm font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] px-3 py-1.5 transition-colors">Login</a>
                        <a href="/register" class="text-sm font-semibold text-white bg-[var(--color-ember-500)] hover:bg-[var(--color-ember-600)] px-4 py-2 rounded-xl transition-all shadow-sm">Register</a>
                    <?php endif; ?>

                    <!-- Mobile Menu Trigger -->
                    <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-[var(--color-ink-600)] focus:outline-none" title="Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu (Section 69) -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-[var(--color-border)] bg-[var(--color-surface)] py-4 px-6 space-y-4">
            <a href="/tools" class="block font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)]">All Tools</a>
            <a href="/categories" class="block font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)]">Categories</a>
            <a href="/pricing" class="block font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)]">Pricing</a>
            <a href="/how-it-works" class="block font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)]">How It Works</a>
            <?php if ($user): ?>
                <hr class="border-[var(--color-border)]">
                <a href="/dashboard" class="block font-semibold text-[var(--color-ember-500)]">My Dashboard</a>
                <a href="/logout" class="block font-semibold text-red-500">Logout</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Top Advertisement Placement (Section 48) -->
    <?php if (isset($settings['ads_header_enabled']) && $settings['ads_header_enabled'] == '1'): ?>
        <div class="bg-[var(--color-surface-alt)] py-3 text-center border-b border-[var(--color-border)] text-xs text-[var(--color-ink-400)]">
            <span class="block text-[10px] font-bold uppercase mb-1">Advertisement</span>
            <?= $settings['ads_header_code'] ?? '<!-- Place Ad Code Here -->' ?>
        </div>
    <?php endif; ?>

    <!-- Flash Messages & Toast Notifications (Section 90) -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3">
        <?php if ($flashError): ?>
            <div class="toast-item bg-[var(--color-surface)] border-l-4 border-red-500 text-[var(--color-ink-900)] px-4 py-3 rounded-lg shadow-lg flex items-center justify-between gap-4 max-w-sm" role="alert">
                <span class="text-sm font-semibold">❌ <?= htmlspecialchars($flashError) ?></span>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
        <?php endif; ?>

        <?php if ($flashSuccess): ?>
            <div class="toast-item bg-[var(--color-surface)] border-l-4 border-[var(--color-teal-500)] text-[var(--color-ink-900)] px-4 py-3 rounded-lg shadow-lg flex items-center justify-between gap-4 max-w-sm" role="alert">
                <span class="text-sm font-semibold">🎉 <?= htmlspecialchars($flashSuccess) ?></span>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Adblock Detector Alert Banner (Section 47) -->
    <?php if ($adblockEnabled == '1'): ?>
        <div id="adblock-detector-banner" class="hidden bg-[var(--color-amber-100)] text-[var(--color-ink-900)] px-4 py-3 border-b border-yellow-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm z-40">
            <div class="flex items-center gap-2">
                <span>⚠️</span>
                <span><?= htmlspecialchars($adblockText) ?></span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="disableAdblockNotification()" class="bg-[var(--color-ember-500)] text-white px-3 py-1.5 rounded-lg font-bold text-xs hover:bg-[var(--color-ember-600)] transition-colors">Disable Ad Blocker</button>
                <?php if ($adblockContinue == '1' && $adblockEnforce == '0'): ?>
                    <button onclick="dismissAdblockBanner()" class="text-[var(--color-ink-600)] hover:underline text-xs">Continue without disabling</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Workspace Content -->
    <main class="flex-grow">
        <?= $content ?>
    </main>

    <!-- Footer (Section 2/3) -->
    <footer class="bg-[var(--color-surface-alt)] border-t border-[var(--color-border)] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <span class="text-xl font-black tracking-tight block mb-3">Utilazy<span class="text-[var(--color-ember-500)]">•</span></span>
                <p class="text-sm text-[var(--color-ink-600)] max-w-md">Smart online utility toolbox for converting formats, managing document properties, text parsing, URL compression, and giveaways — optimized for everyday performance.</p>
                <div class="mt-4 text-xs text-[var(--color-ink-400)]">
                    &copy; <?= date('Y') ?> Utilazy.com. All rights reserved. Built with premium quality.
                </div>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-[var(--color-ink-400)] mb-3">Legal & Terms</h4>
                <ul class="space-y-2 text-sm text-[var(--color-ink-600)] font-medium">
                    <li><a href="/terms" class="hover:text-[var(--color-ember-500)]">Terms of Service</a></li>
                    <li><a href="/privacy" class="hover:text-[var(--color-ember-500)]">Privacy Policy</a></li>
                    <li><a href="/faq" class="hover:text-[var(--color-ember-500)]">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-[var(--color-ink-400)] mb-3">Contact</h4>
                <p class="text-sm text-[var(--color-ink-600)] font-medium">Have feedback or want to request a custom tool? Email us directly.</p>
                <a href="/contact" class="inline-block mt-3 text-xs font-bold text-[var(--color-ember-500)] hover:underline">Contact Support &rarr;</a>
            </div>
        </div>
    </footer>

    <!-- Dark Mode & UI Scripts -->
    <script>
        // Init Theme
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            document.getElementById('sun-icon').classList.remove('hidden');
            document.getElementById('moon-icon').classList.add('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            document.getElementById('sun-icon').classList.add('hidden');
            document.getElementById('moon-icon').classList.remove('hidden');
        }

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                document.getElementById('sun-icon').classList.add('hidden');
                document.getElementById('moon-icon').classList.remove('hidden');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                document.getElementById('sun-icon').classList.remove('hidden');
                document.getElementById('moon-icon').classList.add('hidden');
            }
        }

        // Adblock Detection (Section 47)
        window.addEventListener('load', () => {
            const testAd = document.createElement('div');
            testAd.innerHTML = '&nbsp;';
            testAd.className = 'adsbox doubleclick ad-placement sponsored-links';
            testAd.style.position = 'absolute';
            testAd.style.left = '-9999px';
            testAd.style.height = '1px';
            document.body.appendChild(testAd);

            setTimeout(() => {
                if (testAd.offsetHeight === 0) {
                    const banner = document.getElementById('adblock-detector-banner');
                    if (banner && !sessionStorage.getItem('adblock_dismissed')) {
                        banner.classList.remove('hidden');
                    }
                }
                testAd.remove();
            }, 150);
        });

        function dismissAdblockBanner() {
            const banner = document.getElementById('adblock-detector-banner');
            if (banner) {
                banner.classList.add('hidden');
                sessionStorage.setItem('adblock_dismissed', '1');
            }
        }

        function disableAdblockNotification() {
            showToast('Please whitelist Utilazy in your browser adblock configurations.', true);
        }
    </script>
</body>
</html>
