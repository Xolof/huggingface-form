<?php

namespace App\Models;

use App\Helpers\FlashMessage;
use App\Helpers\Session;
use App\Models\Db;

class User
{
    private Db $db;
    private FlashMessage $flashMessage;

    public function __construct(Db $db, FlashMessage $flashMessage)
    {
        $this->db = $db;
        $this->flashMessage = $flashMessage;
    }

    public function getAll(): array
    {
        $this->db->connect();
        return $this->db->runQuery("SELECT * FROM users");
    }

    public function getByEmail(string $email): array
    {
        $this->db->connect();
        $res = $this->db->runQueryWithParams("SELECT * FROM users WHERE email=:email", [":email"], [$email], true);
        if ($res === false) {
            return [];
        }
        return $res;
    }

    public function login(int $userId, string $userName): void
    {
        Session::destroy();
        Session::start();

        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $userName;

        $this->flashMessage->set("Login successful.", "success");

        header("Location: /admin");
    }

    public function logout(): void
    {
        Session::destroy();
        Session::start();

        $this->flashMessage->set("You have been logged out.", "success");

        header("Location: /");
    }

    public function create(string $username, string $email, int $isAdmin, string $password): void
    {
        $this->db->connect();
        $this->db->runQueryWithParams(
            "INSERT INTO users (name, email, isAdmin, password) VALUES (:name, :email, :isAdmin, :password)",
            [
                ":name",
                ":email",
                ":isAdmin", ":password"
            ],
            [
                $username,
                $email,
                $isAdmin, $password
            ],
            false
        );
    }
}
