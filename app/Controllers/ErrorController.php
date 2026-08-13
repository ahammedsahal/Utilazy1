<?php
namespace App\Controllers;
use App\Helpers\View;
class ErrorController {
    public function show404() {
        http_response_code(404);
        return View::render('errors/404');
    }
    public function show500($message = "Internal Server Error") {
        http_response_code(500);
        return View::render('errors/500', ['message' => $message]);
    }
}