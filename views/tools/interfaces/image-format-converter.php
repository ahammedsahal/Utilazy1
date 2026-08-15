<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider mb-2">Select Target Format</label>
            <select id="targetFormat" class="w-full bg-[var(--color-surface-alt)] border border-[var(--color-border)] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[var(--color-ember-500)]">
                <option value="image/png">PNG (.png)</option>
                <option value="image/jpeg">JPG (.jpg)</option>
                <option value="image/webp">WebP (.webp)</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider mb-2">Quality (JPG/WebP)</label>
            <input type="range" id="qualityRange" min="10" max="100" value="90" class="w-full accent-[var(--color-ember-500)] mt-2">
            <span id="qualityVal" class="text-xs text-[var(--color-ink-400)] block text-right">90%</span>
        </div>
    </div>

    <div class="border-2 border-dashed border-[var(--color-border)] rounded-2xl p-8 text-center hover:border-[var(--color-ember-500)] transition bg-[var(--color-surface-alt)]">
        <input type="file" id="imgInput" accept="image/*" class="hidden">
        <label for="imgInput" class="cursor-pointer space-y-2 block">
            <div class="text-4xl">🖼️</div>
            <div class="text-sm font-bold text-[var(--color-ink-900)]">Click or drag image file here</div>
            <div class="text-xs text-[var(--color-ink-400)]">Supports JPG, PNG, WebP, GIF, AVIF</div>
        </label>
    </div>

    <div id="previewArea" class="hidden space-y-4">
        <div class="flex items-center justify-between p-4 bg-[var(--color-surface-alt)] rounded-xl border border-[var(--color-border)]">
            <div class="flex items-center gap-3">
                <img id="previewImg" class="w-16 h-16 object-cover rounded-lg border border-[var(--color-border)]">
                <div>
                    <div id="origName" class="text-sm font-bold text-[var(--color-ink-900)]">image.jpg</div>
                    <div id="origSize" class="text-xs text-[var(--color-ink-400)]">0 KB</div>
                </div>
            </div>
            <button id="convertBtn" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Convert Format →</button>
        </div>
    </div>

    <div id="resultArea" class="hidden p-4 bg-[var(--color-surface-alt)] rounded-xl border border-[var(--color-border)] text-center space-y-3">
        <div class="text-xs font-bold text-[var(--color-teal-500)] uppercase tracking-wider">Conversion Complete!</div>
        <a id="downloadBtn" download="converted-image" class="btn-ember inline-block px-8 py-3 rounded-xl font-bold text-xs shadow-sm">↓ Download Converted Image</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const imgInput = document.getElementById('imgInput');
    const previewArea = document.getElementById('previewArea');
    const previewImg = document.getElementById('previewImg');
    const origName = document.getElementById('origName');
    const origSize = document.getElementById('origSize');
    const convertBtn = document.getElementById('convertBtn');
    const resultArea = document.getElementById('resultArea');
    const downloadBtn = document.getElementById('downloadBtn');
    const qualityRange = document.getElementById('qualityRange');
    const qualityVal = document.getElementById('qualityVal');

    let loadedImg = null;
    let fileMeta = null;

    qualityRange.addEventListener('input', (e) => qualityVal.textContent = e.target.value + '%');

    imgInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        fileMeta = file;
        origName.textContent = file.name;
        origSize.textContent = (file.size / 1024).toFixed(1) + ' KB';

        const reader = new FileReader();
        reader.onload = (evt) => {
            const img = new Image();
            img.onload = () => {
                loadedImg = img;
                previewImg.src = evt.target.result;
                previewArea.classList.remove('hidden');
                resultArea.classList.add('hidden');
            };
            img.src = evt.target.result;
        };
        reader.readAsDataURL(file);
    });

    convertBtn.addEventListener('click', () => {
        if (!loadedImg) return;
        const canvas = document.createElement('canvas');
        canvas.width = loadedImg.width;
        canvas.height = loadedImg.height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(loadedImg, 0, 0);

        const targetFmt = document.getElementById('targetFormat').value;
        const quality = parseInt(qualityRange.value, 10) / 100;
        const dataUrl = canvas.toDataURL(targetFmt, quality);

        const ext = targetFmt.split('/')[1];
        downloadBtn.href = dataUrl;
        downloadBtn.download = (fileMeta.name.split('.')[0] || 'converted') + '.' + ext;
        resultArea.classList.remove('hidden');
    });
});
</script>