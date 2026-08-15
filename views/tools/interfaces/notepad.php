<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-4 p-4 bg-[var(--color-surface-alt)] rounded-2xl border border-[var(--color-border)]">
        <div class="flex items-center gap-4">
            <span class="text-xs text-[var(--color-ink-600)]">Font Size:</span>
            <select id="notepad-font-size" class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg px-2.5 py-1.5 text-xs">
                <option value="12px">Small (12px)</option>
                <option value="14px" selected>Normal (14px)</option>
                <option value="16px">Large (16px)</option>
                <option value="18px">Extra Large (18px)</option>
            </select>
        </div>

        <div class="flex items-center gap-6 text-xs text-[var(--color-ink-600)]">
            <span>Words: <strong id="np-words" class="text-[var(--color-ember-500)]">0</strong></span>
            <span>Chars: <strong id="np-chars" class="text-[var(--color-teal-500)]">0</strong></span>
            <span>Lines: <strong id="np-lines">0</strong></span>
        </div>

        <div class="flex items-center gap-2">
            <button id="np-clear" class="px-3 py-1.5 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-lg text-xs font-bold text-[var(--color-ink-600)] hover:bg-[var(--color-border)]">Clear</button>
            <button id="np-download" class="btn-ember px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm">↓ Download TXT</button>
        </div>
    </div>

    <textarea id="notepad-editor" class="w-full h-80 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-2xl p-4 text-sm font-sans focus:outline-none focus:border-[var(--color-ember-500)] shadow-inner" placeholder="Start typing your note here... Auto-saves locally in browser."></textarea>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const editor = document.getElementById("notepad-editor");
    const fontSize = document.getElementById("notepad-font-size");
    const npWords = document.getElementById("np-words");
    const npChars = document.getElementById("np-chars");
    const npLines = document.getElementById("np-lines");
    const npClear = document.getElementById("np-clear");
    const npDownload = document.getElementById("np-download");

    // Load saved
    const saved = localStorage.getItem("utilazy_notepad_content");
    if (saved) editor.value = saved;

    function updateStats() {
        const val = editor.value;
        localStorage.setItem("utilazy_notepad_content", val);
        npChars.textContent = val.length;
        const words = val.trim() ? val.trim().split(/\s+/).length : 0;
        npWords.textContent = words;
        const lines = val ? val.split("\n").length : 0;
        npLines.textContent = lines;
    }

    editor.addEventListener("input", updateStats);
    fontSize.addEventListener("change", (e) => editor.style.fontSize = e.target.value);

    npClear.addEventListener("click", () => {
        if (confirm("Clear notepad content?")) {
            editor.value = "";
            updateStats();
        }
    });

    npDownload.addEventListener("click", () => {
        const blob = new Blob([editor.value], {type: "text/plain"});
        const a = document.createElement("a");
        a.href = URL.createObjectURL(blob);
        a.download = "notepad-note.txt";
        a.click();
    });

    updateStats();
});
</script>