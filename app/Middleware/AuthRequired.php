<?php
namespace App\Middleware;
use App\Helpers\Session;
use App\Helpers\Response;
class AuthRequired {
    public static function handle($next) {
        if (!Session::check()) {
            if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
                return Response::json(['error' => 'Authentication required.'], 401);
            } else {
                $_SESSION['flash_error'] = "Please log in to continue.";
                return Response::redirect('/login');
            }
        }
        return $next();
    }
}