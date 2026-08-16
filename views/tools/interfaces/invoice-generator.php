<div class="space-y-6" id="invoice-builder">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)]">
        <div class="space-y-3">
            <h4 class="text-xs font-bold text-[var(--color-ember-500)] uppercase tracking-wider">Company Information (From)</h4>
            <input type="text" id="inv-company-name" placeholder="Company Name" value="Utilazy Inc." class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[var(--color-ember-500)]">
            <input type="text" id="inv-company-email" placeholder="Email" value="billing@utilazy.com" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[var(--color-ember-500)]">
            <textarea id="inv-company-address" placeholder="Address" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[var(--color-ember-500)] h-16">100 Tech Plaza, San Francisco, CA</textarea>
        </div>
        <div class="space-y-3">
            <h4 class="text-xs font-bold text-[var(--color-teal-500)] uppercase tracking-wider">Customer Information (Bill To)</h4>
            <input type="text" id="inv-client-name" placeholder="Client / Customer Name" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[var(--color-ember-500)]">
            <input type="text" id="inv-client-email" placeholder="Client Email" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[var(--color-ember-500)]">
            <textarea id="inv-client-address" placeholder="Client Address" class="w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[var(--color-ember-500)] h-16"></textarea>
        </div>
    </div>

    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold text-[var(--color-ink-900)] uppercase tracking-wider">Line Items</h4>
            <button id="inv-add-item" class="btn-ember px-3 py-1.5 rounded-lg text-xs font-bold">+ Add Line Item</button>
        </div>

        <table class="w-full text-xs text-left text-[var(--color-ink-900)]">
            <thead>
                <tr class="border-b border-[var(--color-border)] text-[var(--color-ink-400)]">
                    <th class="py-2">Description</th>
                    <th class="py-2 w-20">Qty</th>
                    <th class="py-2 w-24">Price ($)</th>
                    <th class="py-2 w-24 text-right">Total</th>
                </tr>
            </thead>
            <tbody id="inv-items-body" class="divide-y divide-[var(--color-border)]">
                <tr>
                    <td class="py-2"><input type="text" value="Web Development Service" class="inv-desc w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded px-2 py-1"></td>
                    <td class="py-2"><input type="number" value="1" class="inv-qty w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded px-2 py-1"></td>
                    <td class="py-2"><input type="number" value="500" class="inv-price w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded px-2 py-1"></td>
                    <td class="py-2 text-right font-bold inv-row-total">$500.00</td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-end pt-4 border-t border-[var(--color-border)]">
            <div class="w-64 space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-[var(--color-ink-600)]">Subtotal:</span>
                    <strong id="inv-subtotal">$500.00</strong>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[var(--color-ink-600)]">Tax (%):</span>
                    <input type="number" id="inv-tax-rate" value="10" class="w-16 bg-[var(--color-surface)] border border-[var(--color-border)] rounded px-2 py-0.5 text-right">
                </div>
                <div class="flex justify-between pt-2 border-t border-[var(--color-border)] text-sm font-black text-[var(--color-ember-500)]">
                    <span>Grand Total:</span>
                    <span id="inv-grand-total">$550.00</span>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <button id="inv-print" class="btn-ember px-8 py-3 rounded-xl font-bold text-xs shadow-sm">🖨️ Print / Save PDF Invoice</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const itemsBody = document.getElementById("inv-items-body");
    const addItemBtn = document.getElementById("inv-add-item");
    const subtotalEl = document.getElementById("inv-subtotal");
    const grandTotalEl = document.getElementById("inv-grand-total");
    const taxRateInput = document.getElementById("inv-tax-rate");
    const printBtn = document.getElementById("inv-print");

    function recalc() {
        let sub = 0;
        const rows = itemsBody.querySelectorAll("tr");
        rows.forEach(r => {
            const qty = parseFloat(r.querySelector(".inv-qty").value) || 0;
            const price = parseFloat(r.querySelector(".inv-price").value) || 0;
            const rowTot = qty * price;
            r.querySelector(".inv-row-total").textContent = "$" + rowTot.toFixed(2);
            sub += rowTot;
        });

        subtotalEl.textContent = "$" + sub.toFixed(2);
        const taxRate = parseFloat(taxRateInput.value) || 0;
        const grand = sub + (sub * (taxRate / 100));
        grandTotalEl.textContent = "$" + grand.toFixed(2);
    }

    itemsBody.addEventListener("input", recalc);
    taxRateInput.addEventListener("input", recalc);

    addItemBtn.addEventListener("click", () => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td class="py-2"><input type="text" value="New Service / Product" class="inv-desc w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded px-2 py-1"></td>
            <td class="py-2"><input type="number" value="1" class="inv-qty w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded px-2 py-1"></td>
            <td class="py-2"><input type="number" value="100" class="inv-price w-full bg-[var(--color-surface)] border border-[var(--color-border)] rounded px-2 py-1"></td>
            <td class="py-2 text-right font-bold inv-row-total">$100.00</td>
        `;
        itemsBody.appendChild(tr);
        recalc();
    });

    printBtn.addEventListener("click", () => window.print());
});
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoice-builder, #invoice-builder * {
        visibility: visible;
    }
    #invoice-builder {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
        background: #ffffff !important;
        color: #14120f !important;
    }
    #inv-add-item, #inv-print {
        display: none !important;
    }
    input, textarea {
        border: none !important;
        background: transparent !important;
        resize: none !important;
    }
}
</style>