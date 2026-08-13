<?php
$pageTitle = "My Shortened URLs — Utilazy";
?>

<div class="max-w-6xl mx-auto my-10 px-6 py-8 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm">
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">My Shortened URLs</h1>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Check clicks, redirect channels, custom slugs, and QR configurations.</p>
        </div>
        <div class="flex gap-2">
            <a href="/tools/url-shortener" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl shadow-sm">Shorten New Link</a>
            <a href="/tools/custom-url-shortener" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl">Create Custom Slug</a>
        </div>
    </div>

    <div class="space-y-8">
        <!-- Shortened URLs Section -->
        <div>
            <h3 class="text-xs font-black uppercase text-[var(--color-ink-400)] tracking-wider mb-4">Standard Shortened URLs</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[var(--color-border)] text-[10px] font-bold uppercase text-[var(--color-ink-400)]">
                            <th class="py-3 px-2">Short Link</th>
                            <th class="py-3 px-2">Original Destination</th>
                            <th class="py-3 px-2">Expires At</th>
                            <th class="py-3 px-2 text-center">Clicks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-border)]">
                        <?php if (empty($shortenedUrls)): ?>
                            <tr>
                                <td colspan="4" class="py-6 text-center text-[var(--color-ink-600)]">No standard URLs created yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($shortenedUrls as $url): ?>
                                <tr class="hover:bg-[var(--color-surface-alt)] transition-colors">
                                    <td class="py-3 px-2 font-bold text-[var(--color-ember-500)]">
                                        <a href="/s/<?= htmlspecialchars($url['short_code']) ?>" target="_blank" class="hover:underline">
                                            utilazy.com/s/<?= htmlspecialchars($url['short_code']) ?>
                                        </a>
                                    </td>
                                    <td class="py-3 px-2 text-[var(--color-ink-600)] truncate max-w-xs" title="<?= htmlspecialchars($url['original_url']) ?>">
                                        <?= htmlspecialchars($url['original_url']) ?>
                                    </td>
                                    <td class="py-3 px-2 text-[var(--color-ink-400)]"><?= $url['expires_at'] ?? 'Never' ?></td>
                                    <td class="py-3 px-2 text-center font-bold text-[var(--color-ink-900)]"><?= number_format($url['clicks']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Custom URLs Section -->
        <div>
            <h3 class="text-xs font-black uppercase text-[var(--color-ink-400)] tracking-wider mb-4">Custom Slug Redirections</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[var(--color-border)] text-[10px] font-bold uppercase text-[var(--color-ink-400)]">
                            <th class="py-3 px-2">Custom URL</th>
                            <th class="py-3 px-2">Destination</th>
                            <th class="py-3 px-2">Expires At</th>
                            <th class="py-3 px-2 text-center">Clicks</th>
                            <th class="py-3 px-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-border)]">
                        <?php if (empty($customUrls)): ?>
                            <tr>
                                <td colspan="5" class="py-6 text-center text-[var(--color-ink-600)]">No custom slug redirections created yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($customUrls as $curl): ?>
                                <tr class="hover:bg-[var(--color-surface-alt)] transition-colors">
                                    <td class="py-3 px-2 font-bold text-[var(--color-teal-500)]">
                                        <a href="/go/<?= htmlspecialchars($curl['slug']) ?>" target="_blank" class="hover:underline">
                                            utilazy.com/go/<?= htmlspecialchars($curl['slug']) ?>
                                        </a>
                                    </td>
                                    <td class="py-3 px-2 text-[var(--color-ink-600)] truncate max-w-xs" title="<?= htmlspecialchars($curl['destination_url']) ?>">
                                        <?= htmlspecialchars($curl['destination_url']) ?>
                                    </td>
                                    <td class="py-3 px-2 text-[var(--color-ink-400)]"><?= $curl['expires_at'] ?? 'Never' ?></td>
                                    <td class="py-3 px-2 text-center font-bold text-[var(--color-ink-900)]"><?= number_format($curl['clicks']) ?></td>
                                    <td class="py-3 px-2 text-right">
                                        <!-- Editable Destination inline (Section 11) -->
                                        <button onclick="editCustomSlug(<?= $curl['id'] ?>, '<?= htmlspecialchars($curl['destination_url']) ?>')" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">Edit URL</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Simple Edit Custom Redirect Modal -->
<div id="edit-url-modal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-lg max-w-md w-full p-6 space-y-4">
        <h3 class="text-sm font-black text-[var(--color-ink-900)] uppercase">Update Custom Redirection Destination</h3>
        <form id="edit-url-form" onsubmit="submitEditUrl(event)" class="space-y-4">
            <input type="hidden" id="edit-url-id">
            <div>
                <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">New Destination URL</label>
                <input type="url" id="edit-url-destination" required class="input-custom w-full h-10 px-4 text-xs">
            </div>
            <div class="flex justify-end gap-2 text-xs">
                <button type="button" onclick="closeEditModal()" class="btn-secondary px-4 py-2 rounded-xl">Cancel</button>
                <button type="submit" class="btn-ember px-4 py-2 rounded-xl shadow-sm font-bold">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editCustomSlug(id, currentUrl) {
        document.getElementById('edit-url-id').value = id;
        document.getElementById('edit-url-destination').value = currentUrl;
        document.getElementById('edit-url-modal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('edit-url-modal').classList.add('hidden');
    }
    async function submitEditUrl(e) {
        e.preventDefault();
        const id = document.getElementById('edit-url-id').value;
        const destination = document.getElementById('edit-url-destination').value;
        const res = await fetch('/api/urls/edit', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, destination_url: destination })
        });
        const data = await res.json();
        if (data.success) {
            showToast('Destination updated successfully!');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast(data.message || 'Update failed', false);
        }
    }
</script>
