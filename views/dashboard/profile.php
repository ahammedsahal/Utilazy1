<?php
$pageTitle = "My Profile — Utilazy";
?>

<div class="max-w-2xl mx-auto my-10 px-6 py-8 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">Profile Settings</h1>
        <p class="text-xs text-[var(--color-ink-600)] mt-1">Keep your email contact and password credentials updated secure.</p>
    </div>

    <form action="/profile" method="POST" class="space-y-4">
        <?= \App\Helpers\CSRF::input() ?>

        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="input-custom w-full h-11 px-4 text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Email Address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="input-custom w-full h-11 px-4 text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">New Password (leave blank to keep current)</label>
            <input type="password" name="password" placeholder="••••••••" class="input-custom w-full h-11 px-4 text-sm">
        </div>

        <button type="submit" class="btn-ember px-5 py-2.5 text-xs font-bold rounded-xl shadow-sm">Update Profile</button>
    </form>
</div>
