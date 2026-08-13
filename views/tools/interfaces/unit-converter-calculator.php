<div class="space-y-6">
    <div class="flex flex-wrap gap-2 pb-4 border-b border-[var(--color-border)]">
        <button onclick="switchTab('length')" class="tab-btn px-4 py-2 text-xs font-bold bg-[var(--color-ember-500)] text-white radius-pill hover:scale-105 transition-transform" id="btn-tab-length">📏 Length</button>
        <button onclick="switchTab('mass')" class="tab-btn px-4 py-2 text-xs font-bold bg-[var(--color-surface-alt)] text-[var(--color-ink-900)] radius-pill hover:scale-105 transition-transform" id="btn-tab-mass">⚖️ Weight/Mass</button>
        <button onclick="switchTab('temp')" class="tab-btn px-4 py-2 text-xs font-bold bg-[var(--color-surface-alt)] text-[var(--color-ink-900)] radius-pill hover:scale-105 transition-transform" id="btn-tab-temp">🌡️ Temperature</button>
        <button onclick="switchTab('digital')" class="tab-btn px-4 py-2 text-xs font-bold bg-[var(--color-surface-alt)] text-[var(--color-ink-900)] radius-pill hover:scale-105 transition-transform" id="btn-tab-digital">💾 Digital Data</button>
        <button onclick="switchTab('calc')" class="tab-btn px-4 py-2 text-xs font-bold bg-[var(--color-surface-alt)] text-[var(--color-ink-900)] radius-pill hover:scale-105 transition-transform" id="btn-tab-calc">🧮 Calculator Mode</button>
    </div>
    <div id="converter-workspace" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">From Value</label>
            <input type="number" id="convert-input" value="1" oninput="runConversion()" class="input-custom w-full h-[48px] px-4 font-bold text-lg">
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1 mt-4">From Unit</label>
            <select id="convert-from" onchange="runConversion()" class="input-custom w-full h-[48px] px-4 font-semibold text-sm"></select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1">Calculated Result</label>
            <div id="convert-result" class="w-full h-[48px] border border-[var(--color-border)] bg-[var(--color-surface-alt)] rounded-xl flex items-center px-4 font-black text-lg text-[var(--color-ember-500)]">0</div>
            <label class="block text-xs font-bold uppercase text-[var(--color-ink-600)] mb-1 mt-4">To Unit</label>
            <select id="convert-to" onchange="runConversion()" class="input-custom w-full h-[48px] px-4 font-semibold text-sm"></select>
        </div>
    </div>
    <div id="calculator-workspace" class="hidden space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button onclick="switchCalc('sci')" class="calc-sub-btn p-3 bg-[var(--color-ember-500)] text-white font-bold text-xs rounded-xl" id="btn-calc-sci">Scientific Calc</button>
            <button onclick="switchCalc('loan')" class="calc-sub-btn p-3 bg-[var(--color-surface-alt)] text-[var(--color-ink-900)] font-bold text-xs rounded-xl" id="btn-calc-loan">EMI/Loan Calculator</button>
            <button onclick="switchCalc('discount')" class="calc-sub-btn p-3 bg-[var(--color-surface-alt)] text-[var(--color-ink-900)] font-bold text-xs rounded-xl" id="btn-calc-discount">Discount Calculator</button>
        </div>
        <div id="calc-sci-pane" class="max-w-md mx-auto bg-[var(--color-surface-alt)] p-4 rounded-2xl border border-[var(--color-border)]">
            <input type="text" id="calc-screen" readonly class="w-full h-12 px-3 text-right text-xl font-bold bg-white border border-[var(--color-border)] rounded-lg mb-4 text-[var(--color-ink-900)]">
            <div class="grid grid-cols-4 gap-2">
                <button onclick="calcPress('C')" class="p-3 bg-red-100 text-red-600 font-bold rounded-lg text-sm">C</button>
                <button onclick="calcPress('(')" class="p-3 bg-white font-bold rounded-lg text-sm">(</button>
                <button onclick="calcPress(')')" class="p-3 bg-white font-bold rounded-lg text-sm">)</button>
                <button onclick="calcPress('/')" class="p-3 bg-[var(--color-ember-100)] text-[var(--color-ember-500)] font-bold rounded-lg text-sm">/</button>
                <button onclick="calcPress('7')" class="p-3 bg-white font-bold rounded-lg text-sm">7</button>
                <button onclick="calcPress('8')" class="p-3 bg-white font-bold rounded-lg text-sm">8</button>
                <button onclick="calcPress('9')" class="p-3 bg-white font-bold rounded-lg text-sm">9</button>
                <button onclick="calcPress('*')" class="p-3 bg-[var(--color-ember-100)] text-[var(--color-ember-500)] font-bold rounded-lg text-sm">*</button>
                <button onclick="calcPress('4')" class="p-3 bg-white font-bold rounded-lg text-sm">4</button>
                <button onclick="calcPress('5')" class="p-3 bg-white font-bold rounded-lg text-sm">5</button>
                <button onclick="calcPress('6')" class="p-3 bg-white font-bold rounded-lg text-sm">6</button>
                <button onclick="calcPress('-')" class="p-3 bg-[var(--color-ember-100)] text-[var(--color-ember-500)] font-bold rounded-lg text-sm">-</button>
                <button onclick="calcPress('1')" class="p-3 bg-white font-bold rounded-lg text-sm">1</button>
                <button onclick="calcPress('2')" class="p-3 bg-white font-bold rounded-lg text-sm">2</button>
                <button onclick="calcPress('3')" class="p-3 bg-white font-bold rounded-lg text-sm">3</button>
                <button onclick="calcPress('+')" class="p-3 bg-[var(--color-ember-100)] text-[var(--color-ember-500)] font-bold rounded-lg text-sm">+</button>
                <button onclick="calcPress('0')" class="p-3 bg-white font-bold rounded-lg text-sm">0</button>
                <button onclick="calcPress('.')" class="p-3 bg-white font-bold rounded-lg text-sm">.</button>
                <button onclick="calcPress('sqrt')" class="p-3 bg-white font-bold rounded-lg text-sm">√</button>
                <button onclick="calcEval()" class="p-3 bg-[var(--color-ember-500)] text-white font-bold rounded-lg text-sm">=</button>
            </div>
        </div>
        <div id="calc-loan-pane" class="hidden max-w-md mx-auto space-y-4">
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Loan Amount ($)</label>
                    <input type="number" id="loan-amount" value="100000" class="input-custom w-full h-10 px-3 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Interest Rate (%)</label>
                    <input type="number" id="loan-rate" value="6.5" step="0.1" class="input-custom w-full h-10 px-3 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Tenure (Months)</label>
                    <input type="number" id="loan-tenure" value="120" class="input-custom w-full h-10 px-3 text-sm">
                </div>
            </div>
            <button onclick="calcEMI()" class="btn-ember w-full py-2.5 rounded-xl font-bold text-sm shadow-sm">Calculate EMI</button>
            <div id="loan-result" class="p-4 bg-[var(--color-surface-alt)] border border-[var(--color-border)] rounded-xl text-center hidden">
                <span class="block text-xs font-bold text-[var(--color-ink-400)] uppercase">EMI Payment</span>
                <span id="loan-emi-val" class="text-xl font-black text-[var(--color-ember-500)]">$0.00</span>
            </div>
        </div>
        <div id="calc-discount-pane" class="hidden max-w-md mx-auto space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Price ($)</label>
                    <input type="number" id="discount-price" value="150" class="input-custom w-full h-10 px-3 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[var(--color-ink-600)] mb-1">Discount (%)</label>
                    <input type="number" id="discount-percent" value="20" class="input-custom w-full h-10 px-3 text-sm">
                </div>
            </div>
            <button onclick="calcDiscount()" class="btn-ember w-full py-2.5 rounded-xl font-bold text-sm shadow-sm">Calculate Discount</button>
            <div id="discount-result" class="p-4 bg-[var(--color-surface-alt)] border border-[var(--color-border)] rounded-xl text-center hidden grid grid-cols-2 gap-4">
                <div>
                    <span class="block text-[10px] font-bold text-[var(--color-ink-400)] uppercase">Final Price</span>
                    <span id="discount-final-val" class="text-lg font-black text-[var(--color-success-500)]">$0.00</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-[var(--color-ink-400)] uppercase">Amount Saved</span>
                    <span id="discount-saved-val" class="text-lg font-black text-[var(--color-ember-500)]">$0.00</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const units = {
        length: { meter: 1, kilometer: 0.001, centimeter: 100, millimeter: 1000, mile: 0.000621371, yard: 1.09361, foot: 3.28084, inch: 39.3701 },
        mass: { kilogram: 1, gram: 1000, milligram: 1000000, pound: 2.20462, ounce: 35.274 },
        temp: { celsius: 'C', fahrenheit: 'F', kelvin: 'K' },
        digital: { bit: 8, byte: 1, kilobyte: 0.0009765625, megabyte: 0.00000095367431640625, gigabyte: 0.0000000009313225746154785 }
    };
    let activeTab = 'length';
    function switchTab(tab) {
        activeTab = tab;
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-[var(--color-ember-500)]', 'text-white');
            btn.classList.add('bg-[var(--color-surface-alt)]', 'text-[var(--color-ink-900)]');
        });
        document.getElementById('btn-tab-' + tab).classList.add('bg-[var(--color-ember-500)]', 'text-white');
        if (tab === 'calc') {
            document.getElementById('converter-workspace').classList.add('hidden');
            document.getElementById('calculator-workspace').classList.remove('hidden');
        } else {
            document.getElementById('converter-workspace').classList.remove('hidden');
            document.getElementById('calculator-workspace').classList.add('hidden');
            populateSelects(tab);
        }
    }
    function populateSelects(category) {
        const fromSelect = document.getElementById('convert-from');
        const toSelect = document.getElementById('convert-to');
        fromSelect.innerHTML = ''; toSelect.innerHTML = '';
        Object.keys(units[category]).forEach(unit => {
            const friendly = unit.charAt(0).toUpperCase() + unit.slice(1);
            fromSelect.add(new Option(friendly, unit));
            toSelect.add(new Option(friendly, unit));
        });
        if (toSelect.options.length > 1) toSelect.selectedIndex = 1;
        runConversion();
    }
    function runConversion() {
        const value = parseFloat(document.getElementById('convert-input').value);
        if (isNaN(value)) {
            document.getElementById('convert-result').textContent = '0'; return;
        }
        const from = document.getElementById('convert-from').value;
        const to = document.getElementById('convert-to').value;
        if (activeTab === 'temp') {
            let celsiusValue = 0;
            if (from === 'celsius') celsiusValue = value;
            else if (from === 'fahrenheit') celsiusValue = (value - 32) * 5/9;
            else if (from === 'kelvin') celsiusValue = value - 273.15;
            let finalValue = 0;
            if (to === 'celsius') finalValue = celsiusValue;
            else if (to === 'fahrenheit') finalValue = (celsiusValue * 9/5) + 32;
            else if (to === 'kelvin') finalValue = celsiusValue + 273.15;
            document.getElementById('convert-result').textContent = finalValue.toFixed(4);
        } else {
            const baseValue = value / units[activeTab][from];
            const finalValue = baseValue * units[activeTab][to];
            document.getElementById('convert-result').textContent = finalValue.toLocaleString(undefined, {maximumFractionDigits: 6});
        }
    }
    let activeCalc = 'sci';
    function switchCalc(calc) {
        activeCalc = calc;
        document.querySelectorAll('.calc-sub-btn').forEach(btn => {
            btn.classList.remove('bg-[var(--color-ember-500)]', 'text-white');
            btn.classList.add('bg-[var(--color-surface-alt)]', 'text-[var(--color-ink-900)]');
        });
        document.getElementById('btn-calc-' + calc).classList.add('bg-[var(--color-ember-500)]', 'text-white');
        document.getElementById('calc-sci-pane').classList.add('hidden');
        document.getElementById('calc-loan-pane').classList.add('hidden');
        document.getElementById('calc-discount-pane').classList.add('hidden');
        document.getElementById('calc-' + calc + '-pane').classList.remove('hidden');
    }
    function calcPress(val) {
        const screen = document.getElementById('calc-screen');
        if (val === 'C') screen.value = '';
        else screen.value += val;
    }
    function calcEval() {
        const screen = document.getElementById('calc-screen');
        try {
            let expr = screen.value;
            if (expr.includes('sqrt')) expr = expr.replace(/sqrt/g, 'Math.sqrt');
            screen.value = eval(expr);
        } catch (e) { screen.value = 'Error'; }
    }
    function calcEMI() {
        const P = parseFloat(document.getElementById('loan-amount').value);
        const r = parseFloat(document.getElementById('loan-rate').value) / 12 / 100;
        const n = parseInt(document.getElementById('loan-tenure').value);
        if (isNaN(P) || isNaN(r) || isNaN(n)) return;
        const emi = (P * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
        document.getElementById('loan-emi-val').textContent = '$' + emi.toFixed(2);
        document.getElementById('loan-result').classList.remove('hidden');
    }
    function calcDiscount() {
        const price = parseFloat(document.getElementById('discount-price').value);
        const pct = parseFloat(document.getElementById('discount-percent').value);
        if (isNaN(price) || isNaN(pct)) return;
        const saved = price * (pct / 100);
        const finalPrice = price - saved;
        document.getElementById('discount-final-val').textContent = '$' + finalPrice.toFixed(2);
        document.getElementById('discount-saved-val').textContent = '$' + saved.toFixed(2);
        document.getElementById('discount-result').classList.remove('hidden');
    }
    window.addEventListener('load', () => { populateSelects('length'); });
</script>