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

        $inWishlist = false;
        // On utilise student_id pour parler à la base de données !
        $studentId = $_SESSION['student_id'] ?? null;

        if ($studentId && $offer !== null) {
            $inWishlist = $this->model->isInWishlist((int) $studentId, $offer['id']);
        }

        return $this->twig->render('offers/detail.twig.html', [
            'page_title' => $offer['titre'],
            'offer' => $offer,
            'user' => $_SESSION['user_id'] ?? null, // Juste pour l'affichage HTML
            'error' => $_GET['error'] ?? null,
            'in_wishlist' => $inWishlist
        ]);
    }

    public function wishlist(): string
    {
        // On utilise student_id pour la base de données
        $studentId = $_SESSION['student_id'] ?? null;
        $mesFavoris = [];

        if ($studentId !== null) {
            $mesFavoris = $this->model->getWishlistByStudent((int) $studentId);
        }

        return $this->twig->render('offers/wishlist.twig.html', [
            'page_title' => 'Wishlist - Stage-Link',
            'wishlist' => $mesFavoris,
            'user_id' => $_SESSION['user_id'] ?? null // Pour afficher la page si connecté
        ]);
    }

    public function createOfferForm(): string
    {
        return $this->twig->render('offers/create_offer.twig.html', [
            'page_title' => 'Créer une offre - Stage-Link',
            'meta_description' => 'Publiez une nouvelle offre de stage.',
        ]);
    }

    public function createOffer()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $old = $_POST;

            if (empty($_POST['titre']))
                $errors['titre'] = "Le titre est obligatoire.";
            if (empty($_POST['ville']))
                $errors['ville'] = "La ville est obligatoire.";
            if (empty($_POST['description']))
                $errors['description'] = "La description est obligatoire.";

            if (empty($errors)) {
                $entrepriseId = $_SESSION['entreprise_id'];

                $offerId = $this->model->createOffer($_POST, $entrepriseId);

                if ($offerId !== false) {
                    header('Location: /?uri=offer_detail&id=' . $offerId);
                    exit;
                }

                $flash = [
                    'type' => 'danger',
                    'message' => "Une erreur est survenue lors de l'enregistrement de l'offre."
                ];
            }

            echo $this->twig->render('create_offer.twig.html', [
                'errors' => $errors,
                'old' => $old,
                'flash' => $flash ?? null,
            ]);
            return;
        }

        echo $this->twig->render('create_offer.twig.html');
    }


    public function search(): string
    {
        $keyword = $_GET['q'] ?? '';
        $keyword = trim(htmlspecialchars($keyword));

        if ($keyword === '') {
            header('Location: /?uri=offers');
            exit;
        }

        $offers = $this->model->searchOffers($keyword);

        return $this->twig->render('offers/search_results.twig.html', [
            'page_title' => 'Recherche : ' . $keyword . ' - Stage-Link',
            'offers' => $offers,
            'keyword' => $keyword
        ]);
    }

    public function removeWishlist(): string
    {
        header('Content-Type: application/json');

        // On utilise student_id !
        $studentId = isset($_SESSION['student_id']) ? (int) $_SESSION['student_id'] : null;
        $wishlistId = isset($_GET['id']) ? (int) $_GET['id'] : null;

        if ($studentId && $wishlistId) {
            $success = $this->model->deleteFromWishlist($wishlistId, $studentId);
            if ($success) {
                return json_encode(['success' => true]);
            }
            http_response_code(500);
            return json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }

        http_response_code(400);
        return json_encode(['success' => false, 'message' => 'Requête invalide']);
    }


    public function toggleWishlistAjax(): string
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $offerId = isset($data['offer_id']) ? (int) $data['offer_id'] : null;

        // On utilise student_id !
        $studentId = isset($_SESSION['student_id']) ? (int) $_SESSION['student_id'] : null;

        if (!$studentId || !$offerId) {
            http_response_code(400);
            return json_encode(['success' => false, 'message' => 'Requête invalide ou non connecté']);
        }

        try {
            $isAlreadyFavorited = $this->model->isInWishlist($studentId, $offerId);

            if ($isAlreadyFavorited) {
                $stmt = \App\Database\Database::getConnection()->prepare('DELETE FROM wishlist WHERE student_id = :student AND offre_id = :offer');
                $success = $stmt->execute(['student' => $studentId, 'offer' => $offerId]);
                $action = 'removed';
            } else {
                $success = $this->model->addToWishlist($studentId, $offerId);
                $action = 'added';
            }

            return json_encode(['success' => $success, 'action' => $action]);

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['success' => false, 'message' => 'Erreur BDD: ' . $e->getMessage()]);
        }
    }

    public function editOffer(): string
    {
        if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
            header('Location: /?uri=company_dashboard&error=offre_introuvable');
            exit;
        }

        $offerId = (int) $_GET['id'];

        $offer = $this->model->findOfferById($offerId);

        

        if (
            !$offer ||
            !isset($offer['entreprise_id'])) {
            header('Location: /?uri=company_dashboard&error=offre_introuvable');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre'] ?? '');
            $lieu = trim($_POST['lieu'] ?? '');
            $secteur = trim($_POST['secteur'] ?? '');
            $remuneration = trim($_POST['remuneration'] ?? '');
            $typeContrat = trim($_POST['type_contrat'] ?? '');
            $duree = trim($_POST['duree'] ?? '');
            $description = trim($_POST['description'] ?? '');

            $errors = [];
            if ($titre === '') {
                $errors[] = 'Le titre est obligatoire.';
            }
            if ($lieu === '') {
                $errors[] = 'Le lieu est obligatoire.';
            }

            if (!empty($errors)) {
                return $this->twig->render('offer/edit_offer.twig.html', [
                    'offer' => $offer,
                    'errors' => $errors,
                    'success' => false,
                ]);
            }

            $this->model->updateOffer(
                $offerId,
                $titre,
                $lieu,
                $remuneration,
                $duree,
                $description
            );

            header('Location: /?uri=company_dashboard' . '&success_offer=1');
            exit;
        }

        return $this->twig->render('offers/edit_offer.twig.html', [
            'offer' => $offer,
            'errors' => [],
            'success' => isset($_GET['success']),
        ]);
    }
}