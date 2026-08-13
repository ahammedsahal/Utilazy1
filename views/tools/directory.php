<?php
$pageTitle = "Explore Tools Directory — Utilazy";
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-10">
    <div class="text-center max-w-xl mx-auto mb-10">
        <h1 class="text-3xl font-black text-[var(--color-ink-900)] tracking-tight">Utilazy Tools Directory</h1>
        <p class="text-xs text-[var(--color-ink-600)] mt-1.5">Select and use any of our online digital utilities. Free and Premium options are fully supported.</p>
    </div>

    <!-- Filters Shell -->
    <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-5 rounded-[20px] shadow-sm mb-8 space-y-4">
        <!-- Search -->
        <form action="/tools" method="GET" class="relative max-w-md">
            <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search all tools..." class="input-custom w-full h-10 px-4 text-xs font-semibold pl-10">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
        </form>

        <!-- Category chips row -->
        <div>
            <span class="block text-[10px] font-bold text-[var(--color-ink-400)] uppercase tracking-wider mb-2">Category Filters</span>
            <div class="flex flex-wrap gap-1.5">
                <a href="/tools?category=all&access=<?= $selectedAccess ?>" class="px-3 py-1.5 rounded-full text-xs font-bold border <?= $selectedCategory === 'all' ? 'bg-[var(--color-ember-500)] text-white border-[var(--color-ember-500)]' : 'bg-[var(--color-surface-alt)] text-[var(--color-ink-600)] border-[var(--color-border)] hover:bg-[var(--color-border)]' ?> transition-colors">
                    All Categories
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="/tools?category=<?= $cat['slug'] ?>&access=<?= $selectedAccess ?>" class="px-3 py-1.5 rounded-full text-xs font-bold border <?= $selectedCategory === $cat['slug'] ? 'bg-[var(--color-ember-500)] text-white border-[var(--color-ember-500)]' : 'bg-[var(--color-surface-alt)] text-[var(--color-ink-600)] border-[var(--color-border)] hover:bg-[var(--color-border)]' ?> transition-colors">
                        <?= $cat['icon'] ?> <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Access filter row -->
        <div>
            <span class="block text-[10px] font-bold text-[var(--color-ink-400)] uppercase tracking-wider mb-2">Access Type</span>
            <div class="flex flex-wrap gap-1.5">
                <a href="/tools?category=<?= $selectedCategory ?>&access=all" class="px-3 py-1.5 rounded-full text-xs font-bold border <?= $selectedAccess === 'all' ? 'bg-[var(--color-ember-500)] text-white border-[var(--color-ember-500)]' : 'bg-[var(--color-surface-alt)] text-[var(--color-ink-600)] border-[var(--color-border)] hover:bg-[var(--color-border)]' ?> transition-colors">All</a>
                <a href="/tools?category=<?= $selectedCategory ?>&access=free" class="px-3 py-1.5 rounded-full text-xs font-bold border <?= $selectedAccess === 'free' ? 'bg-[var(--color-ember-500)] text-white border-[var(--color-ember-500)]' : 'bg-[var(--color-surface-alt)] text-[var(--color-ink-600)] border-[var(--color-border)] hover:bg-[var(--color-border)]' ?> transition-colors">Free Tools</a>
                <a href="/tools?category=<?= $selectedCategory ?>&access=premium" class="px-3 py-1.5 rounded-full text-xs font-bold border <?= $selectedAccess === 'premium' ? 'bg-[var(--color-ember-500)] text-white border-[var(--color-ember-500)]' : 'bg-[var(--color-surface-alt)] text-[var(--color-ink-600)] border-[var(--color-border)] hover:bg-[var(--color-border)]' ?> transition-colors">Premium Tools</a>
            </div>
        </div>
    </div>

    <!-- Results Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($tools)): ?>
            <div class="col-span-3 text-center py-12 text-zinc-400 font-medium">No tools found matching your active filters.</div>
        <?php else: ?>
            <?php foreach ($tools as $tl): ?>
                <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] shadow-sm flex flex-col justify-between hover:translate-y-[-2px] transition-transform duration-150">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-3xl p-2.5 bg-[var(--color-surface-alt)] border border-[var(--color-border)] rounded-xl"><?= $tl['icon'] ?></span>

                            <?php if ($tl['access_type'] === 'free'): ?>
                                <span class="text-[10px] bg-[var(--color-amber-100)] text-[var(--color-ink-900)] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">Free</span>
                            <?php else: ?>
                                <span class="text-[10px] bg-[var(--color-teal-100)] text-[var(--color-teal-500)] font-black px-2.5 py-1 rounded-full uppercase tracking-wider"><?= $tl['token_cost'] ?> Tokens</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-base font-bold text-[var(--color-ink-900)] mb-1"><?= htmlspecialchars($tl['name']) ?></h3>
                        <p class="text-xs text-[var(--color-ink-600)] leading-relaxed mb-6"><?= htmlspecialchars($tl['description']) ?></p>
                    </div>
                    <a href="/tools/<?= $tl['slug'] ?>" class="text-xs font-bold text-[var(--color-ember-500)] hover:text-[var(--color-ember-600)] flex items-center gap-1 group-hover:underline">
                        Open Tool <span>&rarr;</span>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
