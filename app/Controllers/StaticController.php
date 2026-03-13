<?php
namespace App\Controllers;
use App\Core\Controller;
class StaticController extends Controller {
    public function contact(array $params = []): void {
        $this->render('static/contact', ['pageTitle' => 'Contact - StageLink']);
    }
    public function mentions(array $params = []): void {
        $this->render('static/mentions', ['pageTitle' => 'Mentions légales - StageLink']);
    }
    public function cookies(array $params = []): void {
        $this->render('static/cookies', ['pageTitle' => 'Cookies - StageLink']);
    }
}
