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
        $this->twig = $twig;
        $this->model = new OfferModel();
    }

    public function index(): string
    {
        $offers = $this->model->findAll();
        return $this->twig->render('offers/list.twig.html', [
            'page_title' => 'Toutes les offres - Stage-Link',
            'offers' => $offers,
        ]);
    }

    public function btplist(): string
    {
        $offers = $this->model->findBySecteur('btp');
        return $this->twig->render('offers/BTPlist.twig.html', [
            'page_title' => 'Offres BTP - Stage-Link',
            'offers' => $offers,
        ]);
    }

    public function itlist(): string
    {
        $offers = $this->model->findBySecteur('informatique');
        return $this->twig->render('offers/ITlist.twig.html', [
            'page_title' => 'Offres IT - Stage-Link',
            'offers' => $offers,
        ]);
    }

    public function show(): string
    {
        if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
            http_response_code(400);
            return 'Bad request';
        }
        $id = (int) $_GET['id'];
        $offer = $this->model->getById($id);
        if ($offer === null) {
            http_response_code(404);
            return 'Offre introuvable';
        }
        return $this->twig->render('offers/detail.twig.html', [
            'page_title' => $offer['titre'],
            'offer' => $offer,
            'user'       => $_SESSION['user_id'] ?? null,
        ]);
    }

    public function wishlist(): string
    {
        return $this->twig->render('offers/wishlist.twig.html', [
            'page_title' => 'Wishlist - Stage-Link',
        ]);
    }

    public function createOfferForm(): string {
        return $this->twig->render('offers/create_offer.twig.html', [
            'page_title' => 'Créer une offre - Stage-Link',
            'meta_description' => 'Publiez une nouvelle offre de stage.',
        ]);
    }

    public function createOffer()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = [];
            $old    = $_POST;

            if (empty($_POST['titre']))       $errors['titre'] = "Le titre est obligatoire.";
            if (empty($_POST['ville']))       $errors['ville'] = "La ville est obligatoire.";
            if (empty($_POST['description'])) $errors['description'] = "La description est obligatoire.";

            if (empty($errors)) {
                $entrepriseId = $_SESSION['entreprise_id']; // adapte selon ton système

                // ICI on appelle le modèle, pas $this->db
                $offerId = $this->model->createOffer($_POST, $entrepriseId);

                if ($offerId !== false) {
                    header('Location: /?uri=offer_detail&id=' . $offerId);
                    exit;
                }

                $flash = [
                    'type'    => 'danger',
                    'message' => "Une erreur est survenue lors de l'enregistrement de l'offre."
                ];
            }

            echo $this->twig->render('create_offer.twig.html', [
                'errors' => $errors,
                'old'    => $old,
                'flash'  => $flash ?? null,
            ]);
            return;
        }

        echo $this->twig->render('create_offer.twig.html');
    }
}
