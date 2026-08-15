<div class="space-y-6">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider mb-1">Regular Expression Pattern</label>
                <input type="text" id="regex-pattern" value="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl px-3 py-2 text-xs font-mono focus:outline-none focus:border-[var(--color-ember-500)]">
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider mb-1">Flags</label>
                <input type="text" id="regex-flags" value="gi" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl px-3 py-2 text-xs font-mono focus:outline-none focus:border-[var(--color-ember-500)]">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider mb-1">Test String</label>
            <textarea id="regex-input" class="w-full h-32 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono focus:outline-none focus:border-[var(--color-ember-500)]" placeholder="Enter text to test matches against...">Hello user@utilazy.com, please reach support at help@utilazy.com or sales@domain.org.</textarea>
        </div>

        <button id="btn-run-regex" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Test Matches →</button>
    </div>

    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3">
        <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider block">Matches Found</span>
        <div id="regex-output" class="p-3 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl text-xs font-mono text-[var(--color-teal-500)] min-h-[80px]">Results will appear here...</div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const patternInput = document.getElementById("regex-pattern");
    const flagsInput = document.getElementById("regex-flags");
    const testInput = document.getElementById("regex-input");
    const output = document.getElementById("regex-output");

    function runRegex() {
        try {
            const re = new RegExp(patternInput.value, flagsInput.value);
            const matches = testInput.value.match(re);
            if (matches && matches.length > 0) {
                output.innerHTML = "<strong>Found " + matches.length + " match(es):</strong><br>" + matches.map(m => "• " + m).join("<br>");
            } else {
                output.innerHTML = "<span class='text-[var(--color-ink-400)]'>No matches found.</span>";
            }
        } catch(e) {
            output.innerHTML = "<span class='text-red-500'>Invalid Regex: " + e.message + "</span>";
        }
    }

    document.getElementById("btn-run-regex").addEventListener("click", runRegex);
    runRegex();
});
</script>