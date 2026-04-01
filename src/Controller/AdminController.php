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
        // On sécurise l'intégralité du contrôleur dès sa construction
        $this->requireRole('admin');
    }

    /**
     * Méthode de sécurité : vérifie que l'utilisateur possède le rôle requis.
     * Si l'utilisateur n'est pas connecté ou n'a pas le bon rôle,
     * il est immédiatement redirigé vers la page de connexion (ou l'accueil).
     */
    private function requireRole(string $requiredRole): void
    {
        // 1. On s'assure que la session est bien démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. On vérifie si l'utilisateur est connecté ET s'il a le bon rôle
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
            // Sécurité : on peut aussi détruire la session suspecte si on le souhaite
            // session_destroy(); 

            // Redirection vers la page d'accueil (ou login)
            header('Location: /?uri=home');
            exit; // Arrêt immédiat de l'exécution du script
        }
    }

    public function dashboard(): string
    {
        // On stocke le message pour la vue
        $flashSuccess = $_SESSION['flash_success'] ?? null;
        
        // On supprime le message pour qu'il ne s'affiche plus au prochain rafraîchissement
        unset($_SESSION['flash_success']);

        return $this->twig->render('admin/dashboard.twig.html', [
            'page_title'       => 'Dashboard Admin - Stage-Link',
            'session'          => ['flash_success' => $flashSuccess] // On passe le flash à Twig
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
        // Le contrôle de rôle est déjà géré par le constructeur !
        return $this->twig->render('admin/pilot_create.twig.html', [
            'page_title'       => 'Créer un compte Pilote - StageLink',
            'meta_description' => 'Créer un compte pilote - StageLink',
        ]);
    }

    public function createStudentForm(): string
    {   
        // Le contrôle de rôle est déjà géré par le constructeur !
        return $this->twig->render('admin/student_create.twig.html', [
            'page_title'       => 'Créer un compte Étudiant - StageLink',
            'meta_description' => 'Créer un compte étudiant - StageLink',
        ]);
    }


    public function createCompanySubmit(): void
    {
        // On vérifie qu'on est bien en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Récupération des données
            $name     = trim($_POST['name'] ?? '');
            $siret    = trim($_POST['siret'] ?? '');
            $secteur  = trim($_POST['secteur'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // 2. Validation de base côté serveur
            if (empty($name) || empty($siret) || empty($secteur) || empty($email) || empty($password)) {
                $_SESSION['flash_error'] = "Veuillez remplir tous les champs obligatoires.";
                header('Location: /?uri=admin_company_create');
                exit;
            }

            $userModel = new \App\Model\UserModel();

            // 3. Vérification si l'email existe déjà
            if ($userModel->emailExists($email)) {
                $_SESSION['flash_error'] = "Cet email est déjà utilisé par un autre compte.";
                header('Location: /?uri=admin_company_create');
                exit;
            }

            // 4. Appel à la méthode existante du modèle
            $success = $userModel->register_entreprises($name, $siret, $secteur, $email, $password);

            // 5. Redirection avec message Flash
            if ($success) {
                $_SESSION['flash_success'] = "Le compte entreprise '{$name}' a été créé avec succès !";
                header('Location: /?uri=admin_dashboard');
            } else {
                $_SESSION['flash_error'] = "Une erreur est survenue lors de la création en base de données.";
                header('Location: /?uri=admin_company_create');
            }
            exit;
        }
        
        // Si on essaie d'accéder à l'URL en GET, on redirige vers le formulaire
        header('Location: /?uri=admin_company_create');
        exit;
    }
}