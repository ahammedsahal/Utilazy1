<?php
$pageTitle = "My Invoices — Utilazy";
?>

<div class="max-w-4xl mx-auto my-10 px-6 py-8 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">Saved Invoices</h1>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Review, download, print, or view A4 formatted historical invoice records.</p>
        </div>
        <a href="/tools/invoice-generator" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl shadow-sm">Generate Invoice</a>
    </div>

    <div class="space-y-4">
        <?php if (empty($invoices)): ?>
            <div class="p-8 text-center text-xs text-[var(--color-ink-600)] border border-[var(--color-border)] rounded-2xl bg-[var(--color-surface-alt)]">
                No invoices saved yet. Head to the Invoice Generator tool, fill out invoice items, and hit "Save Invoice".
            </div>
        <?php else: ?>
            <?php foreach ($invoices as $inv): ?>
                <?php
                    $invData = json_decode($inv['invoice_data'], true);
                    $customer = $invData['customer_name'] ?? 'Guest Customer';
                    $total = $invData['total'] ?? '0.00';
                    $currency = $invData['currency'] ?? 'USD';
                    $dueDate = $invData['due_date'] ?? 'N/A';
                ?>
                <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-5 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:border-[var(--color-ember-500)] transition-colors">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-black text-[var(--color-ink-900)]">#<?= htmlspecialchars($inv['invoice_number']) ?></span>
                            <span class="text-[10px] font-bold text-[var(--color-ink-400)]">| Customer: <?= htmlspecialchars($customer) ?></span>
                        </div>
                        <div class="text-[11px] text-[var(--color-ink-600)] space-x-3">
                            <span>Due Date: <span class="font-bold text-[var(--color-ink-900)]"><?= htmlspecialchars($dueDate) ?></span></span>
                            <span>Created At: <span class="text-[var(--color-ink-400)]"><?= $inv['created_at'] ?></span></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-right">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-[var(--color-ink-400)] block">Invoice Amount</span>
                            <span class="text-sm font-black text-[var(--color-ink-900)]"><?= htmlspecialchars($total) ?> <?= htmlspecialchars($currency) ?></span>
                        </div>
                        <button onclick="viewInvoiceDetails(<?= htmlspecialchars(json_encode($invData)) ?>, '<?= htmlspecialchars($inv['invoice_number']) ?>')" class="btn-secondary px-3.5 py-2 text-xs font-bold rounded-xl">View Details</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal showing full Invoice data -->
<div id="invoice-details-modal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-lg max-w-2xl w-full max-h-[85vh] overflow-y-auto p-6 space-y-6">
        <div class="flex justify-between items-center pb-4 border-b border-[var(--color-border)]">
            <h3 id="modal-invoice-number" class="text-sm font-black text-[var(--color-ink-900)] uppercase">Invoice Details</h3>
            <button onclick="closeInvoiceModal()" class="text-gray-400 hover:text-gray-600 font-bold">&times; Close</button>
        </div>
        <div id="modal-invoice-content" class="text-xs space-y-4">
            <!-- Filled dynamically -->
        </div>
    </div>
</div>

<script>
    function viewInvoiceDetails(data, num) {
        document.getElementById('modal-invoice-number').textContent = 'Invoice Details — #' + num;
        let html = `
            <div class="grid grid-cols-2 gap-4 border-b border-[var(--color-border)] pb-4">
                <div>
                    <h4 class="font-bold uppercase text-[var(--color-ink-400)] mb-1">Company Info</h4>
                    <p class="font-black">${data.company_name || 'N/A'}</p>
                    <p class="text-[var(--color-ink-600)]">${data.company_email || ''}</p>
                </div>
                <div>
                    <h4 class="font-bold uppercase text-[var(--color-ink-400)] mb-1">Customer Info</h4>
                    <p class="font-black">${data.customer_name || 'N/A'}</p>
                    <p class="text-[var(--color-ink-600)]">${data.customer_email || ''}</p>
                </div>
            </div>
            <div>
                <h4 class="font-bold uppercase text-[var(--color-ink-400)] mb-2">Line Items</h4>
                <div class="space-y-1.5">
        `;
        (data.items || []).forEach(item => {
            html += `
                <div class="flex justify-between bg-[var(--color-surface-alt)] p-2 rounded-lg">
                    <span>${item.desc || 'Product/Service'} x${item.qty || 1}</span>
                    <span class="font-bold">$${(parseFloat(item.qty || 0)*parseFloat(item.price || 0)).toFixed(2)}</span>
                </div>
            `;
        });
        html += `
                </div>
            </div>
            <div class="pt-4 border-t border-[var(--color-border)] text-right space-y-1">
                <p>Tax rate: ${data.tax_rate || 0}%</p>
                <p class="text-sm font-black">Total Amount: <span class="text-[var(--color-ember-500)]">${data.total || '0.00'} ${data.currency || 'USD'}</span></p>
            </div>
        `;
        document.getElementById('modal-invoice-content').innerHTML = html;
        document.getElementById('invoice-details-modal').classList.remove('hidden');
    }
    function closeInvoiceModal() {
        document.getElementById('invoice-details-modal').classList.add('hidden');
    }
</script>
