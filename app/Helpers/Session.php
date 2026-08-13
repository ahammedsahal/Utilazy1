<?php

namespace App\Helpers;

use App\Helpers\DB;
use App\Helpers\Env;

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            Env::load(dirname(__DIR__, 2) . '/.env');

            $secure = Env::get('APP_ENV') === 'production';
            $cookieParams = [
                'lifetime' => 86400 * 30, // 30 days
                'path' => '/',
                'domain' => '',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Lax'
            ];

            session_set_cookie_params($cookieParams);
            session_start();
        }

        // Use isset directly to prevent infinite recursion
        if (!isset($_SESSION['user_id']) && !empty($_COOKIE['utilazy_session'])) {
            $sessionIdent = $_COOKIE['utilazy_session'];
            $dbSession = DB::fetch(
                "SELECT * FROM user_sessions WHERE session_identifier = ? AND expires_at > NOW()",
                [$sessionIdent]
            );

            if ($dbSession) {
                $user = DB::fetch("SELECT * FROM users WHERE id = ? AND status = 'active'", [$dbSession['user_id']]);
                if ($user) {
                    self::loginUser($user, $sessionIdent);
                }
            }
        }
    }

    public static function check() {
        self::start();
        return !empty($_SESSION['user_id']);
    }

    public static function user() {
        if (!self::check()) {
            return null;
        }

        $user = DB::fetch("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
        if (!$user || $user['status'] !== 'active') {
            self::logout();
            return null;
        }
        return $user;
    }

    public static function userId() {
        self::start();
        return $_SESSION['user_id'] ?? null;
    }

    public static function isAdmin() {
        $user = self::user();
        if (!$user) return false;
        return $user['id'] == 1 || $user['email'] === 'admin@utilazy.com';
    }

    public static function login($userId) {
        $user = DB::fetch("SELECT * FROM users WHERE id = ? AND status = 'active'", [$userId]);
        if (!$user) {
            return false;
        }

        session_regenerate_id(true);

        $sessionIdent = bin2hex(random_bytes(32));
        $ipHash = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $expiresAt = date('Y-m-d H:i:s', time() + 86400 * 30);

        DB::query("DELETE FROM user_sessions WHERE user_id = ?", [$userId]);

        DB::query(
            "INSERT INTO user_sessions (user_id, session_identifier, ip_hash, user_agent, expires_at) VALUES (?, ?, ?, ?, ?)",
            [$userId, $sessionIdent, $ipHash, $userAgent, $expiresAt]
        );

        $secure = Env::get('APP_ENV') === 'production';
        setcookie('utilazy_session', $sessionIdent, time() + 86400 * 30, '/', '', $secure, true);

        DB::query("UPDATE users SET last_login_at = NOW() WHERE id = ?", [$userId]);

        self::loginUser($user, $sessionIdent);
        return true;
    }

    protected static function loginUser($user, $sessionIdent) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['session_ident'] = $sessionIdent;
    }

    public static function logout() {
        self::start();

        if (!empty($_SESSION['session_ident'])) {
            DB::query("DELETE FROM user_sessions WHERE session_identifier = ?", [$_SESSION['session_ident']]);
        }

        if (isset($_COOKIE['utilazy_session'])) {
            setcookie('utilazy_session', '', time() - 3600, '/');
        }

        $_SESSION = [];
        session_destroy();
    }
}
