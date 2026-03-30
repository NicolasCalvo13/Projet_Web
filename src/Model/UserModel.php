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
        try {
            $this->pdo->beginTransaction();

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (email, password, role) VALUES (:email, :password, 'student')");
            $stmt->execute([':email' => $email, ':password' => $hash]);
            $userId = $this->pdo->lastInsertId();

            $stmt2 = $this->pdo->prepare("INSERT INTO student (user_id, nom, prenom, gender) VALUES (:user_id, :nom, :prenom, :gender)");
            $stmt2->execute([':user_id' => $userId, ':nom' => $nom, ':prenom' => $prenom, ':gender' => $gender]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function register_entreprises(string $name, string $siret, string $secteur, string $email, string $password): bool {
        try {
            $this->pdo->beginTransaction();

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (email, password, role) VALUES (:email, :password, 'entreprise')");
            $stmt->execute([':email' => $email, ':password' => $hash]);
            $userId = $this->pdo->lastInsertId();

            $stmt2 = $this->pdo->prepare("INSERT INTO entreprises (user_id, nom, siret, secteur) VALUES (:user_id, :nom, :siret, :secteur)");
            $stmt2->execute([':user_id' => $userId, ':nom' => $name, ':siret' => $siret, ':secteur' => $secteur]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function emailExists(string $email): bool {
        $stmt = $this->pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return (bool) $stmt->fetch();
    }

    public function login(string $email, string $password): ?array {
        $stmt = $this->pdo->prepare("
            SELECT u.*, s.id AS student_id, s.nom, s.prenom, s.gender, s.ecole, s.formation, s.cv_path, s.photo
            FROM utilisateurs u
            LEFT JOIN student s ON s.user_id = u.id
            WHERE u.email = :email
        ");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public function login_entreprise(string $email, string $password): ?array {
        $stmt = $this->pdo->prepare("
            SELECT u.*, e.id AS entreprise_id, e.nom AS entreprise_nom, e.siret, e.secteur
            FROM utilisateurs u
            LEFT JOIN entreprises e ON e.user_id = u.id
            WHERE u.email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare("
            SELECT u.*, s.id AS student_id, s.nom, s.prenom, s.gender, s.ecole, s.formation, s.cv_path, s.photo
            FROM utilisateurs u
            LEFT JOIN student s ON s.user_id = u.id
            WHERE u.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getRecentCandidatures(int $studentId, int $limit = 2): array {
        $stmt = $this->pdo->prepare("
            SELECT c.*, o.titre, o.lieu AS ville, e.nom AS entreprise_nom
            FROM candidatures c
            JOIN offres o ON c.offre_id = o.id
            JOIN entreprises e ON o.entreprise_id = e.id
            WHERE c.student_id = :id
            ORDER BY c.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':id', $studentId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRecentWishlist(int $studentId, int $limit = 2): array {
        $stmt = $this->pdo->prepare("
            SELECT o.*, e.nom AS entreprise_nom
            FROM wishlist w
            JOIN offres o ON w.offre_id = o.id
            JOIN entreprises e ON o.entreprise_id = e.id
            WHERE w.student_id = :id
            ORDER BY w.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':id', $studentId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getStudentStats(int $studentId): array {
        $stmt = $this->pdo->prepare("
            SELECT
                COUNT(*) AS total_candidatures,
                SUM(statut = 'acceptee') AS candidatures_acceptees,
                SUM(statut = 'en_attente') AS candidatures_en_attente
            FROM candidatures WHERE student_id = :id
        ");
        $stmt->execute([':id' => $studentId]);
        $stats = $stmt->fetch();

        $stmt2 = $this->pdo->prepare("SELECT COUNT(*) AS total_wishlist FROM wishlist WHERE student_id = :id");
        $stmt2->execute([':id' => $studentId]);
        $stats['total_wishlist'] = $stmt2->fetch()['total_wishlist'];

        return $stats;
    }

    public function getCompanyStats(int $entrepriseId): array
    {
        $sql = "
            SELECT
                -- nombre d'offres de l'entreprise
                (SELECT COUNT(*)
                 FROM offres o
                 WHERE o.entreprise_id = :entreprise_id) AS total_offres,

                -- nombre total de candidatures sur les offres de l'entreprise
                (SELECT COUNT(*)
                 FROM candidatures c
                 JOIN offres o2 ON o2.id = c.offre_id
                 WHERE o2.entreprise_id = :entreprise_id) AS total_candidatures,

                -- nombre de candidats distincts ayant postulé sur ses offres
                (SELECT COUNT(DISTINCT c2.student_id)
                 FROM candidatures c2
                 JOIN offres o3 ON o3.id = c2.offre_id
                 WHERE o3.entreprise_id = :entreprise_id) AS total_candidats,

                -- note moyenne des avis sur l'entreprise (1 à 5)
                (SELECT ROUND(AVG(a.note), 1)
                 FROM avis a
                 WHERE a.entreprise_id = :entreprise_id) AS note_moyenne,

                -- nombre d'avis
                (SELECT COUNT(*)
                 FROM avis a2
                 WHERE a2.entreprise_id = :entreprise_id) AS total_avis
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':entreprise_id', $entrepriseId, \PDO::PARAM_INT);
        $stmt->execute();

        $stats = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Sécuriser un minimum les valeurs (éviter les null dans le Twig)
        return [
            'total_offres'        => (int)($stats['total_offres'] ?? 0),
            'total_candidatures'  => (int)($stats['total_candidatures'] ?? 0),
            'total_candidats'     => (int)($stats['total_candidats'] ?? 0),
            'note_moyenne'        => $stats['note_moyenne'] !== null ? (float)$stats['note_moyenne'] : null,
            'total_avis'          => (int)($stats['total_avis'] ?? 0),
        ];
    }

    public function getRecentCompanyOffers(int $companyId): ?array
{
    $stmt = $this->pdo->prepare("
        SELECT *
        FROM offres
        WHERE entreprise_id = :id
        ORDER BY created_at DESC
        LIMIT 1
    ");
    $stmt->execute([':id' => $companyId]);
    $offer = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $offer ?: null;
}

    public function getRecentCompanyApplications(int $entrepriseId): array
    {
        $sql = "
            SELECT 
                c.id,
                c.created_at,
                c.statut,
                s.prenom      AS candidat_prenom,
                s.nom         AS candidat_nom,
                o.titre       AS offre_titre
            FROM candidatures c
            JOIN offres o   ON o.id = c.offre_id
            JOIN student s  ON s.id = c.student_id
            WHERE o.entreprise_id = :entreprise_id
            ORDER BY c.created_at DESC
            LIMIT 2
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':entreprise_id', $entrepriseId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}