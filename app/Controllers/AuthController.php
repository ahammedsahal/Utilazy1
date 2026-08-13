<?php
namespace App\Controllers;
use App\Helpers\DB;
use App\Helpers\Session;
use App\Helpers\Response;
use App\Helpers\Turnstile;
use App\Helpers\View;
class AuthController {
    public function showLogin() {
        if (Session::check()) return Response::redirect('/dashboard');
        return View::render('auth/login');
    }
    public function showRegister() {
        if (Session::check()) return Response::redirect('/dashboard');
        return View::render('auth/register');
    }
    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $turnstileToken = $_POST['cf-turnstile-response'] ?? '';
        if (!Turnstile::verify($turnstileToken)) {
            $_SESSION['flash_error'] = "Security verification failed.";
            return Response::redirect('/login');
        }
        if (empty($email) || empty($password)) {
            $_SESSION['flash_error'] = "All fields are required.";
            return Response::redirect('/login');
        }
        $user = DB::fetch("SELECT * FROM users WHERE email = ?", [$email]);
        $emailHash = hash('sha256', $email);
        $ipHash = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['status'] !== 'active') {
                $_SESSION['flash_error'] = "Your account has been deactivated.";
                return Response::redirect('/login');
            }
            DB::query("INSERT INTO login_attempts (user_id, email_hash, success, ip_hash, user_agent) VALUES (?, ?, 1, ?, ?)", [$user['id'], $emailHash, $ipHash, $userAgent]);
            Session::login($user['id']);
            return Response::redirect('/dashboard');
        } else {
            $userId = $user ? $user['id'] : null;
            DB::query("INSERT INTO login_attempts (user_id, email_hash, success, ip_hash, user_agent) VALUES (?, ?, 0, ?, ?)", [$userId, $emailHash, $ipHash, $userAgent]);
            $_SESSION['flash_error'] = "Invalid email or password.";
            return Response::redirect('/login');
        }
    }
    public function register() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';
        $agree = isset($_POST['agree_terms']);
        $turnstileToken = $_POST['cf-turnstile-response'] ?? '';
        if (!Turnstile::verify($turnstileToken)) {
            $_SESSION['flash_error'] = "Security verification failed.";
            return Response::redirect('/register');
        }
        if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
            $_SESSION['flash_error'] = "All fields are required.";
            return Response::redirect('/register');
        }
        if (!$agree) {
            $_SESSION['flash_error'] = "You must accept the Terms.";
            return Response::redirect('/register');
        }
        if ($password !== $confirm) {
            $_SESSION['flash_error'] = "Passwords do not match.";
            return Response::redirect('/register');
        }
        $existing = DB::fetch("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existing) {
            $_SESSION['flash_error'] = "Email already registered.";
            return Response::redirect('/register');
        }
        $freeTokensSetting = DB::fetch("SELECT value FROM site_settings WHERE `key` = 'first_user_free_tokens'");
        $freeTokens = $freeTokensSetting ? (int)$freeTokensSetting['value'] : 100;
        DB::beginTransaction();
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            DB::query("INSERT INTO users (name, email, password_hash, email_verified_at, status) VALUES (?, ?, ?, NOW(), 'active')", [$name, $email, $hash]);
            $userId = DB::lastInsertId();
            if ($freeTokens > 0) {
                DB::query("INSERT INTO token_transactions (user_id, amount, transaction_type, reference_type, reference_id, description, balance_after) VALUES (?, ?, 'promotional', 'registration', ?, 'Initial Registration Bonus Credits', ?)", [$userId, $freeTokens, $userId, $freeTokens]);
            }
            DB::commit();
            Session::login($userId);
            return Response::redirect('/dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            $_SESSION['flash_error'] = "Registration failed.";
            return Response::redirect('/register');
        }
    }
    public function logout() {
        Session::logout();
        return Response::redirect('/');
    }
    public function googleRedirect() { return Response::redirect('/auth/google/mock-callback'); }
    public function googleMockCallback() { return View::render('auth/google_mock'); }
    public function googleMockSubmit() {
        $email = trim($_POST['email'] ?? 'mockuser.google@gmail.com');
        $name = trim($_POST['name'] ?? 'Mock Google User');
        $googleId = trim($_POST['google_id'] ?? 'g_1234567890');
        return $this->handleSocialLogin($email, $name, 'google_id', $googleId);
    }
    public function appleRedirect() { return Response::redirect('/auth/apple/mock-callback'); }
    public function appleMockCallback() { return View::render('auth/apple_mock'); }
    public function appleMockSubmit() {
        $email = trim($_POST['email'] ?? 'mockuser.apple@icloud.com');
        $name = trim($_POST['name'] ?? 'Mock Apple User');
        $appleId = trim($_POST['apple_id'] ?? 'a_1234567890');
        return $this->handleSocialLogin($email, $name, 'apple_id', $appleId);
    }
    protected function handleSocialLogin($email, $name, $providerField, $providerId) {
        DB::beginTransaction();
        try {
            $user = DB::fetch("SELECT * FROM users WHERE `{$providerField}` = ?", [$providerId]);
            if (!$user) {
                $user = DB::fetch("SELECT * FROM users WHERE email = ?", [$email]);
                if ($user) {
                    DB::query("UPDATE users SET `{$providerField}` = ? WHERE id = ?", [$providerId, $user['id']]);
                } else {
                    $freeTokensSetting = DB::fetch("SELECT value FROM site_settings WHERE `key` = 'first_user_free_tokens'");
                    $freeTokens = $freeTokensSetting ? (int)$freeTokensSetting['value'] : 100;
                    $randomPassword = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
                    DB::query("INSERT INTO users (name, email, password_hash, email_verified_at, `{$providerField}`, status) VALUES (?, ?, ?, NOW(), ?, 'active')", [$name, $email, $randomPassword, $providerId]);
                    $userId = DB::lastInsertId();
                    if ($freeTokens > 0) {
                        DB::query("INSERT INTO token_transactions (user_id, amount, transaction_type, reference_type, reference_id, description, balance_after) VALUES (?, ?, 'promotional', 'registration', ?, 'Initial Social Registration Bonus Credits', ?)", [$userId, $freeTokens, $userId, $freeTokens]);
                    }
                    $user = DB::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
                }
            }
            if ($user['status'] !== 'active') {
                DB::rollBack();
                $_SESSION['flash_error'] = "Your account is deactivated.";
                return Response::redirect('/login');
            }
            DB::commit();
            Session::login($user['id']);
            return Response::redirect('/dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            $_SESSION['flash_error'] = "Social sign-in failed.";
            return Response::redirect('/login');
        }
    }
    public function showForgotPassword() { return View::render('auth/forgot_password'); }
    public function forgotPassword() {
        $_SESSION['flash_success'] = "If that email is registered, we have sent a secure password reset link.";
        return Response::redirect('/forgot-password');
    }
}