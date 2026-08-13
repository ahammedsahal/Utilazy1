<?php
namespace App\Controllers;

use App\Helpers\DB;
use App\Helpers\Session;
use App\Helpers\Response;
use App\Helpers\View;
use App\Services\TokenService;

class DashboardController {
    public function index() {
        $userId = Session::userId();
        $user = DB::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
        $balance = TokenService::getBalance($userId);

        // Claim status details
        $currentMonth = date('Y-m');
        $hasClaimed = DB::fetch("SELECT id FROM monthly_claims WHERE user_id = ? AND month = ?", [$userId, $currentMonth]) ? true : false;

        // Fetch user content with limits (Section 2 - slots)
        $shortenedUrls = DB::fetchAll("SELECT * FROM shortened_urls WHERE user_id = ? ORDER BY id DESC LIMIT 3", [$userId]);
        $customUrls = DB::fetchAll("SELECT * FROM custom_urls WHERE user_id = ? ORDER BY id DESC LIMIT 3", [$userId]);
        $invoices = DB::fetchAll("SELECT * FROM invoices WHERE user_id = ? ORDER BY id DESC LIMIT 3", [$userId]);
        $savedNotes = DB::fetchAll("SELECT * FROM tool_usage WHERE user_id = ? AND tool_id = (SELECT id FROM tools WHERE slug = 'encrypted-note') ORDER BY id DESC LIMIT 3", [$userId]);

        return View::render('dashboard/index', [
            'user' => $user,
            'balance' => $balance,
            'hasClaimed' => $hasClaimed,
            'shortenedUrls' => $shortenedUrls,
            'customUrls' => $customUrls,
            'invoices' => $invoices,
            'savedNotes' => $savedNotes
        ]);
    }

    public function profile() {
        $userId = Session::userId();
        $user = DB::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
        return View::render('dashboard/profile', ['user' => $user]);
    }

    public function updateProfile() {
        $userId = Session::userId();
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email)) {
            $_SESSION['flash_error'] = "Name and email are required fields.";
            return Response::redirect('/profile');
        }

        DB::beginTransaction();
        try {
            DB::query("UPDATE users SET name = ?, email = ? WHERE id = ?", [$name, $email, $userId]);

            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                DB::query("UPDATE users SET password_hash = ? WHERE id = ?", [$hash, $userId]);
            }
            DB::commit();
            $_SESSION['flash_success'] = "Profile updated successfully.";
        } catch (\Exception $e) {
            DB::rollBack();
            $_SESSION['flash_error'] = "Profile update failed. Email may already be in use.";
        }

        return Response::redirect('/profile');
    }

    public function tokenHistory() {
        $userId = Session::userId();
        $history = DB::fetchAll("SELECT * FROM token_transactions WHERE user_id = ? ORDER BY id DESC", [$userId]);
        $balance = TokenService::getBalance($userId);
        return View::render('dashboard/token_history', [
            'history' => $history,
            'balance' => $balance
        ]);
    }

    public function claimFreeTokens() {
        $userId = Session::userId();
        try {
            $amount = TokenService::claimMonthly($userId);
            $_SESSION['flash_success'] = "Claimed {$amount} monthly tokens successfully!";
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }
        return Response::redirect('/dashboard');
    }

    public function myUrls() {
        $userId = Session::userId();
        $shortenedUrls = DB::fetchAll("SELECT s.*, (SELECT COUNT(*) FROM url_clicks WHERE short_url_id = s.id) as clicks FROM shortened_urls s WHERE user_id = ? ORDER BY id DESC", [$userId]);
        $customUrls = DB::fetchAll("SELECT c.*, (SELECT COUNT(*) FROM url_clicks WHERE custom_url_id = c.id) as clicks FROM custom_urls c WHERE user_id = ? ORDER BY id DESC", [$userId]);
        return View::render('dashboard/my_urls', [
            'shortenedUrls' => $shortenedUrls,
            'customUrls' => $customUrls
        ]);
    }

    public function myInvoices() {
        $userId = Session::userId();
        $invoices = DB::fetchAll("SELECT * FROM invoices WHERE user_id = ? ORDER BY id DESC", [$userId]);
        return View::render('dashboard/my_invoices', ['invoices' => $invoices]);
    }

    public function savedNotes() {
        $userId = Session::userId();
        $savedNotes = DB::fetchAll("SELECT * FROM tool_usage WHERE user_id = ? AND tool_id = (SELECT id FROM tools WHERE slug = 'encrypted-note') ORDER BY id DESC", [$userId]);
        return View::render('dashboard/saved_notes', ['savedNotes' => $savedNotes]);
    }
}
