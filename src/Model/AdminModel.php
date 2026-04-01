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

    /**
     * Récupère les statistiques globales pour le tableau de bord administrateur
     */
    public function getDashboardStats(): array
    {
        // On utilise des sous-requêtes pour tout compter d'un coup de manière optimisée
        $sql = "
            SELECT 
                (SELECT COUNT(*) FROM offres) AS total_offres,
                (SELECT COUNT(*) FROM entreprises) AS total_entreprises,
                (SELECT COUNT(*) FROM student) AS total_students,
                (SELECT ROUND(AVG(note), 1) FROM avis) AS avg_note
        ";
        
        $stmt = $this->db->query($sql);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // On formate les données pour éviter les erreurs (surtout si la table avis est vide)
        return [
            'total_offres'      => (int) ($stats['total_offres'] ?? 0),
            'total_entreprises' => (int) ($stats['total_entreprises'] ?? 0),
            'total_students'    => (int) ($stats['total_students'] ?? 0),
            'avg_note'          => $stats['avg_note'] !== null ? (float) $stats['avg_note'] : 'N/A' // 'N/A' si aucun avis
        ];
    }
}