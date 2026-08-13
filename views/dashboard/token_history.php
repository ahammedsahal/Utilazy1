<?php
$pageTitle = "Token Transaction History — Utilazy";
?>

<div class="max-w-4xl mx-auto my-10 px-6 py-8 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm">
    <div class="flex items-center justify-between mb-6 pb-6 border-b border-[var(--color-border)]">
        <div>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">Ledger Transaction History</h1>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Review complete audit logs of every token transaction on your account.</p>
        </div>
        <div class="text-right">
            <span class="block text-[10px] font-black uppercase text-[var(--color-ink-400)] tracking-wider">Current Balance</span>
            <span class="text-2xl font-black text-[var(--color-teal-500)]"><?= number_format($balance) ?> Tokens</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="border-b border-[var(--color-border)] text-[10px] font-bold uppercase text-[var(--color-ink-400)]">
                    <th class="py-3 px-2">Date</th>
                    <th class="py-3 px-2">Description</th>
                    <th class="py-3 px-2">Type</th>
                    <th class="py-3 px-2 text-right">Amount</th>
                    <th class="py-3 px-2 text-right">Balance After</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--color-border)]">
                <?php if (empty($history)): ?>
                    <tr>
                        <td colspan="5" class="py-8 text-center text-[var(--color-ink-600)]">No token transactions recorded yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($history as $tx): ?>
                        <tr class="hover:bg-[var(--color-surface-alt)] transition-colors">
                            <td class="py-3 px-2 text-[var(--color-ink-400)]"><?= $tx['created_at'] ?></td>
                            <td class="py-3 px-2 font-semibold text-[var(--color-ink-900)]"><?= htmlspecialchars($tx['description']) ?></td>
                            <td class="py-3 px-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase <?= $tx['transaction_type'] === 'purchase' || $tx['transaction_type'] === 'promotional' ? 'bg-[var(--color-teal-100)] text-[var(--color-teal-500)]' : 'bg-red-100 text-red-500' ?>">
                                    <?= htmlspecialchars($tx['transaction_type']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-2 text-right font-bold <?= $tx['amount'] >= 0 ? 'text-green-500' : 'text-red-500' ?>">
                                <?= $tx['amount'] >= 0 ? '+' : '' ?><?= number_format($tx['amount']) ?>
                            </td>
                            <td class="py-3 px-2 text-right font-black text-[var(--color-ink-900)]"><?= number_format($tx['balance_after']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
