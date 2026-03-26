<?php

namespace App\Controller;

use App\Model\UserModel;
use Twig\Environment;

class StudentController extends BaseController {

    private Environment $twig;
    private UserModel $userModel;

    public function __construct(Environment $twig) {
        $this->requireRole('etudiant'); 
        $this->twig      = $twig;
        $this->userModel = new UserModel();
    }

    public function dashboard(): string {
        $id = $_SESSION['user_id'];
        $user = $this->userModel->findById($id);

        return $this->twig->render('auth/student_dashboard.twig.html', [
            'page_title'   => 'Tableau de bord - Stage-Link',
            'user'         => $user,
            'candidatures' => $this->userModel->getRecentCandidatures($id, 2),
            'wishlist'     => $this->userModel->getRecentWishlist($id, 2),
            'stats'        => $this->userModel->getStudentStats($id),
        ]);
    }

    public function account(): string {
        $user = $this->userModel->findById($_SESSION['user_id']);

        return $this->twig->render('auth/account.twig.html', [
            'page_title' => 'Mon compte - Stage-Link',
            'user'       => $user,
        ]);
    }
}