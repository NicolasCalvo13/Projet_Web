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
        $this->db = $db ?? Database::getConnection();
    }

    /**
     * Récupère toutes les offres avec leur entreprise.
     */
    public function findAll(): array
    {
        $sql = '
            SELECT 
                o.id,
                o.titre,
                o.description,
                o.lieu,
                o.duree,
                o.remuneration,
                o.date_debut,
                o.created_at,
                e.id        AS entreprise_id,
                e.nom       AS entreprise_nom,
                e.secteur   AS entreprise_secteur,
                e.taille    AS entreprise_taille,
                e.logo_path AS entreprise_logo
            FROM offres o
            LEFT JOIN entreprises e ON o.entreprise_id = e.id
        ';

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les offres filtrées par secteur de l'entreprise.
     */
    public function findBySecteur(string $secteur): array
    {
        $sql = '
            SELECT 
                o.id,
                o.titre,
                o.description,
                o.lieu,
                o.duree,
                o.remuneration,
                o.date_debut,
                o.created_at,
                e.id        AS entreprise_id,
                e.nom       AS entreprise_nom,
                e.secteur   AS entreprise_secteur,
                e.taille    AS entreprise_taille,
                e.logo_path AS entreprise_logo
            FROM offres o
            LEFT JOIN entreprises e ON o.entreprise_id = e.id
            WHERE e.secteur = :secteur
        ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['secteur' => $secteur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une offre par son id avec infos entreprise.
     */
    public function getById(int $id): ?array
    {
        $sql = '
            SELECT 
                o.id,
                o.titre,
                o.description,
                o.lieu,
                o.duree,
                o.remuneration,
                o.date_debut,
                o.created_at,
                e.id        AS entreprise_id,
                e.nom       AS entreprise_nom,
                e.secteur   AS entreprise_secteur,
                e.taille    AS entreprise_taille,
                e.logo_path AS entreprise_logo
            FROM offres o
            JOIN entreprises e ON o.entreprise_id = e.id
            WHERE o.id = :id
        ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
     * Crée une nouvelle offre pour une entreprise.
     */
    public function createOffer(array $postData, int $entrepriseId)
    {
        $sql = "
            INSERT INTO offres (
                entreprise_id,
                titre,
                description,
                lieu,
                duree,
                remuneration,
                date_debut,
                created_at
            ) VALUES (
                :entreprise_id,
                :titre,
                :description,
                :lieu,
                :duree,
                :remuneration,
                :date_debut,
                NOW()
            )
        ";

        $stmt = $this->db->prepare($sql);

        $titre        = trim($postData['titre']        ?? '');
        $description  = trim($postData['description']  ?? '');
        $lieu         = trim($postData['ville']        ?? ''); // champ du form = ville
        $duree        = trim($postData['duree']        ?? '');
        $date_debut   = trim($postData['date_debut']   ?? '');
        $remuneration = $postData['remuneration'] !== '' ? (float)$postData['remuneration'] : null;

        $stmt->bindValue(':entreprise_id', $entrepriseId, PDO::PARAM_INT);
        $stmt->bindValue(':titre',         $titre,       PDO::PARAM_STR);
        $stmt->bindValue(':description',   $description, PDO::PARAM_STR);
        $stmt->bindValue(':lieu',          $lieu,        PDO::PARAM_STR);
        $stmt->bindValue(':duree',         $duree,       PDO::PARAM_STR);
        if ($remuneration === null) {
            $stmt->bindValue(':remuneration', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':remuneration', $remuneration);
        }
        $stmt->bindValue(':date_debut',    $date_debut,  PDO::PARAM_STR);

        if ($stmt->execute()) {
            return (int)$this->db->lastInsertId();
        }

        return false;
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

    // =========================================================================
    // 👇👇👇 LES DEUX FONCTIONS MANQUANTES QU'IL FALLAIT AJOUTER SONT ICI 👇👇👇
    // =========================================================================

    /**
     * Vérifie si une offre est dans les favoris d'un étudiant
     */
    public function isOfferInWishlist(int $offerId, int $studentId): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM wishlist WHERE offre_id = :o AND student_id = :s');
        $stmt->execute(['o' => $offerId, 's' => $studentId]);
        return (bool) $stmt->fetch();
    }

    /**
     * Ajoute ou retire une offre des favoris
     */
    public function toggleWishlist(int $offerId, int $studentId): bool
    {
        if ($this->isOfferInWishlist($offerId, $studentId)) {
            // L'offre y est déjà, on la supprime
            $stmt = $this->db->prepare('DELETE FROM wishlist WHERE offre_id = :o AND student_id = :s');
        } else {
            // L'offre n'y est pas, on l'ajoute
            $stmt = $this->db->prepare('INSERT INTO wishlist (offre_id, student_id) VALUES (:o, :s)');
        }
        
        return $stmt->execute(['o' => $offerId, 's' => $studentId]);
    }
}