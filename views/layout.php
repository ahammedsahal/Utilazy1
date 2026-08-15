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

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Satoshi:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <style>
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

        .btn-ember {
            background-color: var(--color-ember-500);
            color: #FFFFFF;
            transition: all 150ms ease-out;
        }
        .btn-ember:hover {
            background-color: var(--color-ember-600);
            transform: translateY(-1px);
        }

        /* Mega Dropdown Animation & Multi-level Hover */
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

        .cat-hover-group:hover .cat-tools-panel {
            display: grid !important;
        }
    </style>

    <?= $headInjections ?>
</head>
<body class="h-full flex flex-col">

    <!-- Header Navigation (Section 6-A & 69-A) -->
    <header class="sticky top-0 z-50 border-b border-[var(--color-border)] bg-[var(--color-surface)]/95 backdrop-blur shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <span class="text-2xl font-black tracking-tight flex items-center">
                            Utilazy<span class="text-[var(--color-ember-500)] ml-0.5">•</span>
                        </span>
                    </a>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center space-x-6">
                    <div class="relative nav-tools-trigger py-4">
                        <button class="flex items-center gap-1 font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] transition-colors focus:outline-none">
                            Tools
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <!-- MEGA DROPDOWN PANEL -->
                        <div id="tools-dropdown" class="absolute left-0 mt-2 w-[850px] bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-2xl p-6 z-50 flex gap-6">
                            <!-- Left Sidebar: Category List -->
                            <div class="w-1/3 border-r border-[var(--color-border)] pr-4 space-y-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--color-ink-400)] block mb-2">Categories</span>
                                <?php
                                $cIdx = 0;
                                foreach ($categories as $cat):
                                    if (empty($cat['tools'])) continue;
                                    $cIdx++;
                                ?>
                                    <div class="cat-hover-group group relative p-2 rounded-xl hover:bg-[var(--color-surface-alt)] cursor-pointer transition flex items-center justify-between">
                                        <span class="text-xs font-bold text-[var(--color-ink-900)] group-hover:text-[var(--color-ember-500)] flex items-center gap-2">
                                            <?= \App\Helpers\Icon::render($cat['icon']) ?> <?= htmlspecialchars($cat['name']) ?>
                                        </span>
                                        <span class="text-[10px] text-[var(--color-ink-400)]">›</span>

                                        <!-- Sub panel revealed on category hover -->
                                        <div class="cat-tools-panel hidden absolute top-0 left-full ml-4 w-[520px] bg-[var(--color-surface)] border border-[var(--color-border)] rounded-2xl p-4 shadow-xl grid-cols-2 gap-3 z-50">
                                            <?php foreach ($cat['tools'] as $tl): ?>
                                                <a href="/tools/<?= $tl['slug'] ?>" class="flex items-center justify-between p-2 rounded-xl bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] transition border border-[var(--color-border)] group">
                                                    <span class="text-xs font-bold text-[var(--color-ink-900)] group-hover:text-[var(--color-ember-500)] flex items-center gap-2 line-clamp-1">
                                                        <?= \App\Helpers\Icon::render($tl['icon']) ?> <?= htmlspecialchars($tl['name']) ?>
                                                    </span>
                                                    <?php if ($tl['access_type'] === 'free'): ?>
                                                        <span class="text-[9px] bg-[var(--color-amber-100)] text-[var(--color-ink-900)] px-1.5 py-0.5 rounded-full font-bold">Free</span>
                                                    <?php else: ?>
                                                        <span class="text-[9px] bg-[var(--color-teal-100)] text-[var(--color-teal-500)] px-1.5 py-0.5 rounded-full font-bold"><?= $tl['token_cost'] ?>T</span>
                                                    <?php endif; ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Right Default Panel -->
                            <div class="w-2/3 pl-2 flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--color-ink-400)] block mb-3">Popular Utilities</span>
                                    <div class="grid grid-cols-2 gap-3">
                                        <?php
                                        $allToolsList = [];
                                        foreach ($categories as $cat) {
                                            if (!empty($cat['tools'])) {
                                                foreach ($cat['tools'] as $tl) {
                                                    $allToolsList[] = $tl;
                                                }
                                            }
                                        }
                                        foreach (array_slice($allToolsList, 0, 8) as $tl):
                                        ?>
                                            <a href="/tools/<?= $tl['slug'] ?>" class="flex items-center justify-between p-2 rounded-xl bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] transition border border-[var(--color-border)] group">
                                                <span class="text-xs font-bold text-[var(--color-ink-900)] group-hover:text-[var(--color-ember-500)] flex items-center gap-2 line-clamp-1">
                                                    <?= \App\Helpers\Icon::render($tl['icon']) ?> <?= htmlspecialchars($tl['name']) ?>
                                                </span>
                                                <?php if ($tl['access_type'] === 'free'): ?>
                                                    <span class="text-[9px] bg-[var(--color-amber-100)] text-[var(--color-ink-900)] px-1.5 py-0.5 rounded-full font-bold">Free</span>
                                                <?php else: ?>
                                                    <span class="text-[9px] bg-[var(--color-teal-100)] text-[var(--color-teal-500)] px-1.5 py-0.5 rounded-full font-bold"><?= $tl['token_cost'] ?>T</span>
                                                <?php endif; ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="border-t border-[var(--color-border)] pt-4 mt-4 flex items-center justify-between">
                                    <span class="text-xs text-[var(--color-ink-400)]">Search 40+ online utility converters.</span>
                                    <a href="/tools" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline flex items-center gap-1">
                                        Explore All Tools <span>→</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="/categories" class="font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)]">Categories</a>
                    <a href="/pricing" class="font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)]">Pricing</a>
                    <a href="/how-it-works" class="font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)]">How It Works</a>
                </nav>

                <!-- Right Side Buttons -->
                <div class="flex items-center space-x-3">
                    <button onclick="toggleDarkMode()" class="p-2 text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] rounded-full" title="Toggle Theme">
                        <svg id="sun-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m2.828 0l-.707-.707m12.828-12.828l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
                        <svg id="moon-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <?php if ($user): ?>
                        <div class="flex items-center gap-3">
                            <a href="/token-history" class="flex items-center gap-1.5 px-3 py-1.5 bg-[var(--color-teal-100)] text-[var(--color-teal-500)] rounded-full font-bold text-xs">
                                <span>🪙</span>
                                <span><?= $user['unlimited_tokens'] == 1 ? 'Unlimited' : number_format(\App\Services\TokenService::getBalance($user['id'])) . ' Credits' ?></span>
                            </a>
                            <?php if ($isAdmin): ?>
                                <a href="/admin" class="text-xs font-bold text-[var(--color-ember-500)] border border-[var(--color-ember-500)] px-3 py-1.5 rounded-full hover:bg-[var(--color-ember-100)]">Admin</a>
                            <?php endif; ?>
                            <a href="/dashboard" class="w-8 h-8 rounded-full bg-[var(--color-surface-alt)] border border-[var(--color-border)] flex items-center justify-center font-bold text-sm text-[var(--color-ember-500)]">
                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                            </a>
                            <a href="/logout" class="text-xs font-medium text-[var(--color-ink-400)] hover:text-[var(--color-danger-500)]">Logout</a>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="text-sm font-semibold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] px-3 py-1.5">Login</a>
                        <a href="/register" class="text-sm font-semibold text-white bg-[var(--color-ember-500)] hover:bg-[var(--color-ember-600)] px-4 py-2 rounded-xl shadow-sm">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Workspace Content -->
    <main class="flex-grow">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-[var(--color-surface-alt)] border-t border-[var(--color-border)] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <span class="text-xl font-black tracking-tight block mb-3">Utilazy<span class="text-[var(--color-ember-500)]">•</span></span>
                <p class="text-sm text-[var(--color-ink-600)] max-w-md">Smart online utility toolbox for converting formats, managing document properties, text parsing, URL compression, and giveaways.</p>
                <div class="mt-4 text-xs text-[var(--color-ink-400)]">&copy; <?= date('Y') ?> Utilazy.com. All rights reserved.</div>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-[var(--color-ink-400)] mb-3">Legal</h4>
                <ul class="space-y-2 text-sm text-[var(--color-ink-600)] font-medium">
                    <li><a href="/terms" class="hover:text-[var(--color-ember-500)]">Terms of Service</a></li>
                    <li><a href="/privacy" class="hover:text-[var(--color-ember-500)]">Privacy Policy</a></li>
                    <li><a href="/faq" class="hover:text-[var(--color-ember-500)]">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-[var(--color-ink-400)] mb-3">Support</h4>
                <a href="/contact" class="inline-block text-xs font-bold text-[var(--color-ember-500)] hover:underline">Contact Support &rarr;</a>
            </div>
        </div>
    </footer>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
            document.getElementById('sun-icon')?.classList.remove('hidden');
            document.getElementById('moon-icon')?.classList.add('hidden');
        }

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                document.getElementById('sun-icon')?.classList.add('hidden');
                document.getElementById('moon-icon')?.classList.remove('hidden');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                document.getElementById('sun-icon')?.classList.remove('hidden');
                document.getElementById('moon-icon')?.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
