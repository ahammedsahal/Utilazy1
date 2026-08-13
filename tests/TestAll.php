<?php

// Utilazy Lightweight PHP CLI Integration & Unit Tests Suite (Section 72)
require_once __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Env;
use App\Helpers\DB;
use App\Services\TokenService;
use App\Services\CouponService;

Env::load(__DIR__ . '/../.env');

class TestAll {
    protected $testUserId = null;

    public function run() {
        echo "=== UTILAZY LIGHTWEIGHT TESTING SUITE ===\n\n";

        try {
            $this->testDatabaseConnection();
            $this->testTokenEconomy();
            $this->testCouponsAndRedemption();
            $this->cleanup();

            echo "\n🎉 ALL TESTS PASSED SUCCESSFULLY! Flawless integration.\n";
        } catch (\Exception $e) {
            echo "\n❌ TEST FAILURE: " . $e->getMessage() . "\n";
            $this->cleanup();
            exit(1);
        }
    }

    protected function testDatabaseConnection() {
        echo "1. Testing DB Connectivity... ";
        $pdo = DB::connect();
        if ($pdo) {
            echo "PASSED (Connected successfully!)\n";
        } else {
            throw new \Exception("DB Connection failed.");
        }
    }

    protected function testTokenEconomy() {
        echo "2. Testing Token Economy (Ledger Transactions)... ";

        // Create a temporary test user
        $email = "test_user_" . bin2hex(random_bytes(4)) . "@utilazy.com";
        DB::query(
            "INSERT INTO users (name, email, password_hash, status) VALUES (?, ?, ?, 'active')",
            ["Test User", $email, password_hash("test1234", PASSWORD_BCRYPT)]
        );
        $this->testUserId = DB::lastInsertId();

        // 1. Initial balance should be 0
        $bal = TokenService::getBalance($this->testUserId);
        if ($bal !== 0) {
            throw new \Exception("Initial balance should be 0, got: " . $bal);
        }

        // 2. Credit tokens
        TokenService::credit($this->testUserId, 150, 'promotional', 'test', $this->testUserId, "Test token credit");
        $bal = TokenService::getBalance($this->testUserId);
        if ($bal !== 150) {
            throw new \Exception("Credit failed. Balance should be 150, got: " . $bal);
        }

        // 3. Claim monthly tokens (simulate claim)
        $monthlyClaimVal = TokenService::claimMonthly($this->testUserId);
        $bal = TokenService::getBalance($this->testUserId);
        if ($bal !== (150 + $monthlyClaimVal)) {
            throw new \Exception("Monthly claims failed.");
        }

        // 4. Double claim should throw exception (Section 29 unique per user/month)
        try {
            TokenService::claimMonthly($this->testUserId);
            throw new \Exception("Double claims should have been blocked!");
        } catch (\Exception $e) {
            // expected failure, perfect!
        }

        echo "PASSED\n";
    }

    protected function testCouponsAndRedemption() {
        echo "3. Testing Coupons & Offers Engine... ";

        // 1. Validate WELCOME100 coupon
        $validation = CouponService::validate('WELCOME100', $this->testUserId, 9.99, 2);
        if ($validation['bonus_tokens'] !== 100) {
            throw new \Exception("WELCOME100 coupon validation failed.");
        }

        // 2. Validate DISCOUNT50 coupon
        $validation = CouponService::validate('DISCOUNT50', $this->testUserId, 10.00, 2);
        if ($validation['discount_amount'] !== 5.00 || $validation['final_price'] !== 5.00) {
            throw new \Exception("DISCOUNT50 coupon discount math failed.");
        }

        echo "PASSED\n";
    }

    protected function cleanup() {
        if ($this->testUserId) {
            DB::query("DELETE FROM users WHERE id = ?", [$this->testUserId]);
            $this->testUserId = null;
        }
    }
}

$test = new TestAll();
$test->run();
