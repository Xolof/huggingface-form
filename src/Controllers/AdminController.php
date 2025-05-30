<?php

namespace App\Controllers;

use App\Helpers\FlashMessage;
use App\Models\Post;
use \InvalidArgumentException;
use App\Models\Db;
use App\Helpers\Session;
use \Exception;

class AdminController extends Controller
{
    private FlashMessage $flashMessage;

    public function __construct(FlashMessage $flashMessage)
    {
        $this->flashMessage = $flashMessage;
    }

    public function admin(): void
    {
        if (!$_SESSION["username"]) {
            header("Location: /");
            exit;
        }

        $post = new Post(new Db());

        $allPosts = $post->getAll();

        include __DIR__ . "/../views/adminView.php";
    }

    public function deletePost(): void
    {
        if (!$_SESSION["username"]) {
            header("Location: /");
            exit;
        }

        $idOfPostToDelete = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$idOfPostToDelete) {
            throw new InvalidArgumentException("Invalid input");
        }

        $postObj = new Post(new Db());

        if (!$postObj->getById($idOfPostToDelete)) {
            $this->flashMessage->set("No such post.", "error");
            Session::createCsrfToken();
            header("Location: /admin");
            exit;
        }

        $postObj->delete($idOfPostToDelete);

        $this->flashMessage->set("Post $idOfPostToDelete deleted", "success");
        header("Location: /admin");
        exit;
    }

    public function addPost(): void
    {
        if (!$_SESSION["username"]) {
            header("Location: /");
            exit;
        }

        $question = filter_input(INPUT_POST, 'question', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $publishUnixTimestamp = strtotime($date . " " . $time);

        $post = new Post(new Db());

        if (!$post->isValidUnixTimestamp($publishUnixTimestamp)) {
            throw new Exception("Invalid timestamp.");
        }

        $post->add($_SESSION["user_id"], $question, "", $publishUnixTimestamp);
        $this->flashMessage->set("Post scheduled", "success");
        Session::createCsrfToken();
        header("Location: /admin");
    }

    public function showLoginPage(): void
    {
        include __DIR__ . "/../views/loginView.php";
    }
}
