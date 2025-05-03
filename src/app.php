<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Markdowner;
use App\Helpers\Logger;
use App\Models\Api;
use App\Models\Post;
use App\Models\User;
use App\Clients\CurlHttpClient;

if (session_status() != 2) {
    session_start();
}

$uri = $_SERVER['REQUEST_URI'];

if ($positionQuestionMark = strpos($uri, "?")) {
    $uri = substr($uri, 0, $positionQuestionMark);
}

$markdowner = new Markdowner();

switch ($uri) {
case '/':
    $question = filter_input(INPUT_GET, 'question', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (isset($question)) {

        if (!defined("HF_API_TOKEN")) {
            throw new Exception("Could not get the API token.");
        };

        $token = constant("HF_API_TOKEN");
        $logger = new Logger();
        $curlHttpClient = new CurlHttpClient();

        $api = new Api($question, $logger, $curlHttpClient, $token);
        $res = $api->makeCurlRequest();
        $markdown = $markdowner->print($res);
    }
    include __DIR__ . "/views/homeView.php";
    break;

case '/blog':
    $post = new Post();
    $allPosts = $post->getAll();
    include __DIR__ . "/views/blogView.php";
    break;

case '/admin':
    if (!$_SESSION["username"]) {
        header("Location: /");
        exit;
    }
    include __DIR__ . "/views/adminView.php";
    break;

case '/login':
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $user = new User();
    if (isset($email) && $email != "") {
        $userData = $user->getByEmail(trim($email));
        if (count($userData)) {
            if (password_verify(trim($password), $userData["password"])) {
                $user->login($userData["id"], $userData["name"]);
            } else {
                echo "<br>Could not login, check your password.";
            }
        } else {
            echo "<br>Could not find that user.";
        };
    }

    include __DIR__ . "/views/loginView.php";
    break;

case '/logout':
    $user = new User();
    $user->logout();
    break;

default:
    http_response_code(404);
    echo '404 Not Found';
}

exit;
