<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;

class AuthController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
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

}

