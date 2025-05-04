<?php

namespace App\Models;

use App\Models\Db;

class User
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
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
        session_unset();
        session_destroy();

        session_start();

        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $userName;

        $_SESSION["message"]["message"] = "Login successful.";
        $_SESSION["message"]["status"] = "success";

        header("Location: /admin");
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        session_start();

        $_SESSION["message"]["message"] = "You have been logged out.";
        $_SESSION["message"]["status"] = "success";

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
