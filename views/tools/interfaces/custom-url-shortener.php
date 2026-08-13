<div class="space-y-4 max-w-lg mx-auto">
    <div>
        <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Enter Destination URL</label>
        <input type="text" id="cust-url-input" placeholder="https://example.com/some/long/path" class="input-custom w-full h-11 px-4 text-sm">
    </div>
    <div>
        <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Enter Custom Slug</label>
        <div class="flex items-center gap-1">
            <span class="text-xs font-bold text-zinc-500">utilazy.com/go/</span>
            <input type="text" id="cust-slug-input" placeholder="my-custom-slug" class="input-custom flex-grow h-11 px-4 text-sm">
        </div>
    </div>
    <button onclick="createCustomUrl()" class="btn-ember w-full py-2.5 rounded-xl font-bold text-xs shadow-sm">Create Custom Redirect</button>
    <div id="cust-url-result" class="hidden p-4 bg-[var(--color-surface-alt)] border border-[var(--color-border)] rounded-xl space-y-2">
        <span class="block text-[10px] font-bold text-[var(--color-ink-400)] uppercase">Your Custom Redirect Link:</span>
        <div class="flex gap-2">
            <input type="text" id="cust-url-output" readonly class="input-custom flex-grow h-9 px-3 text-xs bg-white font-bold text-[var(--color-ember-500)]">
            <button onclick="copyToClipboard('cust-url-output')" class="btn-ember px-3 h-9 text-xs font-bold rounded-lg shadow-sm">Copy</button>
        </div>
    </div>
</div>
<script>
    async function createCustomUrl() {
        const url = document.getElementById('cust-url-input').value.trim();
        const slug = document.getElementById('cust-slug-input').value.trim();
        if (url === '' || slug === '') { showToast('Inputs are required!', false); return; }
        const formData = new FormData();
        formData.append('destination_url', url);
        formData.append('slug', slug);
        try {
            const res = await fetch('/api/shorten-custom', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.error) {
                showToast(data.error, false);
            } else {
                document.getElementById('cust-url-output').value = data.custom_url;
                document.getElementById('cust-url-result').classList.remove('hidden');
                showToast('Custom link created!');
            }
        } catch(e) { showToast('Custom link creation failed.', false); }
    }
</script>