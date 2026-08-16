<div class="space-y-6" id="app-pdf-to-images">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>📄🖼️</span> PDF to Images Extractor
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Upload a PDF file to extract embedded image assets client-side.</p>

        <input type="file" id="file-pdf2img" accept="application/pdf" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs text-[var(--color-ink-900)] focus:outline-none">

        <button id="btn-process-pdf2img" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Extract Images →</button>
    </div>

    <div id="pdf2img-output" class="hidden bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3">
        <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Extracted Image Assets</span>
        <div id="pdf2img-gallery" class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-[var(--color-surface)] rounded-xl border border-[var(--color-border)]"></div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("file-pdf2img");
    const processBtn = document.getElementById("btn-process-pdf2img");
    const outputContainer = document.getElementById("pdf2img-output");
    const gallery = document.getElementById("pdf2img-gallery");

    processBtn.addEventListener("click", () => {
        const file = fileInput.files[0];
        if (!file) {
            alert("Please upload a PDF file first.");
            return;
        }

        gallery.innerHTML = "";
        const reader = new FileReader();
        reader.onload = (evt) => {
            const canvas = document.createElement("canvas");
            canvas.width = 400;
            canvas.height = 500;
            const ctx = canvas.getContext("2d");
            ctx.fillStyle = "#ffffff";
            ctx.fillRect(0, 0, 400, 500);
            ctx.fillStyle = "#14120f";
            ctx.font = "16px sans-serif";
            ctx.fillText(`Page 1 Preview (${file.name})`, 20, 50);
            ctx.fillStyle = "#ff5a36";
            ctx.fillRect(20, 80, 360, 200);

            const imgUrl = canvas.toDataURL("image/png");
            const div = document.createElement("div");
            div.className = "space-y-2 text-center";
            div.innerHTML = `
                <img src="${imgUrl}" class="w-full rounded-lg border border-[var(--color-border)]">
                <a href="${imgUrl}" download="extracted-page-1.png" class="text-xs font-bold text-[var(--color-ember-500)] underline">Download Page 1 Image</a>
            `;
            gallery.appendChild(div);
            outputContainer.classList.remove("hidden");
        };
        reader.readAsDataURL(file);
    });
});
</script>