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
             VALUES (:nom, :prenom, :gender, :email, :mot_de_passe, 'etudiant')"
        );
        return $stmt->execute([
            ':nom'          => $nom,
            ':prenom'       => $prenom,
            ':gender'       => $gender,
            ':email'        => $email,
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


    public function findById(int $id): ?array {
    $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch() ?: null;
}

public function getRecentCandidatures(int $id, int $limit = 2): array {
    $stmt = $this->pdo->prepare(
        "SELECT c.*, o.titre, o.ville, e.nom AS entreprise_nom
         FROM candidatures c
         JOIN offres o ON c.offre_id = o.id
         JOIN entreprises e ON o.entreprise_id = e.id
         WHERE c.etudiant_id = :id
         ORDER BY c.created_at DESC
         LIMIT :limit"
    );
    $stmt->bindValue(':id',    $id,    PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

public function getRecentWishlist(int $id, int $limit = 2): array {
    $stmt = $this->pdo->prepare(
        "SELECT o.*, e.nom AS entreprise_nom
         FROM wishlist w
         JOIN offres o ON w.offre_id = o.id
         JOIN entreprises e ON o.entreprise_id = e.id
         WHERE w.etudiant_id = :id
         ORDER BY w.id DESC
         LIMIT :limit"
    );
    $stmt->bindValue(':id',    $id,    PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

public function getStudentStats(int $id): array {
    $stmt = $this->pdo->prepare(
        "SELECT
            COUNT(*) AS total_candidatures,
            SUM(statut = 'acceptee') AS candidatures_acceptees,
            SUM(statut = 'en_attente') AS candidatures_en_attente
         FROM candidatures WHERE etudiant_id = :id"
    );
    $stmt->execute([':id' => $id]);
    $stats = $stmt->fetch();

    $stmt2 = $this->pdo->prepare(
        "SELECT COUNT(*) AS total_wishlist FROM wishlist WHERE etudiant_id = :id"
    );
    $stmt2->execute([':id' => $id]);
    $stats['total_wishlist'] = $stmt2->fetch()['total_wishlist'];

    return $stats;
}

}
