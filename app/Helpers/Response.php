<?php
namespace App\Helpers;
class Response {
    public static function json($data, $status = 200) {
        if (ob_get_length()) ob_clean();
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    public static function redirect($url) {
        header("Location: " . $url);
        exit;
    }
}