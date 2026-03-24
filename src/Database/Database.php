<?php
declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=stage-link;charset=utf8',
                    'root',      // user XAMPP par défaut
                    '',          // mot de passe XAMPP par défaut (vide)
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                die('Erreur BDD : ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
