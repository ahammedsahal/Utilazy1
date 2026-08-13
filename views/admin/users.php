<?php
$pageTitle = "Manage Users — Utilazy";
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-10 space-y-8">
    <div class="flex items-center justify-between border-b border-[var(--color-border)] pb-6">
        <div>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">Manage Platform Users</h1>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Ban/deactivate accounts, toggle unlimited credits, adjust live tokens ledger balance.</p>
        </div>
        <a href="/admin" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl">&larr; Back to Admin Console</a>
    </div>

    <!-- Users Listings -->
    <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-[var(--color-border)] text-[10px] font-bold uppercase text-[var(--color-ink-400)] bg-[var(--color-surface-alt)]">
                        <th class="py-3.5 px-4">Name</th>
                        <th class="py-3.5 px-4">Email</th>
                        <th class="py-3.5 px-4">Ledger Balance</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Unlimited</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-border)]">
                    <?php foreach ($users as $usr): ?>
                        <tr class="hover:bg-[var(--color-surface-alt)] transition-colors">
                            <td class="py-3 px-4 font-bold text-[var(--color-ink-900)]"><?= htmlspecialchars($usr['name']) ?></td>
                            <td class="py-3 px-4 text-[var(--color-ink-600)]"><?= htmlspecialchars($usr['email']) ?></td>
                            <td class="py-3 px-4 font-black text-[var(--color-teal-500)]"><?= number_format($usr['balance'] ?? 0) ?> Tokens</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase <?= $usr['status'] === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500' ?>">
                                    <?= htmlspecialchars($usr['status']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase <?= $usr['unlimited_tokens'] ? 'bg-[var(--color-amber-100)] text-[var(--color-amber-400)]' : 'bg-zinc-100 text-zinc-400' ?>">
                                    <?= $usr['unlimited_tokens'] ? 'Yes' : 'No' ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right space-x-2.5">
                                <button onclick="openTokenAdjustmentModal(<?= $usr['id'] ?>, '<?= htmlspecialchars($usr['name']) ?>')" class="text-xs font-bold text-[var(--color-teal-500)] hover:underline">Adjust Balance</button>
                                <a href="/admin/users?action=toggle_unlimited&id=<?= $usr['id'] ?>" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">Toggle Unlimited</a>
                                <?php if ($usr['status'] === 'active'): ?>
                                    <a href="/admin/users?action=deactivate&id=<?= $usr['id'] ?>" class="text-xs font-bold text-red-500 hover:underline">Deactivate/Ban</a>
                                <?php else: ?>
                                    <a href="/admin/users?action=activate&id=<?= $usr['id'] ?>" class="text-xs font-bold text-green-500 hover:underline">Activate</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Token Adjustment Modal -->
<div id="adjust-token-modal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-lg max-w-sm w-full p-6 space-y-4">
        <h3 class="text-sm font-black text-[var(--color-ink-900)] uppercase">Adjust Tokens Ledger Balance</h3>
        <p id="modal-user-target" class="text-xs text-[var(--color-ink-600)]">User: John Doe</p>
        <form id="adjust-token-form" method="POST" action="" class="space-y-4">
            <?= \App\Helpers\CSRF::input() ?>
            <div>
                <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Adjustment Amount</label>
                <input type="number" name="amount" required placeholder="Use positive to credit, negative to deduct" class="input-custom w-full h-10 px-4 text-xs">
            </div>
            <div class="flex justify-end gap-2 text-xs font-bold">
                <button type="button" onclick="closeTokenModal()" class="btn-secondary px-4 py-2 rounded-xl">Cancel</button>
                <button type="submit" class="btn-ember px-4 py-2 rounded-xl shadow-sm">Save Balance</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openTokenAdjustmentModal(id, name) {
        document.getElementById('modal-user-target').textContent = 'User Target: ' + name;
        document.getElementById('adjust-token-form').action = '/admin/users?action=adjust_tokens&id=' + id;
        document.getElementById('adjust-token-modal').classList.remove('hidden');
    }
    function closeTokenModal() {
        document.getElementById('adjust-token-modal').classList.add('hidden');
    }
</script>
