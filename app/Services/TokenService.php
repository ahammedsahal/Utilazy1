<?php
namespace App\Services;
use App\Helpers\DB;
use Exception;
class TokenService {
    public static function getBalance($userId) {
        $user = DB::fetch("SELECT unlimited_tokens FROM users WHERE id = ?", [$userId]);
        if ($user && $user['unlimited_tokens'] == 1) return 999999;
        $result = DB::fetch("SELECT SUM(amount) as balance FROM token_transactions WHERE user_id = ?", [$userId]);
        return isset($result['balance']) ? (int)$result['balance'] : 0;
    }
    public static function hasUnlimited($userId) {
        $user = DB::fetch("SELECT unlimited_tokens FROM users WHERE id = ?", [$userId]);
        return $user && $user['unlimited_tokens'] == 1;
    }
    public static function deduct($userId, $toolId, $cost, $metadata = []) {
        $cost = (int)$cost;
        if ($cost <= 0) {
            DB::query(
                "INSERT INTO tool_usage (user_id, tool_id, token_cost, metadata) VALUES (?, ?, 0, ?)",
                [$userId, $toolId, json_encode($metadata)]
            );
            return true;
        }
        DB::beginTransaction();
        try {
            $user = DB::fetch("SELECT unlimited_tokens FROM users WHERE id = ? FOR UPDATE", [$userId]);
            if (!$user) throw new Exception("User not found.");
            if ($user['unlimited_tokens'] == 1) {
                DB::query(
                    "INSERT INTO tool_usage (user_id, tool_id, token_cost, metadata) VALUES (?, ?, 0, ?)",
                    [$userId, $toolId, json_encode($metadata)]
                );
                DB::commit();
                return true;
            }
            $currentBalance = self::getBalance($userId);
            if ($currentBalance < $cost) {
                throw new Exception("Insufficient balance. Need {$cost}, have {$currentBalance}.");
            }
            $newBalance = $currentBalance - $cost;
            DB::query(
                "INSERT INTO tool_usage (user_id, tool_id, token_cost, metadata) VALUES (?, ?, ?, ?)",
                [$userId, $toolId, $cost, json_encode($metadata)]
            );
            $usageId = DB::lastInsertId();
            DB::query(
                "INSERT INTO token_transactions (user_id, amount, transaction_type, reference_type, reference_id, description, balance_after)
                 VALUES (?, ?, 'tool_usage', 'tool_usage', ?, ?, ?)",
                [$userId, -$cost, $usageId, "Used tool ID: {$toolId}", $newBalance]
            );
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public static function credit($userId, $amount, $type, $refType = null, $refId = null, $description = '') {
        $amount = (int)$amount;
        if ($amount <= 0) return false;
        DB::beginTransaction();
        try {
            $user = DB::fetch("SELECT id FROM users WHERE id = ? FOR UPDATE", [$userId]);
            if (!$user) throw new Exception("User not found.");
            $currentBalance = self::getBalance($userId);
            $newBalance = $currentBalance + $amount;
            DB::query(
                "INSERT INTO token_transactions (user_id, amount, transaction_type, reference_type, reference_id, description, balance_after)
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$userId, $amount, $type, $refType, $refId, $description, $newBalance]
            );
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public static function claimMonthly($userId) {
        $enabledSetting = DB::fetch("SELECT value FROM site_settings WHERE `key` = 'monthly_free_tokens_enabled'");
        $enabled = $enabledSetting ? (bool)$enabledSetting['value'] : true;
        if (!$enabled) throw new Exception("Monthly token claiming is currently disabled.");
        $tokensSetting = DB::fetch("SELECT value FROM site_settings WHERE `key` = 'monthly_free_tokens'");
        $monthlyTokens = $tokensSetting ? (int)$tokensSetting['value'] : 50;
        $currentMonth = date('Y-m');
        DB::beginTransaction();
        try {
            $existing = DB::fetch(
                "SELECT id FROM monthly_claims WHERE user_id = ? AND month = ? FOR UPDATE",
                [$userId, $currentMonth]
            );
            if ($existing) throw new Exception("You have already claimed your free credits for this month ({$currentMonth}).");
            DB::query("INSERT INTO monthly_claims (user_id, month, tokens) VALUES (?, ?, ?)", [$userId, $currentMonth, $monthlyTokens]);
            $claimId = DB::lastInsertId();
            self::credit($userId, $monthlyTokens, 'claim', 'monthly_claims', $claimId, "Claimed Monthly Free Credits for {$currentMonth}");
            DB::commit();
            return $monthlyTokens;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}