<?php
namespace App\Services;
use App\Helpers\DB;
use Exception;
class CouponService {
    public static function validate($code, $userId, $packagePrice, $packageId) {
        $code = strtoupper(trim($code));
        if (empty($code)) throw new Exception("Coupon code cannot be empty.");
        $coupon = DB::fetch("SELECT * FROM coupons WHERE code = ?", [$code]);
        if (!$coupon) throw new Exception("Invalid coupon code.");
        if ($coupon['active'] != 1) throw new Exception("This coupon code is no longer active.");
        $now = time();
        if ($coupon['starts_at'] && strtotime($coupon['starts_at']) > $now) throw new Exception("This coupon promotion has not started yet.");
        if ($coupon['expires_at'] && strtotime($coupon['expires_at']) < $now) throw new Exception("This coupon code has expired.");
        if ($packagePrice < $coupon['minimum_purchase']) {
            throw new Exception("This coupon requires a minimum purchase of $" . number_format($coupon['minimum_purchase'], 2));
        }
        if ($coupon['usage_limit'] !== null) {
            $redemptions = DB::fetch("SELECT COUNT(*) as cnt FROM coupon_redemptions WHERE coupon_id = ?", [$coupon['id']]);
            if ($redemptions['cnt'] >= $coupon['usage_limit']) throw new Exception("This coupon code usage limit has been reached.");
        }
        if ($coupon['per_user_limit'] !== null) {
            $userRedemptions = DB::fetch(
                "SELECT COUNT(*) as cnt FROM coupon_redemptions WHERE coupon_id = ? AND user_id = ?",
                [$coupon['id'], $userId]
            );
            if ($userRedemptions['cnt'] >= $coupon['per_user_limit']) throw new Exception("You have already used this coupon code the maximum number of times allowed.");
        }
        $discountAmount = 0.00;
        if ($coupon['type'] === 'percentage') {
            $discountAmount = round(($packagePrice * ($coupon['value'] / 100)), 2);
        } elseif ($coupon['type'] === 'fixed') {
            $discountAmount = min((float)$coupon['value'], (float)$packagePrice);
        }
        $finalPrice = max(0.00, $packagePrice - $discountAmount);
        $bonusTokens = (int)$coupon['bonus_tokens'];
        return [
            'coupon_id' => $coupon['id'],
            'code' => $coupon['code'],
            'type' => $coupon['type'],
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
            'bonus_tokens' => $bonusTokens,
            'original_price' => $packagePrice
        ];
    }
    public static function redeem($couponId, $userId, $paymentId) {
        DB::query("INSERT INTO coupon_redemptions (coupon_id, user_id, payment_id) VALUES (?, ?, ?)", [$couponId, $userId, $paymentId]);
    }
}