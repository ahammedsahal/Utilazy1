<?php
$pageTitle = "Branding Tokens — Utilazy";
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 my-10 space-y-8">
    <div class="flex items-center justify-between border-b border-[var(--color-border)] pb-6">
        <div>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">Ember & Ink Branding Theme Customizer</h1>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Adjust design tokens dynamically in light/dark mode. Modified tokens apply live across all layouts immediately.</p>
        </div>
        <a href="/admin" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl">&larr; Back to Admin Console</a>
    </div>

    <form action="/admin/branding/update" method="POST" class="space-y-6">
        <?= \App\Helpers\CSRF::input() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Light Mode Tokens -->
            <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] shadow-sm space-y-4">
                <h3 class="text-xs font-black uppercase text-[var(--color-ink-900)] tracking-wider">Light Theme Colors</h3>
                <div class="space-y-3">
                    <?php foreach ($tokens as $tk): if ($tk['scope'] === 'light'): ?>
                        <div class="flex items-center justify-between gap-4 text-xs">
                            <span class="font-bold text-[var(--color-ink-600)]"><?= htmlspecialchars($tk['token_key']) ?></span>
                            <div class="flex items-center gap-2">
                                <input type="text" name="tokens[<?= $tk['id'] ?>]" value="<?= htmlspecialchars($tk['token_value']) ?>" class="input-custom w-32 h-8 px-2 text-center font-mono">
                                <?php if (strpos($tk['token_value'], '#') === 0): ?>
                                    <span class="w-6 h-6 rounded-lg border border-[var(--color-border)] block" style="background-color: <?= $tk['token_value'] ?>"></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; endforeach; ?>
                </div>
            </div>

            <!-- Dark Mode Tokens -->
            <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] shadow-sm space-y-4">
                <h3 class="text-xs font-black uppercase text-[var(--color-ink-900)] tracking-wider">Dark Theme Colors</h3>
                <div class="space-y-3">
                    <?php foreach ($tokens as $tk): if ($tk['scope'] === 'dark'): ?>
                        <div class="flex items-center justify-between gap-4 text-xs">
                            <span class="font-bold text-[var(--color-ink-600)]"><?= htmlspecialchars($tk['token_key']) ?></span>
                            <div class="flex items-center gap-2">
                                <input type="text" name="tokens[<?= $tk['id'] ?>]" value="<?= htmlspecialchars($tk['token_value']) ?>" class="input-custom w-32 h-8 px-2 text-center font-mono">
                                <?php if (strpos($tk['token_value'], '#') === 0): ?>
                                    <span class="w-6 h-6 rounded-lg border border-[var(--color-border)] block" style="background-color: <?= $tk['token_value'] ?>"></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; endforeach; ?>
                </div>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn-ember px-6 py-2.5 text-xs font-bold rounded-xl shadow-sm">Save Brand Design Tokens</button>
        </div>
    </form>
</div>
