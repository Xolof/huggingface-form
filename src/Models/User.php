<?php

namespace App\Models;

use App\Models\Db;

class User
{
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
}
