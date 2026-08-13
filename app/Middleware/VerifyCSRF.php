<?php
namespace App\Middleware;
use App\Helpers\CSRF;
use App\Helpers\Response;
class VerifyCSRF {
    public static function handle($next) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            if (strpos($requestUri, '/api/payments/webhook') !== false) return $next();
            if (!CSRF::validate($token)) {
                if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
                    return Response::json(['error' => 'CSRF verification failed.'], 403);
                } else {
                    http_response_code(403);
                    echo "<h3>403 Forbidden</h3><p>CSRF verification failed. Please refresh.</p>";
                    exit;
                }
            }
        }
        return $next();
    }
}