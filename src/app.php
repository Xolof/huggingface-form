<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . "/functions/functions.php";
require __DIR__ . "/models/Api.php";

$uri = $_SERVER['REQUEST_URI'];

if ($positionQuestionMark = strpos($uri, "?")) {
    $uri = substr($uri, 0, $positionQuestionMark);
}

switch ($uri) {
    case '/':
        $question = filter_input(INPUT_GET, 'question', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (isset($question)) {
            $api = new Api($question);
            $res = $api->makeCurlRequest();
            $markdown = makeMarkdown($res);
        }
        require __DIR__ . "/views/homeView.php";
        break;

    case '/blog':
        require __DIR__ . "/views/blogView.php";
        break;

    case '/admin':
        require __DIR__ . "/views/adminView.php";
        break;

    case '/login':
        require __DIR__ . "/views/loginView.php";
        break;

    default:
        http_response_code(404);
        echo '404 Not Found';
}

exit;
