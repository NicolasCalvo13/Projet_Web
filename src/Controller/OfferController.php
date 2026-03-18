<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;
use App\Model\OfferModel;

class OfferController
{
    private Environment $twig;
    private OfferModel $model;

    public function __construct(Environment $twig)
    {
        $this->twig  = $twig;
        $this->model = new OfferModel();
    }

    public function index(): string
    {
         // plus tard: vraies données depuis le modèle
         return $this->twig->render('offers/list.twig.html', [
             'page_title' => 'Toutes les offres - Stage-Link',
         ]);
    }
    

    public function btplist(): string
    {
        return $this->twig->render('offers/BTPlist.twig.html', [
            'page_title' => 'Offres BTP - Stage-Link',
        ]);
    }

    public function itlist(): string
        {
            return $this->twig->render('offers/ITlist.twig.html', [
                'page_title' => 'Offres IT - Stage-Link',
            ]);
        }
    
    public function show(): string
    {
        if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
            http_response_code(400);
            return 'Bad request';
        }

        $id    = (int) $_GET['id'];
        $offer = $this->model->getById($id);

        if ($offer === null) {
            http_response_code(404);
            return 'Offre introuvable';
        }

        return $this->twig->render('offers/detail.twig.html', [
            'page_title' => $offer['title'],
            'offer'      => $offer,
        ]);
    }

    public function wishlist(): string
        {
            return $this->twig->render('offers/wishlist.twig.html', [
                'page_title' => 'Wishlist - Stage-Link',
            ]);
        }
}

