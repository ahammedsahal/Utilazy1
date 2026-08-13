<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4 bg-[var(--color-surface-alt)] p-5 rounded-2xl border">
            <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400 border-b pb-2 mb-2">Epoch to Date</h3>
            <input type="number" id="ts-epoch-input" value="1786521600" class="input-custom w-full h-10 px-3 text-sm code-font">
            <button onclick="convertEpochToDate()" class="btn-ember w-full py-2 rounded-xl font-bold text-xs">Convert</button>
            <div id="ts-epoch-result" class="hidden p-3 bg-white border rounded-xl text-xs font-bold text-zinc-600">Date: <span id="ts-local-date" class="text-[var(--color-ember-500)]"></span></div>
        </div>
        <div class="space-y-4 bg-[var(--color-surface-alt)] p-5 rounded-2xl border">
            <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400 border-b pb-2 mb-2">Date to Epoch</h3>
            <input type="text" id="ts-date-input" value="2026-08-13 12:00:00" class="input-custom w-full h-10 px-3 text-sm code-font">
            <button onclick="convertDateToEpoch()" class="btn-ember w-full py-2 rounded-xl font-bold text-xs">Convert</button>
            <div id="ts-date-result" class="hidden p-3 bg-white border rounded-xl text-xs font-bold text-zinc-600">Timestamp: <span id="ts-epoch-val" class="text-[var(--color-ember-500)]"></span></div>
        </div>
    </div>
</div>
<script>
    function convertEpochToDate() {
        const epoch = parseInt(document.getElementById('ts-epoch-input').value); if (isNaN(epoch)) return;
        const date = new Date(epoch * 1000); document.getElementById('ts-local-date').textContent = date.toString();
        document.getElementById('ts-epoch-result').classList.remove('hidden'); showToast('Converted!');
    }
    function convertDateToEpoch() {
        const dateStr = document.getElementById('ts-date-input').value; const time = Date.parse(dateStr);
        if (isNaN(time)) { showToast('Invalid format.', false); return; }
        document.getElementById('ts-epoch-val').textContent = Math.round(time / 1000);
        document.getElementById('ts-date-result').classList.remove('hidden'); showToast('Converted!');
    }
</script>