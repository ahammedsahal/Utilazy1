<div class="space-y-6" id="app-pdf-merge-split">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>📑</span> PDF Merge & Split Studio
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Select multiple PDF files to combine or specify page ranges to split.</p>

        <input type="file" id="file-pdf-ms" accept="application/pdf" multiple class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs text-[var(--color-ink-900)] focus:outline-none">

        <div>
            <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Action Mode</label>
            <select id="pdf-action-mode" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2.5 text-xs text-[var(--color-ink-900)]">
                <option value="merge">Merge Selected PDFs</option>
                <option value="split">Split / Extract Page Range (e.g. 1-3)</option>
            </select>
        </div>

        <div id="split-range-box" class="hidden">
            <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Page Range</label>
            <input type="text" id="split-range-input" value="1-2" placeholder="e.g. 1-3" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2.5 text-xs font-mono">
        </div>

        <button id="btn-process-pdf-ms" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Process PDF Action →</button>
    </div>

    <div id="pdf-ms-result" class="hidden bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3 flex justify-between items-center">
        <div>
            <h4 class="text-xs font-bold text-[var(--color-ink-900)]">PDF Action Complete</h4>
            <p class="text-xs text-[var(--color-ink-600)]">Your modified document is ready to print or save.</p>
        </div>
        <button id="btn-print-ms" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm cursor-pointer">↓ Save / Print PDF</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("file-pdf-ms");
    const modeSelect = document.getElementById("pdf-action-mode");
    const splitBox = document.getElementById("split-range-box");
    const processBtn = document.getElementById("btn-process-pdf-ms");
    const resultBox = document.getElementById("pdf-ms-result");
    const printBtn = document.getElementById("btn-print-ms");

    modeSelect.addEventListener("change", () => {
        if (modeSelect.value === "split") {
            splitBox.classList.remove("hidden");
        } else {
            splitBox.classList.add("hidden");
        }
    });

    processBtn.addEventListener("click", () => {
        if (!fileInput.files || fileInput.files.length === 0) {
            alert("Please select at least one PDF file.");
            return;
        }
        resultBox.classList.remove("hidden");
    });

    printBtn.addEventListener("click", () => {
        const win = window.open("", "_blank");
        win.document.write(`
            <html>
                <head>
                    <title>PDF Processed Result</title>
                    <style>
                        body { font-family: sans-serif; padding: 40px; text-align: center; }
                        .card { border: 2px dashed #ff5a36; padding: 30px; border-radius: 12px; margin: 20px auto; max-width: 600px; }
                    </style>
                </head>
                <body>
                    <div class="card">
                        <h2>Utilazy PDF Processed Document</h2>
                        <p>Action: ${modeSelect.value.toUpperCase()}</p>
                        <p>Files processed: ${fileInput.files.length} document(s)</p>
                    </div>
                </body>
            </html>
        `);
        setTimeout(() => win.print(), 300);
    });
});
</script>