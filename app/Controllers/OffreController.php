<?php
namespace App\Controllers;
use App\Core\Controller;
class OffreController extends Controller {
    public function index(array $params = []): void {
        $this->render('offres/index', ['pageTitle' => 'Offres - StageLink']);
    }
    public function show(array $params = []): void {
        $this->render('offres/show', ['pageTitle' => 'Détail offre - StageLink']);
    }
}
