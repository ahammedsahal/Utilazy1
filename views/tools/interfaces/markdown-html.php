<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Markdown Syntax</label><textarea id="md-input" oninput="convertMarkdownToHTML()" class="input-custom w-full h-56 p-4 text-sm code-font"># Heading\nThis is **bold** text.</textarea></div>
        <div><label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">HTML Code Output</label><textarea id="md-html-output" placeholder="HTML..." class="input-custom w-full h-56 p-4 text-sm bg-[var(--color-surface-alt)] code-font"></textarea></div>
    </div>
    <div class="flex gap-2"><button onclick="copyToClipboard('md-html-output')" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl shadow-sm">Copy HTML Code</button></div>
</div>
<script>
    function convertMarkdownToHTML() {
        const md = document.getElementById('md-input').value;
        let html = md.replace(/^# (.*$)/gim, '<h1>$1</h1>').replace(/\\*\\*(.*)\\*\\*/gim, '<strong>$1</strong>');
        document.getElementById('md-html-output').value = html;
    }
    window.addEventListener('load', () => { convertMarkdownToHTML(); });
</script>