<?php

namespace App\Models;

class Post
{
    public function __construct()
    {
        $this->db = new Db();
    }

    public function getAll(): array
    {
        $this->db->connect();
        return $this->db->runQuery("SELECT * FROM posts");
    }

    public function getById(int $id): array
    {
        $this->db->connect();
        return $this->db->runQueryWithParams("SELECT * FROM posts WHERE post_id=:post_id", [":post_id"], [$id]);
    }

    public function add(int $userId, string $text, int $timeToPublish): bool
    {
        $this->db->connect();
    }

    public function update(int $id): bool
    {
        $this->db->connect();
    }

    public function delete(int $id): bool
    {
        $this->db->connect();
    }
}
