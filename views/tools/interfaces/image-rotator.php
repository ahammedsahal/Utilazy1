<div class="space-y-6" id="app-image-rotator">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>🔄</span> Image Rotator Studio
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Upload an image and rotate client-side instantly.</p>

        <input type="file" id="file-image-rotator" accept="image/*" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs text-[var(--color-ink-900)] focus:outline-none">

        <div>
            <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Rotation Angle</label>
            <select id="rot-angle" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2.5 text-xs text-[var(--color-ink-900)]">
                <option value="90">90° Clockwise</option>
                <option value="180">180° Flip</option>
                <option value="270">270° Counter-Clockwise</option>
            </select>
        </div>

        <button id="btn-process-rotator" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Rotate Image →</button>
    </div>

    <div id="preview-container-rotator" class="hidden bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Rotated Output</span>
        <div class="flex justify-center bg-[var(--color-surface)] p-4 rounded-xl border border-[var(--color-border)]">
            <img id="rotated-preview-img" class="max-h-64 object-contain rounded-lg">
        </div>
        <div class="flex justify-end">
            <a id="btn-download-rotator" download="rotated-image.png" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm cursor-pointer">↓ Download Rotated Image</a>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("file-image-rotator");
    const angleSelect = document.getElementById("rot-angle");
    const processBtn = document.getElementById("btn-process-rotator");
    const previewContainer = document.getElementById("preview-container-rotator");
    const previewImg = document.getElementById("rotated-preview-img");
    const downloadBtn = document.getElementById("btn-download-rotator");

    let loadedImg = null;

    fileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (evt) => {
            loadedImg = new Image();
            loadedImg.src = evt.target.result;
        };
        reader.readAsDataURL(file);
    });

    processBtn.addEventListener("click", () => {
        if (!loadedImg) {
            alert("Please select an image file first.");
            return;
        }
        const angle = parseInt(angleSelect.value);
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");

        if (angle === 90 || angle === 270) {
            canvas.width = loadedImg.height;
            canvas.height = loadedImg.width;
        } else {
            canvas.width = loadedImg.width;
            canvas.height = loadedImg.height;
        }

        ctx.translate(canvas.width / 2, canvas.height / 2);
        ctx.rotate((angle * Math.PI) / 180);
        ctx.drawImage(loadedImg, -loadedImg.width / 2, -loadedImg.height / 2);

        const dataUrl = canvas.toDataURL("image/png");
        previewImg.src = dataUrl;
        downloadBtn.href = dataUrl;
        previewContainer.classList.remove("hidden");
    });
});
</script>