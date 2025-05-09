<?php

$router->get('/', function () use ($homeController) {
    $homeController->home();
});

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

$router->get('/blog', function () use ($blogController) {
    $blogController->blog();
});
