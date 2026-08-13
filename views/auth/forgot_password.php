<?php
$pageTitle = "Forgot Password - Utilazy";
?>
<div class="max-w-md mx-auto my-12 px-4">
    <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-8 rounded-[20px] shadow-sm">
        <h2 class="text-2xl font-black text-center mb-1">Forgot Password</h2>
        <p class="text-sm text-center text-[var(--color-ink-400)] mb-6">Enter your email to request a secure password reset link.</p>

        <!-- Form -->
        <form action="/forgot-password" method="POST" class="space-y-4">
            <?= \App\Helpers\CSRF::input() ?>

            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="john@example.com" class="input-custom w-full h-[48px] px-4 text-sm">
            </div>

            <!-- Turnstile -->
            <div class="cf-turnstile py-2 flex justify-center" data-sitekey="1x00000000000000000000AA" data-theme="light"></div>

            <button type="submit" class="btn-ember w-full h-[48px] radius-btn font-bold text-sm tracking-wide shadow-sm mt-2">
                Send Reset Link &rarr;
            </button>
        </form>

        <p class="text-xs text-center text-[var(--color-ink-600)] mt-6">
            Remembered your password?
            <a href="/login" class="font-bold text-[var(--color-ember-500)] hover:underline">Sign in here</a>
        </p>
    </div>
</div>