<?php
$pageTitle = "Global Settings — Utilazy";
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 my-10 space-y-8">
    <div class="flex items-center justify-between border-b border-[var(--color-border)] pb-6">
        <div>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">Global Configurations & Settings</h1>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Configure site defaults, cloudflare site keys, adblock messages, ad schedules, and maintenance parameters.</p>
        </div>
        <a href="/admin" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl">&larr; Back to Admin Console</a>
    </div>

    <form action="/admin/settings/update" method="POST" class="bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] shadow-sm space-y-6">
        <?= \App\Helpers\CSRF::input() ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <?php foreach ($settings as $set): ?>
                <div class="space-y-1">
                    <label class="block font-bold uppercase text-[var(--color-ink-600)]"><?= htmlspecialchars(str_replace('_', ' ', $set['key'])) ?></label>

                    <?php if ($set['key'] === 'maintenance_mode' || $set['key'] === 'turnstile_enabled' || $set['key'] === 'adblock_enabled' || $set['key'] === 'adblock_enforce' || $set['key'] === 'adblock_continue' || $set['key'] === 'ads_header_enabled' || $set['key'] === 'ads_footer_enabled'): ?>
                        <select name="<?= htmlspecialchars($set['key']) ?>" class="input-custom w-full h-10 px-3 font-bold">
                            <option value="1" <?= $set['value'] == '1' ? 'selected' : '' ?>>Enabled / True</option>
                            <option value="0" <?= $set['value'] == '0' ? 'selected' : '' ?>>Disabled / False</option>
                        </select>
                    <?php elseif (strpos($set['key'], '_code') !== false || $set['key'] === 'adblock_text'): ?>
                        <textarea name="<?= htmlspecialchars($set['key']) ?>" class="input-custom w-full h-24 p-3 font-semibold"><?= htmlspecialchars($set['value']) ?></textarea>
                    <?php else: ?>
                        <input type="text" name="<?= htmlspecialchars($set['key']) ?>" value="<?= htmlspecialchars($set['value']) ?>" class="input-custom w-full h-10 px-3 font-semibold">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-right pt-4 border-t border-[var(--color-border)]">
            <button type="submit" class="btn-ember px-5 py-2.5 font-bold rounded-xl shadow-sm">Save Global Configurations</button>
        </div>
    </form>
</div>
