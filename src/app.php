<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Markdowner;
use App\Helpers\Logger;
use App\Models\Api;
use App\Models\Post;
use App\Models\User;
use App\Clients\CurlHttpClient;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

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

        $token = getenv("HF_API_TOKEN");
        if (!$token) {
            throw new Exception("Could not get the API token.");
        };

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

case '/add-post':
    if (!$_SESSION["username"]) {
        header("Location: /");
        exit;
    }

    $question = filter_input(INPUT_POST, 'question', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $publishUnixTimestamp = filter_input(INPUT_POST, 'publish_unix_timestamp', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $publishUnixTimestamp = (int) $publishUnixTimestamp;

    $post = new Post();

    if (!$post->isValidUnixTimestamp($publishUnixTimestamp)) {
        throw new \Exception("Invalid timestamp.");
    }

    $post->add($_SESSION["user_id"], $question, "", $publishUnixTimestamp);
    $_SESSION["message"]["message"] = "Post scheduled";
    $_SESSION["message"]["status"] = "success";
    header("Location: /admin");

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
                break;
            }
            $_SESSION["message"]["message"] = "Could not login, check your password.";
            $_SESSION["message"]["status"] = "error";
            break;
        }
        $_SESSION["message"]["message"] = "Could not find that user.";
        $_SESSION["message"]["status"] = "error";
        break;
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
