<?php
namespace App\Helpers;
use Exception;
class Router {
    protected static $routes = [];
    public static function get($route, $action, $middlewares = []) {
        self::addRoute('GET', $route, $action, $middlewares);
    }
    public static function post($route, $action, $middlewares = []) {
        self::addRoute('POST', $route, $action, $middlewares);
    }
    protected static function addRoute($method, $route, $action, $middlewares = []) {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route);
        $pattern = '/^' . str_replace('/', '\/', trim($pattern, '/')) . '$/';
        self::$routes[] = [
            'method' => $method,
            'route' => trim($route, '/'),
            'pattern' => $pattern,
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }
    public static function dispatch($uri, $method) {
        $uri = trim(parse_url($uri, PHP_URL_PATH), '/');
        $method = strtoupper($method);
        foreach (self::$routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $middlewares = $route['middlewares'];
                $runMiddlewares = function($index) use (&$runMiddlewares, $middlewares, $route, $params) {
                    if ($index >= count($middlewares)) {
                        return self::executeAction($route['action'], $params);
                    }
                    $middlewareClass = $middlewares[$index];
                    $middleware = new $middlewareClass();
                    return $middleware->handle(function() use ($index, $runMiddlewares) {
                        return $runMiddlewares($index + 1);
                    });
                };
                return $runMiddlewares(0);
            }
        }
        return self::handle404();
    }
    protected static function executeAction($action, $params) {
        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }
        if (is_string($action) && strpos($action, '@') !== false) {
            list($controllerName, $method) = explode('@', $action);
            $controllerClass = "App\\Controllers\\" . $controllerName;
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $method)) {
                    return call_user_func_array([$controller, $method], $params);
                }
            }
        }
        throw new Exception("Route action is invalid or controller method does not exist.");
    }
    protected static function handle404() {
        http_response_code(404);
        $controller = new \App\Controllers\ErrorController();
        return $controller->show404();
    }
}