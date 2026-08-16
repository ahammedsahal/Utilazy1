<div class="space-y-6" id="app-timezone-converter">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>🌐</span> Timezone Converter Studio
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Convert date and time seamlessly between world timezones.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Date & Time</label>
                <input type="datetime-local" id="tz-datetime" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2.5 text-xs text-[var(--color-ink-900)]">
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">From Timezone</label>
                <select id="tz-from" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2.5 text-xs text-[var(--color-ink-900)]">
                    <option value="UTC">UTC</option>
                    <option value="America/New_York">America/New_York (EST)</option>
                    <option value="America/Los_Angeles">America/Los_Angeles (PST)</option>
                    <option value="Europe/London">Europe/London (GMT)</option>
                    <option value="Europe/Paris">Europe/Paris (CET)</option>
                    <option value="Asia/Tokyo">Asia/Tokyo (JST)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">To Timezone</label>
                <select id="tz-to" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2.5 text-xs text-[var(--color-ink-900)]">
                    <option value="America/New_York">America/New_York (EST)</option>
                    <option value="UTC">UTC</option>
                    <option value="America/Los_Angeles">America/Los_Angeles (PST)</option>
                    <option value="Europe/London">Europe/London (GMT)</option>
                    <option value="Europe/Paris">Europe/Paris (CET)</option>
                    <option value="Asia/Tokyo">Asia/Tokyo (JST)</option>
                </select>
            </div>
        </div>

        <button id="btn-convert-tz" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Convert Timezone →</button>
    </div>

    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3">
        <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Converted Result</span>
        <div id="tz-result" class="text-sm font-bold text-[var(--color-teal-500)] bg-[var(--color-surface)] p-4 rounded-xl border border-[var(--color-border)]">
            Result will appear here.
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const dtInput = document.getElementById("tz-datetime");
    const fromSelect = document.getElementById("tz-from");
    const toSelect = document.getElementById("tz-to");
    const convertBtn = document.getElementById("btn-convert-tz");
    const resultDiv = document.getElementById("tz-result");

    const now = new Date();
    dtInput.value = now.toISOString().slice(0, 16);

    convertBtn.addEventListener("click", () => {
        if (!dtInput.value) {
            resultDiv.textContent = "Please select a date and time.";
            return;
        }
        try {
            const dateObj = new Date(dtInput.value);
            const targetTz = toSelect.value;
            const formatter = new Intl.DateTimeFormat('en-US', {
                timeZone: targetTz,
                dateStyle: 'full',
                timeStyle: 'long'
            });
            resultDiv.textContent = `${formatter.format(dateObj)} (${targetTz})`;
        } catch (err) {
            resultDiv.textContent = "Conversion error: " + err.message;
        }
    });
});
</script>