<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . "/functions/functions.php";

$uri = $_SERVER['REQUEST_URI'];

if ($positionQuestionMark = strpos($uri, "?")) {
    $uri = substr($uri, 0, $positionQuestionMark);
}

switch ($uri) {
    case '/':
        require __DIR__ . "/models/home.php";
        break;
    case '/blog':
        require __DIR__ . "/models/blog.php";
        break;
    case '/admin':
        require __DIR__ . "/models/admin.php";
        break;
    case '/login':
        require __DIR__ . "/models/login.php";
        break;
    default:
        http_response_code(404);
        echo '404 Not Found';
}

exit;
