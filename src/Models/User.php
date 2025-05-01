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
        return $this->db->runQueryWithParams("SELECT * FROM users WHERE email=:email", [":email"], [$email]);
    }

    public function login(string $userId, string $userName): void
    {
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $userName;
        header("Location: /admin");
        exit;
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header("Location: /");
        exit;
    }
}
