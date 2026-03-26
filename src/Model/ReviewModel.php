<?php
declare(strict_types=1);

namespace App\Model;

use App\Database\Database;
use PDO;

class ReviewModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findEntreprises(): array {
        $stmt = $this->db->query('SELECT id, nom FROM entreprises');
        return $stmt->fetchAll();
    }
}