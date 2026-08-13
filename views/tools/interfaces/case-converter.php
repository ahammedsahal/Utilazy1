<div class="space-y-4">
    <div>
        <label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-2">Input Text</label>
        <textarea id="case-input" placeholder="Type or paste your content here..." oninput="updateCounts()" class="input-custom w-full h-40 p-4 text-sm"></textarea>
    </div>
    <div class="flex flex-wrap gap-4 text-xs font-bold text-[var(--color-ink-600)] bg-[var(--color-surface-alt)] p-3 rounded-xl border border-[var(--color-border)]">
        <div>Characters: <span id="char-count" class="text-[var(--color-ember-500)]">0</span></div>
        <div>Words: <span id="word-count" class="text-[var(--color-ember-500)]">0</span></div>
        <div>Lines: <span id="line-count" class="text-[var(--color-ember-500)]">0</span></div>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
        <button onclick="convertCase('upper')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">UPPERCASE</button>
        <button onclick="convertCase('lower')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">lowercase</button>
        <button onclick="convertCase('title')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">Title Case</button>
        <button onclick="convertCase('sentence')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">Sentence case</button>
        <button onclick="convertCase('camel')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">camelCase</button>
        <button onclick="convertCase('pascal')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">PascalCase</button>
        <button onclick="convertCase('snake')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">snake_case</button>
        <button onclick="convertCase('kebab')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">kebab-case</button>
        <button onclick="convertCase('alternating')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">aLtErNaTiNg</button>
        <button onclick="convertCase('inverse')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">iNVERSE cASE</button>
        <button onclick="convertCase('dot')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">dot.case</button>
        <button onclick="convertCase('constant')" class="p-2.5 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] text-xs font-bold rounded-xl border border-[var(--color-border)]">CONSTANT_CASE</button>
    </div>
    <div>
        <label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-2">Transformed Output</label>
        <textarea id="case-output" readonly placeholder="Result..." class="input-custom w-full h-40 p-4 text-sm bg-[var(--color-surface-alt)]"></textarea>
    </div>
    <div class="flex gap-2">
        <button onclick="copyToClipboard('case-output')" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-sm">Copy Output</button>
        <button onclick="clearAll()" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl flex items-center gap-1.5">Clear</button>
    </div>
</div>
<script>
    function updateCounts() {
        const text = document.getElementById('case-input').value;
        document.getElementById('char-count').textContent = text.length;
        document.getElementById('word-count').textContent = text.trim() === '' ? 0 : text.trim().split(/\\s+/).length;
        document.getElementById('line-count').textContent = text.trim() === '' ? 0 : text.split('\\n').length;
    }
    function clearAll() {
        document.getElementById('case-input').value = '';
        document.getElementById('case-output').value = '';
        updateCounts();
        showToast('Form cleared.');
    }
    function copyToClipboard(id) {
        const text = document.getElementById(id).value;
        if (text.trim() === '') { showToast('Output is empty!', false); return; }
        navigator.clipboard.writeText(text);
        showToast('Copied!');
    }
    function convertCase(type) {
        const input = document.getElementById('case-input').value;
        if (input.trim() === '') { showToast('Input first!', false); return; }
        let output = '';
        if (type === 'upper') output = input.toUpperCase();
        else if (type === 'lower') output = input.toLowerCase();
        else if (type === 'title') output = input.toLowerCase().replace(/\\b\\w/g, c => c.toUpperCase());
        else if (type === 'sentence') output = input.toLowerCase().replace(/(^\\s*|[.!?]\\s+)([a-z])/g, c => c.toUpperCase());
        else if (type === 'alternating') output = input.split('').map((c, i) => i % 2 === 0 ? c.toLowerCase() : c.toUpperCase()).join('');
        else if (type === 'inverse') output = input.split('').map(c => c === c.toUpperCase() ? c.toLowerCase() : c.toUpperCase()).join('');
        else if (type === 'camel') output = input.toLowerCase().replace(/[^a-zA-Z0-9]+(.)/g, (m, chr) => chr.toUpperCase()).replace(/[^a-zA-Z0-9]/g, '');
        else if (type === 'pascal') {
            const camel = input.toLowerCase().replace(/[^a-zA-Z0-9]+(.)/g, (m, chr) => chr.toUpperCase()).replace(/[^a-zA-Z0-9]/g, '');
            output = camel.charAt(0).toUpperCase() + camel.slice(1);
        } else if (type === 'snake') output = input.toLowerCase().trim().replace(/[\\s\\-\\.]+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
        else if (type === 'kebab') output = input.toLowerCase().trim().replace(/[\\s_\\.]+/g, '-').replace(/[^a-zA-Z0-9\\-]/g, '');
        else if (type === 'dot') output = input.toLowerCase().trim().replace(/[\\s_\\-]+/g, '.').replace(/[^a-zA-Z0-9\\.]/g, '');
        else if (type === 'constant') output = input.toUpperCase().trim().replace(/[\\s\\-\\.]+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
        document.getElementById('case-output').value = output;
        showToast('Casing converted!');
    }
</script>