<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;
use App\Model\UserModel;
use App\Database\Database;

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
        return $this->twig->render('auth/login.twig.html', [
            'page_title'       => 'Connexion - Stage-Link',
            'meta_description' => 'Connectez-vous à votre espace.',
        ]);
    }

    public function showLoginEnterpriseForm(): string
    {
        return $this->twig->render('auth/login_entreprise.twig.html', [
            'page_title'       => 'Connexion Entreprise - Stage-Link',
            'meta_description' => 'Connectez-vous à votre espace entreprise.',
        ]);
    }

    // plus tard: traitement POST du login
    public function loginCheck(): string
    {
        // lire $_POST['email'], $_POST['password'], vérifier, etc.
        // pour l'instant tu peux juste faire un var_dump ou rediriger
        return $this->twig->render('auth/login.twig.html', [
            'page_title'       => 'Connexion - Stage-Link',
            'meta_description' => 'Connectez-vous à votre espace.',
            'error'            => 'Authentification non implémentée',
        ]);
    }

    public function registerForm(): string
    {
        return $this->twig->render('auth/register.twig.html', [
            'page_title'       => 'Inscription - Stage-Link',
            'meta_description' => 'Inscrivez-vous pour obtenir une meilleure expérience utilisateur.',
        ]);
    }

    public function register(): string {
    $error = null;

    $gender    = trim($_POST['gender']            ?? '');
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
        var_dump(error_get_last()); 
        $error = 'Une erreur est survenue, réessayez.';
    }

    return $this->twig->render('auth/register.twig.html', [
        'page_title' => 'Inscription - Stage-Link',
        'error'      => $error,
    ]);
}

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']);
            $password = $_POST['password'];
            $user     = $this->userModel->login($email, $password);

            if ($user) {
                session_start();
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                header('Location: /dashboard');
                exit;
            }
            $error = "Email ou mot de passe incorrect.";
        }
        echo $this->twig->render('login.html.twig', ['error' => $error ?? null]);
    }

}

