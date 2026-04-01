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
            'page_title'    => $offer['titre'],
            'offer'         => $offer,
            'user'          => $_SESSION['user_id'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function wishlist(): string
    {
        // On récupère l'ID de l'utilisateur connecté (null s'il ne l'est pas)
        $studentId = $_SESSION['user_id'] ?? null;
        $mesFavoris = [];

        // S'il est connecté, on va chercher ses favoris dans la base de données
        if ($studentId !== null) {
            $mesFavoris = $this->model->getWishlistByStudent((int) $studentId);
        }

        // On rend la vue en lui passant les favoris ET l'identifiant de l'utilisateur
        return $this->twig->render('offers/wishlist.twig.html', [
            'page_title' => 'Wishlist - Stage-Link',
            'wishlist'   => $mesFavoris,
            'user_id'    => $studentId // Cette variable va nous servir pour le {% if %}
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
    public function removeWishlist(): void
    {
        // On indique que cette page renvoie du JSON (pratique pour AJAX)
        header('Content-Type: application/json');

        $studentId = $_SESSION['user_id'] ?? null;
        $wishlistId = $_GET['id'] ?? null;

        // On vérifie que l'utilisateur est connecté et que l'ID est valide
        if ($studentId && $wishlistId && ctype_digit($wishlistId)) {
            
            // On appelle le modèle pour supprimer la ligne
            // On passe aussi studentId par sécurité (pour qu'un étudiant ne supprime pas le favori d'un autre)
            $success = $this->model->deleteFromWishlist((int) $wishlistId, (int) $studentId);

            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Requête invalide']);
        }
    }
}
