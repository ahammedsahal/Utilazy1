<?php
use App\Helpers\Session;

$pageTitle = "Pricing Plans & Token Packages — Utilazy";
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-12">
    <div class="text-center max-w-xl mx-auto mb-12">
        <h1 class="text-3xl font-black text-[var(--color-ink-900)] tracking-tight">Purchase Credit Packages</h1>
        <p class="text-xs text-[var(--color-ink-600)] mt-1.5">No recurring subscriptions required. Simply purchase tokens on-demand as needed for advanced tools.</p>
    </div>

    <!-- Promo alert showing default testing coupons -->
    <div class="max-w-md mx-auto mb-8 bg-[var(--color-amber-100)] border border-[var(--color-amber-400)] text-[var(--color-ink-900)] text-xs p-3.5 rounded-xl text-center space-y-1">
        <strong class="block uppercase font-black tracking-wide">🔥 Sandbox Testing Coupons Available:</strong>
        <p>Use <strong class="text-[var(--color-ember-500)]">WELCOME100</strong> for 100 extra tokens, or <strong class="text-[var(--color-ember-500)]">DISCOUNT50</strong> for 50% discount!</p>
    </div>

    <!-- Package Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <?php foreach ($packages as $pkg): ?>
            <div class="bg-[var(--color-surface)] border-2 border-[var(--color-border)] hover:border-[var(--color-ember-500)] p-6 rounded-[20px] shadow-sm flex flex-col justify-between text-center relative group transition-all">
                <div>
                    <h3 class="text-base font-black text-[var(--color-ink-900)] mb-1"><?= htmlspecialchars($pkg['name']) ?></h3>
                    <div class="my-4">
                        <span class="text-4xl font-black text-[var(--color-teal-500)]">🪙 <?= number_format($pkg['tokens']) ?></span>
                        <?php if ($pkg['bonus_tokens'] > 0): ?>
                            <span class="block text-[10px] font-black text-white bg-[var(--color-ember-500)] uppercase radius-pill px-2 py-0.5 mt-2 w-max mx-auto">+ <?= number_format($pkg['bonus_tokens']) ?> BONUS</span>
                        <?php endif; ?>
                    </div>
                    <div class="text-2xl font-black text-[var(--color-ink-900)] mb-6">$<?= number_format($pkg['price'], 2) ?></div>
                </div>

                <!-- Select Package -->
                <button onclick="initiateCheckout(<?= $pkg['id'] ?>, <?= $pkg['price'] ?>, '<?= htmlspecialchars($pkg['name']) ?>')" class="btn-ember w-full py-2.5 rounded-xl font-bold text-xs shadow-sm">
                    Select Package
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Checkout Slide Over -->
    <div id="checkout-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-8 rounded-[20px] shadow-2xl w-full max-w-md relative">
            <button onclick="closeCheckout()" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-600 text-2xl font-bold focus:outline-none">&times;</button>

            <h3 class="text-lg font-black text-[var(--color-ink-900)] mb-4">Complete Your Purchase</h3>

            <div class="space-y-4 text-xs font-semibold text-[var(--color-ink-600)]">
                <div class="flex justify-between border-b border-[var(--color-border)] pb-2">
                    <span>Selected Plan:</span>
                    <span id="chk-package-name" class="font-black text-[var(--color-ink-900)]">-</span>
                </div>
                <div class="flex justify-between border-b border-[var(--color-border)] pb-2">
                    <span>Base Price:</span>
                    <span id="chk-base-price" class="font-black text-[var(--color-ink-900)]">$0.00</span>
                </div>

                <!-- Coupon Entry Row -->
                <div>
                    <label class="block text-[10px] font-bold uppercase text-[var(--color-ink-400)] mb-1">Coupon / Discount Code</label>
                    <div class="flex gap-2">
                        <input type="text" id="chk-coupon-code" placeholder="WELCOME100" class="input-custom flex-grow h-9 px-3 uppercase font-bold text-xs">
                        <button onclick="applyCoupon()" class="btn-secondary px-3 py-1 text-xs font-bold rounded-xl">Apply</button>
                    </div>
                    <span id="coupon-feedback" class="text-[9px] font-bold block mt-1"></span>
                </div>

                <!-- Final Calculations -->
                <div class="bg-[var(--color-surface-alt)] p-4 rounded-xl space-y-2 border border-[var(--color-border)]">
                    <div class="flex justify-between text-red-500">
                        <span>Discount:</span>
                        <span id="chk-discount-val">-$0.00</span>
                    </div>
                    <div class="flex justify-between text-[var(--color-teal-500)]">
                        <span>Bonus Tokens:</span>
                        <span id="chk-bonus-val">+0 Credits</span>
                    </div>
                    <div class="flex justify-between text-base font-black text-[var(--color-ink-900)] border-t border-[var(--color-border)] pt-2 mt-1">
                        <span>Total Price:</span>
                        <span id="chk-final-price">$0.00</span>
                    </div>
                </div>

                <!-- Simulation Trigger -->
                <button onclick="triggerSimulatedPayment()" class="btn-ember w-full py-3 rounded-xl font-bold text-sm tracking-wide shadow-md flex items-center justify-center gap-1.5">
                    🪙 Confirm Payment (Simulated)
                </button>
                <p class="text-[9px] text-center text-[var(--color-ink-400)] italic">This simulates Lemon Squeezy order fulfillment in local developer sandbox environment.</p>
            </div>
        </div>
    </div>
</div>

<script>
    let activePackageId = null;
    let activePrice = 0.00;
    let appliedCouponCode = '';

    function initiateCheckout(pkgId, price, name) {
        <?php if (!Session::check()): ?>
            showToast('Please log in first to purchase packages.', false);
            setTimeout(() => { window.location.href = '/login'; }, 1000);
            return;
        <?php endif; ?>

        activePackageId = pkgId;
        activePrice = parseFloat(price);
        appliedCouponCode = '';

        document.getElementById('chk-package-name').textContent = name;
        document.getElementById('chk-base-price').textContent = '$' + activePrice.toFixed(2);
        document.getElementById('chk-final-price').textContent = '$' + activePrice.toFixed(2);
        document.getElementById('chk-discount-val').textContent = '-$0.00';
        document.getElementById('chk-bonus-val').textContent = '+0 Credits';
        document.getElementById('chk-coupon-code').value = '';
        document.getElementById('coupon-feedback').className = 'text-[9px] font-bold block mt-1';
        document.getElementById('coupon-feedback').textContent = '';

        document.getElementById('checkout-modal').classList.remove('hidden');
    }

    function closeCheckout() {
        document.getElementById('checkout-modal').classList.add('hidden');
    }

    async function applyCoupon() {
        const code = document.getElementById('chk-coupon-code').value.trim();
        const feedback = document.getElementById('coupon-feedback');

        if (code === '') {
            feedback.textContent = 'Please enter a coupon code.';
            feedback.className = 'text-[9px] font-bold text-red-500 block mt-1';
            return;
        }

        const formData = new FormData();
        formData.append('code', code);
        formData.append('package_id', activePackageId);

        try {
            const res = await fetch('/api/coupons/validate', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (data.error) {
                feedback.textContent = data.error;
                feedback.className = 'text-[9px] font-bold text-red-500 block mt-1';
            } else {
                appliedCouponCode = code;
                feedback.textContent = 'Coupon applied successfully!';
                feedback.className = 'text-[9px] font-bold text-green-500 block mt-1';

                document.getElementById('chk-discount-val').textContent = '-$' + parseFloat(data.discount_amount).toFixed(2);
                document.getElementById('chk-bonus-val').textContent = '+' + data.bonus_tokens + ' Credits';
                document.getElementById('chk-final-price').textContent = '$' + parseFloat(data.final_price).toFixed(2);
                showToast('Coupon applied!');
            }
        } catch(e) {
            feedback.textContent = 'Validation error.';
        }
    }

    async function triggerSimulatedPayment() {
        const formData = new FormData();
        formData.append('package_id', activePackageId);
        formData.append('coupon_code', appliedCouponCode);
        formData.append('csrf_token', '<?= \App\Helpers\CSRF::getToken() ?>');

        try {
            const res = await fetch('/api/payments/simulate-checkout', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (data.error) {
                showToast(data.error, false);
            } else {
                showToast(data.message);
                closeCheckout();
                setTimeout(() => { window.location.reload(); }, 1500);
            }
        } catch(e) {
            showToast('Simulated checkout failed.', false);
        }
    }
</script>