<?php
$pageTitle = "Admin Dashboard — Utilazy";
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-10 space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-[var(--color-border)] pb-6 gap-4">
        <div>
            <span class="text-[10px] uppercase font-black tracking-wider text-[var(--color-ember-500)] bg-[var(--color-ember-100)] px-2.5 py-1 rounded-full">Secure Management Console</span>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight mt-2">Utilazy Platform Controller</h1>
        </div>
        <div class="flex flex-wrap gap-2 text-xs font-bold">
            <a href="/admin/users" class="btn-secondary px-4 py-2 rounded-xl">Users</a>
            <a href="/admin/tools" class="btn-secondary px-4 py-2 rounded-xl">Tools</a>
            <a href="/admin/payments" class="btn-secondary px-4 py-2 rounded-xl">Payments</a>
            <a href="/admin/coupons" class="btn-secondary px-4 py-2 rounded-xl">Coupons</a>
            <a href="/admin/branding" class="btn-secondary px-4 py-2 rounded-xl">Branding Tokens</a>
            <a href="/admin/code-injection" class="btn-secondary px-4 py-2 rounded-xl">Injections</a>
            <a href="/admin/settings" class="btn-ember px-4 py-2 rounded-xl shadow-sm">Site Settings</a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-5 rounded-[20px] shadow-sm">
            <span class="block text-[10px] font-black uppercase text-[var(--color-ink-400)] tracking-wider mb-1">Registered Users</span>
            <span class="text-3xl font-black text-[var(--color-ink-900)]"><?= number_format($stats['total_users']) ?></span>
        </div>
        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-5 rounded-[20px] shadow-sm">
            <span class="block text-[10px] font-black uppercase text-[var(--color-ink-400)] tracking-wider mb-1">Total Token Runs</span>
            <span class="text-3xl font-black text-[var(--color-teal-500)]"><?= number_format($stats['total_tokens_used']) ?></span>
        </div>
        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-5 rounded-[20px] shadow-sm">
            <span class="block text-[10px] font-black uppercase text-[var(--color-ink-400)] tracking-wider mb-1">Total Platform Revenue</span>
            <span class="text-3xl font-black text-green-500">$<?= number_format($stats['total_revenue'], 2) ?></span>
        </div>
        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-5 rounded-[20px] shadow-sm">
            <span class="block text-[10px] font-black uppercase text-[var(--color-ink-400)] tracking-wider mb-1">Shortened URLs Redirection</span>
            <span class="text-3xl font-black text-[var(--color-ember-500)]"><?= number_format($stats['total_redirects']) ?></span>
        </div>
    </div>

    <!-- Split details views -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent usage -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-[var(--color-border)] flex items-center justify-between">
                <h3 class="text-xs font-black uppercase tracking-wider text-[var(--color-ink-900)]">Recent Tools Activity</h3>
                <span class="text-[10px] bg-[var(--color-ember-100)] text-[var(--color-ember-500)] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">Usage</span>
            </div>
            <div class="p-6 space-y-4">
                <?php if (empty($recentUsage)): ?>
                    <p class="text-xs text-[var(--color-ink-600)] text-center py-4">No recent tools usage logged in ledger.</p>
                <?php else: ?>
                    <?php foreach ($recentUsage as $use): ?>
                        <div class="flex justify-between items-center text-xs">
                            <div>
                                <span class="font-bold text-[var(--color-ink-900)] block"><?= htmlspecialchars($use['tool_name']) ?></span>
                                <span class="text-[10px] text-[var(--color-ink-400)]">User: <?= htmlspecialchars($use['email']) ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-black text-red-500">-<?= $use['token_cost'] ?> Tokens</span>
                                <span class="block text-[10px] text-[var(--color-ink-400)]"><?= $use['created_at'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent payments -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-[var(--color-border)] flex items-center justify-between">
                <h3 class="text-xs font-black uppercase tracking-wider text-[var(--color-ink-900)]">Recent Transaction Receipts</h3>
                <span class="text-[10px] bg-[var(--color-teal-100)] text-[var(--color-teal-500)] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">Payments</span>
            </div>
            <div class="p-6 space-y-4">
                <?php if (empty($recentPayments)): ?>
                    <p class="text-xs text-[var(--color-ink-600)] text-center py-4">No receipts recorded in ledger yet.</p>
                <?php else: ?>
                    <?php foreach ($recentPayments as $pay): ?>
                        <div class="flex justify-between items-center text-xs">
                            <div>
                                <span class="font-bold text-[var(--color-ink-900)] block">Order #<?= htmlspecialchars($pay['provider_order_id']) ?></span>
                                <span class="text-[10px] text-[var(--color-ink-400)]">Email: <?= htmlspecialchars($pay['email']) ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-black text-green-500">+$<?= number_format($pay['amount'], 2) ?></span>
                                <span class="block text-[10px] text-[var(--color-ink-400)]"><?= $pay['created_at'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
