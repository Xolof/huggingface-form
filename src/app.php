<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Logger;
use App\Routers\Router;
use App\Helpers\FlashMessage;
use App\Controllers\BlogController;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\AuthenticationController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

date_default_timezone_set("Europe/Stockholm");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$flashMessage = new FlashMessage;
$homeController = new HomeController(new Logger());
$blogController = new BlogController();
$adminController = new AdminController($flashMessage);
$authenticationController = new AuthenticationController($flashMessage);

$router = new Router();

$router->get('/', [$homeController, 'home']);
$router->get('/blog', [$blogController, 'blog']);
$router->get('/admin', [$adminController, 'admin']);
$router->post('/add-post', [$adminController, 'addPost']);
$router->get('/delete-post', [$adminController, 'deletePost']);
$router->get('/login', [$adminController, 'showLoginPage']);
$router->post('/login', [$authenticationController, 'doLogin']);
$router->get('/logout', [$authenticationController, 'doLogout']);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

try {
    echo $router->dispatch($method, $uri);
} catch (\Exception $e) {
    Logger::log($e);
    http_response_code(500);
    echo "500 Internal Server Error";
}
