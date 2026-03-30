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
        $this->twig      = $twig;
        $this->userModel = new UserModel();
    }

    public function student_dashboard(): string {
        $userId    = $_SESSION['user_id'];
        $studentId = $_SESSION['student_id'];
        $user      = $this->userModel->findById($userId);

        return $this->twig->render('auth/student_dashboard.twig.html', [
            'page_title'    => 'Tableau de bord - Stage-Link',
            'user'          => $user,
            'candidatures'  => $this->userModel->getRecentCandidatures($studentId, 2),
            'wishlist'      => $this->userModel->getRecentWishlist($studentId, 2),
            'stats'         => $this->userModel->getStudentStats($studentId),
        ]);
    }

    public function company_dashboard(): string {
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
            'company'      => $_SESSION['company'] ?? [],
            'stats'        => $stats,
            'offres'       => $offres,
            'candidatures' => $candidatures,
        ]);
    }


}