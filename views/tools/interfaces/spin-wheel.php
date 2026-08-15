<div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="spin-wheel-app">
    <div class="col-span-1 bg-[var(--color-surface-alt)] p-5 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-sm font-bold text-[var(--color-ink-900)]">Entries List</h3>
        <textarea id="wheel-entries" class="w-full h-48 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs focus:outline-none focus:border-[var(--color-ember-500)]" placeholder="Enter items (one per line)...">Option 1
Option 2
Option 3
Option 4
Option 5</textarea>
        <button id="update-wheel-btn" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] py-2 text-xs font-bold text-[var(--color-ink-900)] rounded-xl hover:bg-[var(--color-border)]">Update Wheel</button>
    </div>

    <div class="col-span-2 flex flex-col items-center justify-center bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-6">
        <div class="relative w-72 h-72">
            <canvas id="wheelCanvas" width="300" height="300" class="w-full h-full rounded-full border-4 border-[var(--color-ember-500)] shadow-lg"></canvas>
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 text-2xl text-[var(--color-ember-500)]">▼</div>
        </div>

        <button id="spin-btn" class="btn-ember px-10 py-3 rounded-xl font-black text-sm shadow-md transition transform active:scale-95">🎡 SPIN THE WHEEL!</button>

        <div id="winner-modal" class="hidden p-4 bg-[var(--color-amber-100)] border border-[var(--color-amber-400)] rounded-xl text-center text-[var(--color-ink-900)] font-black text-lg animate-bounce">
            🎉 Winner: <span id="winner-name">---</span> 🎉
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const canvas = document.getElementById("wheelCanvas");
    const ctx = canvas.getContext("2d");
    const entriesInput = document.getElementById("wheel-entries");
    const updateBtn = document.getElementById("update-wheel-btn");
    const spinBtn = document.getElementById("spin-btn");
    const winnerModal = document.getElementById("winner-modal");
    const winnerName = document.getElementById("winner-name");

    let colors = ["#FF5A36", "#16B8A6", "#FFC857", "#2FA36B", "#3B82F6", "#8B5CF6"];
    let options = [];
    let startAngle = 0;
    let isSpinning = false;

    function getOptions() {
        return entriesInput.value.split("\n").map(s => s.trim()).filter(s => s.length > 0);
    }

    function drawWheel() {
        options = getOptions();
        if (options.length === 0) return;

        const numOptions = options.length;
        const arc = Math.PI * 2 / numOptions;
        const radius = canvas.width / 2;

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (let i = 0; i < numOptions; i++) {
            const angle = startAngle + i * arc;
            ctx.fillStyle = colors[i % colors.length];
            ctx.beginPath();
            ctx.arc(radius, radius, radius, angle, angle + arc, false);
            ctx.lineTo(radius, radius);
            ctx.fill();

            ctx.save();
            ctx.fillStyle = "#FFFFFF";
            ctx.translate(radius + Math.cos(angle + arc / 2) * (radius * 0.65), radius + Math.sin(angle + arc / 2) * (radius * 0.65));
            ctx.rotate(angle + arc / 2 + Math.PI / 2);
            ctx.font = "bold 12px sans-serif";
            ctx.fillText(options[i], -ctx.measureText(options[i]).width / 2, 0);
            ctx.restore();
        }
    }

    updateBtn.addEventListener("click", drawWheel);

    spinBtn.addEventListener("click", () => {
        if (isSpinning) return;
        options = getOptions();
        if (options.length === 0) return;

        isSpinning = true;
        winnerModal.classList.add("hidden");

        let spinTime = 0;
        const spinTimeTotal = Math.random() * 3000 + 4000;
        let spinAngleStart = Math.random() * 10 + 10;

        function rotateWheel() {
            spinTime += 30;
            if (spinTime >= spinTimeTotal) {
                isSpinning = false;
                const numOptions = options.length;
                const degrees = startAngle * 180 / Math.PI + 90;
                const arcd = 360 / numOptions;
                const index = Math.floor((360 - degrees % 360) / arcd) % numOptions;
                winnerName.textContent = options[index];
                winnerModal.classList.remove("hidden");
                return;
            }
            const spinAngle = spinAngleStart - easeOut(spinTime, 0, spinAngleStart, spinTimeTotal);
            startAngle += (spinAngle * Math.PI / 180);
            drawWheel();
            requestAnimationFrame(rotateWheel);
        }

        function easeOut(t, b, c, d) {
            const ts = (t /= d) * t;
            const tc = ts * t;
            return b + c * (tc + -3 * ts + 3 * t);
        }

        rotateWheel();
    });

    drawWheel();
});
</script>