<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

class HomeModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=localhost;dbname=stage-link;charset=utf8',
            'root',
            '',
            [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }

    public function getSampleOffers(): array
    {
        $stmt = $this->pdo->query('
            SELECT
                o.id,
                o.titre,
                o.description,
                o.remuneration,
                e.nom    AS entreprise_nom,
                o.lieu   AS ville,
                e.secteur,
                e.logo_path   AS entreprise_logo
            FROM offres o
            JOIN entreprises e ON o.entreprise_id = e.id
            ORDER BY o.created_at DESC
            limit 6
        ');

        return $stmt->fetchAll();
    }

    public function countAllOffers(): int
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM offres');
        return (int) $stmt->fetchColumn();
    }

    public function countAllEntreprises(): int
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM entreprises');
        return (int) $stmt->fetchColumn();
    }

    public function countAllSectors(): int
    {
        $stmt = $this->pdo->query('SELECT COUNT(DISTINCT secteur) FROM entreprises');
        return (int) $stmt->fetchColumn();
    }


}