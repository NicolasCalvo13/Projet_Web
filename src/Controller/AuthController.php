<?php

namespace App\Controller;

use App\Model\UserModel;
use Twig\Environment;

class AuthController {

    private Environment $twig;
    private UserModel $userModel;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
        $this->userModel = new UserModel();
    }

    public function loginForm(): string {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Si déjà connecté → redirige selon le rôle
        if (isset($_SESSION['user_id'])) {
            switch ($_SESSION['role']) {
                case 'admin':
                    header('Location: /?uri=admin_dashboard'); break;
                case 'pilote':
                    header('Location: /?uri=pilot_dashboard'); break;
                case 'student':
                    header('Location: /?uri=student_dashboard'); break;
                default:
                    session_destroy();
                    header('Location: /?uri=login'); break;
            }
            exit;
        }

        // Sinon → affiche le formulaire
        return $this->twig->render('auth/login.twig.html', [
            'page_title'       => 'Connexion - Stage-Link',
            'meta_description' => 'Connectez-vous à votre espace.',
        ]);
    }

    public function logout(): void {
        session_unset();
        session_destroy();
        header('location: /?uri=login');
        exit;
    }

    public function showLoginEnterpriseForm(): string {
        return $this->twig->render('auth/login_entreprise.twig.html', [
            'page_title'       => 'Connexion Entreprise - Stage-Link',
            'meta_description' => 'Connectez-vous à votre espace entreprise.',
        ]);
    }

    public function registerForm(): string {
        return $this->twig->render('auth/register.twig.html', [
            'page_title'       => 'Inscription - Stage-Link',
            'meta_description' => 'Inscrivez-vous pour obtenir une meilleure expérience utilisateur.',
        ]);
    }

    public function registerForm_entreprise(): string {
        return $this->twig->render('auth/register_entreprise.twig.html', [
            'page_title'       => 'Inscription Entreprise - Stage-Link',
            'meta_description' => 'Inscrivez votre entreprise pour publier des offres de stage.',
        ]);
    }

    public function register(): string {
        $error = null;

        $gender   = trim($_POST['gender']           ?? '');
        $nom      = trim($_POST['lastname']         ?? '');
        $prenom   = trim($_POST['surname']          ?? '');
        $email    = trim($_POST['email']            ?? '');
        $password = trim($_POST['password']         ?? '');
        $confirm  = trim($_POST['password_confirm'] ?? '');

        if (empty($nom) || empty($email) || empty($password)) {
            $error = 'Tous les champs sont obligatoires.';
        } elseif ($password !== $confirm) {
            $error = 'Les mots de passe ne correspondent pas.';
        } elseif ($this->userModel->emailExists($email)) {
            $error = 'Cet email est déjà utilisé.';
        } elseif ($this->userModel->register($gender, $nom, $prenom, $email, $password)) {
            header('Location: /?uri=login');
            exit;
        } else {
            $error = 'Une erreur est survenue, réessayez.';
        }

        return $this->twig->render('auth/register.twig.html', [
            'page_title' => 'Inscription - Stage-Link',
            'error'      => $error,
        ]);
    }

    public function register_entreprise(): string {
        $error = null;

        $name       = trim($_POST['name']               ?? ''); 
        $siret      = trim($_POST['siret']              ?? ''); 
        $secteur    = trim($_POST['secteur']            ?? '');
        $email      = trim($_POST['email']              ?? '');
        $password   = trim($_POST['password']           ?? '');
        $confirm    = trim($_POST['password_confirm']   ?? '');

        if (empty($name) || empty($siret) || empty($secteur) || empty($email) || empty($password) || empty($confirm)) {
            $error = 'Tous les champs sont obligatoires.';
        } elseif ($password !== $confirm) {
            $error = 'Les mots de passe ne correspondent pas.';
        } elseif ($this->userModel->emailExists($email)) {
            $error = 'Cet email est déjà utilisé.';
        } elseif ($this->userModel->register_entreprises($name, $siret, $secteur, $email, $password)) {
            header('Location: /?uri=login');
            exit;
        } else {
            $error = 'Une erreur est survenue, réessayez.';
        }

        return $this->twig->render('auth/register_entreprise.twig.html', [
            'page_title' => 'Inscription Entreprise - Stage-Link',
            'error'      => $error,
        ]);
    }

    public function login(): string {
        $error = null;

        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $user = $this->userModel->login($email, $password);

        if ($user) {
            session_start();
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['student_id'] = $user['student_id'] ?? null; 
            $_SESSION['nom']        = $user['nom'];
            $_SESSION['prenom']     = $user['prenom'];             
            $_SESSION['role']       = $user['role'];

            switch ($user['role']) {
                case 'admin':    header('Location: /?uri=admin_dashboard');   break;
                case 'entreprise':   header('Location: /?uri=company_dashboard');   break;
                case 'student': header('Location: /?uri=student_dashboard'); break;
                default:         header('Location: /?uri=home');              break;
            }
            exit;
        }

        $error = 'Email ou mot de passe incorrect.';
        return $this->twig->render('auth/login.twig.html', [
            'page_title' => 'Connexion - Stage-Link',
            'error'      => $error,
        ]);
    }

    public function login_entreprise(): string {
        $error = null;

        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $user = $this->userModel->login_entreprise($email, $password);


        if ($user) {
            session_start();
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['entreprise_id'] = $user['entreprise_id'] ?? null; 
            $_SESSION['nom']        = $user['nom'];
            $_SESSION['role']       = $user['role'];

            switch ($user['role']) {
                case 'admin':    header('Location: /?uri=admin_dashboard');   break;
                case 'entreprise':   header('Location: /?uri=company_dashboard');   break;
                case 'student': header('Location: /?uri=student_dashboard'); break;
                default:         header('Location: /?uri=home');              break;
            }
            exit;
        }

        $error = 'Email ou mot de passe incorrect.';
        return $this->twig->render('auth/login.twig.html', [
            'page_title' => 'Connexion - Stage-Link',
            'error'      => $error,
        ]);
    }

    public function loginCheck(): string {
        return $this->twig->render('auth/login.twig.html', [
            'page_title'       => 'Connexion - Stage-Link',
            'meta_description' => 'Connectez-vous à votre espace.',
            'error'            => 'Authentification non implémentée',
        ]);
    }

    

}
