<?php

declare(strict_types=1);

namespace App\Controller;
use App\Model\OfferModel;
use App\Model\UserModel;


use Twig\Environment;

class ApplicationController
{
    private Environment $twig;
    private OfferModel $model;
    private UserModel $userModel;
    private \App\Model\ApplicationModel $applicationModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->model = new OfferModel();
        $this->userModel = new UserModel();
        $this->applicationModel = new \App\Model\ApplicationModel();
    }

    public function showApplyForm(): string
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?uri=login');
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        $student = $this->userModel->findById($_SESSION['user_id']);
        $offre = $this->model->getById($id);

        return $this->twig->render('applications/apply.twig.html', [
            'page_title' => 'Postuler à une offre - Stage-Link',
            'offre' => $offre,
            'student' => $student, // ← manquait
            'user' => $student,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function applications(): string
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?uri=login');
            exit;
        }

        $studentId = (int) $_SESSION['student_id'];
        $candidatures = $this->applicationModel->getStudentApplications($studentId);

        return $this->twig->render('applications/applications.twig.html', [
            'page_title' => 'Mes candidatures - Stage-Link',
            'candidatures' => $candidatures,
            'total' => count($candidatures),
            'success' => isset($_GET['success']),
        ]);
    }

    public function publishApplication(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = (int) $_SESSION['student_id'];
            $statut = 'en_attente';

            $offreId = (int) ($_GET['id'] ?? $_POST['offre_id'] ?? 0);
            if ($offreId === 0) {
                header('Location: /?uri=offers');
                exit;
            }

            $student = $this->userModel->findById($_SESSION['user_id']);

            if (!empty($_FILES['cv']['name'])) {
                $cvPath = 'assets/images/cv/' . basename($_FILES['cv']['name']);
                move_uploaded_file($_FILES['cv']['tmp_name'], $cvPath);
            } else {
                $cvPath = $_POST['cv_path'] ?? $student['cv_path'] ?? '';
            }

            $lm = trim($_POST['lm'] ?? '');

            if ($this->applicationModel->verifyExistingApplication($offreId, $studentId)) {
                header('Location: /?uri=offer_detail&id=' . $offreId . '&error=exists');
                exit;
            } else {
                $success = $this->applicationModel->publishApplication($offreId, $studentId, $statut, $cvPath, $lm);
            }


            if ($success) {
                header('Location: /?uri=applications&success=1');
                exit;
            } else {
                header('Location: /?uri=apply_offer&id=' . $offreId . '&error=1');
                exit;
            }
        }
    }

    public function applicationDetail(): string
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?uri=login');
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        $candidature = $this->applicationModel->getApplicationById($id);

        if ($candidature === null) {
            header('Location: /?uri=company_dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $statut = $_POST['statut'] ?? null;
            if (in_array($statut, ['acceptee', 'refusee', 'en_attente'])) {
                $this->applicationModel->updateStatut($id, $statut);
            }
            header('Location: /?uri=company_dashboard&success=1');
            exit;
        }

        return $this->twig->render('applications/application_detail.twig.html', [
            'candidature' => $candidature,
        ]);
    }
}

