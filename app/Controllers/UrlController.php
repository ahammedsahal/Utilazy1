<?php
namespace App\Controllers;
use App\Helpers\DB;
use App\Helpers\Session;
use App\Helpers\Response;
use App\Helpers\AgentParser;
use App\Helpers\Env;
use App\Services\TokenService;
use Exception;
class UrlController {
    public function shorten() {
        $originalUrl = trim($_POST['original_url'] ?? '');
        if (empty($originalUrl)) return Response::json(['error' => 'Original URL is required.'], 400);
        if (!filter_var($originalUrl, FILTER_VALIDATE_EMAIL) && !filter_var($originalUrl, FILTER_VALIDATE_URL)) {
            if (!str_starts_with($originalUrl, 'http://') && !str_starts_with($originalUrl, 'https://')) {
                $originalUrl = 'https://' . $originalUrl;
            }
            if (!filter_var($originalUrl, FILTER_VALIDATE_URL)) return Response::json(['error' => 'Invalid URL.'], 400);
        }
        if (preg_match('/^(javascript|data|vbscript):/i', $originalUrl)) return Response::json(['error' => 'Malicious blocked.'], 400);
        $userId = Session::userId();
        $tokenCost = 10;
        if ($userId) {
            $cnt = DB::fetch("SELECT COUNT(*) as count FROM shortened_urls WHERE user_id = ?", [$userId]);
            $urlCount = (int)$cnt['count'];
            if ($urlCount >= 3) {
                $balance = TokenService::getBalance($userId);
                if ($balance < $tokenCost && !TokenService::hasUnlimited($userId)) {
                    return Response::json(['error' => 'Insufficient balance for extra link.', 'insufficient_tokens' => true], 402);
                }
                try {
                    $tool = DB::fetch("SELECT id FROM tools WHERE slug = 'url-shortener'");
                    $toolId = $tool ? $tool['id'] : 3;
                    TokenService::deduct($userId, $toolId, $tokenCost, ['original_url' => $originalUrl]);
                } catch (Exception $e) { return Response::json(['error' => $e->getMessage()], 400); }
            }
        }
        $shortCode = $this->generateUniqueShortCode();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+3 months'));
        DB::query("INSERT INTO shortened_urls (user_id, original_url, short_code, expires_at, active) VALUES (?, ?, ?, ?, 1)", [$userId, $originalUrl, $shortCode, $expiresAt]);
        $urlId = DB::lastInsertId();
        $shortUrl = Env::get('APP_URL', 'http://localhost:8000') . '/s/' . $shortCode;
        return Response::json(['success' => true, 'short_url' => $shortUrl, 'short_code' => $shortCode, 'expires_at' => $expiresAt, 'id' => $urlId]);
    }
    public function shortenCustom() {
        if (!Session::check()) return Response::json(['error' => 'Login required.'], 401);
        $userId = Session::userId();
        $destinationUrl = trim($_POST['destination_url'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        if (empty($destinationUrl) || empty($slug)) return Response::json(['error' => 'Fields required.'], 400);
        $slug = preg_replace('/[^a-zA-Z0-9_\\-]/', '', $slug);
        if (empty($slug)) return Response::json(['error' => 'Invalid slug.'], 400);
        $reserved = ['admin', 'login', 'register', 'dashboard', 'tools', 'api', 'go', 's', 'public', 'assets', 'logout', 'pricing', 'about', 'contact', 'install'];
        if (in_array(strtolower($slug), $reserved)) return Response::json(['error' => 'Reserved slug.'], 400);
        if (!str_starts_with($destinationUrl, 'http://') && !str_starts_with($destinationUrl, 'https://')) {
            $destinationUrl = 'https://' . $destinationUrl;
        }
        if (!filter_var($destinationUrl, FILTER_VALIDATE_URL)) return Response::json(['error' => 'Invalid destination.'], 400);
        if (preg_match('/^(javascript|data|vbscript):/i', $destinationUrl)) return Response::json(['error' => 'Blocked.'], 400);
        $duplicate = DB::fetch("SELECT id FROM custom_urls WHERE slug = ?", [$slug]);
        if ($duplicate) return Response::json(['error' => 'Slug taken.'], 400);
        $tokenCost = 20;
        $cnt = DB::fetch("SELECT COUNT(*) as count FROM custom_urls WHERE user_id = ?", [$userId]);
        $customCount = (int)$cnt['count'];
        if ($customCount >= 1) {
            $balance = TokenService::getBalance($userId);
            if ($balance < $tokenCost && !TokenService::hasUnlimited($userId)) {
                return Response::json(['error' => 'Insufficient balance for custom link.', 'insufficient_tokens' => true], 402);
            }
            try {
                $tool = DB::fetch("SELECT id FROM tools WHERE slug = 'custom-url-shortener'");
                $toolId = $tool ? $tool['id'] : 4;
                TokenService::deduct($userId, $toolId, $tokenCost, ['slug' => $slug, 'destination_url' => $destinationUrl]);
            } catch (Exception $e) { return Response::json(['error' => $e->getMessage()], 400); }
        }
        $expiresAt = date('Y-m-d H:i:s', strtotime('+3 months'));
        DB::query("INSERT INTO custom_urls (user_id, slug, destination_url, expires_at, active) VALUES (?, ?, ?, ?, 1)", [$userId, $slug, $destinationUrl, $expiresAt]);
        $customUrlId = DB::lastInsertId();
        $customUrl = Env::get('APP_URL', 'http://localhost:8000') . '/go/' . $slug;
        return Response::json(['success' => true, 'custom_url' => $customUrl, 'slug' => $slug, 'expires_at' => $expiresAt, 'id' => $customUrlId]);
    }
    public function editCustom() {
        if (!Session::check()) return Response::json(['error' => 'Required.'], 401);
        $userId = Session::userId();
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id = (int)($input['id'] ?? 0);
        $newDestination = trim($input['destination_url'] ?? '');
        if ($id <= 0 || empty($newDestination)) return Response::json(['error' => 'Invalid parameters.'], 400);
        if (!str_starts_with($newDestination, 'http://') && !str_starts_with($newDestination, 'https://')) {
            $newDestination = 'https://' . $newDestination;
        }
        if (!filter_var($newDestination, FILTER_VALIDATE_URL)) return Response::json(['error' => 'Invalid URL.'], 400);
        $customUrl = DB::fetch("SELECT * FROM custom_urls WHERE id = ? AND user_id = ?", [$id, $userId]);
        if (!$customUrl) return Response::json(['error' => 'Not found.'], 404);
        DB::query("UPDATE custom_urls SET destination_url = ? WHERE id = ?", [$newDestination, $id]);
        return Response::json(['success' => true, 'message' => 'Destination link updated successfully!']);
    }
    public function shortRedirect($code) {
        $url = DB::fetch("SELECT * FROM shortened_urls WHERE short_code = ? AND active = 1", [$code]);
        if (!$url) {
            http_response_code(404);
            $controller = new ErrorController();
            return $controller->show404();
        }
        if ($url['expires_at'] && strtotime($url['expires_at']) < time()) {
            echo "Expired."; exit;
        }
        $this->logClick($url['id'], null);
        header("Location: " . $url['original_url']);
        exit;
    }
    public function customRedirect($slug) {
        $url = DB::fetch("SELECT * FROM custom_urls WHERE slug = ? AND active = 1", [$slug]);
        if (!$url) {
            http_response_code(404);
            $controller = new ErrorController();
            return $controller->show404();
        }
        if ($url['expires_at'] && strtotime($url['expires_at']) < time()) {
            echo "Expired."; exit;
        }
        $this->logClick(null, $url['id']);
        header("Location: " . $url['destination_url']);
        exit;
    }
    protected function logClick($shortUrlId, $customUrlId) {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $parser = AgentParser::parse($userAgent);
        $country = AgentParser::detectCountry();
        $device = $parser['device'];
        $browser = $parser['browser'];
        $refererHeader = $_SERVER['HTTP_REFERER'] ?? '';
        $referrer = 'Direct';
        if (!empty($refererHeader)) {
            $host = parse_url($refererHeader, PHP_URL_HOST);
            if ($host) $referrer = $host;
        }
        DB::query("INSERT INTO url_clicks (short_url_id, custom_url_id, country, device, browser, referrer) VALUES (?, ?, ?, ?, ?, ?)", [$shortUrlId, $customUrlId, $country, $device, $browser, $referrer]);
    }
    protected function generateUniqueShortCode($length = 6) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        while (true) {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $chars[rand(0, strlen($chars) - 1)];
            }
            $exist = DB::fetch("SELECT id FROM shortened_urls WHERE short_code = ?", [$code]);
            if (!$exist) return $code;
        }
    }
}