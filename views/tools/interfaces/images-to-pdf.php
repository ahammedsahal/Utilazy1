<div class="space-y-6" id="app-images-to-pdf">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>🖼️📄</span> Images to PDF Converter
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Upload one or multiple images and compile them into a PDF document client-side.</p>

        <input type="file" id="file-img2pdf" accept="image/*" multiple class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs text-[var(--color-ink-900)] focus:outline-none">

        <button id="btn-process-img2pdf" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Convert to PDF →</button>
    </div>

    <div id="pdf-output-container" class="hidden bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3 flex justify-between items-center">
        <div>
            <h4 class="text-xs font-bold text-[var(--color-ink-900)]">PDF Document Created</h4>
            <p class="text-xs text-[var(--color-ink-600)]">Your images have been compiled into a printable PDF document.</p>
        </div>
        <button id="btn-print-pdf" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm cursor-pointer">↓ View / Save PDF</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("file-img2pdf");
    const processBtn = document.getElementById("btn-process-img2pdf");
    const outputContainer = document.getElementById("pdf-output-container");
    const printBtn = document.getElementById("btn-print-pdf");

    let selectedFiles = [];

    fileInput.addEventListener("change", (e) => {
        selectedFiles = Array.from(e.target.files);
    });

    processBtn.addEventListener("click", () => {
        if (selectedFiles.length === 0) {
            alert("Please select at least one image file.");
            return;
        }
        outputContainer.classList.remove("hidden");
    });

    printBtn.addEventListener("click", () => {
        const win = window.open("", "_blank");
        win.document.write(`
            <html>
                <head>
                    <title>Compiled Images PDF</title>
                    <style>
                        body { margin: 0; padding: 20px; background: #fff; text-align: center; }
                        img { max-width: 100%; max-height: 95vh; margin-bottom: 20px; page-break-after: always; }
                    </style>
                </head>
                <body>
        `);
        let loadedCount = 0;
        selectedFiles.forEach(file => {
            const reader = new FileReader();
            reader.onload = (evt) => {
                const img = win.document.createElement("img");
                img.src = evt.target.result;
                win.document.body.appendChild(img);
                loadedCount++;
                if (loadedCount === selectedFiles.length) {
                    setTimeout(() => win.print(), 500);
                }
            };
            reader.readAsDataURL(file);
        });
        win.document.write(`</body></html>`);
    });
});
</script>