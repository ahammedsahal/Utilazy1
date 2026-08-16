<div class="space-y-6" id="app-image-cropper">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>✂️</span> Image Cropper Studio
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Upload an image and crop custom dimensions client-side.</p>

        <input type="file" id="file-image-cropper" accept="image/*" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs text-[var(--color-ink-900)] focus:outline-none">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Crop X (px)</label>
                <input type="number" id="crop-x" value="0" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2 text-xs font-mono">
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Crop Y (px)</label>
                <input type="number" id="crop-y" value="0" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2 text-xs font-mono">
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Crop Width (px)</label>
                <input type="number" id="crop-w" value="300" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2 text-xs font-mono">
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Crop Height (px)</label>
                <input type="number" id="crop-h" value="300" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2 text-xs font-mono">
            </div>
        </div>

        <button id="btn-process-cropper" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Crop Image →</button>
    </div>

    <div id="preview-container-cropper" class="hidden bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Cropped Output</span>
        <div class="flex justify-center bg-[var(--color-surface)] p-4 rounded-xl border border-[var(--color-border)]">
            <img id="cropped-preview-img" class="max-h-64 object-contain rounded-lg">
        </div>
        <div class="flex justify-end">
            <a id="btn-download-cropper" download="cropped-image.png" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm cursor-pointer">↓ Download Cropped Image</a>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("file-image-cropper");
    const cropX = document.getElementById("crop-x");
    const cropY = document.getElementById("crop-y");
    const cropW = document.getElementById("crop-w");
    const cropH = document.getElementById("crop-h");
    const processBtn = document.getElementById("btn-process-cropper");
    const previewContainer = document.getElementById("preview-container-cropper");
    const previewImg = document.getElementById("cropped-preview-img");
    const downloadBtn = document.getElementById("btn-download-cropper");

    let loadedImg = null;

    fileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (evt) => {
            loadedImg = new Image();
            loadedImg.onload = () => {
                cropW.value = Math.min(300, loadedImg.width);
                cropH.value = Math.min(300, loadedImg.height);
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
        const x = parseInt(cropX.value) || 0;
        const y = parseInt(cropY.value) || 0;
        const w = parseInt(cropW.value) || loadedImg.width;
        const h = parseInt(cropH.value) || loadedImg.height;

        const canvas = document.createElement("canvas");
        canvas.width = w;
        canvas.height = h;
        const ctx = canvas.getContext("2d");
        ctx.drawImage(loadedImg, x, y, w, h, 0, 0, w, h);

        const dataUrl = canvas.toDataURL("image/png");
        previewImg.src = dataUrl;
        downloadBtn.href = dataUrl;
        previewContainer.classList.remove("hidden");
    });
});
</script>