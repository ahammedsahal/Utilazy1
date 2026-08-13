<div class="space-y-4">
    <div>
        <label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Enter Text Content</label>
        <textarea id="counter-input" placeholder="Start typing or pasting text for a real-time deep analysis..." oninput="runDetailedAnalysis()" class="input-custom w-full h-56 p-5 text-sm font-medium leading-relaxed" style="font-family: 'Inter', sans-serif;"></textarea>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-5 bg-[var(--color-surface-alt)] border border-[var(--color-border)] rounded-2xl text-xs font-bold text-[var(--color-ink-600)] shadow-inner">
        <div>Characters: <span id="c-chars" class="text-[var(--color-ember-500)] text-sm">0</span></div>
        <div>Characters (no spaces): <span id="c-chars-nospace" class="text-[var(--color-ember-500)] text-sm">0</span></div>
        <div>Words: <span id="c-words" class="text-[var(--color-ember-500)] text-sm">0</span></div>
        <div>Sentences: <span id="c-sentences" class="text-[var(--color-ember-500)] text-sm">0</span></div>
        <div>Paragraphs: <span id="c-paragraphs" class="text-[var(--color-ember-500)] text-sm">0</span></div>
        <div>Lines: <span id="c-lines" class="text-[var(--color-ember-500)] text-sm">0</span></div>
        <div>Reading Speed: <span id="c-read" class="text-[var(--color-ember-500)] text-sm">0s</span></div>
        <div>Speaking Speed: <span id="c-speak" class="text-[var(--color-ember-500)] text-sm">0s</span></div>
    </div>
    <div class="flex gap-2">
        <button onclick="copyToClipboard('counter-input')" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-sm">Copy Input Text</button>
        <button onclick="clearCounterInput()" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl flex items-center gap-1.5">Clear Input</button>
    </div>
</div>
<script>
    function runDetailedAnalysis() {
        const text = document.getElementById('counter-input').value;
        const chars = text.length;
        const charsNoSpace = text.replace(/\\s+/g, '').length;
        const cleanText = text.trim();
        const words = cleanText === '' ? 0 : cleanText.split(/\\s+/).length;
        const sentences = cleanText === '' ? 0 : cleanText.split(/[.!?]+/).filter(Boolean).length;
        const paragraphs = cleanText === '' ? 0 : cleanText.split(/\\n\\s*\\n/).filter(Boolean).length;
        const lines = cleanText === '' ? 0 : text.split('\\n').length;
        const readSeconds = Math.round((words / 225) * 60);
        const speakSeconds = Math.round((words / 150) * 60);
        document.getElementById('c-chars').textContent = chars;
        document.getElementById('c-chars-nospace').textContent = charsNoSpace;
        document.getElementById('c-words').textContent = words;
        document.getElementById('c-sentences').textContent = sentences;
        document.getElementById('c-paragraphs').textContent = paragraphs;
        document.getElementById('c-lines').textContent = lines;
        document.getElementById('c-read').textContent = readSeconds >= 60 ? `${Math.round(readSeconds/60)}m` : `${readSeconds}s`;
        document.getElementById('c-speak').textContent = speakSeconds >= 60 ? `${Math.round(speakSeconds/60)}m` : `${speakSeconds}s`;
    }
    function clearCounterInput() {
        document.getElementById('counter-input').value = '';
        runDetailedAnalysis();
        showToast('Form cleared.');
    }
</script>