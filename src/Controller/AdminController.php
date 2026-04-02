<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;
use App\Model\ReviewModel;
use App\Model\OfferModel;
use App\Model\UserModel;


class AdminController
{
    private Environment $twig;
    private ReviewModel $reviewModel;
    private OfferModel $model;
    private UserModel $userModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->reviewModel = new ReviewModel();
        $this->model = new OfferModel();
        $this->userModel = new UserModel();
        $this->requireRole('admin');
    }

    private function requireRole(string $requiredRole): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
            header('Location: /?uri=home');
            exit;
        }
    }

    public function dashboard(): string
    {
        $flashSuccess = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);

        $adminModel = new \App\Model\AdminModel();
        $stats = $adminModel->getDashboardStats();

        return $this->twig->render('admin/dashboard.twig.html', [
            'page_title' => 'Dashboard Admin - Stage-Link',
            'meta_description' => 'Tableau de bord administrateur - StageLink',
            'admin_nom' => $_SESSION['nom'] ?? 'Admin',
            'admin_prenom' => $_SESSION['prenom'] ?? '',
            'session' => ['flash_success' => $flashSuccess],
            'stats' => $stats,
            'success' => $_GET['success'] ?? null,
        ]);
    }

    public function createCompanyForm(): string
    {
        return $this->twig->render('admin/company_create.twig.html', [
            'page_title' => 'Créer une entreprise - StageLink',
            'meta_description' => 'Créer une entreprise - StageLink',

        ]);
    }

    public function createOfferForm(): string
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?uri=login');
            exit;
        }

        $offerModel = new OfferModel();
        $entreprises = $offerModel->getAllEntreprises();

        return $this->twig->render('admin/offer_create.twig.html', [
            'entreprises' => $entreprises,
        ]);
    }

    public function createOffer(): string
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?uri=login');
            exit;
        }

        $offerModel = new OfferModel();
        $entreprises = $offerModel->getAllEntreprises();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->twig->render('admin/offer_create.twig.html', [
                'entreprises' => $entreprises,
                'errors' => [],
                'old' => [],
            ]);
        }

        $titre = trim($_POST['titre'] ?? '');
        $entrepriseId = (int) ($_POST['entreprise_id'] ?? 0);
        $ville = trim($_POST['ville'] ?? '');
        $duree = trim($_POST['duree'] ?? '');
        $date_debut = trim($_POST['date_debut'] ?? '');
        $remuneration = trim($_POST['remuneration'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $competences = trim($_POST['competences'] ?? '');
        $profil = trim($_POST['profil'] ?? '');

        $errors = [];

        if ($titre === '') {
            $errors['titre'] = 'Le titre est obligatoire.';
        } elseif (mb_strlen($titre) < 5 || mb_strlen($titre) > 200) {
            $errors['titre'] = 'Le titre doit contenir entre 5 et 200 caractères.';
        }

        if ($entrepriseId === 0) {
            $errors['entreprise_id'] = 'Veuillez sélectionner une entreprise.';
        }

        if ($ville === '') {
            $errors['ville'] = 'La ville est obligatoire.';
        } elseif (mb_strlen($ville) < 2 || mb_strlen($ville) > 100) {
            $errors['ville'] = 'La ville doit contenir entre 2 et 100 caractères.';
        }

        if ($duree === '') {
            $errors['duree'] = 'La durée est obligatoire.';
        } elseif (!ctype_digit($duree)) {
            $errors['duree'] = 'La durée doit être un nombre entier.';
        } elseif ((int) $duree < 1 || (int) $duree > 12) {
            $errors['duree'] = 'La durée doit être comprise entre 1 et 12 mois.';
        }

        if ($date_debut === '') {
            $errors['date_debut'] = 'La date de début est obligatoire.';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_debut)) {
            $errors['date_debut'] = 'La date de début doit être au format AAAA-MM-JJ.';
        }

        if ($remuneration !== '') {
            if (!is_numeric($remuneration)) {
                $errors['remuneration'] = 'La rémunération doit être un nombre.';
            } elseif ((float) $remuneration < 0 || (float) $remuneration > 5000) {
                $errors['remuneration'] = 'La rémunération doit être comprise entre 0 et 5000 €.';
            }
        }

        if ($description === '') {
            $errors['description'] = 'La description est obligatoire.';
        } elseif (mb_strlen($description) < 30 || mb_strlen($description) > 3000) {
            $errors['description'] = 'La description doit contenir entre 30 et 3000 caractères.';
        }

        if ($competences === '') {
            $errors['competences'] = 'Les compétences sont obligatoires.';
        } elseif (mb_strlen($competences) < 3 || mb_strlen($competences) > 1000) {
            $errors['competences'] = 'Les compétences doivent contenir entre 3 et 1000 caractères.';
        }

        if ($profil !== '' && mb_strlen($profil) > 1500) {
            $errors['profil'] = 'Le profil recherché ne doit pas dépasser 1500 caractères.';
        }

        if (!empty($errors)) {
            return $this->twig->render('admin/offer_create.twig.html', [
                'entreprises' => $entreprises,
                'errors' => $errors,
                'old' => $_POST,
            ]);
        }

        $result = $offerModel->createOffer($_POST, $entrepriseId);

        if ($result) {
            header('Location: /?uri=admin_dashboard&success=offer_created');
            exit;
        }

        return $this->twig->render('admin/offer_create.twig.html', [
            'entreprises' => $entreprises,
            'errors' => ['global' => 'Une erreur est survenue, veuillez réessayer.'],
            'old' => $_POST,
        ]);
    }


    public function createPilotForm(): string
    {
        return $this->twig->render('admin/pilot_create.twig.html', [
            'page_title' => 'Créer un compte Pilote - StageLink',
            'meta_description' => 'Créer un compte pilote - StageLink',
        ]);
    }

    public function createStudentForm(): string
    {
        return $this->twig->render('admin/student_create.twig.html', [
            'page_title' => 'Créer un compte Étudiant - StageLink',
            'meta_description' => 'Créer un compte étudiant - StageLink',
        ]);
    }


    public function createCompanySubmit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $siret = trim($_POST['siret'] ?? '');
            $secteur = trim($_POST['secteur'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($name) || empty($siret) || empty($secteur) || empty($email) || empty($password)) {
                $_SESSION['flash_error'] = "Veuillez remplir tous les champs obligatoires.";
                header('Location: /?uri=admin_company_create');
                exit;
            }

            $userModel = new \App\Model\UserModel();

            if ($userModel->emailExists($email)) {
                $_SESSION['flash_error'] = "Cet email est déjà utilisé par un autre compte.";
                header('Location: /?uri=admin_company_create');
                exit;
            }

            $success = $userModel->register_entreprises($name, $siret, $secteur, $email, $password);

            if ($success) {
                $_SESSION['flash_success'] = "Le compte entreprise '{$name}' a été créé avec succès !";
                header('Location: /?uri=admin_dashboard');
            } else {
                $_SESSION['flash_error'] = "Une erreur est survenue lors de la création en base de données.";
                header('Location: /?uri=admin/admin_company_create');
            }
            exit;
        }

        header('Location: /?uri=admin_company_create');
        exit;
    }



    public function manageOffers(): string
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?uri=login');
            exit;
        }

        $offerModel = new OfferModel();

        return $this->twig->render('admin/admin_offers.twig.html', [
            'offres' => $offerModel->findAll(),
            'success' => $_GET['success'] ?? null,
        ]);
    }

    public function deleteOffer(): void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?uri=login');
            exit;
        }

        $id = (int) ($_POST['offre_id'] ?? 0);

        if ($id > 0) {
            $offerModel = new OfferModel();
            $offerModel->deleteOffer($id);
        }

        header('Location: /?uri=admin_manage_offers&success=deleted');
        exit;
    }

    public function manageCompanies(): string
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?uri=login');
            exit;
        }

        $offerModel = new OfferModel();

        return $this->twig->render('admin/admin_companies.twig.html', [
            'entreprises' => $offerModel->getAllEntreprisesDetails(),
            'success' => $_GET['success'] ?? null,
        ]);
    }

    public function deleteCompany(): void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?uri=login');
            exit;
        }

        $id = (int) ($_POST['entreprise_id'] ?? 0);

        if ($id > 0) {
            $offerModel = new OfferModel();
            $offerModel->deleteEntreprise($id);
        }

        header('Location: /?uri=admin_manage_companies&success=deleted');
        exit;
    }

    public function manageStudents(): string
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?uri=login');
            exit;
        }

        return $this->twig->render('admin/admin_student.twig.html', [
            'etudiants' => $this->userModel->getAllStudents(),
            'success' => $_GET['success'] ?? null,
        ]);
    }

    public function deleteStudent(): void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?uri=login');
            exit;
        }

        $userId = (int) ($_POST['user_id'] ?? 0);

        if ($userId > 0) {
            $this->userModel->deleteStudent($userId);
        }

        header('Location: /?uri=admin_manage_students&success=deleted');
        exit;
    }


    public function manageReviews(): string
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?uri=login');
            exit;
        }


        return $this->twig->render('admin/admin_reviews.twig.html', [
            'avis' => $this->reviewModel->getAllReviews(),
            'success' => $_GET['success'] ?? null,
        ]);
    }

    public function deleteReview(): void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?uri=login');
            exit;
        }

        $id = (int) ($_POST['avis_id'] ?? 0);

        if ($id > 0) {
            $this->reviewModel->deleteReview($id);
        }

        header('Location: /?uri=admin_manage_reviews&success=deleted');
        exit;
    }



    public function editCompanyForm(): string
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id === 0) {
            header('Location: /?uri=admin_manage_companies');
            exit;
        }

        $company = $this->userModel->getCompanyByIdForAdmin($id);
        if (!$company) {
            header('Location: /?uri=admin_manage_companies');
            exit;
        }

        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        return $this->twig->render('admin/company_edit.twig.html', [
            'page_title' => 'Modifier une entreprise - StageLink',
            'company' => $company,
            'flash_error' => $flashError
        ]);
    }

    public function editCompanySubmit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrepriseId = (int) ($_POST['entreprise_id'] ?? 0);
            $userId = (int) ($_POST['user_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $siret = trim($_POST['siret'] ?? '');
            $secteur = trim($_POST['secteur'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if (empty($name) || empty($siret) || empty($secteur) || empty($email) || $entrepriseId === 0 || $userId === 0) {
                $_SESSION['flash_error'] = "Veuillez remplir tous les champs.";
                header('Location: /?uri=admin_company_edit&id=' . $entrepriseId);
                exit;
            }

            $success = $this->userModel->updateCompanyByAdmin($entrepriseId, $userId, $name, $siret, $secteur, $email);

            if ($success) {
                header('Location: /?uri=admin_manage_companies&success=updated');
            } else {
                $_SESSION['flash_error'] = "Erreur lors de la mise à jour.";
                header('Location: /?uri=admin_company_edit&id=' . $entrepriseId);
            }
            exit;
        }
        header('Location: /?uri=admin_manage_companies');
        exit;
    }


    public function statsOffers(): string
    {
        $adminModel = new \App\Model\AdminModel();
        $stats = $adminModel->getDetailedOfferStats();

        return $this->twig->render('admin/offer_stats.twig.html', [
            'page_title' => 'Statistiques détaillées - StageLink',
            'stats' => $stats
        ]);
    }
}