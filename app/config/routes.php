<?php
$router->get('/', 'HomeController', 'index');
$router->get('/offres', 'OffreController', 'index');
$router->get('/offres/:id', 'OffreController', 'show');
$router->get('/login', 'AuthController', 'loginForm');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'registerForm');
$router->post('/register', 'AuthController', 'register');
$router->get('/contact', 'StaticController', 'contact');
$router->get('/mentions-legales', 'StaticController', 'mentions');
$router->get('/cookies', 'StaticController', 'cookies');
$router->get('/wishlist', 'HomeController', 'wishlist');
