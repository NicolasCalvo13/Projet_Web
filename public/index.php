<?php
define('ROOT', dirname(__DIR__));
require ROOT . '/vendor/autoload.php';
use App\Core\Router;
$router = new Router();
require ROOT . '/app/config/routes.php';
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
