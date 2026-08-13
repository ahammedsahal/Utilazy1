<div class="space-y-4">
    <div>
        <label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Raw HTML Input</label>
        <textarea id="html-input" placeholder="Type or paste your HTML layout code here..." class="input-custom w-full h-56 p-4 text-sm code-font"><h2>Relaunched Project Design</h2><p>This is a <b>formatted paragraph</b> from your code input.</p></textarea>
    </div>
    <button onclick="downloadAsDocx()" class="btn-ember px-4 py-2.5 rounded-xl font-bold text-xs shadow-sm">↓ Convert & Download .DOCX Word File</button>
</div>
<script>
    function downloadAsDocx() {
        const html = document.getElementById('html-input').value; if (html.trim() === '') { showToast('HTML input is empty!', false); return; }
        const header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><title>Utilazy</title></head><body>";
        const footer = "</body></html>"; const sourceHTML = header + html + footer;
        const blob = new Blob(['\\ufeff' + sourceHTML], { type: 'application/msword' });
        const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = 'document.docx'; a.click();
        showToast('Converted and downloaded Word document!');
    }
</script>