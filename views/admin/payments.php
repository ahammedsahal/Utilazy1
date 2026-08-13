<?php
$pageTitle = "Manage Payments — Utilazy";
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-10 space-y-8">
    <div class="flex items-center justify-between border-b border-[var(--color-border)] pb-6">
        <div>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">Financial Ledger Payments Monitoring</h1>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Review complete Lemon Squeezy sandbox checkout receipts, transactions, and available credits packages.</p>
        </div>
        <a href="/admin" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl">&larr; Back to Admin Console</a>
    </div>

    <!-- Active Token Packages -->
    <div>
        <h3 class="text-xs font-black uppercase text-[var(--color-ink-400)] tracking-wider mb-4">Platform Token Packages</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($packages as $pkg): ?>
                <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] shadow-sm relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-[10px] bg-[var(--color-teal-100)] text-[var(--color-teal-500)] font-black px-2.5 py-1 rounded-full uppercase tracking-wider"><?= htmlspecialchars($pkg['name']) ?></span>
                            <?php if ($pkg['bonus_tokens'] > 0): ?>
                                <span class="text-[10px] bg-[var(--color-amber-100)] text-[var(--color-ink-900)] font-black px-2 py-0.5 rounded-full">+<?= number_format($pkg['bonus_tokens']) ?> Bonus</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-3xl font-black text-[var(--color-ink-900)]">$<?= number_format($pkg['price'], 2) ?></span>
                            <span class="text-xs text-[var(--color-ink-600)]">/ <?= number_format($pkg['tokens']) ?> Tokens</span>
                        </div>
                    </div>
                    <span class="text-[10px] text-[var(--color-ink-400)] font-bold block">Lemon Squeezy Product ID: <?= htmlspecialchars($pkg['lemon_product_id'] ?? 'mock_prod_123') ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Historical Receipts -->
    <div>
        <h3 class="text-xs font-black uppercase text-[var(--color-ink-400)] tracking-wider mb-4">Completed Payments Receipts</h3>
        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[var(--color-border)] text-[10px] font-bold uppercase text-[var(--color-ink-400)] bg-[var(--color-surface-alt)]">
                            <th class="py-3.5 px-4">Receipt Order ID</th>
                            <th class="py-3.5 px-4">User</th>
                            <th class="py-3.5 px-4">Provider</th>
                            <th class="py-3.5 px-4">Total Amount</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Receipt Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-border)]">
                        <?php if (empty($payments)): ?>
                            <tr>
                                <td colspan="6" class="py-6 text-center text-[var(--color-ink-600)]">No historical financial transactions recorded.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($payments as $pay): ?>
                                <tr class="hover:bg-[var(--color-surface-alt)] transition-colors">
                                    <td class="py-3 px-4 font-bold text-[var(--color-ink-900)]">#<?= htmlspecialchars($pay['provider_order_id']) ?></td>
                                    <td class="py-3 px-4 text-[var(--color-ink-600)]"><?= htmlspecialchars($pay['email']) ?></td>
                                    <td class="py-3 px-4 font-bold uppercase text-[var(--color-ink-400)]"><?= htmlspecialchars($pay['provider']) ?></td>
                                    <td class="py-3 px-4 font-black text-green-500">$<?= number_format($pay['amount'], 2) ?></td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-green-100 text-green-600">
                                            <?= htmlspecialchars($pay['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-[var(--color-ink-400)]"><?= $pay['created_at'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
