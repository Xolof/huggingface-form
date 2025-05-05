<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Markdowner;
use App\Helpers\Logger;
use App\Models\Api;
use App\Models\Post;
use App\Models\User;
use App\Clients\CurlHttpClient;
use App\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

date_default_timezone_set("Europe/Stockholm");

if (session_status() != 2) {
    session_start();
}

$uri = $_SERVER['REQUEST_URI'];

$router = new Router();

$router->get(
    '/', function () {
        $question = filter_input(INPUT_GET, 'question', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (isset($question)) {

            $token = getenv("HF_API_TOKEN");
            if (!$token) {
                throw new Exception("Could not get the API token.");
            };

            $logger = new Logger();
            $curlHttpClient = new CurlHttpClient();

            $api = new Api($question, $logger, $curlHttpClient, $token);
            $res = $api->makeCurlRequest();
            $markdowner = new Markdowner();
            $markdown = $markdowner->print($res);
        }
        include __DIR__ . "/views/homeView.php";
    }
);

$router->get(
    '/blog', function () {
        $post = new Post();
        $publishedPosts = $post->getPublished();
        include __DIR__ . "/views/blogView.php";
    }
);


$router->get(
    '/admin', function () {
        if (!$_SESSION["username"]) {
            header("Location: /");
            exit;
        }

        $post = new Post();
        $allPosts = $post->getAll();

        include __DIR__ . "/views/adminView.php";
    }
);

$router->post(
    '/add-post', function () {
        if (!$_SESSION["username"]) {
            header("Location: /");
            exit;
        }

        $question = filter_input(INPUT_POST, 'question', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $publishUnixTimestamp = strtotime($date . " " . $time);

        $post = new Post();

        if (!$post->isValidUnixTimestamp($publishUnixTimestamp)) {
            throw new \Exception("Invalid timestamp.");
        }

        $post->add($_SESSION["user_id"], $question, "", $publishUnixTimestamp);
        $_SESSION["message"]["message"] = "Post scheduled";
        $_SESSION["message"]["status"] = "success";
        header("Location: /admin");
    }
);

$router->get(
    '/login', function () {
        include __DIR__ . "/views/loginView.php";
    }
);

$router->post(
    '/login', function () {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $user = new User();
        if (isset($email) && $email != "") {
            $userData = $user->getByEmail(trim($email));
            if (count($userData)) {
                if (password_verify(trim($password), $userData["password"])) {
                    $user->login($userData["id"], $userData["name"]);
                    return;
                }
                $_SESSION["message"]["message"] = "Could not login, check your password.";
                $_SESSION["message"]["status"] = "error";
                include __DIR__ . "/views/loginView.php";
                return;
            }
            $_SESSION["message"]["message"] = "Could not find that user.";
            $_SESSION["message"]["status"] = "error";
            include __DIR__ . "/views/loginView.php";
            return;
        }
    }
);

$router->get(
    '/logout', function () {
        $user = new User();
        $user->logout();
    }
);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

echo $router->dispatch($method, $uri);

exit;
