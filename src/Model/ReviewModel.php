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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 1. Sauvegarder un avis dans la table 'avis'
    public function saveReview(int $studentId, int $entrepriseId, int $note, string $commentaire): bool {
        $query = 'INSERT INTO avis (student_id, entreprise_id, note, commentaire, created_at) 
                  VALUES (:student_id, :entreprise_id, :note, :commentaire, NOW())';
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':student_id'    => $studentId,
            ':entreprise_id' => $entrepriseId,
            ':note'          => $note,
            ':commentaire'   => $commentaire
        ]);
    }

    // 2. Récupérer tous les avis (avec tri)
    public function findAllReviews(string $sort = 'recent'): array {
        // Sécurisation du tri
        $orderBy = 'a.created_at DESC'; // Par défaut : les plus récents
        
        if ($sort === 'note_desc') {
            $orderBy = 'a.note DESC';
        } elseif ($sort === 'note_asc') {
            $orderBy = 'a.note ASC';
        }

        // On fait des jointures pour récupérer le nom de l'entreprise et du stagiaire
        $query = "
            SELECT a.*, e.nom AS nom_entreprise, s.prenom AS stagiaire_prenom 
            FROM avis a 
            JOIN entreprises e ON a.entreprise_id = e.id 
            JOIN student s ON a.student_id = s.id
            ORDER BY $orderBy
        ";
                  
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Récupérer les avis d'une entreprise spécifique
    public function findReviewsByEntreprise(int $entrepriseId): array {
        $query = '
            SELECT a.*, s.prenom AS stagiaire_prenom 
            FROM avis a 
            JOIN student s ON a.student_id = s.id
            WHERE a.entreprise_id = :entreprise_id 
            ORDER BY a.created_at DESC
        ';
                  
        $stmt = $this->db->prepare($query);
        $stmt->execute([':entreprise_id' => $entrepriseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}