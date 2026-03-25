<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;

class UserController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function userSettings(): string
{
    // Simulation de données utilisateur (en temps réel, elles viendraient de la base de données)
    $user = [
        'nom' => 'Dupont',
        'prenom' => 'Jean',
        'email' => 'jean.dupont@email.com'
    ];

    return $this->twig->render('auth/user.twig.html', [
        'page_title' => 'Mon Compte - Stage-Link',
        'user'       => $user, // On envoie les infos de l'utilisateur à la vue
    ]);
}
}