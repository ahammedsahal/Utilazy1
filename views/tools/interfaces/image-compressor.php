<div class="space-y-6">
    <div id="img-upload-pane" class="border-2 border-dashed border-[var(--color-border)] rounded-[20px] p-8 text-center bg-[var(--color-surface-alt)] cursor-pointer" onclick="document.getElementById('img-file-input').click()">
        <span class="text-4xl block mb-2">🖼️</span>
        <span class="block text-sm font-bold">Compress Images</span>
        <input type="file" id="img-file-input" accept="image/jpeg, image/png, image/webp" multiple class="hidden" onchange="loadImages(this)">
    </div>
    <div id="img-workspace" class="hidden space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-[var(--color-surface-alt)] p-5 rounded-2xl border">
            <div>
                <label class="block text-xs font-bold uppercase mb-1">Quality (<span id="quality-val">80</span>%)</label>
                <input type="range" id="img-quality" min="10" max="100" value="80" oninput="changeQuality(this.value)" class="w-full h-2">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase mb-1">Max Width</label>
                <input type="number" id="img-max-width" placeholder="Original" oninput="reprocessImages()" class="input-custom w-full h-10 px-3">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase mb-1">Format</label>
                <select id="img-format" onchange="reprocessImages()" class="input-custom w-full h-10 px-3">
                    <option value="original">Original</option>
                    <option value="image/jpeg">JPEG</option>
                    <option value="image/png">PNG</option>
                    <option value="image/webp">WebP</option>
                </select>
            </div>
        </div>
        <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold uppercase">Image List</h4>
            <button onclick="downloadAllImages()" class="btn-ember px-3.5 py-1.5 text-xs font-bold rounded-lg shadow-sm">↓ Download All</button>
        </div>
        <div id="image-list-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
    </div>
</div>
<script>
    let rawImagesList = []; let processedImagesList = [];
    function loadImages(input) {
        const files = Array.from(input.files); if (files.length === 0) return;
        rawImagesList = []; let loadedCount = 0;
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    rawImagesList.push({ name: file.name, type: file.type, size: file.size, imgObject: img });
                    loadedCount++;
                    if (loadedCount === files.length) {
                        document.getElementById('img-upload-pane').classList.add('hidden');
                        document.getElementById('img-workspace').classList.remove('hidden');
                        reprocessImages();
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }
    function changeQuality(val) { document.getElementById('quality-val').textContent = val; reprocessImages(); }
    function reprocessImages() {
        const quality = parseFloat(document.getElementById('img-quality').value) / 100;
        const maxWidthInput = document.getElementById('img-max-width').value;
        const formatSelect = document.getElementById('img-format').value;
        const maxWidth = maxWidthInput ? parseInt(maxWidthInput) : null;
        processedImagesList = []; const container = document.getElementById('image-list-container'); container.innerHTML = '';
        rawImagesList.forEach((raw, idx) => {
            const canvas = document.createElement('canvas'); const ctx = canvas.getContext('2d');
            let width = raw.imgObject.width, height = raw.imgObject.height;
            if (maxWidth && width > maxWidth) { const ratio = maxWidth / width; width = maxWidth; height = height * ratio; }
            canvas.width = width; canvas.height = height; ctx.drawImage(raw.imgObject, 0, 0, width, height);
            const targetFormat = formatSelect === 'original' ? raw.type : formatSelect;
            const dataUrl = canvas.toDataURL(targetFormat, quality);
            const head = 'data:' + targetFormat + ';base64,'; const base64Length = dataUrl.length - head.length;
            const approxSize = Math.round(base64Length * 0.75);
            const savedPercent = Math.max(0, Math.round(((raw.size - approxSize) / raw.size) * 100));
            let downloadName = raw.name; const dotIdx = downloadName.lastIndexOf('.');
            const baseName = dotIdx !== -1 ? downloadName.slice(0, dotIdx) : downloadName;
            const ext = targetFormat === 'image/png' ? 'png' : (targetFormat === 'image/webp' ? 'webp' : 'jpg');
            downloadName = `${baseName}-compressed.${ext}`;
            processedImagesList.push({ name: downloadName, dataUrl: dataUrl });
            const card = document.createElement('div'); card.className = 'bg-white border rounded-xl p-4 flex flex-col justify-between gap-3 shadow-sm';
            card.innerHTML = `
                <div class="flex items-center gap-3">
                    <img src="${dataUrl}" class="w-16 h-16 object-cover rounded-lg border">
                    <div class="flex-grow">
                        <span class="text-xs font-bold block line-clamp-1">${raw.name}</span>
                        <div class="flex items-center gap-2 text-[10px] mt-1 text-zinc-500">
                            <span>Before: ${(raw.size / 1024).toFixed(1)} KB</span>
                            <span>&rarr;</span>
                            <span class="text-[var(--color-success-500)]">After: ${(approxSize / 1024).toFixed(1)} KB</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between border-t pt-2 mt-1">
                    <span class="text-[10px] bg-[var(--color-amber-100)] px-2 py-0.5 rounded-full font-black">${savedPercent}% Saved</span>
                    <a href="${dataUrl}" download="${downloadName}" class="text-xs font-bold text-[var(--color-ember-500)]">Download &rarr;</a>
                </div>
            `;
            container.appendChild(card);
        });
    }
    function downloadAllImages() {
        if (processedImagesList.length === 0) return;
        processedImagesList.forEach(img => {
            const a = document.createElement('a'); a.href = img.dataUrl; a.download = img.name; a.click();
        });
        showToast('Batch download started.');
    }
</script>