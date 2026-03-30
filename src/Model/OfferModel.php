<?php
declare(strict_types=1);

namespace App\Model;

use App\Database\Database;
use PDO;

class OfferModel
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? 
    Database::getConnection();
    }
    public function findAll(): array
    {
        $stmt = $this->db->query('
            SELECT o.*, e.nom AS entreprise_nom, e.ville AS entreprise_ville, e.logo AS entreprise_logo
            FROM offres o
            LEFT JOIN entreprises e ON o.entreprise_id = e.id
        ');
        return $stmt->fetchAll();
    }

    public function findBySecteur(string $secteur): array
    {
        $stmt = $this->db->prepare('
            SELECT o.*, e.nom AS entreprise_nom, e.ville AS entreprise_ville, e.logo AS entreprise_logo
            FROM offres o
            LEFT JOIN entreprises e ON o.entreprise_id = e.id
            WHERE o.secteur = :secteur
        ');
        $stmt->execute(['secteur' => $secteur]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('
            SELECT o.*, e.nom AS entreprise_nom, e.description AS entreprise_description, 
                   e.ville AS entreprise_ville, e.logo AS entreprise_logo
            FROM offres o
            LEFT JOIN entreprises e ON o.entreprise_id = e.id
            WHERE o.id = :id
        ');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getWishlistByStudent(int $studentId): array
    {
        // On fait une double jointure : 
        // 1. Pour lier la wishlist à l'offre
        // 2. Pour lier l'offre à l'entreprise et récupérer son nom
        $stmt = $this->db->prepare('
            SELECT w.id, w.offre_id, o.titre, o.remuneration, e.nom AS entreprise_nom
            FROM wishlist w
            JOIN offres o ON w.offre_id = o.id
            LEFT JOIN entreprises e ON o.entreprise_id = e.id
            WHERE w.student_id = :student_id
        ');
        
        $stmt->execute(['student_id' => $studentId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime une offre de la wishlist
     */
    public function deleteFromWishlist(int $wishlistId, int $studentId): bool
    {
        // On supprime la ligne en vérifiant l'ID de la wishlist ET l'ID de l'étudiant
        $stmt = $this->db->prepare('
            DELETE FROM wishlist 
            WHERE id = :id AND student_id = :student_id
        ');
        
        return $stmt->execute([
            'id' => $wishlistId,
            'student_id' => $studentId
        ]);
    }
}
