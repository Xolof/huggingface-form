<?php

require_once __DIR__ . '/../config.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Markdowner;
use App\Models\Api;
use App\Models\Post;
use App\Models\User;

session_start();

$uri = $_SERVER['REQUEST_URI'];

if ($positionQuestionMark = strpos($uri, "?")) {
    $uri = substr($uri, 0, $positionQuestionMark);
}

$markdowner = new Markdowner();

switch ($uri) {
    case '/':
        $question = filter_input(INPUT_GET, 'question', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (isset($question)) {
            $api = new Api($question);
            $res = $api->makeCurlRequest();
            $markdown = $markdowner->print($res);
        }
        require __DIR__ . "/views/homeView.php";
        break;

    case '/blog':
        $post = new Post();
        $allPosts = $post->getAll();
        echo "<pre>";
        var_dump($allPosts);
        var_dump($post->getById(1));
        require __DIR__ . "/views/blogView.php";
        break;

    case '/admin':
        if (!$_SESSION["username"]) {
            header("Location: /");
            exit;
        }
        require __DIR__ . "/views/adminView.php";
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

        require __DIR__ . "/views/loginView.php";
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
