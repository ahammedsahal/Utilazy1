<?php
$pageTitle = "Manage Coupons — Utilazy";
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-10 space-y-8">
    <div class="flex items-center justify-between border-b border-[var(--color-border)] pb-6">
        <div>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">Platform Coupons Registry</h1>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Add, update, or revoke promotion codes, percentage off, or bonus token coupons.</p>
        </div>
        <a href="/admin" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl">&larr; Back to Admin Console</a>
    </div>

    <!-- Grid: Add Coupon Form & Coupon List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Form -->
        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] shadow-sm space-y-4">
            <h3 class="text-sm font-black uppercase text-[var(--color-ink-900)] tracking-wider">Add New Coupon</h3>
            <form action="/admin/coupons/add" method="POST" class="space-y-4">
                <?= \App\Helpers\CSRF::input() ?>

                <div>
                    <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Coupon Code</label>
                    <input type="text" name="code" required placeholder="WELCOME100" class="input-custom w-full h-10 px-4 text-xs font-black uppercase">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Coupon Type</label>
                    <select name="type" required class="input-custom w-full h-10 px-4 text-xs font-bold">
                        <option value="percentage">Percentage Discount (%)</option>
                        <option value="fixed">Fixed Discount ($)</option>
                        <option value="bonus">Free/Bonus Tokens Only</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Discount Value</label>
                    <input type="number" step="0.01" name="value" required value="0" class="input-custom w-full h-10 px-4 text-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Free Bonus Tokens Awarded</label>
                    <input type="number" name="bonus_tokens" required value="0" class="input-custom w-full h-10 px-4 text-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Global Usage Limit</label>
                    <input type="number" name="usage_limit" placeholder="Leave empty for unlimited" class="input-custom w-full h-10 px-4 text-xs">
                </div>

                <button type="submit" class="btn-ember w-full h-10 text-xs font-bold rounded-xl shadow-sm">Save Coupon Code</button>
            </form>
        </div>

        <!-- List -->
        <div class="col-span-1 lg:col-span-2 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[var(--color-border)] text-[10px] font-bold uppercase text-[var(--color-ink-400)] bg-[var(--color-surface-alt)]">
                            <th class="py-3.5 px-4">Coupon Code</th>
                            <th class="py-3.5 px-4">Type</th>
                            <th class="py-3.5 px-4">Value</th>
                            <th class="py-3.5 px-4">Bonus Tokens</th>
                            <th class="py-3.5 px-4">Usage Limit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-border)]">
                        <?php foreach ($coupons as $cop): ?>
                            <tr class="hover:bg-[var(--color-surface-alt)] transition-colors">
                                <td class="py-3 px-4 font-black text-[var(--color-ember-500)]"><?= htmlspecialchars($cop['code']) ?></td>
                                <td class="py-3 px-4 font-bold uppercase text-[var(--color-ink-400)]"><?= htmlspecialchars($cop['type']) ?></td>
                                <td class="py-3 px-4 font-bold text-[var(--color-ink-900)]">
                                    <?= $cop['type'] === 'percentage' ? number_format($cop['value']) . '%' : '$' . number_format($cop['value'], 2) ?>
                                </td>
                                <td class="py-3 px-4 font-black text-[var(--color-teal-500)]">+<?= number_format($cop['bonus_tokens']) ?> Tokens</td>
                                <td class="py-3 px-4 text-[var(--color-ink-600)] font-medium"><?= $cop['usage_limit'] ?? 'Unlimited' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
