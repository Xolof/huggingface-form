<?php

namespace App\Models;

class Post
{
    private Db $db;

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
        return $this->db->runQueryWithParams("SELECT * FROM posts WHERE post_id=:post_id", [":post_id"], [$id], true);
    }

    public function add(int $userId, string $text, int $timeToPublish): void
    {
        $this->db->connect();
        $this->db->runQueryWithParams(
            "INSERT INTO posts (user_id, post, publish_unix_timestamp) VALUES (:user_id, :post, :publish_unix_timestamp)",
            [
                ":user_id",
                ":post", ":publish_unix_timestamp"
            ],
            [
                $userId,
                $text,
                $timeToPublish
            ],
            false
        );
    }

    public function update(int $id, string $text, int $timeToPublish): void
    {
        $this->db->connect();
        $this->db->runQueryWithParams(
            "UPDATE posts SET post=:post, publish_unix_timestamp=:publish_unix_timestamp WHERE post_id=:post_id",
            [
                ":post_id",
                ":post",
                ":publish_unix_timestamp"
            ],
            [
                $id,
                $text,
                $timeToPublish
            ],
            false
        );
    }

    public function delete(int $id): bool
    {
        $this->db->connect();
        return false;
    }
}
