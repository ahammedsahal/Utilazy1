<?php
namespace App\Controllers;

use App\Helpers\DB;
use App\Helpers\Session;
use App\Helpers\Response;
use App\Helpers\View;
use App\Services\TokenService;

class AdminController {
    public function index() {
        $stats = [
            'total_users' => DB::fetch("SELECT COUNT(*) as c FROM users")['c'] ?? 0,
            'total_tokens_used' => DB::fetch("SELECT SUM(token_cost) as c FROM tool_usage")['c'] ?? 0,
            'total_revenue' => DB::fetch("SELECT SUM(amount) as c FROM payments WHERE status = 'completed'")['c'] ?? 0.00,
            'total_redirects' => (DB::fetch("SELECT COUNT(*) as c FROM shortened_urls")['c'] ?? 0) + (DB::fetch("SELECT COUNT(*) as c FROM custom_urls")['c'] ?? 0)
        ];

        $recentPayments = DB::fetchAll("SELECT p.*, u.email FROM payments p JOIN users u ON p.user_id = u.id ORDER BY p.id DESC LIMIT 5");
        $recentUsage = DB::fetchAll("SELECT tu.*, u.email, t.name as tool_name FROM tool_usage tu JOIN users u ON tu.user_id = u.id JOIN tools t ON tu.tool_id = t.id ORDER BY tu.id DESC LIMIT 5");

        return View::render('admin/index', [
            'stats' => $stats,
            'recentPayments' => $recentPayments,
            'recentUsage' => $recentUsage
        ]);
    }

    public function users() {
        // Simple search / action dispatch
        if (isset($_GET['action'])) {
            $userId = (int)($_GET['id'] ?? 0);
            $adminId = Session::userId();

            if ($_GET['action'] === 'deactivate') {
                DB::query("UPDATE users SET status = 'deactivated' WHERE id = ?", [$userId]);
                DB::query("INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, metadata) VALUES (?, 'deactivate_user', 'users', ?, ?)", [$adminId, $userId, json_encode(['status' => 'deactivated'])]);
                $_SESSION['flash_success'] = "User account deactivated successfully.";
            } elseif ($_GET['action'] === 'activate') {
                DB::query("UPDATE users SET status = 'active' WHERE id = ?", [$userId]);
                DB::query("INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, metadata) VALUES (?, 'activate_user', 'users', ?, ?)", [$adminId, $userId, json_encode(['status' => 'active'])]);
                $_SESSION['flash_success'] = "User account activated successfully.";
            } elseif ($_GET['action'] === 'toggle_unlimited') {
                $user = DB::fetch("SELECT unlimited_tokens FROM users WHERE id = ?", [$userId]);
                $newVal = $user['unlimited_tokens'] ? 0 : 1;
                DB::query("UPDATE users SET unlimited_tokens = ? WHERE id = ?", [$newVal, $userId]);
                DB::query("INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, metadata) VALUES (?, 'toggle_unlimited_tokens', 'users', ?, ?)", [$adminId, $userId, json_encode(['unlimited_tokens' => $newVal])]);
                $_SESSION['flash_success'] = "Unlimited tokens flag updated.";
            } elseif ($_GET['action'] === 'adjust_tokens' && isset($_POST['amount'])) {
                $amount = (int)$_POST['amount'];
                if ($amount !== 0) {
                    if ($amount > 0) {
                        TokenService::credit($userId, $amount, 'adjustment', 'admin', $adminId, "Admin manual balance Adjustment");
                    } else {
                        TokenService::deduct($userId, abs($amount), 'adjustment', 'admin', $adminId, "Admin manual balance Adjustment");
                    }
                    DB::query("INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, metadata) VALUES (?, 'adjust_tokens', 'users', ?, ?)", [$adminId, $userId, json_encode(['amount' => $amount])]);
                    $_SESSION['flash_success'] = "User tokens balance adjusted by {$amount}.";
                }
            }
            return Response::redirect('/admin/users');
        }

        $usersList = DB::fetchAll("SELECT u.*, (SELECT SUM(amount) FROM token_transactions WHERE user_id = u.id) as balance FROM users u ORDER BY u.id DESC");
        return View::render('admin/users', ['users' => $usersList]);
    }

    public function tools() {
        $toolsList = DB::fetchAll("SELECT t.*, c.name as category_name FROM tools t JOIN categories c ON t.category_id = c.id ORDER BY t.sort_order ASC, t.id ASC");
        return View::render('admin/tools', ['tools' => $toolsList]);
    }

    public function updateTool() {
        $toolId = (int)($_POST['id'] ?? 0);
        $enabled = isset($_POST['enabled']) ? 1 : 0;
        $tokenCost = (int)($_POST['token_cost'] ?? 0);
        $adminId = Session::userId();

        DB::query("UPDATE tools SET enabled = ?, token_cost = ? WHERE id = ?", [$enabled, $tokenCost, $toolId]);
        DB::query("INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, metadata) VALUES (?, 'update_tool', 'tools', ?, ?)", [$adminId, $toolId, json_encode(['enabled' => $enabled, 'token_cost' => $tokenCost])]);

        $_SESSION['flash_success'] = "Tool configurations saved successfully.";
        return Response::redirect('/admin/tools');
    }

    public function payments() {
        $paymentsList = DB::fetchAll("SELECT p.*, u.email FROM payments p JOIN users u ON p.user_id = u.id ORDER BY p.id DESC");
        $packagesList = DB::fetchAll("SELECT * FROM token_packages ORDER BY sort_order ASC");
        return View::render('admin/payments', [
            'payments' => $paymentsList,
            'packages' => $packagesList
        ]);
    }

    public function coupons() {
        $couponsList = DB::fetchAll("SELECT * FROM coupons ORDER BY id DESC");
        return View::render('admin/coupons', ['coupons' => $couponsList]);
    }

    public function addCoupon() {
        $code = strtoupper(trim($_POST['code'] ?? ''));
        $type = $_POST['type'] ?? 'percentage';
        $value = (float)($_POST['value'] ?? 0);
        $bonusTokens = (int)($_POST['bonus_tokens'] ?? 0);
        $limit = empty($_POST['usage_limit']) ? null : (int)$_POST['usage_limit'];
        $adminId = Session::userId();

        if (empty($code)) {
            $_SESSION['flash_error'] = "Coupon code is required.";
            return Response::redirect('/admin/coupons');
        }

        try {
            DB::query(
                "INSERT INTO coupons (code, type, value, bonus_tokens, usage_limit, active) VALUES (?, ?, ?, ?, ?, 1)",
                [$code, $type, $value, $bonusTokens, $limit]
            );
            $couponId = DB::lastInsertId();
            DB::query("INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, metadata) VALUES (?, 'create_coupon', 'coupons', ?, ?)", [$adminId, $couponId, json_encode(['code' => $code, 'type' => $type, 'value' => $value])]);
            $_SESSION['flash_success'] = "Coupon code '{$code}' added successfully!";
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Could not insert coupon. Ensure code is unique.";
        }

        return Response::redirect('/admin/coupons');
    }

    public function settings() {
        $settingsList = DB::fetchAll("SELECT * FROM site_settings");
        return View::render('admin/settings', ['settings' => $settingsList]);
    }

    public function updateSettings() {
        $adminId = Session::userId();
        foreach ($_POST as $key => $value) {
            if ($key === 'csrf_token') continue;
            DB::query("UPDATE site_settings SET value = ? WHERE `key` = ?", [$value, $key]);
        }
        DB::query("INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, metadata) VALUES (?, 'update_settings', 'site_settings', NULL, NULL)", [$adminId]);
        $_SESSION['flash_success'] = "Site global configurations saved successfully.";
        return Response::redirect('/admin/settings');
    }

    public function branding() {
        $tokens = DB::fetchAll("SELECT * FROM design_tokens ORDER BY id ASC");
        return View::render('admin/branding', ['tokens' => $tokens]);
    }

    public function updateBranding() {
        $adminId = Session::userId();
        foreach ($_POST['tokens'] ?? [] as $id => $val) {
            DB::query("UPDATE design_tokens SET token_value = ?, updated_by = ?, updated_at = NOW() WHERE id = ?", [$val, $adminId, (int)$id]);
        }
        DB::query("INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, metadata) VALUES (?, 'update_branding', 'design_tokens', NULL, NULL)", [$adminId]);
        $_SESSION['flash_success'] = "Brand colors and typography tokens saved successfully.";
        return Response::redirect('/admin/branding');
    }

    public function codeInjection() {
        $injections = DB::fetchAll("SELECT * FROM code_injections ORDER BY id ASC");
        return View::render('admin/code_injection', ['injections' => $injections]);
    }

    public function updateCodeInjection() {
        $adminId = Session::userId();
        foreach ($_POST['injection'] ?? [] as $id => $data) {
            $code = $data['code'] ?? '';
            $enabled = isset($data['enabled']) ? 1 : 0;
            DB::query("UPDATE code_injections SET code = ?, enabled = ?, updated_by = ?, updated_at = NOW() WHERE id = ?", [$code, $enabled, $adminId, (int)$id]);
        }
        DB::query("INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, metadata) VALUES (?, 'update_code_injection', 'code_injections', NULL, NULL)", [$adminId]);
        $_SESSION['flash_success'] = "Code injection updates applied successfully.";
        return Response::redirect('/admin/code-injection');
    }
}
