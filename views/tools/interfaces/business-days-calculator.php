<div class="space-y-6" id="app-business-days-calculator">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>📅</span> Business Days Calculator
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Calculate working days between two dates, excluding weekends.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Start Date</label>
                <input type="date" id="bd-start" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2.5 text-xs text-[var(--color-ink-900)]">
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">End Date</label>
                <input type="date" id="bd-end" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-2.5 text-xs text-[var(--color-ink-900)]">
            </div>
        </div>

        <button id="btn-calc-bd" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Calculate Business Days →</button>
    </div>

    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3">
        <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Result</span>
        <div id="bd-result" class="text-sm font-bold text-[var(--color-teal-500)] bg-[var(--color-surface)] p-4 rounded-xl border border-[var(--color-border)]">
            Select dates above to calculate business days.
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const startInput = document.getElementById("bd-start");
    const endInput = document.getElementById("bd-end");
    const calcBtn = document.getElementById("btn-calc-bd");
    const resultDiv = document.getElementById("bd-result");

    const today = new Date().toISOString().split('T')[0];
    startInput.value = today;

    calcBtn.addEventListener("click", () => {
        if (!startInput.value || !endInput.value) {
            resultDiv.textContent = "Please select both start and end dates.";
            return;
        }

        let cur = new Date(startInput.value);
        const end = new Date(endInput.value);

        if (cur > end) {
            resultDiv.textContent = "Start date must be before or equal to End date.";
            return;
        }

        let count = 0;
        let totalCalendarDays = 0;

        while (cur <= end) {
            const dayOfWeek = cur.getDay();
            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                count++;
            }
            totalCalendarDays++;
            cur.setDate(cur.getDate() + 1);
        }

        resultDiv.textContent = `Total Working / Business Days: ${count} days (${totalCalendarDays} total calendar days).`;
    });
});
</script>