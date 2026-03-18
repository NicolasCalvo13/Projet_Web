<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;

class AdminController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function dashboard(): string
    {
        return $this->twig->render('admin/dashboard.twig.html', [
            'page_title'       => 'Dashboard Admin - Stage-Link',
            'meta_description' => 'Tableau de bord administrateur - StageLink',
        ]);
    }

    public function createCompanyForm(): string
    {
        return $this->twig->render('admin/company_create.twig.html', [
            'page_title'       => 'Créer une entreprise - StageLink',
            'meta_description' => 'Créer une entreprise - StageLink',
        ]);
    }

    public function createOfferForm(): string
        {  
            return $this->twig->render('admin/offer_create.twig.html', [
                'page_title'       => 'Créer une offre - StageLink',
                'meta_description' => 'Créer une offre de stage - StageLink',
            ]);
        }
    public function createPilotForm(): string
        {
            // plus tard : contrôle de rôle (admin) ici
            return $this->twig->render('admin/pilot_create.twig.html', [
                'page_title'       => 'Créer un compte Pilote - StageLink',
                'meta_description' => 'Créer un compte pilote - StageLink',
            ]);
        }

    public function createStudentForm(): string
    {   
        // plus tard : contrôle de rôle (admin) ici
        return $this->twig->render('admin/student_create.twig.html', [
            'page_title'       => 'Créer un compte Étudiant - StageLink',
            'meta_description' => 'Créer un compte étudiant - StageLink',
        ]);
    }

}

