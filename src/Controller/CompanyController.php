<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;

class CompanyController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function show(): string
    {
        // plus tard: récupérer l'id de l'entreprise via $_GET['id'] et le modèle
        return $this->twig->render('companies/detail.twig.html', [
            'page_title'       => 'Fiche entreprise - Stage-Link',
            'meta_description' => 'Fiche entreprise - StageLink, la plateforme de recherche de stages CESI',
        ]);
    }
}

