<?php
namespace App\Helpers;
class Env {
    protected static $loaded = false;
    public static function load($path) {
        if (self::$loaded) return;
        if (!file_exists($path)) return;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) continue;
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                    $value = substr($value, 1, -1);
                } elseif (str_starts_with($value, "'") && str_ends_with($value, "'")) {
                    $value = substr($value, 1, -1);
                }
                if (strtolower($value) === 'true') $value = true;
                elseif (strtolower($value) === 'false') $value = false;
                elseif (strtolower($value) === 'null') $value = null;
                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
        self::$loaded = true;
    }
    public static function get($key, $default = null) {
        if (array_key_exists($key, $_ENV)) return $_ENV[$key];
        $val = getenv($key);
        return $val !== false ? $val : $default;
    }
}