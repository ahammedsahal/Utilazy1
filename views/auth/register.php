<?php
$pageTitle = "Register — Utilazy";
$settings = \App\Helpers\DB::fetchAll("SELECT `key`, `value` FROM site_settings");
$settingsMap = [];
foreach ($settings as $s) {
    $settingsMap[$s['key']] = $s['value'];
}
$turnstileEnabled = (($settingsMap['turnstile_enabled'] ?? '0') === '1') && (!empty($settingsMap['turnstile_site_key']) && $settingsMap['turnstile_site_key'] !== '1x00000000000000000000AA');
$turnstileSiteKey = $settingsMap['turnstile_site_key'] ?? '';
?>

<div class="max-w-md mx-auto my-12 px-6 py-8 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm">
    <div class="text-center mb-6">
        <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">Create Account</h1>
        <p class="text-xs text-[var(--color-ink-600)] mt-1">Get 100 free tokens on registration instantly.</p>
    </div>

    <!-- Section 33-A: Unified Social Login Buttons -->
    <div class="space-y-3 mb-6">
        <a href="/auth/google" class="h-12 border border-[var(--color-border)] bg-[var(--color-surface)] hover:bg-[var(--color-surface-alt)] rounded-xl flex items-center justify-center gap-3 px-4 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-[var(--color-ember-500)] text-sm font-semibold text-[var(--color-ink-900)]">
            <!-- Official G mark -->
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#EA4335" d="M12 5.04c1.65 0 3.13.57 4.3 1.69l3.22-3.22C17.56 1.74 14.94 1 12 1 7.35 1 3.4 3.65 1.56 7.56l3.86 3C6.34 7.42 8.93 5.04 12 5.04z"/>
                <path fill="#4285F4" d="M23.49 12.27c0-.81-.07-1.59-.2-2.34H12v4.43h6.46c-.28 1.47-1.11 2.72-2.36 3.56l3.66 2.84c2.14-1.97 3.39-4.87 3.39-8.49z"/>
                <path fill="#FBBC05" d="M5.42 10.56A7.124 7.124 0 0 1 5 12c0 .51.05 1.01.14 1.5l-3.86 3A11.956 11.956 0 0 1 1 12c0-1.89.44-3.69 1.22-5.3l3.2 2.86z"/>
                <path fill="#34A853" d="M12 23c3.24 0 5.97-1.07 7.96-2.91l-3.66-2.84c-1.1.74-2.51 1.18-4.3 1.18-3.07 0-5.66-2.38-6.58-5.52l-3.86 3C3.4 20.35 7.35 23 12 23z"/>
            </svg>
            Continue with Google
        </a>

        <a href="/auth/apple" class="h-12 border border-[var(--color-border)] bg-[var(--color-surface)] hover:bg-[var(--color-surface-alt)] rounded-xl flex items-center justify-center gap-3 px-4 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-[var(--color-ember-500)] text-sm font-semibold text-[var(--color-ink-900)]">
            <!-- Official Apple glyph -->
            <svg class="w-5 h-5 fill-current text-[var(--color-ink-900)]" viewBox="0 0 24 24">
                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.17c.66-.81 1.11-1.93.99-3.06-.96.04-2.13.64-2.82 1.45-.6.69-1.12 1.84-.98 2.94.97.08 2.05-.52 2.81-1.33z"/>
            </svg>
            Continue with Apple
        </a>
    </div>

    <div class="relative flex py-4 items-center">
        <div class="flex-grow border-t border-[var(--color-border)]"></div>
        <span class="flex-shrink mx-4 text-[10px] font-black uppercase text-[var(--color-ink-400)] tracking-wider">or continue with email</span>
        <div class="flex-grow border-t border-[var(--color-border)]"></div>
    </div>

    <!-- Core Register Form -->
    <form action="/register" method="POST" class="space-y-4">
        <?= \App\Helpers\CSRF::input() ?>

        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Full Name</label>
            <input type="text" name="name" required placeholder="John Doe" class="input-custom w-full h-11 px-4 text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Email Address</label>
            <input type="email" name="email" required placeholder="name@example.com" class="input-custom w-full h-11 px-4 text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Password</label>
            <input type="password" name="password" required placeholder="Minimum 8 characters" class="input-custom w-full h-11 px-4 text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Confirm Password</label>
            <input type="password" name="password_confirm" required placeholder="Re-enter password" class="input-custom w-full h-11 px-4 text-sm">
        </div>

        <label class="flex items-start gap-2.5 cursor-pointer py-1">
            <input type="checkbox" name="agree_terms" required class="mt-0.5 rounded text-[var(--color-ember-500)] focus:ring-[var(--color-ember-500)] border-[var(--color-border)]">
            <span class="text-xs text-[var(--color-ink-600)] font-medium leading-normal">I accept the <a href="/terms" class="text-[var(--color-ember-500)] font-bold hover:underline">Terms of Service</a> & <a href="/privacy" class="text-[var(--color-ember-500)] font-bold hover:underline">Privacy Policy</a>.</span>
        </label>

        <!-- Cloudflare Turnstile -->
        <?php if ($turnstileEnabled): ?>
            <div class="flex justify-center py-2">
                <div class="cf-turnstile" data-sitekey="<?= htmlspecialchars($turnstileSiteKey) ?>"></div>
            </div>
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        <?php endif; ?>

        <button type="submit" class="btn-ember w-full h-11 text-sm font-bold rounded-xl shadow-sm">Register Account</button>
    </form>

    <div class="text-center mt-6 pt-4 border-t border-[var(--color-border)]">
        <p class="text-xs text-[var(--color-ink-600)]">Already have an account? <a href="/login" class="font-bold text-[var(--color-ember-500)] hover:underline">Sign In</a></p>
    </div>
</div>
