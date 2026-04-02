<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\UserModel;
use Twig\Environment;

class UserController
{
    private Environment $twig;
    private UserModel $userModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->userModel = new UserModel();
    }

    public function student_dashboard(): string
    {
        $userId = $_SESSION['user_id'];
        $studentId = $_SESSION['student_id'];
        $user = $this->userModel->findById($userId);

        return $this->twig->render('auth/student_dashboard.twig.html', [
            'page_title' => 'Tableau de bord - Stage-Link',
            'user' => $user,
            'candidatures' => $this->userModel->getRecentCandidatures($studentId, 2),
            'wishlist' => $this->userModel->getRecentWishlist($studentId, 2),
            'stats' => $this->userModel->getStudentStats($studentId),
            'success' => isset($_GET['success']),
            'error' => isset($_GET['error']),
        ]);
    }

    public function company_dashboard(): string
    {
        // Id entreprise récupéré depuis la session
        $companyId = $_SESSION['entreprise_id'] ?? null;

        if ($companyId === null) {
            // redirection login entreprise
            header('Location: /?uri=login_entreprise');
            exit;
        }

        // Stats entreprise
        $stats = $this->userModel->getCompanyStats($companyId);

        // Offres de l'entreprise
        $offres = $this->userModel->getRecentCompanyOffers($companyId);

        // Candidatures reçues sur les offres de cette entreprise
        $candidatures = $this->userModel->getRecentCompanyApplications($companyId);

        return $this->twig->render('auth/company_dashboard.twig.html', [
            'company' => $_SESSION['company'] ?? [],
            'stats' => $stats,
            'offres' => $offres,
            'candidatures' => $candidatures,
            'entreprise' => $this->userModel->getEntrepriseInfo($companyId),
            'success' => isset($_GET['success']),
            'error' => isset($_GET['error']),
        ]);
    }


    public function editStudent(): string
    {
        $student = $this->userModel->findById($_SESSION['user_id']); // ✅ décommenté

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');
            $ecole = trim($_POST['ecole'] ?? '');
            $formation = trim($_POST['formation'] ?? '');

            $photo = $student['photo'];
            if (!empty($_FILES['photo']['name'])) {
                $photo = 'assets/images/photos/' . basename($_FILES['photo']['name']);
                move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
            }

            $cv = $student['cv_path'];
            if (!empty($_FILES['cv']['name'])) {
                $cv = 'assets/images/cv/' . basename($_FILES['cv']['name']);
                move_uploaded_file($_FILES['cv']['tmp_name'], $cv);
            }

            $this->userModel->updateStudent(
                $_SESSION['user_id'],
                $nom,
                $prenom,
                $email,
                $telephone,
                $ecole,
                $formation,
                $photo,
                $cv
            );

            header('Location: /?uri=student_dashboard&success=1');
            exit;
        }

        try {
            return $this->twig->render('auth/edit_profile.twig.html', [
                'student' => $student,
                'success' => isset($_GET['success']),
                'error' => null,
            ]);
        } catch (\Exception $e) {
            return '<pre>' . $e->getMessage() . '</pre>';
        }
    }

    public function editCompany(): string
    {
        $company = $this->userModel->findCompanyByUserId($_SESSION['user_id']);

        if (!$company) {
            header('Location: /?uri=company_dashboard&error=1');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nomEntreprise = trim($_POST['nom_entreprise'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');
            $secteur = trim($_POST['secteur'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($nomEntreprise) || empty($email)) {
                return $this->twig->render('auth/edit_company.twig.html', [
                    'company' => $company,
                    'success' => false,
                    'error' => 'Le nom de l’entreprise et l’email sont obligatoires.',
                ]);
            }

            $logo = $company['logo_path'];

            if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'assets/images/logos/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filename = time() . '_' . basename($_FILES['logo']['name']);
                $targetPath = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
                    $logo = '/' . $targetPath;
                }
            }

            $updated = $this->userModel->updateCompany(
                $_SESSION['user_id'],
                $nomEntreprise,
                $email,
                $telephone,
                $secteur,
                $description,
                $logo
            );

            if ($updated) {
                header('Location: /?uri=company_dashboard&success=1');
                exit;
            }

            return $this->twig->render('auth/edit_company.twig.html', [
                'company' => $company,
                'success' => false,
                'error' => 'Une erreur est survenue lors de la mise à jour.',
            ]);
        }

        try {
            return $this->twig->render('auth/edit_company.twig.html', [
                'company' => $company,
                'success' => isset($_GET['success']),
                'error' => null,
            ]);
        } catch (\Exception $e) {
            return '<pre>' . $e->getMessage() . '</pre>';
        }
    }

    public function companyOffers(): string
    {
        $companyId = $_SESSION['entreprise_id'] ?? null;

        if ($companyId === null) {
            header('Location: /?uri=login_entreprise');
            exit;
        }

        $offres = $this->userModel->getAllCompanyOffers((int) $companyId);

        return $this->twig->render('companies/company_offers.twig.html', [
            'page_title' => 'Mes offres - Stage-Link',
            'offres' => $offres,
            'total' => count($offres),
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function deleteCompanyOffer(): void
    {
        if (!isset($_SESSION['entreprise_id'])) {
            header('Location: /?uri=login_entreprise');
            exit;
        }

        $offreId = (int) ($_POST['offre_id'] ?? 0);

        if ($offreId <= 0) {
            header('Location: /?uri=company_offers&error=Offre invalide');
            exit;
        }

        $offerModel = new \App\Model\OfferModel();
        $offerModel->deleteOffer($offreId);

        header('Location: /?uri=company_offers&success=deleted');
        exit;
    }





}