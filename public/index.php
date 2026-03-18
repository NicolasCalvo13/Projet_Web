<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);



// 1. Autoloader Composer
require_once __DIR__ . '/../vendor/autoload.php';

use App\Routing\Router;
use App\Controller\HomeController;
use App\Controller\OfferController;
use App\Controller\AuthController;
use App\Controller\AdminController;
use App\Controller\ApplicationController;
use App\Controller\StaticController;
use App\Controller\CompanyController;




// 2. Initialisation du moteur de templates Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig   = new \Twig\Environment($loader, [
    'cache' => false, // met un dossier de cache en prod
    'debug' => true,
]);

// 3. Initialisation (simple) de la session
session_start();

// 4. Création du Router
$router = new Router();

// === DÉCLARATION DES ROUTES ===

// Home (page d’accueil, offres récentes, etc.)
$router->get('home', function () use ($twig) {
    $controller = new HomeController($twig);
    return $controller->index();
});

// Liste des offres
$router->get('offers', function () use ($twig) {
    $controller = new OfferController($twig);
    return $controller->index();
});

// Détail d’une offre
$router->get('offer_detail', function () use ($twig) {
    $controller = new OfferController($twig);
    return $controller->show();
});

// Page de login (affichage du formulaire)
$router->get('login', function () use ($twig) {
    $controller = new AuthController($twig);
    return $controller->showLoginForm();
});

// Traitement du login (on l’implémentera plus tard avec la BDD)
$router->post('login', function () use ($twig) {
    $controller = new AuthController($twig);
    return $controller->login();
});

$router->get('offers', function () use ($twig) {
    $controller = new OfferController($twig);
    return $controller->index();
});

$router->get('admin_dashboard', function () use ($twig) {
    $controller = new AdminController($twig);
    return $controller->dashboard();
});

$router->get('apply_offer', function () use ($twig) {
    $controller = new ApplicationController($twig);
    return $controller->showApplyForm();
});

$router->get('reviews', function () use ($twig) {
    $controller = new StaticController($twig);
    return $controller->reviews();
});

$router->get('btp_offers', function () use ($twig) {
    $controller = new OfferController($twig);
    return $controller->btplist();
});

$router->get('it_offers', function () use ($twig) {
    $controller = new OfferController($twig);
    return $controller->itlist();
});

$router->get('company_detail', function () use ($twig) {
    $controller = new CompanyController($twig);
    return $controller->show();
});

$router->get('login', function () use ($twig) {
    $controller = new AuthController($twig);
    return $controller->loginForm();
});

$router->post('login_check', function () use ($twig) {
    $controller = new AuthController($twig);
    return $controller->loginCheck();
});

$router->get('contact', function () use ($twig) {
    $controller = new StaticController($twig);
    return $controller->contact();
});

$router->get('cookies', function () use ($twig) {
    $controller = new StaticController($twig);
    return $controller->cookies();
});

$router->get('legal', function () use ($twig) {
    $controller = new StaticController($twig);
    return $controller->legal();
});

$router->get('admin_company_create', function () use ($twig) {
    $controller = new AdminController($twig);
    return $controller->createCompanyForm();
});

$router->get('offer_create', function () use ($twig) {
    $controller = new AdminController($twig);
    return $controller->createOfferForm();
});

$router->get('admin_pilot_create', function () use ($twig) {
    $controller = new AdminController($twig);
    return $controller->createPilotForm();
});

$router->get('admin_student_create', function () use ($twig) {
    $controller = new AdminController($twig);
    return $controller->createStudentForm();
});

$router->get('register', function () use ($twig) {
    $controller = new AuthController($twig);
    return $controller->registerForm();
});

$router->get('applications', function () use ($twig){
    $controller = new ApplicationController($twig);
    return $controller->applications();
});

$router->get('wishlist', function () use ($twig){
    $controller = new OfferController($twig);
    return $controller->wishlist();
});


// 5. Récupération de l’URI (?uri=home, ?uri=offers, etc.)
$uri    = $_GET['uri'] ?? 'home';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// 6. Dispatch et affichage de la réponse
try {
    $response = $router->dispatch($uri, $method);
    echo $response;
} catch (\Throwable $e) {
    http_response_code(500);
    echo '<pre>';
    echo get_class($e) . "\n";
    echo $e->getMessage() . "\n\n";
    echo $e->getFile() . ':' . $e->getLine() . "\n\n";
    echo $e->getTraceAsString();
    echo '</pre>';
}

