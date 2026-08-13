<div class="space-y-4">
    <div><label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Messy SQL Input</label><textarea id="sql-input" class="input-custom w-full h-44 p-4 text-sm code-font"></textarea></div>
    <button onclick="formatSQL()" class="btn-ember px-4 py-2.5 rounded-xl font-bold text-xs shadow-sm">Prettify SQL Query</button>
    <div><label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Formatted Output</label><textarea id="sql-output" readonly class="input-custom w-full h-44 p-4 text-sm bg-[var(--color-surface-alt)] code-font"></textarea></div>
</div>
<script>
    function formatSQL() {
        const sql = document.getElementById('sql-input').value; if (sql.trim() === '') return;
        const keywords = ['SELECT', 'FROM', 'WHERE', 'AND', 'OR', 'ORDER BY', 'LIMIT'];
        let formatted = sql; keywords.forEach(kw => { const regex = new RegExp('\\b' + kw + '\\b', 'gi'); formatted = formatted.replace(regex, '\n' + kw); });
        document.getElementById('sql-output').value = formatted.trim(); showToast('SQL query formatted.');
    }
</script>