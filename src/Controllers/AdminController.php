<?php

namespace App\Controllers;

use App\Models\Post;
use \Exception;
use \InvalidArgumentException;

class AdminController extends Controller
{
    public static function admin(): void
    {
        if (!$_SESSION["username"]) {
            header("Location: /");
            exit;
        }

        $post = new Post();

        $allPosts = $post->getAll();

        include __DIR__ . "/../views/adminView.php";
    }

    public static function deletePost(): void
    {
        if (!$_SESSION["username"]) {
            header("Location: /");
            exit;
        }

        $idOfPostToDelete = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$idOfPostToDelete) {
            throw new InvalidArgumentException("Invalid input");
        }

        $postObj = new Post();

        if (!$postObj->getById($idOfPostToDelete)) {
            $_SESSION["message"]["message"] = "No such post.";
            $_SESSION["message"]["status"] = "error";
            header("Location: /admin");
            exit;
        }

        $postObj->delete($idOfPostToDelete);
        $_SESSION["message"]["message"] = "Post $idOfPostToDelete deleted";
        $_SESSION["message"]["status"] = "success";
        header("Location: /admin");
        exit;
    }

    public static function addPost(): void
    {
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
            throw new Exception("Invalid timestamp.");
        }

        $post->add($_SESSION["user_id"], $question, "", $publishUnixTimestamp);
        $_SESSION["message"]["message"] = "Post scheduled";
        $_SESSION["message"]["status"] = "success";
        header("Location: /admin");
    }

    public static function showLoginPage(): void
    {
        include __DIR__ . "/../views/loginView.php";
    }
}
