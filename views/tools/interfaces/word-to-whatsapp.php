<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Standard Text Input</label>
            <textarea id="wa-input" placeholder="Enter standard text..." oninput="convertToWhatsApp()" class="input-custom w-full h-56 p-4 text-sm">Hello friends,\nThis is a *bold* statement, or maybe some _italic_ thoughts.</textarea>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">WhatsApp Formatted Output</label>
            <textarea id="wa-output" readonly placeholder="WhatsApp compatible..." class="input-custom w-full h-56 p-4 text-sm bg-[var(--color-surface-alt)] code-font"></textarea>
        </div>
    </div>
    <div class="flex gap-2"><button onclick="copyToClipboard('wa-output')" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-sm">Copy WhatsApp Text</button></div>
</div>
<script>
    function convertToWhatsApp() {
        const input = document.getElementById('wa-input').value;
        let output = input.replace(/`([^`]+)`/g, '```$1```');
        document.getElementById('wa-output').value = output;
    }
    window.addEventListener('load', () => { convertToWhatsApp(); });
</script>