<div class="space-y-6">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Target String / URL Query</label>
        <textarea id="url-input" class="w-full h-32 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono focus:outline-none focus:border-[var(--color-ember-500)]" placeholder="Paste raw URL or string here..."></textarea>

        <div class="flex flex-wrap items-center gap-3">
            <button id="btn-url-encode" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Encode URL</button>
            <button id="btn-url-decode" class="px-6 py-2.5 bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-ink-900)] rounded-xl text-xs font-bold hover:bg-[var(--color-border)]">Decode URL</button>
            <button id="btn-url-clear" class="px-4 py-2.5 text-[var(--color-ink-400)] text-xs hover:underline">Clear</button>
        </div>
    </div>

    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Output Result</span>
            <button id="btn-url-copy" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">⧉ Copy Output</button>
        </div>
        <textarea id="url-output" readonly class="w-full h-32 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono text-[var(--color-teal-500)] focus:outline-none"></textarea>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("url-input");
    const output = document.getElementById("url-output");

    document.getElementById("btn-url-encode").addEventListener("click", () => {
        try { output.value = encodeURIComponent(input.value); } catch(e) { output.value = "Error encoding URL"; }
    });

    document.getElementById("btn-url-decode").addEventListener("click", () => {
        try { output.value = decodeURIComponent(input.value); } catch(e) { output.value = "Error decoding URL"; }
    });

    document.getElementById("btn-url-clear").addEventListener("click", () => {
        input.value = "";
        output.value = "";
    });

    document.getElementById("btn-url-copy").addEventListener("click", () => {
        navigator.clipboard.writeText(output.value);
        alert("Copied!");
    });
});
</script>