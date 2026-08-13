<?php

// Single Entry Point for Utilazy

// Autoload files
require_once __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Env;
use App\Helpers\Session;
use App\Helpers\Router;
use App\Helpers\DB;

// Load Environment Configuration
Env::load(__DIR__ . '/../.env');

// Start secure session
Session::start();

// Handle Displaying Detailed Errors in Development Mode
$debug = Env::get('APP_DEBUG', true);
if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Global exception handling
set_exception_handler(function ($e) use ($debug) {
    http_response_code(500);
    if ($debug) {
        echo "<h2>Internal Server Error (Debug Mode)</h2>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " on line " . $e->getLine() . "</p>";
        echo "<h3>Stack Trace:</h3>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        try {
            $controller = new \App\Controllers\ErrorController();
            $controller->show500($e->getMessage());
        } catch (\Exception $ex) {
            echo "<h3>Something went wrong</h3><p>An unexpected error occurred. Please try again later.</p>";
        }
    }
    exit;
});

// Load registered web routes
require_once __DIR__ . '/../routes/web.php';

// Dispatch routing matching
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

// Strip public subfolder if cPanel is running in public/ folder directly or via subfolder rewrite
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = dirname($scriptName);
if ($basePath !== '/' && strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}

// Normalize URL rewriting query param e.g. /index.php?_url=something
if (isset($_GET['_url'])) {
    $requestUri = '/' . ltrim($_GET['_url'], '/');
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

Router::dispatch($requestUri, $method);
