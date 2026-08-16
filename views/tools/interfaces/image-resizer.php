<div class="space-y-6" id="app-image-resizer">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>🖼️</span> Image Resizer Studio
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Upload an image, specify width and height, and resize client-side instantly.</p>

        <input type="file" id="file-image-resizer" accept="image/*" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs text-[var(--color-ink-900)] focus:outline-none">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Target Width (px)</label>
                <input type="number" id="img-target-width" value="800" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2.5 text-xs font-mono text-[var(--color-ink-900)]">
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Target Height (px)</label>
                <input type="number" id="img-target-height" value="600" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2.5 text-xs font-mono text-[var(--color-ink-900)]">
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button id="btn-process-image-resizer" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Resize Image →</button>
        </div>
    </div>

    <div id="preview-container-resizer" class="hidden bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Resized Preview</span>
        <div class="flex justify-center bg-[var(--color-surface)] p-4 rounded-xl border border-[var(--color-border)]">
            <img id="resized-preview-img" class="max-h-64 object-contain rounded-lg">
        </div>
        <div class="flex justify-end">
            <a id="btn-download-resizer" download="resized-image.png" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm cursor-pointer">↓ Download Resized Image</a>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("file-image-resizer");
    const widthInput = document.getElementById("img-target-width");
    const heightInput = document.getElementById("img-target-height");
    const processBtn = document.getElementById("btn-process-image-resizer");
    const previewContainer = document.getElementById("preview-container-resizer");
    const previewImg = document.getElementById("resized-preview-img");
    const downloadBtn = document.getElementById("btn-download-resizer");

    let loadedImg = null;

    fileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (evt) => {
            loadedImg = new Image();
            loadedImg.onload = () => {
                widthInput.value = loadedImg.width;
                heightInput.value = loadedImg.height;
            };
            loadedImg.src = evt.target.result;
        };
        reader.readAsDataURL(file);
    });

    processBtn.addEventListener("click", () => {
        if (!loadedImg) {
            alert("Please select an image file first.");
            return;
        }
        const w = parseInt(widthInput.value) || loadedImg.width;
        const h = parseInt(heightInput.value) || loadedImg.height;

        const canvas = document.createElement("canvas");
        canvas.width = w;
        canvas.height = h;
        const ctx = canvas.getContext("2d");
        ctx.drawImage(loadedImg, 0, 0, w, h);

        const dataUrl = canvas.toDataURL("image/png");
        previewImg.src = dataUrl;
        downloadBtn.href = dataUrl;
        previewContainer.classList.remove("hidden");
    });
});
</script>