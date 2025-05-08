<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Logger;
use App\Routers\Router;
use App\Controllers\BlogController;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\AuthenticationController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

date_default_timezone_set("Europe/Stockholm");

if (session_status() != 2) {
    session_start();
}

$router = new Router();

$router->get('/', [HomeController::class, "home"]);
$router->get('/admin', [AdminController::class, "admin"]);
$router->get('/delete-post', [AdminController::class, "deletePost"]);
$router->post('/add-post', [AdminController::class, "addPost"]);
$router->get('/login', [AdminController::class, "showLoginPage"]);
$router->post('/login', [AuthenticationController::class, "doLogin"]);
$router->get('/logout', [AuthenticationController::class, "doLogout"]);
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
