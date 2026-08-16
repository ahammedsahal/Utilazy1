<div class="space-y-6" id="app-metadata-viewer-remover">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>🏷️</span> Image Metadata Viewer & EXIF Remover
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Inspect image details and strip EXIF metadata for privacy.</p>

        <input type="file" id="file-meta" accept="image/*" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs text-[var(--color-ink-900)] focus:outline-none">

        <div class="flex flex-wrap items-center gap-3">
            <button id="btn-inspect-meta" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Inspect Metadata →</button>
            <button id="btn-strip-meta" class="px-6 py-2.5 bg-[var(--color-teal-500)] text-white font-bold text-xs rounded-xl shadow-sm hover:opacity-90">Strip Metadata & Clean Image →</button>
        </div>
    </div>

    <div id="meta-output-container" class="hidden bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3">
        <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Metadata Info</span>
        <textarea id="meta-info-text" readonly class="w-full h-36 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono text-[var(--color-teal-500)] focus:outline-none"></textarea>
    </div>

    <div id="clean-download-container" class="hidden bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3 flex justify-between items-center">
        <div>
            <h4 class="text-xs font-bold text-[var(--color-ink-900)]">Cleaned Image Ready</h4>
            <p class="text-xs text-[var(--color-ink-600)]">EXIF and camera metadata stripped completely.</p>
        </div>
        <a id="btn-download-clean" download="clean-image.png" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm cursor-pointer">↓ Download Clean Image</a>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("file-meta");
    const inspectBtn = document.getElementById("btn-inspect-meta");
    const stripBtn = document.getElementById("btn-strip-meta");
    const metaContainer = document.getElementById("meta-output-container");
    const metaText = document.getElementById("meta-info-text");
    const cleanContainer = document.getElementById("clean-download-container");
    const downloadClean = document.getElementById("btn-download-clean");

    let loadedFile = null;

    fileInput.addEventListener("change", (e) => {
        loadedFile = e.target.files[0];
    });

    inspectBtn.addEventListener("click", () => {
        if (!loadedFile) {
            alert("Please select an image file first.");
            return;
        }
        metaText.value = `File Name: ${loadedFile.name}\nFile Size: ${(loadedFile.size / 1024).toFixed(2)} KB\nFile Type: ${loadedFile.type}\nLast Modified: ${new Date(loadedFile.lastModified).toISOString()}`;
        metaContainer.classList.remove("hidden");
    });

    stripBtn.addEventListener("click", () => {
        if (!loadedFile) {
            alert("Please select an image file first.");
            return;
        }
        const img = new Image();
        const reader = new FileReader();
        reader.onload = (evt) => {
            img.onload = () => {
                const canvas = document.createElement("canvas");
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0);
                const cleanDataUrl = canvas.toDataURL("image/png");
                downloadClean.href = cleanDataUrl;
                cleanContainer.classList.remove("hidden");
            };
            img.src = evt.target.result;
        };
        reader.readAsDataURL(loadedFile);
    });
});
</script>