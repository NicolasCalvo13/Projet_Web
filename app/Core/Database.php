<?php
namespace App\Core;
use PDO;
class Database {
    private static ?PDO $instance = null;
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $cfg = require ROOT . '/app/config/database.php';
            self::$instance = new PDO(
                "mysql:host={$cfg['host']};dbname={$cfg['dbname']};charset=utf8mb4",
                $cfg['user'], $cfg['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        }
        return self::$instance;
    }
}
