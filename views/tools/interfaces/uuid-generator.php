<div class="space-y-6">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider mb-2">Quantity</label>
                <input type="number" id="uuid-count" min="1" max="100" value="5" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:border-[var(--color-ember-500)]">
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider mb-2">Version</label>
                <select id="uuid-version" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:border-[var(--color-ember-500)]">
                    <option value="v4">UUID v4 (Random)</option>
                    <option value="v1">UUID v1 (Timestamp based)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider mb-2">Format Options</label>
                <div class="flex items-center gap-4 mt-2">
                    <label class="inline-flex items-center gap-1.5 text-xs text-[var(--color-ink-600)] cursor-pointer">
                        <input type="checkbox" id="uuid-uppercase" class="rounded accent-[var(--color-ember-500)]"> Uppercase
                    </label>
                    <label class="inline-flex items-center gap-1.5 text-xs text-[var(--color-ink-600)] cursor-pointer">
                        <input type="checkbox" id="uuid-hyphens" checked class="rounded accent-[var(--color-ember-500)]"> Hyphens
                    </label>
                </div>
            </div>
        </div>

        <button id="generate-uuid-btn" class="btn-ember px-8 py-3 rounded-xl font-bold text-xs shadow-sm">⚡ Generate UUIDs</button>
    </div>

    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Generated UUID Tokens</span>
            <button id="copy-uuid-btn" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">⧉ Copy All</button>
        </div>
        <textarea id="uuid-output" readonly class="w-full h-48 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono text-[var(--color-teal-500)] focus:outline-none"></textarea>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const generateBtn = document.getElementById("generate-uuid-btn");
    const copyBtn = document.getElementById("copy-uuid-btn");
    const countInput = document.getElementById("uuid-count");
    const uppercaseCb = document.getElementById("uuid-uppercase");
    const hyphensCb = document.getElementById("uuid-hyphens");
    const output = document.getElementById("uuid-output");

    function generateV4() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    function runGeneration() {
        const count = parseInt(countInput.value, 10) || 1;
        const uppercase = uppercaseCb.checked;
        const hyphens = hyphensCb.checked;

        let results = [];
        for (let i = 0; i < count; i++) {
            let u = generateV4();
            if (!hyphens) u = u.replace(/-/g, '');
            if (uppercase) u = u.toUpperCase();
            results.push(u);
        }
        output.value = results.join("\n");
    }

    generateBtn.addEventListener("click", runGeneration);
    copyBtn.addEventListener("click", () => {
        navigator.clipboard.writeText(output.value);
        alert("UUIDs copied to clipboard!");
    });

    runGeneration();
});
</script>