<?php

namespace App\Helpers;

use PDO;
use PDOException;
use Exception;

class DB {
    protected static $pdo = null;

    public static function connect() {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        Env::load(dirname(__DIR__, 2) . '/.env');

        $host = Env::get('DB_HOST', '127.0.0.1');
        $port = Env::get('DB_PORT', '3306');
        $name = Env::get('DB_NAME', 'utilazy');
        $user = Env::get('DB_USER', 'utilazy_user');
        $pass = Env::get('DB_PASS', 'securepass');

        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

        try {
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            return self::$pdo;
        } catch (PDOException $e) {
            if (Env::get('APP_ENV') === 'development') {
                throw new Exception("Database Connection Failure: " . $e->getMessage());
            } else {
                throw new Exception("We are experiencing database connectivity issues. Please try again later.");
            }
        }
    }

    public static function query($sql, $params = []) {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch($sql, $params = []) {
        return self::query($sql, $params)->fetch();
    }

    public static function fetchAll($sql, $params = []) {
        return self::query($sql, $params)->fetchAll();
    }

    public static function lastInsertId() {
        return self::connect()->lastInsertId();
    }

    public static function beginTransaction() {
        $pdo = self::connect();
        if (!$pdo->inTransaction()) {
            return $pdo->beginTransaction();
        }
        return true;
    }

    public static function commit() {
        $pdo = self::connect();
        if ($pdo->inTransaction()) {
            return $pdo->commit();
        }
        return true;
    }

    public static function rollBack() {
        $pdo = self::connect();
        if ($pdo->inTransaction()) {
            return $pdo->rollBack();
        }
        return true;
    }
}
