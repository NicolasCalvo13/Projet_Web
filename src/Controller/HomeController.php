<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;
use App\Model\HomeModel;

class HomeController
{
    private Environment $twig;
    private HomeModel $model;

    public function __construct(Environment $twig)
    {
        $this->twig  = $twig;
        $this->model = new HomeModel();
    }

    public function index(): string
    {
        // Exemple de données récupérées depuis le modèle
        $offers = $this->model->getSampleOffers();
        $totalOffers = $this->model->countAllOffers();

        // On vérifie si une entreprise est connectée (true ou false)
        $isCompany = isset($_SESSION['entreprise_id']);

        return $this->twig->render('home/index.twig.html', [
            'page_title'      => 'Accueil – Stages-Link',
            'offers'          => $offers,
            'total_offers'    => $totalOffers,
            'total_companies' => $this->model->countAllEntreprises(),
            'total_sectors'   => $this->model->countAllSectors(),
            // 👇 On envoie l'info à Twig
            'is_company'      => $isCompany, 
        ]);
    }
}

