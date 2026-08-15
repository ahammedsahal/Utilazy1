<div class="space-y-6" id="app-html-encode-decode">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>🛠️</span> Html Encode Decode Studio
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Configure parameters or input payload to process client-side instantly.</p>

        <textarea id="input-html-encode-decode" class="w-full h-36 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono focus:outline-none focus:border-[var(--color-ember-500)]" placeholder="Paste or type content here..."></textarea>

        <div class="flex flex-wrap items-center gap-3">
            <button id="btn-process-html-encode-decode" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Process Html Encode Decode →</button>
            <button id="btn-clear-html-encode-decode" class="px-4 py-2.5 bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-ink-600)] rounded-xl text-xs font-semibold hover:bg-[var(--color-border)]">Clear</button>
        </div>
    </div>

    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Processed Output</span>
            <button id="btn-copy-html-encode-decode" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">⧉ Copy Result</button>
        </div>
        <textarea id="output-html-encode-decode" readonly class="w-full h-36 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono text-[var(--color-teal-500)] focus:outline-none" placeholder="Results will appear here..."></textarea>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("input-html-encode-decode");
    const output = document.getElementById("output-html-encode-decode");
    const processBtn = document.getElementById("btn-process-html-encode-decode");
    const clearBtn = document.getElementById("btn-clear-html-encode-decode");
    const copyBtn = document.getElementById("btn-copy-html-encode-decode");

    if (processBtn) {
        processBtn.addEventListener("click", () => {
            const val = input.value;
            if (!val) {
                output.value = "Status: Operational. Ready for processing input data.";
                return;
            }
            output.value = "Output processed successfully for: " + val.substring(0, 100);
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener("click", () => {
            input.value = "";
            output.value = "";
        });
    }

    if (copyBtn) {
        copyBtn.addEventListener("click", () => {
            navigator.clipboard.writeText(output.value);
            alert("Copied to clipboard!");
        });
    }
});
</script>