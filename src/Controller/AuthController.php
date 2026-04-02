<?php

namespace App\Controller;

use App\Model\UserModel;
use Twig\Environment;

class AuthController
{

    private Environment $twig;
    private UserModel $userModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->userModel = new UserModel();
    }

    public function loginForm(): string
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (isset($_SESSION['user_id'])) {
            switch ($_SESSION['role']) {
                case 'admin':
                    header('Location: /?uri=admin_dashboard');
                    break;
                case 'entreprise':
                    header('Location: /?uri=company_dashboard');
                    break;
                case 'student':
                    header('Location: /?uri=student_dashboard');
                    break;
                default:
                    session_destroy();
                    header('Location: /?uri=login');
                    break;
            }
            exit;
        }

        return $this->twig->render('auth/login.twig.html', [
            'page_title' => 'Connexion - Stage-Link',
            'meta_description' => 'Connectez-vous à votre espace.',
            'errors' => [],
            'old' => [],
            'error' => null,
        ]);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('location: /?uri=login');
        exit;
    }

    public function showLoginEnterpriseForm(): string
    {
        return $this->twig->render('auth/login_entreprise.twig.html', [
            'page_title' => 'Connexion Entreprise - Stage-Link',
            'meta_description' => 'Connectez-vous à votre espace entreprise.',
            'errors' => [],
            'old' => [],
            'error' => null,
        ]);
    }

    public function registerForm(): string
    {
        return $this->twig->render('auth/register.twig.html', [
            'page_title' => 'Inscription - Stage-Link',
            'meta_description' => 'Inscrivez-vous pour obtenir une meilleure expérience utilisateur.',
            'errors' => [],
            'old' => [],
            'error' => null,
        ]);
    }

    public function registerForm_entreprise(): string
    {
        return $this->twig->render('auth/register_entreprise.twig.html', [
            'page_title' => 'Inscription Entreprise - Stage-Link',
            'meta_description' => 'Inscrivez votre entreprise pour publier des offres de stage.',
            'errors' => [],
            'old' => [],
            'error' => null,
        ]);
    }

    public function register(): string
    {
        $errors = [];
        $errorGlobal = null;

        $gender = trim($_POST['gender'] ?? '');
        $nom = trim($_POST['lastname'] ?? '');
        $prenom = trim($_POST['surname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm = trim($_POST['password_confirm'] ?? '');

        if ($gender === '') {
            $errors['gender'] = 'La civilité est obligatoire.';
        }

        if ($nom === '') {
            $errors['lastname'] = 'Le nom est obligatoire.';
        } elseif (mb_strlen($nom) < 2 || mb_strlen($nom) > 100) {
            $errors['lastname'] = 'Le nom doit contenir entre 2 et 100 caractères.';
        }

        if ($prenom === '') {
            $errors['surname'] = 'Le prénom est obligatoire.';
        } elseif (mb_strlen($prenom) < 2 || mb_strlen($prenom) > 100) {
            $errors['surname'] = 'Le prénom doit contenir entre 2 et 100 caractères.';
        }

        if ($email === '') {
            $errors['email'] = 'L’email est obligatoire.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Adresse email invalide.';
        } elseif ($this->userModel->emailExists($email)) {
            $errors['email'] = 'Cet email est déjà utilisé.';
        }

        if ($password === '') {
            $errors['password'] = 'Le mot de passe est obligatoire.';
        } elseif (mb_strlen($password) < 8) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if ($confirm === '') {
            $errors['password_confirm'] = 'La confirmation de mot de passe est obligatoire.';
        } elseif ($password !== '' && $password !== $confirm) {
            $errors['password_confirm'] = 'Les mots de passe ne correspondent pas.';
        }

        if (empty($errors)) {
            if ($this->userModel->register($gender, $nom, $prenom, $email, $password)) {
                header('Location: /?uri=login');
                exit;
            }
            $errorGlobal = 'Une erreur est survenue, réessayez.';
        }

        return $this->twig->render('auth/register.twig.html', [
            'page_title' => 'Inscription - Stage-Link',
            'errors' => $errors,
            'old' => [
                'gender' => $gender,
                'lastname' => $nom,
                'surname' => $prenom,
                'email' => $email,
            ],
            'error' => $errorGlobal,
        ]);
    }

    public function register_entreprise(): string
    {
        $errors = [];
        $errorGlobal = null;

        $name = trim($_POST['name'] ?? '');
        $siret = trim($_POST['siret'] ?? '');
        $secteur = trim($_POST['secteur'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm = trim($_POST['password_confirm'] ?? '');

        if ($name === '') {
            $errors['name'] = 'Le nom de l’entreprise est obligatoire.';
        } elseif (mb_strlen($name) < 2 || mb_strlen($name) > 255) {
            $errors['name'] = 'Le nom de l’entreprise doit contenir entre 2 et 255 caractères.';
        }

        if ($siret === '') {
            $errors['siret'] = 'Le numéro SIRET est obligatoire.';
        } elseif (!preg_match('/^\d{14}$/', $siret)) {
            $errors['siret'] = 'Le SIRET doit contenir 14 chiffres.';
        }

        if ($secteur === '') {
            $errors['secteur'] = 'Le secteur d’activité est obligatoire.';
        } elseif (mb_strlen($secteur) < 2 || mb_strlen($secteur) > 255) {
            $errors['secteur'] = 'Le secteur doit contenir entre 2 et 255 caractères.';
        }

        if ($email === '') {
            $errors['email'] = 'L’email est obligatoire.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Adresse email invalide.';
        } elseif ($this->userModel->emailExists($email)) {
            $errors['email'] = 'Cet email est déjà utilisé.';
        }

        if ($password === '') {
            $errors['password'] = 'Le mot de passe est obligatoire.';
        } elseif (mb_strlen($password) < 8) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if ($confirm === '') {
            $errors['password_confirm'] = 'La confirmation de mot de passe est obligatoire.';
        } elseif ($password !== '' && $password !== $confirm) {
            $errors['password_confirm'] = 'Les mots de passe ne correspondent pas.';
        }

        if (empty($errors)) {
            if ($this->userModel->register_entreprises($name, $siret, $secteur, $email, $password)) {
                header('Location: /?uri=login');
                exit;
            }
            $errorGlobal = 'Une erreur est survenue, réessayez.';
        }

        return $this->twig->render('auth/register_entreprise.twig.html', [
            'page_title' => 'Inscription Entreprise - Stage-Link',
            'errors' => $errors,
            'old' => [
                'name' => $name,
                'siret' => $siret,
                'secteur' => $secteur,
                'email' => $email,
            ],
            'error' => $errorGlobal,
        ]);
    }

    public function login(): string
    {
        $errors = [];
        $errorGlobal = null;

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email === '') {
            $errors['email'] = 'L’email est obligatoire.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Adresse email invalide.';
        }

        if ($password === '') {
            $errors['password'] = 'Le mot de passe est obligatoire.';
        } elseif (mb_strlen($password) < 8) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if (empty($errors)) {
            $user = $this->userModel->login($email, $password);

            if ($user) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['student_id'] = $user['student_id'] ?? null;
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['role'] = $user['role'];

                switch ($user['role']) {
                    case 'admin':
                        header('Location: /?uri=admin_dashboard');
                        break;
                    case 'entreprise':
                        header('Location: /?uri=company_dashboard');
                        break;
                    case 'student':
                        header('Location: /?uri=student_dashboard');
                        break;
                    default:
                        header('Location: /?uri=home');
                        break;
                }
                exit;
            }

            $errorGlobal = 'Email ou mot de passe incorrect.';
        }

        return $this->twig->render('auth/login.twig.html', [
            'page_title' => 'Connexion - Stage-Link',
            'errors' => $errors,
            'old' => ['email' => $email],
            'error' => $errorGlobal,
        ]);
    }

    public function login_entreprise(): string
    {
        $errors = [];
        $errorGlobal = null;

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email === '') {
            $errors['email'] = 'L’email est obligatoire.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Adresse email invalide.';
        }

        if ($password === '') {
            $errors['password'] = 'Le mot de passe est obligatoire.';
        } elseif (mb_strlen($password) < 8) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if (empty($errors)) {
            $user = $this->userModel->login_entreprise($email, $password);

            if ($user) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['entreprise_id'] = $user['entreprise_id'] ?? null;
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['role'] = $user['role'];

                switch ($user['role']) {
                    case 'admin':
                        header('Location: /?uri=admin_dashboard');
                        break;
                    case 'entreprise':
                        header('Location: /?uri=company_dashboard');
                        break;
                    case 'student':
                        header('Location: /?uri=student_dashboard');
                        break;
                    default:
                        header('Location: /?uri=home');
                        break;
                }
                exit;
            }

            $errorGlobal = 'Email ou mot de passe incorrect.';
        }

        return $this->twig->render('auth/login_entreprise.twig.html', [
            'page_title' => 'Connexion Entreprise - Stage-Link',
            'errors' => $errors,
            'old' => ['email' => $email],
            'error' => $errorGlobal,
        ]);
    }

    public function loginCheck(): string
    {
        return $this->twig->render('auth/login.twig.html', [
            'page_title' => 'Connexion - Stage-Link',
            'meta_description' => 'Connectez-vous à votre espace.',
            'error' => 'Authentification non implémentée',
        ]);
    }
}