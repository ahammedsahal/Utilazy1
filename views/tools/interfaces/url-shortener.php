<div class="space-y-4 max-w-lg mx-auto">
    <div>
        <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Enter Destination URL</label>
        <input type="text" id="short-url-input" placeholder="https://example.com/some/long/path" class="input-custom w-full h-11 px-4 text-sm">
    </div>
    <button onclick="createShortUrl()" class="btn-ember w-full py-2.5 rounded-xl font-bold text-xs shadow-sm">Shorten Link</button>
    <div id="short-url-result" class="hidden p-4 bg-[var(--color-surface-alt)] border border-[var(--color-border)] rounded-xl space-y-2">
        <span class="block text-[10px] font-bold text-[var(--color-ink-400)] uppercase">Your Shortened Link:</span>
        <div class="flex gap-2">
            <input type="text" id="short-url-output" readonly class="input-custom flex-grow h-9 px-3 text-xs bg-white font-bold text-[var(--color-ember-500)]">
            <button onclick="copyToClipboard('short-url-output')" class="btn-ember px-3 h-9 text-xs font-bold rounded-lg shadow-sm">Copy</button>
        </div>
    </div>
</div>
<script>
    async function createShortUrl() {
        const url = document.getElementById('short-url-input').value.trim();
        if (url === '') { showToast('URL is empty!', false); return; }
        const formData = new FormData();
        formData.append('original_url', url);
        try {
            const res = await fetch('/api/shorten', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.error) {
                showToast(data.error, false);
            } else {
                document.getElementById('short-url-output').value = data.short_url;
                document.getElementById('short-url-result').classList.remove('hidden');
                showToast('URL shortened!');
            }
        } catch(e) { showToast('Shortening failed.', false); }
    }
</script>