<?php
$pageTitle = "Manage Tools — Utilazy";
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-10 space-y-8">
    <div class="flex items-center justify-between border-b border-[var(--color-border)] pb-6">
        <div>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">System Tools Registry</h1>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Configure active tool states, adjust required token cost limits, and update the mega-dropdown real-time.</p>
        </div>
        <a href="/admin" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl">&larr; Back to Admin Console</a>
    </div>

    <!-- Tools List -->
    <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-[var(--color-border)] text-[10px] font-bold uppercase text-[var(--color-ink-400)] bg-[var(--color-surface-alt)]">
                        <th class="py-3.5 px-4">Tool</th>
                        <th class="py-3.5 px-4">Category</th>
                        <th class="py-3.5 px-4">Access Tier</th>
                        <th class="py-3.5 px-4">Token Cost</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-border)]">
                    <?php foreach ($tools as $tool): ?>
                        <tr class="hover:bg-[var(--color-surface-alt)] transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-xl"><?= $tool['icon'] ?></span>
                                    <div>
                                        <span class="font-bold text-[var(--color-ink-900)] block"><?= htmlspecialchars($tool['name']) ?></span>
                                        <span class="text-[10px] text-[var(--color-ink-400)]">Slug: <?= htmlspecialchars($tool['slug']) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-[var(--color-ink-600)] font-medium"><?= htmlspecialchars($tool['category_name']) ?></td>
                            <td class="py-3 px-4 font-bold uppercase text-[var(--color-ink-400)]"><?= htmlspecialchars($tool['access_type']) ?></td>
                            <td class="py-3 px-4 font-black text-[var(--color-teal-500)]"><?= number_format($tool['token_cost']) ?> Tokens</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase <?= $tool['enabled'] ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500' ?>">
                                    <?= $tool['enabled'] ? 'Enabled' : 'Disabled' ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button onclick="openToolEditModal(<?= $tool['id'] ?>, '<?= htmlspecialchars($tool['name']) ?>', <?= $tool['token_cost'] ?>, <?= $tool['enabled'] ?>)" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">Edit Tool</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Tool Settings Modal -->
<div id="edit-tool-modal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-lg max-w-sm w-full p-6 space-y-4">
        <h3 class="text-sm font-black text-[var(--color-ink-900)] uppercase">Edit Tool Setting</h3>
        <p id="modal-tool-title" class="text-xs text-[var(--color-ink-600)]">Tool: Unit Converter</p>
        <form id="edit-tool-form" method="POST" action="/admin/tools/update" class="space-y-4">
            <?= \App\Helpers\CSRF::input() ?>
            <input type="hidden" name="id" id="edit-tool-id">

            <div>
                <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Required Token Cost</label>
                <input type="number" name="token_cost" id="edit-tool-cost" required class="input-custom w-full h-10 px-4 text-xs">
            </div>

            <label class="flex items-center gap-2 cursor-pointer py-1">
                <input type="checkbox" name="enabled" id="edit-tool-enabled" value="1" class="rounded text-[var(--color-ember-500)] focus:ring-[var(--color-ember-500)] border-[var(--color-border)]">
                <span class="text-xs font-bold text-[var(--color-ink-600)]">Enable / Publish Tool on Platform</span>
            </label>

            <div class="flex justify-end gap-2 text-xs font-bold">
                <button type="button" onclick="closeToolModal()" class="btn-secondary px-4 py-2 rounded-xl">Cancel</button>
                <button type="submit" class="btn-ember px-4 py-2 rounded-xl shadow-sm">Save Registry</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openToolEditModal(id, name, cost, enabled) {
        document.getElementById('edit-tool-id').value = id;
        document.getElementById('modal-tool-title').textContent = 'Tool Target: ' + name;
        document.getElementById('edit-tool-cost').value = cost;
        document.getElementById('edit-tool-enabled').checked = !!enabled;
        document.getElementById('edit-tool-modal').classList.remove('hidden');
    }
    function closeToolModal() {
        document.getElementById('edit-tool-modal').classList.add('hidden');
    }
</script>
