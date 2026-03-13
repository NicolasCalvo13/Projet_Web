<?php
namespace App\Controllers;
use App\Core\Controller;
class HomeController extends Controller {
    public function index(array $params = []): void {
        $this->render('home/index', ['pageTitle' => 'Accueil - StageLink']);
    }
}
