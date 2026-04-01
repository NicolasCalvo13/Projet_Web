<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;

class ApplicationController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->model = new OfferModel();
    }

    public function showApplyForm(): string
    {
        return $this->twig->render('applications/apply.twig.html', [
            'page_title' => 'Postuler à une offre - Stage-Link',
            'offre'      => $this->model->getById($id),
        ]);
    }

    public function applications(): string
    {
        return $this->twig->render('applications/applications.twig.html', [
            'page_title' => 'Mes offres - Stage-Link',
        ]);
    }
}

