<?php
declare(strict_types=1);

namespace App\Model;

use App\Database\Database;
use PDO;


class ApplicationModel
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getConnection();
    }

    public function publishApplication(int $offerId, int $studentId, string $statut, string $cv, string $lm): bool
    {
        $sql = 'INSERT INTO candidatures (offre_id, student_id, statut, cv, lettre) VALUES (:offre_id, :student_id, :statut, :cv, :lettre)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':offre_id' => $offerId,
            ':student_id' => $studentId,
            ':statut' => $statut,
            ':cv' => $cv,
            ':lettre' => $lm,
        ]);
    }

    public function verifyExistingApplication(int $offerId, int $studentId): bool
    {
        $sql = 'SELECT COUNT(*) FROM candidatures WHERE offre_id = :offre_id AND student_id = :student_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':offre_id' => $offerId,
            ':student_id' => $studentId,
        ]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function getStudentApplications(int $studentId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                c.id,
                c.statut,
                c.cv,
                c.Lettre,
                c.created_at,
                o.id       AS offre_id,
                o.titre    AS offre_titre,
                e.id       AS entreprise_id,
                e.nom      AS entreprise_nom
            FROM candidatures c
            JOIN offres o      ON o.id = c.offre_id
            JOIN entreprises e ON e.id = o.entreprise_id
            WHERE c.student_id = :student_id
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([':student_id' => $studentId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getApplicationById(int $id): ?array
    {
        $stmt = $this->db->prepare("
        SELECT
            c.id,
            c.statut,
            c.cv,
            c.Lettre,
            c.created_at,
            s.nom           AS candidat_nom,
            s.prenom        AS candidat_prenom,
            s.telephone     AS candidat_telephone,
            s.formation     AS candidat_formation,
            s.ecole         AS candidat_ecole,
            s.photo         AS candidat_photo,
            u.email         AS candidat_email,
            o.id            AS offre_id,
            o.titre         AS offre_titre,
            e.nom           AS entreprise_nom
        FROM candidatures c
        JOIN student s      ON s.id = c.student_id
        JOIN utilisateurs u ON u.id = s.user_id
        JOIN offres o       ON o.id = c.offre_id
        JOIN entreprises e  ON e.id = o.entreprise_id
        WHERE c.id = :id
    ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function updateStatut(int $id, string $statut): bool
    {
        $stmt = $this->db->prepare("
        UPDATE candidatures SET statut = :statut WHERE id = :id
    ");
        return $stmt->execute([':statut' => $statut, ':id' => $id]);
    }
}