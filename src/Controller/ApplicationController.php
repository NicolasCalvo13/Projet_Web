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
            'offre'      => $offre,
            'student'    => $student,
            'user'       => $student,
            'error'      => null,
            'errors'     => [],
            'old'        => [],
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
            'page_title'   => 'Mes candidatures - Stage-Link',
            'candidatures' => $candidatures,
            'total'        => count($candidatures),
            'success'      => isset($_GET['success']),
        ]);
    }

    public function publishApplication(): void
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['student_id'])) {
            header('Location: /?uri=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?uri=offers');
            exit;
        }

        $studentId = (int) $_SESSION['student_id'];
        $statut = 'en_attente';

        $offreId = (int) ($_GET['id'] ?? $_POST['offre_id'] ?? 0);
        if ($offreId === 0) {
            header('Location: /?uri=offers');
            exit;
        }

        $student = $this->userModel->findById($_SESSION['user_id']);
        $offre = $this->model->getById($offreId);

        $errors = [];
        $old = [
            'lm' => trim($_POST['lm'] ?? ''),
        ];

        $lm = $old['lm'];

        if (empty($_FILES['cv']['name']) && empty($student['cv_path'] ?? '')) {
            $errors['cv'] = 'Vous devez fournir un CV (PDF).';
        } elseif (!empty($_FILES['cv']['name'])) {
            $allowedTypes = ['application/pdf', 'application/x-pdf', 'application/acrobat', 'application/nappdf'];
            $fileType = mime_content_type($_FILES['cv']['tmp_name']) ?: ($_FILES['cv']['type'] ?? '');
            $fileSize = $_FILES['cv']['size'] ?? 0;

            if (!in_array($fileType, $allowedTypes, true)) {
                $errors['cv'] = 'Le CV doit être au format PDF.';
            } elseif ($fileSize > 5 * 1024 * 1024) {
                $errors['cv'] = 'Le CV ne doit pas dépasser 5 Mo.';
            }
        }

        if ($lm === '') {
            $errors['lm'] = 'La lettre de motivation est obligatoire.';
        } elseif (mb_strlen($lm) < 50 || mb_strlen($lm) > 3000) {
            $errors['lm'] = 'La lettre de motivation doit contenir entre 50 et 3000 caractères.';
        }

        if ($this->applicationModel->verifyExistingApplication($offreId, $studentId)) {
            header('Location: /?uri=offer_detail&id=' . $offreId . '&error=exists');
            exit;
        }

        if (!empty($errors)) {
            echo $this->twig->render('applications/apply.twig.html', [
                'page_title' => 'Postuler à une offre - Stage-Link',
                'offre'      => $offre,
                'student'    => $student,
                'user'       => $student,
                'errors'     => $errors,
                'old'        => $old,
                'error'      => null,
            ]);
            return;
        }

        if (!empty($_FILES['cv']['name'])) {
            $uploadDir = 'assets/images/cv/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $filename = time() . '_' . basename($_FILES['cv']['name']);
            $cvPath = $uploadDir . $filename;
            move_uploaded_file($_FILES['cv']['tmp_name'], $cvPath);
        } else {
            $cvPath = $_POST['cv_path'] ?? $student['cv_path'] ?? '';
        }

        $success = $this->applicationModel->publishApplication($offreId, $studentId, $statut, $cvPath, $lm);

        if ($success) {
            header('Location: /?uri=applications&success=1');
            exit;
        }

        echo $this->twig->render('applications/apply.twig.html', [
            'page_title' => 'Postuler à une offre - Stage-Link',
            'offre'      => $offre,
            'student'    => $student,
            'user'       => $student,
            'errors'     => [],
            'old'        => $old,
            'error'      => 'Une erreur est survenue, veuillez réessayer.',
        ]);
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
            if (in_array($statut, ['acceptee', 'refusee', 'en_attente'], true)) {
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