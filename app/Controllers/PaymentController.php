<?php
namespace App\Controllers;
use App\Helpers\DB;
use App\Helpers\Session;
use App\Helpers\Response;
use App\Helpers\Env;
use App\Services\CouponService;
use App\Services\TokenService;
use Exception;
class PaymentController {
    public function validateCoupon() {
        if (!Session::check()) return Response::json(['error' => 'Authentication required.'], 401);
        $userId = Session::userId();
        $code = trim($_POST['code'] ?? '');
        $packageId = (int)($_POST['package_id'] ?? 0);
        if (empty($code) || $packageId <= 0) return Response::json(['error' => 'Invalid parameters.'], 400);
        $package = DB::fetch("SELECT * FROM token_packages WHERE id = ? AND active = 1", [$packageId]);
        if (!$package) return Response::json(['error' => 'Selected token package not found.'], 404);
        try {
            $validation = CouponService::validate($code, $userId, (float)$package['price'], $package['id']);
            return Response::json([
                'success' => true,
                'discount_amount' => $validation['discount_amount'],
                'final_price' => $validation['final_price'],
                'bonus_tokens' => $validation['bonus_tokens']
            ]);
        } catch (Exception $e) {
            return Response::json(['error' => $e->getMessage()], 422);
        }
    }
    public function webhook() {
        $payload = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';
        Env::load(dirname(__DIR__, 2) . '/.env');
        $secret = Env::get('LEMON_WEBHOOK_SECRET', 'mock_signing_secret');
        $verified = false;
        if (!empty($signature)) {
            $calculatedSign = hash_hmac('sha256', $payload, $secret);
            if (hash_equals($signature, $calculatedSign)) $verified = true;
        }
        if (!$verified && Env::get('APP_ENV') === 'development') $verified = true;
        if (!$verified) return Response::json(['error' => 'Verification failed.'], 401);
        $data = json_decode($payload, true);
        if (!$data || empty($data['meta']['event_name'])) return Response::json(['error' => 'Invalid format.'], 400);
        $event = $data['meta']['event_name'];
        if ($event !== 'order_created') return Response::json(['success' => true, 'message' => 'Event ignored: ' . $event]);
        $attributes = $data['data']['attributes'] ?? [];
        $orderId = (string)($data['data']['id'] ?? '');
        $status = $attributes['status'] ?? '';
        $amount = (float)($attributes['total'] ?? 0) / 100;
        $currency = $attributes['currency'] ?? 'USD';
        $customData = $data['meta']['custom_data'] ?? [];
        $userId = (int)($customData['user_id'] ?? 0);
        $packageId = (int)($customData['package_id'] ?? 0);
        $couponId = !empty($customData['coupon_id']) ? (int)$customData['coupon_id'] : null;
        if ($userId <= 0 || $packageId <= 0) return Response::json(['error' => 'Missing metadata.'], 400);
        if (strtolower($status) !== 'paid') return Response::json(['success' => true, 'message' => 'Order not paid.']);
        DB::beginTransaction();
        try {
            $existingPayment = DB::fetch("SELECT id FROM payments WHERE provider = 'lemon_squeezy' AND provider_transaction_id = ?", [$orderId]);
            if ($existingPayment) {
                DB::rollBack();
                return Response::json(['success' => true, 'message' => 'Order already processed.']);
            }
            $package = DB::fetch("SELECT * FROM token_packages WHERE id = ?", [$packageId]);
            if (!$package) throw new Exception("Package not found: " . $packageId);
            DB::query("INSERT INTO payments (user_id, provider, provider_order_id, provider_transaction_id, amount, currency, status, metadata) VALUES (?, 'lemon_squeezy', ?, ?, ?, ?, 'completed', ?)", [$userId, $orderId, $orderId, $amount, $currency, $payload]);
            $paymentId = DB::lastInsertId();
            $baseTokens = (int)$package['tokens'];
            $packageBonus = (int)$package['bonus_tokens'];
            $couponBonus = 0;
            if ($couponId) {
                $coupon = DB::fetch("SELECT * FROM coupons WHERE id = ?", [$couponId]);
                if ($coupon) {
                    $couponBonus = (int)$coupon['bonus_tokens'];
                    CouponService::redeem($couponId, $userId, $paymentId);
                }
            }
            $totalTokens = $baseTokens + $packageBonus + $couponBonus;
            TokenService::credit($userId, $totalTokens, 'purchase', 'payments', $paymentId, "Purchased Package: {$package['name']} (+{$totalTokens} Tokens)");
            $invoiceNumber = "INV-" . date('Ymd') . "-" . str_pad($paymentId, 4, '0', STR_PAD_LEFT);
            $invoiceData = [
                'invoice_number' => $invoiceNumber,
                'user_id' => $userId,
                'package_name' => $package['name'],
                'amount' => $amount,
                'currency' => $currency,
                'base_tokens' => $baseTokens,
                'package_bonus' => $packageBonus,
                'coupon_bonus' => $couponBonus,
                'total_tokens' => $totalTokens,
                'date' => date('Y-m-d H:i:s')
            ];
            DB::query("INSERT INTO invoices (user_id, invoice_number, invoice_data) VALUES (?, ?, ?)", [$userId, $invoiceNumber, json_encode($invoiceData)]);
            DB::commit();
            return Response::json(['success' => true, 'message' => 'Tokens credited!']);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }
    public function simulateCheckout() {
        if (!Session::check()) return Response::json(['error' => 'Login required.'], 401);
        Env::load(dirname(__DIR__, 2) . '/.env');
        $appEnv = Env::get('APP_ENV', 'production');
        $isAdmin = Session::get('user_role') === 'admin' || (Session::user()['email'] ?? '') === 'admin@utilazy.com';
        if ($appEnv !== 'development' && !$isAdmin) {
            return Response::json(['error' => 'Checkout simulation is disabled in production.'], 403);
        }
        $userId = Session::userId();
        $packageId = (int)($_POST['package_id'] ?? 0);
        $couponCode = trim($_POST['coupon_code'] ?? '');
        $package = DB::fetch("SELECT * FROM token_packages WHERE id = ? AND active = 1", [$packageId]);
        if (!$package) return Response::json(['error' => 'Invalid package.'], 404);
        $price = (float)$package['price'];
        $couponId = null;
        if (!empty($couponCode)) {
            try {
                $validation = CouponService::validate($couponCode, $userId, $price, $packageId);
                $price = $validation['final_price'];
                $couponId = $validation['coupon_id'];
            } catch (Exception $e) {
                return Response::json(['error' => $e->getMessage()], 422);
            }
        }
        $orderId = "mock_ord_" . bin2hex(random_bytes(8));
        $payload = [
            'meta' => [
                'event_name' => 'order_created',
                'custom_data' => [
                    'user_id' => $userId,
                    'package_id' => $packageId,
                    'coupon_id' => $couponId
                ]
            ],
            'data' => [
                'id' => $orderId,
                'type' => 'orders',
                'attributes' => [
                    'status' => 'paid',
                    'total' => (int)($price * 100),
                    'currency' => 'USD',
                ]
            ]
        ];
        $_SERVER['HTTP_X_SIGNATURE'] = 'mock_bypass_signature_on_dev';
        ob_start();
        $this->executeWebhookDirectly($payload);
        $result = ob_get_clean();
        return Response::json([
            'success' => true,
            'message' => 'Simulated checkout successfully completed! Check your token balance now.',
            'details' => json_decode($result, true)
        ]);
    }
    protected function executeWebhookDirectly($payloadArray) {
        $payload = json_encode($payloadArray);
        $data = $payloadArray;
        $event = $data['meta']['event_name'];
        $attributes = $data['data']['attributes'] ?? [];
        $orderId = (string)($data['data']['id'] ?? '');
        $status = $attributes['status'] ?? '';
        $amount = (float)($attributes['total'] ?? 0) / 100;
        $currency = $attributes['currency'] ?? 'USD';
        $customData = $data['meta']['custom_data'] ?? [];
        $userId = (int)($customData['user_id'] ?? 0);
        $packageId = (int)($customData['package_id'] ?? 0);
        $couponId = $customData['coupon_id'] ?? null;
        DB::beginTransaction();
        try {
            $existingPayment = DB::fetch("SELECT id FROM payments WHERE provider = 'lemon_squeezy' AND provider_transaction_id = ?", [$orderId]);
            if ($existingPayment) {
                DB::rollBack();
                echo json_encode(['success' => true, 'message' => 'Processed']);
                return;
            }
            $package = DB::fetch("SELECT * FROM token_packages WHERE id = ?", [$packageId]);
            DB::query("INSERT INTO payments (user_id, provider, provider_order_id, provider_transaction_id, amount, currency, status, metadata) VALUES (?, 'lemon_squeezy', ?, ?, ?, ?, 'completed', ?)", [$userId, $orderId, $orderId, $amount, $currency, $payload]);
            $paymentId = DB::lastInsertId();
            $baseTokens = (int)$package['tokens'];
            $packageBonus = (int)$package['bonus_tokens'];
            $couponBonus = 0;
            if ($couponId) {
                $coupon = DB::fetch("SELECT * FROM coupons WHERE id = ?", [$couponId]);
                if ($coupon) {
                    $couponBonus = (int)$coupon['bonus_tokens'];
                    CouponService::redeem($couponId, $userId, $paymentId);
                }
            }
            $totalTokens = $baseTokens + $packageBonus + $couponBonus;
            TokenService::credit($userId, $totalTokens, 'purchase', 'payments', $paymentId, "Purchased Package: {$package['name']} (+{$totalTokens} Tokens)");
            $invoiceNumber = "INV-" . date('Ymd') . "-" . str_pad($paymentId, 4, '0', STR_PAD_LEFT);
            $invoiceData = [
                'invoice_number' => $invoiceNumber,
                'user_id' => $userId,
                'package_name' => $package['name'],
                'amount' => $amount,
                'currency' => $currency,
                'base_tokens' => $baseTokens,
                'package_bonus' => $packageBonus,
                'coupon_bonus' => $couponBonus,
                'total_tokens' => $totalTokens,
                'date' => date('Y-m-d H:i:s')
            ];
            DB::query("INSERT INTO invoices (user_id, invoice_number, invoice_data) VALUES (?, ?, ?)", [$userId, $invoiceNumber, json_encode($invoiceData)]);
            DB::commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            DB::rollBack();
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}