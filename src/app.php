<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Logger;
use App\Routers\Router;
use App\Controllers\BlogController;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\AuthenticationController;
use App\Helpers\FlashMessage;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

date_default_timezone_set("Europe/Stockholm");

if (session_status() != 2) {
    session_start();
}

$flashMessage = new FlashMessage;
$adminController = new AdminController($flashMessage);
$authenticationController = new AuthenticationController($flashMessage);

$router = new Router();

$router->get('/', [HomeController::class, "home"]);

$router->get('/admin', function () use ($adminController) {
    $adminController->admin();
});

$router->get('/delete-post', function () use ($adminController) {
    $adminController->deletePost();
});

$router->post('/add-post', function () use ($adminController) {
    $adminController->addPost();
});

$router->get('/login', function () use ($adminController) {
    $adminController->showLoginPage();
});

$router->post('/login', function () use ($authenticationController) {
    $authenticationController->doLogin();
});

$router->get('/logout', function () use ($authenticationController) {
    $authenticationController->doLogout();
});

$router->get('/blog', [BlogController::class, "blog"]);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

try {
    echo $router->dispatch($method, $uri);
} catch (\Exception $e) {
    Logger::log($e);
    http_response_code(500);
    echo "500 Internal Server Error";
}
