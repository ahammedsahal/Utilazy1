<?php
namespace App\Middleware;
use App\Helpers\Session;
use App\Helpers\Response;
class AdminRequired {
    public static function handle($next) {
        if (!Session::isAdmin()) {
            if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
                return Response::json(['error' => 'Admin authorization required.'], 403);
            } else {
                http_response_code(403);
                echo "<h3>403 Forbidden</h3><p>Permission denied.</p>";
                exit;
            }
        }
        return $next();
    }
}