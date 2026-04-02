<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;
use App\Model\CompanyModel;

class CompanyController
{
    private Environment $twig;
    private CompanyModel $model;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->model = new CompanyModel();
    }

    public function show(): string
    {
        if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
            http_response_code(400);
            return 'Requête invalide : ID manquant.';
        }

        $id = (int) $_GET['id'];

        $company = $this->model->getCompanyById($id);

        if ($company === null) {
            http_response_code(404);
            return 'Entreprise introuvable.';
        }

        $stats = $this->model->getCompanyStats($id);
        $offers = $this->model->getOffersByCompany($id);
        $reviews = $this->model->getReviewsByCompany($id);

        return $this->twig->render('companies/detail.twig.html', [
            'page_title' => $company['nom'] . ' - Stage-Link',
            'meta_description' => 'Découvrez les offres et les avis de ' . $company['nom'] . ' sur StageLink.',
            'company' => $company,
            'stats' => $stats,
            'offers' => $offers,
            'reviews' => $reviews
        ]);
    }
}