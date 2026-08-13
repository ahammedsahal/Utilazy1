<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>
<div class="space-y-6">
    <div id="pdf-upload-pane" class="border-2 border-dashed border-[var(--color-border)] rounded-[20px] p-8 text-center bg-[var(--color-surface-alt)] cursor-pointer" onclick="document.getElementById('pdf-file-input').click()">
        <span class="text-4xl block mb-2">📄</span>
        <span class="block text-sm font-bold">Upload PDF</span>
        <input type="file" id="pdf-file-input" accept="application/pdf" class="hidden" onchange="loadPDFFile(this)">
    </div>
    <div id="pdf-workspace" class="hidden space-y-6">
        <div class="p-4 bg-[var(--color-surface-alt)] rounded-2xl flex flex-wrap gap-4 items-center justify-between">
            <span id="pdf-filename" class="text-xs font-black">filename.pdf</span>
            <button onclick="saveAndDeductPDF()" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl shadow-sm">Save & Download PDF</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="col-span-1 space-y-4 bg-[var(--color-surface-alt)] p-4 rounded-2xl">
                <div>
                    <label class="block text-[10px] font-bold uppercase mb-1">Rotate Selected Page</label>
                    <div class="flex gap-1">
                        <input type="number" id="rotate-page-idx" min="1" value="1" class="input-custom w-16 h-8 text-center">
                        <button onclick="rotatePage()" class="btn-secondary h-8 px-2 text-xs font-bold rounded-lg">Rotate</button>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase mb-1">Delete Page</label>
                    <div class="flex gap-1">
                        <input type="number" id="delete-page-idx" min="1" value="1" class="input-custom w-16 h-8 text-center">
                        <button onclick="deletePage()" class="p-1 bg-red-100 text-red-600 rounded-lg text-xs font-bold">Delete</button>
                    </div>
                </div>
            </div>
            <div class="col-span-3">
                <div id="pdf-thumbnails" class="grid grid-cols-2 sm:grid-cols-4 gap-4"></div>
            </div>
        </div>
    </div>
</div>
<script>
    let pdfBytes = null, modifiedPdfDoc = null, workingPdfBytes = null;
    async function loadPDFFile(input) {
        const file = input.files[0]; if (!file) return;
        document.getElementById('pdf-filename').textContent = file.name;
        const reader = new FileReader();
        reader.onload = async function() {
            pdfBytes = new Uint8Array(this.result); workingPdfBytes = pdfBytes.slice();
            try {
                modifiedPdfDoc = await PDFLib.PDFDocument.load(workingPdfBytes);
                document.getElementById('pdf-upload-pane').classList.add('hidden');
                document.getElementById('pdf-workspace').classList.remove('hidden');
                renderThumbnails(); showToast('PDF loaded!');
            } catch(e) { showToast('Load failed.', false); }
        };
        reader.readAsArrayBuffer(file);
    }
    async function renderThumbnails() {
        const count = modifiedPdfDoc.getPageCount();
        const container = document.getElementById('pdf-thumbnails'); container.innerHTML = '';
        for (let i = 0; i < count; i++) {
            const div = document.createElement('div');
            div.className = 'bg-white border rounded-xl p-3 text-center';
            div.innerHTML = `<span class="text-3xl block">📄</span><span class="text-xs font-bold">Page ${i+1}</span>`;
            container.appendChild(div);
        }
    }
    async function rotatePage() {
        const idx = parseInt(document.getElementById('rotate-page-idx').value) - 1;
        if (idx < 0 || idx >= modifiedPdfDoc.getPageCount()) return;
        const page = modifiedPdfDoc.getPage(idx);
        page.setRotation(PDFLib.degrees(page.getRotation().angle + 90));
        renderThumbnails(); showToast('Rotated page.');
    }
    async function deletePage() {
        const idx = parseInt(document.getElementById('delete-page-idx').value) - 1;
        if (idx < 0 || idx >= modifiedPdfDoc.getPageCount() || modifiedPdfDoc.getPageCount() <= 1) return;
        modifiedPdfDoc.removePage(idx);
        renderThumbnails(); showToast('Deleted page.');
    }
    async function saveAndDeductPDF() {
        const toolId = 10;
        const formData = new FormData(); formData.append('tool_id', toolId);
        try {
            const res = await fetch('/api/tools/process', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.error) { showToast(data.error, false); return; }
            const finalPdfBytes = await modifiedPdfDoc.save();
            const blob = new Blob([finalPdfBytes], {type: 'application/pdf'});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a'); a.href = url; a.download = 'edited.pdf'; a.click();
            showToast('Downloaded! Credits charged.');
        } catch(e) { showToast('Error.', false); }
    }
</script>