<div class="space-y-4">
    <div><label class="block text-xs font-bold uppercase mb-1">Raw Input Text</label><textarea id="b64-raw" placeholder="Text..." class="input-custom w-full h-44 p-4 text-sm"></textarea></div>
    <div class="flex gap-2">
        <button onclick="runB64('encode')" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl flex-grow shadow-sm">Encode Base64</button>
        <button onclick="runB64('decode')" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl flex-grow">Decode Base64</button>
    </div>
    <div><label class="block text-xs font-bold uppercase mb-1">Transformed Output</label><textarea id="b64-output" readonly class="input-custom w-full h-44 p-4 text-sm bg-[var(--color-surface-alt)] code-font"></textarea></div>
</div>
<script>
    function runB64(action) {
        const input = document.getElementById('b64-raw').value; if (input.trim() === '') return;
        try {
            if (action === 'encode') {
                document.getElementById('b64-output').value = btoa(unescape(encodeURIComponent(input))); showToast('Encoded!');
            } else {
                document.getElementById('b64-output').value = decodeURIComponent(escape(atob(input))); showToast('Decoded!');
            }
        } catch(e) { showToast('Base64 processing failed!', false); }
    }
</script>