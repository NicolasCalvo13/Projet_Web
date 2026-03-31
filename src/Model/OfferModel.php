<?php
declare(strict_types=1);

namespace App\Model;

use App\Database\Database;
use PDO;

class OfferModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
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
}