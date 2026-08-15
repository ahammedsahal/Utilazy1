<div class="space-y-6">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">HTML / CSS / JS Code Input</label>
        <textarea id="code-input" class="w-full h-40 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono focus:outline-none focus:border-[var(--color-ember-500)]" placeholder="Paste HTML, CSS, or JS code here..."></textarea>

        <div class="flex items-center gap-3">
            <button id="btn-beautify" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Beautify Code</button>
            <button id="btn-code-clear" class="px-4 py-2.5 bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-ink-600)] rounded-xl text-xs font-semibold">Clear</button>
        </div>
    </div>

    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Formatted Output</span>
            <button id="btn-code-copy" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">⧉ Copy Code</button>
        </div>
        <textarea id="code-output" readonly class="w-full h-40 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono text-[var(--color-teal-500)] focus:outline-none"></textarea>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("code-input");
    const output = document.getElementById("code-output");

    document.getElementById("btn-beautify").addEventListener("click", () => {
        let code = input.value;
        if (!code.trim()) { output.value = ""; return; }

        let formatted = "";
        let pad = 0;
        code.split(/\r?\n/).forEach(line => {
            let indent = 0;
            if (line.match( /.+<\/\w[^>]*>$/ )) {
                indent = 0;
            } else if (line.match( /^<\/\w/ )) {
                if (pad !== 0) pad -= 1;
            } else if (line.match( /^<\w[^>]*[^\/]>.*$/ )) {
                indent = 1;
            } else {
                indent = 0;
            }
            let padding = "";
            for (let i = 0; i < pad; i++) padding += "  ";
            formatted += padding + line.trim() + "\n";
            pad += indent;
        });
        output.value = formatted.trim() || code;
    });

    document.getElementById("btn-code-clear").addEventListener("click", () => {
        input.value = "";
        output.value = "";
    });

    document.getElementById("btn-code-copy").addEventListener("click", () => {
        navigator.clipboard.writeText(output.value);
        alert("Code copied!");
    });
});
</script>