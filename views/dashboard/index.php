<?php
$pageTitle = "Dashboard — Utilazy";
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-10">
    <!-- Welcome Header / Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="col-span-1 md:col-span-2 bg-[var(--color-surface)] border border-[var(--color-border)] p-8 rounded-[20px] shadow-sm flex flex-col justify-between">
            <div>
                <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>
                <p class="text-xs text-[var(--color-ink-600)] mt-1">Manage your utility tool usage, balance limits, billing, and custom URLs from one panel.</p>
            </div>

            <div class="mt-6 pt-6 border-t border-[var(--color-border)] flex flex-wrap gap-4 items-center justify-between">
                <div>
                    <span class="block text-[10px] font-black uppercase text-[var(--color-ink-400)] tracking-wider">Active Plan & Perks</span>
                    <span class="text-xs font-bold text-[var(--color-ink-900)]"><?= $user['unlimited_tokens'] ? '🌟 Unlimited Token Power-user' : '⚡ Default Account Tier' ?></span>
                </div>
                <!-- Monthly tokens claim section (Section 29 & Goal 2) -->
                <div>
                    <?php if ($hasClaimed): ?>
                        <button disabled class="px-4 py-2 text-xs font-bold bg-[var(--color-surface-alt)] border border-[var(--color-border)] text-[var(--color-ink-400)] rounded-xl cursor-not-allowed">
                            ✓ Monthly Tokens Claimed
                        </button>
                    <?php else: ?>
                        <form action="/claim-free-tokens" method="POST">
                            <?= \App\Helpers\CSRF::input() ?>
                            <button type="submit" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl shadow-sm hover:scale-[1.02] transition-transform">
                                Claim Monthly Free Tokens
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-8 rounded-[20px] shadow-sm flex flex-col justify-between items-start">
            <div>
                <span class="text-[10px] font-black uppercase text-[var(--color-ink-400)] tracking-wider block mb-1">Your Available Tokens</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-[var(--color-teal-500)]"><?= $user['unlimited_tokens'] ? 'Unlimited' : number_format($balance) ?></span>
                    <span class="text-xs font-bold text-[var(--color-ink-400)]">Tokens</span>
                </div>
                <p class="text-[11px] text-[var(--color-ink-600)] mt-2">Deducted when running premium utility tool workflows.</p>
            </div>
            <div class="flex gap-2 w-full mt-4">
                <a href="/pricing" class="btn-ember flex-1 py-2 text-xs font-bold text-center rounded-xl shadow-sm">Buy Packages</a>
                <a href="/token-history" class="btn-secondary flex-1 py-2 text-xs font-bold text-center rounded-xl">Ledger Ledger</a>
            </div>
        </div>
    </div>

    <!-- Quick Slots Showcase (Goal 2: Invoices, Notes limit to 3) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Saved Notes Slot (Limit 3) -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-[var(--color-border)] flex items-center justify-between">
                <h3 class="text-xs font-black uppercase text-[var(--color-ink-900)] tracking-wider">My Secured Encrypted Notes (Max 3 slots)</h3>
                <span class="text-[10px] font-black uppercase bg-[var(--color-ember-100)] text-[var(--color-ember-500)] px-2.5 py-1 rounded-full">Note Entries</span>
            </div>
            <div class="p-6 space-y-3 flex-grow">
                <?php if (empty($savedNotes)): ?>
                    <p class="text-xs text-[var(--color-ink-600)] py-4 text-center">No decrypted/encrypted notes found in database logs. Launch Encrypted Notes to get started.</p>
                <?php else: ?>
                    <?php foreach ($savedNotes as $note): ?>
                        <?php
                            $meta = json_decode($note['metadata'], true);
                            $preview = $meta['note_preview'] ?? 'Encrypted plain text note';
                        ?>
                        <div class="p-3.5 bg-[var(--color-surface-alt)] border border-[var(--color-border)] rounded-xl flex justify-between items-center text-xs">
                            <div class="truncate max-w-[70%]">
                                <span class="font-bold text-[var(--color-ink-900)] block truncate"><?= htmlspecialchars($preview) ?></span>
                                <span class="text-[10px] text-[var(--color-ink-400)]"><?= $note['created_at'] ?></span>
                            </div>
                            <a href="/tools/encrypted-note" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">Access &rarr;</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="px-6 py-3.5 bg-[var(--color-surface-alt)] border-t border-[var(--color-border)] text-right">
                <a href="/saved-notes" class="text-[11px] font-bold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)]">View All Saved Note Logs &rarr;</a>
            </div>
        </div>

        <!-- Generated Invoices Slot (Limit 3) -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-[var(--color-border)] flex items-center justify-between">
                <h3 class="text-xs font-black uppercase text-[var(--color-ink-900)] tracking-wider">My Saved Invoices (Max 3 slots)</h3>
                <span class="text-[10px] font-black uppercase bg-[var(--color-teal-100)] text-[var(--color-teal-500)] px-2.5 py-1 rounded-full">Receipts</span>
            </div>
            <div class="p-6 space-y-3 flex-grow">
                <?php if (empty($invoices)): ?>
                    <p class="text-xs text-[var(--color-ink-600)] py-4 text-center">No generated invoices saved. Generate professional invoices to view slots.</p>
                <?php else: ?>
                    <?php foreach ($invoices as $inv): ?>
                        <?php
                            $invData = json_decode($inv['invoice_data'], true);
                            $total = $invData['total'] ?? '0.00';
                            $currency = $invData['currency'] ?? 'USD';
                        ?>
                        <div class="p-3.5 bg-[var(--color-surface-alt)] border border-[var(--color-border)] rounded-xl flex justify-between items-center text-xs">
                            <div>
                                <span class="font-bold text-[var(--color-ink-900)] block">Invoice #<?= htmlspecialchars($inv['invoice_number']) ?></span>
                                <span class="text-[10px] text-[var(--color-ink-400)]"><?= $inv['created_at'] ?></span>
                            </div>
                            <div class="text-right flex items-center gap-3">
                                <span class="font-black text-[var(--color-ink-900)]"><?= htmlspecialchars($total) ?> <?= htmlspecialchars($currency) ?></span>
                                <a href="/my-invoices" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">View</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="px-6 py-3.5 bg-[var(--color-surface-alt)] border-t border-[var(--color-border)] text-right">
                <a href="/my-invoices" class="text-[11px] font-bold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)]">View All Saved Invoices &rarr;</a>
            </div>
        </div>

    </div>

    <!-- Additional URL Quick links -->
    <div class="mt-8 bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h4 class="text-sm font-bold text-[var(--color-ink-900)]">Shortened & Custom Redirections Analytics</h4>
            <p class="text-xs text-[var(--color-ink-600)]">Control short links and custom landing page click counts easily.</p>
        </div>
        <div class="flex gap-2">
            <a href="/tools/url-shortener" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl">Create Short Link</a>
            <a href="/my-urls" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl shadow-sm">My Short Link Logs</a>
        </div>
    </div>
</div>
