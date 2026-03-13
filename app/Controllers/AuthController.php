<?php
namespace App\Controllers;
use App\Core\Controller;
class AuthController extends Controller {
    public function loginForm(array $params = []): void {
        $this->render('auth/login', ['pageTitle' => 'Connexion - StageLink']);
    }
    public function login(array $params = []): void {
        $this->redirect('/');
    }
    public function registerForm(array $params = []): void {
        $this->render('auth/register', ['pageTitle' => 'Inscription - StageLink']);
    }
    public function register(array $params = []): void {
        $this->redirect('/');
    }
}
