<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;
use App\Model\ReviewModel;

class StaticController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function reviews(): string
    {
        return $this->twig->render('static/reviews.twig.html', [
            'page_title'       => 'Avis - Stage-Link',
            'meta_description' => 'Déposez un avis.',
            'entreprises'      => (new \App\Model\ReviewModel())->findEntreprises(),
            'user'             => $_SESSION['user_id'] ?? null,
        ]);
    }

    // Soumission du formulaire (URI : reviews_submit)
    public function submitReview(): void
    {
        // On vérifie que la méthode est POST et que l'étudiant est connecté
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['student_id'])) {
            $studentId = (int) $_SESSION['student_id'];
            
            // On récupère les données (le select de l'entreprise doit envoyer son ID)
            $entrepriseId = (int) $_POST['Entreprise']; 
            $note = (int) $_POST['note'];
            // Dans ton HTML tu as "commentaires", on le map sur "commentaire" pour la BDD
            $commentaire = htmlspecialchars($_POST['commentaires'] ?? '');

            $reviewModel = new \App\Model\ReviewModel();
            $reviewModel->saveReview($studentId, $entrepriseId, $note, $commentaire);

            // Redirection vers le formulaire avec succès
            header('Location: /?uri=reviews&success=1');
            exit;
        }
    }


    // Page "Tous les avis" (URI : all_reviews)
    public function allReviews(): string
    {
        $sort = $_GET['sort'] ?? 'recent';
        
        $reviewModel = new \App\Model\ReviewModel();
        $reviews = $reviewModel->findAllReviews($sort);

        // CORRECTION ICI : on pointe vers 'static/all_reviews.twig.html'
        return $this->twig->render('static/all_reviews.twig.html', [
            'page_title'   => 'Tous les avis - Stage-Link',
            'reviews'      => $reviews,
            'current_sort' => $sort
        ]);
    }


    // Page "Mes avis" pour l'entreprise (URI : reviews_company)
    public function companyReviews(): string
    {
        $companyId = $_SESSION['entreprise_id'] ?? null;

        if ($companyId === null) {
            header('Location: /?uri=login_entreprise');
            exit;
        }

        $reviewModel = new \App\Model\ReviewModel();
        $reviews = $reviewModel->findReviewsByEntreprise((int) $companyId);

        // CORRECTION ICI : on pointe vers 'static/company_reviews.twig.html'
        return $this->twig->render('static/company_reviews.twig.html', [
            'page_title' => 'Avis sur mon entreprise - Stage-Link',
            'reviews'    => $reviews,
            'company'    => $_SESSION['company'] ?? []
        ]);
    }


    public function contact(): string
    {
        return $this->twig->render('static/contact.twig.html', [
            'page_title'       => 'Contact - Stage-Link',
            'meta_description' => 'Contactez-nous en utilisant notre formulaire.',
        ]);
    }


    public function cookies(): string
    {
        return $this->twig->render('static/cookies.twig.html', [
            'page_title'       => 'Cookies - Stage-Link',
            'meta_description' => 'Gestion des cookies sur Stage-Link.',
        ]);
    }


    public function legal(): string
    {
        return $this->twig->render('static/legal.twig.html', [
            'page_title'       => 'Mentions Legales - Stage-Link',
            'meta_description' => 'Gestion des mentions legales sur Stage-Link.',
        ]);
    }

    
}

