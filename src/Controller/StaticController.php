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
            'page_title' => 'Avis - Stage-Link',
            'meta_description' => 'Déposez un avis.',
            'entreprises' => (new ReviewModel())->findEntreprises(),
            'user' => $_SESSION['user_id'] ?? null,
            'is_student' => isset($_SESSION['student_id']),
            'errors' => [],
            'old' => [],
            'error' => null,
            'success' => isset($_GET['success']),
        ]);
    }

    public function submitReview(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['student_id'])) {
            header('Location: /?uri=reviews');
            exit;
        }

        $studentId = (int) $_SESSION['student_id'];
        $entrepriseId = (int) ($_POST['Entreprise'] ?? 0);
        $secteur = trim($_POST['secteur'] ?? '');
        $note = trim($_POST['note'] ?? '');
        $recommande = trim($_POST['recommande'] ?? 'oui');
        $commentaire = trim($_POST['commentaires'] ?? '');

        $errors = [];
        $old = [
            'Entreprise' => $_POST['Entreprise'] ?? '',
            'secteur' => $secteur,
            'note' => $note,
            'recommande' => $recommande,
            'commentaires' => $commentaire,
        ];

        if ($entrepriseId <= 0) {
            $errors['Entreprise'] = 'Veuillez sélectionner une entreprise.';
        }

        if ($secteur === '') {
            $errors['secteur'] = 'Le secteur est obligatoire.';
        } elseif (!in_array($secteur, ['informatique', 'btp', 'autre'], true)) {
            $errors['secteur'] = 'Le secteur sélectionné est invalide.';
        }

        if ($note === '') {
            $errors['note'] = 'La note est obligatoire.';
        } elseif (!in_array($note, ['1', '2', '3', '4', '5'], true)) {
            $errors['note'] = 'La note sélectionnée est invalide.';
        }

        if (!in_array($recommande, ['oui', 'non'], true)) {
            $errors['recommande'] = 'La recommandation sélectionnée est invalide.';
        }

        if ($commentaire === '') {
            $errors['commentaires'] = 'Votre avis détaillé est obligatoire.';
        } elseif (mb_strlen($commentaire) < 20 || mb_strlen($commentaire) > 3000) {
            $errors['commentaires'] = 'Votre avis doit contenir entre 20 et 3000 caractères.';
        }

        if (!empty($errors)) {
            echo $this->twig->render('static/reviews.twig.html', [
                'page_title' => 'Avis - Stage-Link',
                'meta_description' => 'Déposez un avis.',
                'entreprises' => (new ReviewModel())->findEntreprises(),
                'user' => $_SESSION['user_id'] ?? null,
                'is_student' => isset($_SESSION['student_id']),
                'errors' => $errors,
                'old' => $old,
                'error' => null,
                'success' => false,
            ]);
            return;
        }

        $reviewModel = new ReviewModel();
        $reviewModel->saveReview($studentId, $entrepriseId, (int) $note, htmlspecialchars($commentaire));

        header('Location: /?uri=reviews&success=1');
        exit;
    }

    public function allReviews(): string
    {
        $sort = $_GET['sort'] ?? 'recent';

        $reviewModel = new ReviewModel();
        $reviews = $reviewModel->findAllReviews($sort);

        return $this->twig->render('static/all_reviews.twig.html', [
            'page_title' => 'Tous les avis - Stage-Link',
            'reviews' => $reviews,
            'current_sort' => $sort,
            'success' => $_GET['success'] ?? null,
        ]);
    }

    public function companyReviews(): string
    {
        $companyId = $_SESSION['entreprise_id'] ?? null;

        if ($companyId === null) {
            header('Location: /?uri=login_entreprise');
            exit;
        }

        $reviewModel = new ReviewModel();
        $reviews = $reviewModel->findReviewsByEntreprise((int) $companyId);

        return $this->twig->render('static/company_reviews.twig.html', [
            'page_title' => 'Avis sur mon entreprise - Stage-Link',
            'reviews' => $reviews,
            'company' => $_SESSION['company'] ?? []
        ]);
    }

    public function contact(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $feedbacks = trim($_POST['feedbacks'] ?? '');

            $errors = [];
            $old = [
                'fullname' => $fullname,
                'feedbacks' => $feedbacks,
            ];

            if ($fullname === '') {
                $errors['fullname'] = 'Le nom complet est obligatoire.';
            } elseif (mb_strlen($fullname) < 2 || mb_strlen($fullname) > 150) {
                $errors['fullname'] = 'Le nom complet doit contenir entre 2 et 150 caractères.';
            }

            if ($feedbacks === '') {
                $errors['feedbacks'] = 'Le message est obligatoire.';
            } elseif (mb_strlen($feedbacks) < 10 || mb_strlen($feedbacks) > 3000) {
                $errors['feedbacks'] = 'Le message doit contenir entre 10 et 3000 caractères.';
            }

            if (!empty($errors)) {
                return $this->twig->render('static/contact.twig.html', [
                    'page_title' => 'Contact - Stage-Link',
                    'meta_description' => 'Contactez-nous en utilisant notre formulaire.',
                    'errors' => $errors,
                    'old' => $old,
                    'error' => null,
                    'success' => null,
                ]);
            }

            return $this->twig->render('static/contact.twig.html', [
                'page_title' => 'Contact - Stage-Link',
                'meta_description' => 'Contactez-nous en utilisant notre formulaire.',
                'errors' => [],
                'old' => [],
                'error' => null,
                'success' => 'Votre message a bien été envoyé.',
            ]);
        }

        return $this->twig->render('static/contact.twig.html', [
            'page_title' => 'Contact - Stage-Link',
            'meta_description' => 'Contactez-nous en utilisant notre formulaire.',
            'errors' => [],
            'old' => [],
            'error' => null,
            'success' => null,
        ]);
    }

    public function cookies(): string
    {
        return $this->twig->render('static/cookies.twig.html', [
            'page_title' => 'Cookies - Stage-Link',
            'meta_description' => 'Gestion des cookies sur Stage-Link.',
        ]);
    }

    public function legal(): string
    {
        return $this->twig->render('static/legal.twig.html', [
            'page_title' => 'Mentions Legales - Stage-Link',
            'meta_description' => 'Gestion des mentions legales sur Stage-Link.',
        ]);
    }
}