<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Formatted Text / Word Input</label>
            <div id="word-editor" contenteditable="true" class="input-custom w-full h-64 p-4 text-sm overflow-y-auto bg-white" style="min-height: 250px;">
                <h2>Welcome to Utilazy Word-to-HTML!</h2>
                <p>You can write <strong>bold</strong>, <em>italic</em>, <u>underlined</u> text directly in this rich-editor box.</p>
                <ul>
                    <li>Dynamic Bullet 1</li>
                    <li>Dynamic Bullet 2</li>
                </ul>
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Generated Clean HTML Code</label>
            <textarea id="html-output" readonly placeholder="HTML code will generate here..." class="input-custom w-full h-64 p-4 text-sm bg-[var(--color-surface-alt)] code-font" style="min-height: 250px;"></textarea>
        </div>
    </div>
    <div class="flex gap-2">
        <button onclick="convertRichTextToHTML()" class="btn-ember px-4 py-2.5 rounded-xl font-bold text-xs shadow-sm">🔄 Convert to Clean HTML</button>
        <button onclick="copyToClipboard('html-output')" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl flex items-center gap-1.5"><span>⧉</span> Copy HTML</button>
    </div>
</div>
<script>
    function convertRichTextToHTML() {
        const editor = document.getElementById('word-editor'); let html = editor.innerHTML;
        html = html.replace(/<div/g, '\n<div').replace(/<p/g, '\n<p').replace(/<ul/g, '\n<ul').replace(/<li/g, '\n  <li');
        document.getElementById('html-output').value = html.trim(); showToast('Converted rich text to HTML!');
    }
    window.addEventListener('load', () => { convertRichTextToHTML(); });
</script>