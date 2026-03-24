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
}
