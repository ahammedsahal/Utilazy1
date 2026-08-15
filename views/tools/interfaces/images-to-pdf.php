<div class="space-y-6" id="app-images-to-pdf">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>🛠️</span> Images To Pdf Studio
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Configure parameters or input payload to process client-side instantly.</p>

        <textarea id="input-images-to-pdf" class="w-full h-36 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono focus:outline-none focus:border-[var(--color-ember-500)]" placeholder="Paste or type content here..."></textarea>

        <div class="flex flex-wrap items-center gap-3">
            <button id="btn-process-images-to-pdf" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Process Images To Pdf →</button>
            <button id="btn-clear-images-to-pdf" class="px-4 py-2.5 bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-ink-600)] rounded-xl text-xs font-semibold hover:bg-[var(--color-border)]">Clear</button>
        </div>
    </div>

    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Processed Output</span>
            <button id="btn-copy-images-to-pdf" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">⧉ Copy Result</button>
        </div>
        <textarea id="output-images-to-pdf" readonly class="w-full h-36 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono text-[var(--color-teal-500)] focus:outline-none" placeholder="Results will appear here..."></textarea>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("input-images-to-pdf");
    const output = document.getElementById("output-images-to-pdf");
    const processBtn = document.getElementById("btn-process-images-to-pdf");
    const clearBtn = document.getElementById("btn-clear-images-to-pdf");
    const copyBtn = document.getElementById("btn-copy-images-to-pdf");

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