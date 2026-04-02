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

        $titre = trim($postData['titre'] ?? '');
        $description = trim($postData['description'] ?? '');
        $lieu = trim($postData['ville'] ?? ''); // champ du form = ville
        $duree = trim($postData['duree'] ?? '');
        $date_debut = trim($postData['date_debut'] ?? '');
        $remuneration = $postData['remuneration'] !== '' ? (float) $postData['remuneration'] : null;

        $stmt->bindValue(':entreprise_id', $entrepriseId, PDO::PARAM_INT);
        $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':lieu', $lieu, PDO::PARAM_STR);
        $stmt->bindValue(':duree', $duree, PDO::PARAM_STR);
        if ($remuneration === null) {
            $stmt->bindValue(':remuneration', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':remuneration', $remuneration);
        }
        $stmt->bindValue(':date_debut', $date_debut, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return (int) $this->db->lastInsertId();
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


    /**
     * Recherche des offres selon un mot-clé (dans le titre, description, lieu ou nom entreprise)
     */
    public function searchOffers(string $keyword): array
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
            WHERE o.titre LIKE :keyword 
               OR o.description LIKE :keyword 
               OR o.lieu LIKE :keyword 
               OR e.nom LIKE :keyword
            ORDER BY o.created_at DESC
        ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['keyword' => '%' . $keyword . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllEntreprises(): array
    {
        $stmt = $this->db->query('SELECT id, nom FROM entreprises ORDER BY nom ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function deleteOffer(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM offres WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function getAllEntreprisesDetails(): array
{
    $stmt = $this->db->query('
        SELECT id, nom, secteur, taille, telephone, logo_path
        FROM entreprises
        ORDER BY nom ASC
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function deleteEntreprise(int $id): bool
{
    // Récupère le user_id lié à cette entreprise
    $stmt = $this->db->prepare('SELECT user_id FROM entreprises WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) return false;

    // Supprimer l'utilisateur → cascade automatique sur :
    // entreprises → offres → candidatures, wishlist
    //             → avis
    $stmt = $this->db->prepare('DELETE FROM utilisateurs WHERE id = :user_id');
    return $stmt->execute(['user_id' => $row['user_id']]);
}

/**
     * Vérifie si une offre est déjà dans la wishlist de l'étudiant
     */
    public function isInWishlist(int $studentId, int $offerId): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM wishlist WHERE student_id = :student_id AND offre_id = :offre_id');
        $stmt->execute(['student_id' => $studentId, 'offre_id' => $offerId]);
        return (bool) $stmt->fetch();
    }

    /**
     * Ajoute une offre à la wishlist
     */
    public function addToWishlist(int $studentId, int $offerId): bool
    {
        // On évite les doublons
        if ($this->isInWishlist($studentId, $offerId)) {
            return true; 
        }

        $stmt = $this->db->prepare('INSERT INTO wishlist (student_id, offre_id) VALUES (:student_id, :offre_id)');
        return $stmt->execute(['student_id' => $studentId, 'offre_id' => $offerId]);
    }
/**
 * Récupère les offres de manière paginée
 */
/**
 * Récupère les offres de manière paginée (avec secteur optionnel)
 */
public function findPaginated(int $limit, int $offset, ?string $secteur = null): array
{
    $sql = '
        SELECT 
            o.id, o.titre, o.description, o.lieu, o.duree, 
            o.remuneration, o.date_debut, o.created_at,
            e.id AS entreprise_id, e.nom AS entreprise_nom, 
            e.logo_path AS entreprise_logo
        FROM offres o
        LEFT JOIN entreprises e ON o.entreprise_id = e.id';

    // Ajout dynamique du filtre secteur si présent
    if ($secteur) {
        $sql .= ' WHERE e.secteur = :secteur';
    }

    $sql .= ' ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset';

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    if ($secteur) {
        $stmt->bindValue(':secteur', $secteur, PDO::PARAM_STR);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Compte le nombre total d'offres (avec secteur optionnel)
 */
public function countAll(?string $secteur = null): int
{
    $sql = 'SELECT COUNT(*) FROM offres o LEFT JOIN entreprises e ON o.entreprise_id = e.id';
    if ($secteur) {
        $sql .= ' WHERE e.secteur = :secteur';
    }
    
    $stmt = $this->db->prepare($sql);
    if ($secteur) {
        $stmt->execute(['secteur' => $secteur]);
    } else {
        $stmt->execute();
    }
    return (int) $stmt->fetchColumn();
}
}
