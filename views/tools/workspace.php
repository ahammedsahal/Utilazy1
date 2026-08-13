<?php
$pageTitle = htmlspecialchars($tool['name']) . " — Utilazy";
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-8">
    <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-4">
            <span class="text-4xl p-3 bg-[var(--color-surface-alt)] rounded-2xl border border-[var(--color-border)]"><?= $tool['icon'] ?></span>
            <div>
                <span class="text-xs font-bold text-[var(--color-ember-500)] uppercase tracking-widest block mb-0.5"><?= htmlspecialchars($category['name']) ?></span>
                <h1 class="text-2xl font-black text-[var(--color-ink-900)] leading-tight"><?= htmlspecialchars($tool['name']) ?></h1>
                <p class="text-sm text-[var(--color-ink-600)] mt-1"><?= htmlspecialchars($tool['description']) ?></p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <?php if ($tool['access_type'] === 'free'): ?>
                <span class="text-xs font-bold bg-[var(--color-amber-100)] text-[var(--color-ink-900)] px-3 py-1.5 rounded-full flex items-center gap-1">
                    <span>⚡</span> Free — No Account Required
                </span>
            <?php elseif ($tool['access_type'] === 'login_required'): ?>
                <span class="text-xs font-bold bg-[var(--color-ember-100)] text-[var(--color-ember-500)] px-3 py-1.5 rounded-full flex items-center gap-1">
                    <span>🔑</span> Registered Users Only
                </span>
            <?php else: ?>
                <span class="text-xs font-bold bg-[var(--color-teal-100)] text-[var(--color-teal-500)] px-3 py-1.5 rounded-full flex items-center gap-1">
                    <span>🪙</span> Premium (Costs <?= $tool['token_cost'] ?> Credits)
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-3">
            <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] shadow-sm mb-8 min-h-[400px]">
                <?php
                $interfacePath = __DIR__ . "/interfaces/" . $tool['slug'] . ".php";
                if (file_exists($interfacePath)) {
                    include $interfacePath;
                } else {
                    echo "<div class='text-center py-12 text-gray-400'>Workspace interface not found.</div>";
                }
                ?>
            </div>
            <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] shadow-sm mb-8">
                <h3 class="text-lg font-black text-[var(--color-ink-900)] mb-4 flex items-center gap-2">
                    <span>📖</span> How to use <?= htmlspecialchars($tool['name']) ?>
                </h3>
                <div class="prose text-sm text-[var(--color-ink-600)] space-y-3">
                    <p>Utilazy tools are engineered for maximum speed, security, and privacy. To get started:</p>
                    <ol class="list-decimal list-inside space-y-2 font-medium">
                        <li>Supply or upload your target digital resource into the input panels above.</li>
                        <li>Configure any filters, rules, dimensions, or text casing as desired.</li>
                        <li>Click the main Action buttons to instantly run the utility client-side.</li>
                        <li>Verify, copy, or download your processed outputs directly from the results pane.</li>
                    </ol>
                    <p class="text-xs text-[var(--color-ink-400)] italic mt-4">Note: Your files are processed entirely in your web browser. Nothing is permanently stored on our servers.</p>
                </div>
            </div>
        </div>
        <div class="col-span-1 space-y-6">
            <?php if (Session::check()): ?>
                <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-5 rounded-[20px] shadow-sm">
                    <h4 class="text-xs font-bold text-[var(--color-ink-400)] uppercase tracking-wider mb-2">My Account Status</h4>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-[var(--color-ink-600)]">Available Balance:</span>
                        <span class="text-sm font-black text-[var(--color-teal-500)] flex items-center gap-1">
                            🪙 <?= $isUnlimited ? 'Unlimited' : number_format($userBalance) ?>
                        </span>
                    </div>
                    <?php if ($tool['access_type'] === 'token_required' && !$isUnlimited): ?>
                        <div class="mt-3 text-xs bg-[var(--color-surface-alt)] p-2.5 rounded-lg border border-[var(--color-border)] text-[var(--color-ink-600)]">
                            This tool costs <strong class="text-[var(--color-teal-500)]"><?= $tool['token_cost'] ?> credits</strong> per conversion run.
                        </div>
                    <?php endif; ?>
                    <a href="/pricing" class="btn-ember w-full block text-center py-2.5 rounded-xl font-bold text-xs mt-4 shadow-sm">Buy More Credits</a>
                </div>
            <?php endif; ?>
            <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-5 rounded-[20px] shadow-sm">
                <h4 class="text-xs font-bold text-[var(--color-ink-400)] uppercase tracking-wider mb-3">Related Utilities</h4>
                <div class="space-y-3">
                    <?php foreach ($related as $rel): ?>
                        <a href="/tools/<?= $rel['slug'] ?>" class="flex items-center gap-3 p-2 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] rounded-xl transition border border-[var(--color-border)] group">
                            <span class="text-2xl group-hover:scale-110 transition-transform"><?= $rel['icon'] ?></span>
                            <div>
                                <span class="text-xs font-bold text-[var(--color-ink-900)] block line-clamp-1"><?= htmlspecialchars($rel['name']) ?></span>
                                <span class="text-[10px] text-[var(--color-ink-600)] block line-clamp-1"><?= htmlspecialchars($rel['description']) ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>