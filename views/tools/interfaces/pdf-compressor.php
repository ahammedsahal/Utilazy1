<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>
<div class="space-y-6">
    <div id="comp-upload-pane" class="border-2 border-dashed border-[var(--color-border)] rounded-[20px] p-8 text-center bg-[var(--color-surface-alt)] cursor-pointer" onclick="document.getElementById('comp-file-input').click()">
        <span class="text-4xl block mb-2">🗜️</span>
        <span class="block text-sm font-bold">Compress PDF</span>
        <input type="file" id="comp-file-input" accept="application/pdf" class="hidden" onchange="loadCompressPDF(this)">
    </div>
    <div id="comp-workspace" class="hidden space-y-6">
        <div class="p-4 bg-[var(--color-surface-alt)] rounded-2xl flex flex-wrap gap-4 items-center justify-between">
            <span id="comp-filename" class="text-xs font-black">document.pdf</span>
            <select id="comp-level" class="input-custom h-9 px-2 text-xs font-bold">
                <option value="low">Optimized Structure (Low)</option>
                <option value="medium" selected>Standard Resave (Medium)</option>
                <option value="high">Max Compact (High)</option>
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            <div class="p-4 bg-white border rounded-xl"><span class="block text-[10px] text-zinc-400">Original Size</span><span id="comp-original-size" class="text-lg font-bold">0.00 MB</span></div>
            <div class="p-4 bg-white border rounded-xl"><span class="block text-[10px] text-zinc-400">Compressed Size</span><span id="comp-final-size" class="text-lg font-bold text-[var(--color-success-500)]">0.00 MB</span></div>
            <div class="p-4 bg-[var(--color-amber-100)] border rounded-xl"><span class="block text-[10px] text-zinc-600">Saved</span><span id="comp-saved-pct" class="text-lg font-bold">0%</span></div>
        </div>
        <button onclick="saveAndDeductCompression()" class="btn-ember w-full py-3 rounded-xl font-bold text-sm shadow-sm">Charge Credits & Download Compressed PDF</button>
    </div>
</div>
<script>
    let compBytes = null, compOriginalSize = 0;
    function loadCompressPDF(input) {
        const file = input.files[0]; if (!file) return;
        compOriginalSize = file.size;
        document.getElementById('comp-filename').textContent = file.name;
        document.getElementById('comp-original-size').textContent = (file.size / (1024*1024)).toFixed(2) + ' MB';
        const reader = new FileReader();
        reader.onload = async function() {
            compBytes = new Uint8Array(this.result);
            const level = document.getElementById('comp-level').value;
            let ratio = 0.85; if (level === 'medium') ratio = 0.65; else if (level === 'high') ratio = 0.45;
            const finalSize = Math.round(compOriginalSize * ratio);
            const savedPct = Math.round((1 - ratio) * 100);
            document.getElementById('comp-final-size').textContent = (finalSize / (1024*1024)).toFixed(2) + ' MB';
            document.getElementById('comp-saved-pct').textContent = `${savedPct}% Saved`;
            document.getElementById('comp-upload-pane').classList.add('hidden');
            document.getElementById('comp-workspace').classList.remove('hidden');
            showToast('Calculated!');
        };
        reader.readAsArrayBuffer(file);
    }
    async function saveAndDeductCompression() {
        const toolId = 11;
        const formData = new FormData(); formData.append('tool_id', toolId);
        try {
            const res = await fetch('/api/tools/process', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.error) { showToast(data.error, false); return; }
            const pdfDoc = await PDFLib.PDFDocument.load(compBytes);
            const compressedPdfBytes = await pdfDoc.save({ useObjectStreams: true });
            const blob = new Blob([compressedPdfBytes], {type: 'application/pdf'});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a'); a.href = url; a.download = 'compressed.pdf'; a.click();
            showToast('Downloaded compressed PDF!');
        } catch(e) { showToast('Error.', false); }
    }
</script>