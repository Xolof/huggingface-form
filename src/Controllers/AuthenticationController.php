<?php

namespace App\Controllers;

use App\Models\User;

class AuthenticationController extends Controller
{
    public static function doLogin(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $user = new User();
        if (!isset($email) || $email === "") {
            throw new \InvalidArgumentException("Invalid input");
        }

        $userData = $user->getByEmail(trim($email));
        if (count($userData)) {
            if (password_verify(trim($password), $userData["password"])) {
                $user->login($userData["id"], $userData["name"]);
                return;
            }
            $_SESSION["message"]["message"] = "Could not login, check your password.";
            $_SESSION["message"]["status"] = "error";
            include __DIR__ . "/../views/loginView.php";
            return;
        }
        $_SESSION["message"]["message"] = "Could not find that user.";
        $_SESSION["message"]["status"] = "error";
        include __DIR__ . "/../views/loginView.php";
        return;
    }

    public static function doLogout(): void
    {
        $user = new User();
        $user->logout();
    }
}
