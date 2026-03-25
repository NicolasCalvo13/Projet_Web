<?php

namespace App\Model;

use App\Database\Database;
use PDO;

class UserModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function register(string $gender, string $nom, string $prenom, string $email, string $password): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO utilisateurs (nom, prenom, gender, email, mot_de_passe, role)
             VALUES (:nom, :prenom,:gender, :email, :mot_de_passe, 'etudiant')"
        );
        return $stmt->execute([
            ':nom'         => $nom,
            ':prenom'      => $prenom,
            ':gender'      => $gender,
            ':email'       => $email,
            ':mot_de_passe' => $hash,
        ]);
    }

    public function emailExists(string $email): bool {
        $stmt = $this->pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return (bool) $stmt->fetch();
    }

    public function login(string $email, string $password): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        return null;
    }
}
