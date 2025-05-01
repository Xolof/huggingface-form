<?php

require_once __DIR__ . '/../config.php';
require __DIR__ . '/../vendor/autoload.php';
use App\Models\Api;
use App\Models\User;
use App\Helpers\Markdowner;

session_start();

$uri = $_SERVER['REQUEST_URI'];

if ($positionQuestionMark = strpos($uri, "?")) {
    $uri = substr($uri, 0, $positionQuestionMark);
}

$parsedown = new Parsedown();
$markdowner = new Markdowner($parsedown);

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
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        $user = new User();
        // TODO: Move logic to the User class.
        if (isset($email) && $email != "") {
            $userData = $user->getByEmail($email);
            if (count($userData)) {
                if (password_verify($password, $userData["password"])) {
                    $_SESSION['user_id'] = $userData["id"];
                    $_SESSION['username'] = $userData["name"];
                    header("Location: /admin");
                    exit;
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
        session_start();
        session_unset();
        session_destroy();
        header("Location: /");
        exit;
        break;

    default:
        http_response_code(404);
        echo '404 Not Found';
}

exit;
