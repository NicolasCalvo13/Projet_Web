<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;
use App\Model\ReviewModel;

class StaticController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function reviews(): string
    {
        return $this->twig->render('static/reviews.twig.html', [
            'page_title'       => 'Avis - Stage-Link',
            'meta_description' => 'Déposez un avis.',
            'entreprises'      => (new \App\Model\ReviewModel())->findEntreprises(),
            'user'             => $_SESSION['user_id'] ?? null,
        ]);
    }

    public function contact(): string
    {
        return $this->twig->render('static/contact.twig.html', [
            'page_title'       => 'Contact - Stage-Link',
            'meta_description' => 'Contactez-nous en utilisant notre formulaire.',
        ]);
    }

    public function cookies(): string
    {
        return $this->twig->render('static/cookies.twig.html', [
            'page_title'       => 'Cookies - Stage-Link',
            'meta_description' => 'Gestion des cookies sur Stage-Link.',
        ]);
    }

    public function legal(): string
    {
        return $this->twig->render('static/legal.twig.html', [
            'page_title'       => 'Mentions Legales - Stage-Link',
            'meta_description' => 'Gestion des mentions legales sur Stage-Link.',
        ]);
    }
}

