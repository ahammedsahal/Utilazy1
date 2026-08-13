<div class="space-y-4">
    <div><label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Raw JSON Input</label><textarea id="json-input" class="input-custom w-full h-56 p-4 text-sm code-font">{"status":"active","count":10}</textarea></div>
    <div class="flex flex-wrap gap-2">
        <button onclick="formatJSON(2)" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl shadow-sm">Prettify (2 spaces)</button>
        <button onclick="minifyJSON()" class="p-2.5 bg-[var(--color-surface-alt)] text-xs font-bold rounded-xl border">Minify</button>
    </div>
    <div><label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Formatted Output</label><textarea id="json-output" readonly class="input-custom w-full h-56 p-4 text-sm bg-[var(--color-surface-alt)] code-font"></textarea></div>
</div>
<script>
    function formatJSON(spaces) {
        const input = document.getElementById('json-input').value; if (input.trim() === '') return;
        try { const parsed = JSON.parse(input); document.getElementById('json-output').value = JSON.stringify(parsed, null, spaces); showToast('JSON prettified!'); }
        catch(e) { showToast('Invalid JSON structure!', false); }
    }
    function minifyJSON() {
        const input = document.getElementById('json-input').value; if (input.trim() === '') return;
        try { const parsed = JSON.parse(input); document.getElementById('json-output').value = JSON.stringify(parsed); showToast('JSON minified.'); }
        catch(e) { showToast('Invalid JSON!', false); }
    }
</script>