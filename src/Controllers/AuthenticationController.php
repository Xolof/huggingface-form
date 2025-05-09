<?php

namespace App\Controllers;

use App\Models\User;
use \InvalidArgumentException;
use \App\Helpers\FlashMessage;
use App\Models\Db;

class AuthenticationController extends Controller
{
    private $flashMessage;

    public function __construct($flashMessage)
    {
        $this->flashMessage = $flashMessage;
    }

    public function doLogin(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $user = new User(new Db(), new FlashMessage());
        if (!isset($email) || $email === "") {
            throw new InvalidArgumentException("Invalid input");
        }

        $userData = $user->getByEmail(trim($email));
        if (count($userData)) {
            if (password_verify(trim($password), $userData["password"])) {
                $user->login($userData["id"], $userData["name"]);
                return;
            }
            $this->flashMessage->set("Invalid password", "error");
            include __DIR__ . "/../views/loginView.php";
            return;
        }
        $this->flashMessage->set("Could not find that user.", "error");
        include __DIR__ . "/../views/loginView.php";
        return;
    }

    public static function doLogout(): void
    {
        $user = new User(new Db(), new FlashMessage());
        $user->logout();
    }
}
