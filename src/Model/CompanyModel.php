<?php
declare(strict_types=1);

namespace App\Model;

use App\Database\Database;
use PDO;

class CompanyModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getCompanyById(int $id): ?array
    {
        $sql = '
            SELECT e.*, u.email
            FROM entreprises e
            LEFT JOIN utilisateurs u ON e.user_id = u.id
            WHERE e.id = :id
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function getCompanyStats(int $id): array
    {
        $stmtAvis = $this->db->prepare('SELECT ROUND(AVG(note), 1) as avg_note, COUNT(id) as nb_avis FROM avis WHERE entreprise_id = :id');
        $stmtAvis->execute(['id' => $id]);
        $avisStats = $stmtAvis->fetch(PDO::FETCH_ASSOC);

        $stmtCandidats = $this->db->prepare('
            SELECT COUNT(DISTINCT c.student_id) as nb_candidats 
            FROM candidatures c
            JOIN offres o ON c.offre_id = o.id
            WHERE o.entreprise_id = :id
        ');
        $stmtCandidats->execute(['id' => $id]);
        $candidatStats = $stmtCandidats->fetch(PDO::FETCH_ASSOC);

        return [
            'avg_note' => $avisStats['avg_note'] ?? 0,
            'nb_avis' => $avisStats['nb_avis'] ?? 0,
            'nb_candidats' => $candidatStats['nb_candidats'] ?? 0
        ];
    }

    public function getOffersByCompany(int $id): array
    {
        $stmt = $this->db->prepare('SELECT * FROM offres WHERE entreprise_id = :id ORDER BY created_at DESC');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReviewsByCompany(int $id): array
    {
        $stmt = $this->db->prepare('
            SELECT a.*, s.prenom, s.nom 
            FROM avis a 
            LEFT JOIN student s ON a.student_id = s.id 
            WHERE a.entreprise_id = :id 
            ORDER BY a.created_at DESC
        ');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}