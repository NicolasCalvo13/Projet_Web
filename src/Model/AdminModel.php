<?php
declare(strict_types=1);

namespace App\Model;

use App\Database\Database;
use PDO;

class AdminModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getDashboardStats(): array
    {
        $sql = "
            SELECT 
                (SELECT COUNT(*) FROM offres) AS total_offres,
                (SELECT COUNT(*) FROM entreprises) AS total_entreprises,
                (SELECT COUNT(*) FROM student) AS total_students,
                (SELECT ROUND(AVG(note), 1) FROM avis) AS avg_note
        ";

        $stmt = $this->db->query($sql);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total_offres' => (int) ($stats['total_offres'] ?? 0),
            'total_entreprises' => (int) ($stats['total_entreprises'] ?? 0),
            'total_students' => (int) ($stats['total_students'] ?? 0),
            'avg_note' => $stats['avg_note'] !== null ? (float) $stats['avg_note'] : 'N/A'
        ];
    }


    public function getDetailedOfferStats(): array
    {
        $stmtTotal = $this->db->query("SELECT COUNT(*) FROM offres");
        $totalOffres = (int) $stmtTotal->fetchColumn();

        $stmtDuration = $this->db->query("SELECT duree, COUNT(*) as nb FROM offres GROUP BY duree ORDER BY nb DESC");
        $repartitionDuree = $stmtDuration->fetchAll(PDO::FETCH_ASSOC);

        $sqlTopWish = "
            SELECT o.titre, COUNT(w.id) as total_wish
            FROM offres o
            LEFT JOIN wishlist w ON o.id = w.offre_id
            GROUP BY o.id
            HAVING total_wish > 0
            ORDER BY total_wish DESC
            LIMIT 5
        ";
        $topWishlisted = $this->db->query($sqlTopWish)->fetchAll(PDO::FETCH_ASSOC);

        $sqlAvgCandidatures = "
            SELECT 
                CASE 
                    WHEN COUNT(DISTINCT o.id) = 0 THEN 0 
                    ELSE COUNT(c.id) / COUNT(DISTINCT o.id) 
                END as moyenne
            FROM offres o
            LEFT JOIN candidatures c ON o.id = c.offre_id
        ";
        $avgCandidatures = (float) $this->db->query($sqlAvgCandidatures)->fetchColumn();

        return [
            'total_offres' => $totalOffres,
            'repartition_duree' => $repartitionDuree,
            'top_wishlisted' => $topWishlisted,
            'avg_applications' => round($avgCandidatures, 2)
        ];
    }
}