<?php
$pageTitle = "Tool Categories — Utilazy";
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <h1 class="text-3xl sm:text-4xl font-black text-[var(--color-ink-900)]">Browse Tools by Category</h1>
        <p class="text-sm text-[var(--color-ink-600)]">Explore our organized toolbox spanning text formatting, PDF management, developer utilities, image optimization, conversion, and giveaways.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($categories as $cat): ?>
            <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] p-6 shadow-sm hover:shadow-md transition space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl p-3 bg-[var(--color-surface-alt)] rounded-xl border border-[var(--color-border)] text-[var(--color-ember-500)]">
                            <?= \App\Helpers\Icon::render($cat['icon']) ?>
                        </span>
                        <div>
                            <h3 class="text-lg font-bold text-[var(--color-ink-900)]"><?= htmlspecialchars($cat['name']) ?></h3>
                            <span class="text-xs text-[var(--color-ink-400)] font-medium"><?= htmlspecialchars($cat['slug']) ?></span>
                        </div>
                    </div>
                    <p class="text-xs text-[var(--color-ink-600)] leading-relaxed"><?= htmlspecialchars($cat['description']) ?></p>
                </div>

                <a href="/tools?category=<?= urlencode($cat['slug']) ?>" class="btn-ember w-full block text-center py-2.5 rounded-xl font-bold text-xs shadow-sm">
                    View <?= htmlspecialchars($cat['name']) ?> →
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>