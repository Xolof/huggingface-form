<?php

namespace App\Models;

class Post
{
    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $this->db->connect();
        return $this->db->runQuery("SELECT * FROM posts ORDER BY publish_unix_timestamp DESC");
    }

    public function getPublished(): array
    {
        $this->db->connect();
        return $this->db->runQuery("SELECT * FROM posts WHERE post != '' ORDER BY publish_unix_timestamp DESC");
    }

    public function getById(int $id): array|bool
    {
        $this->db->connect();
        return $this->db->runQueryWithParams("SELECT * FROM posts WHERE post_id=:post_id", [":post_id"], [$id], true);
    }

    public function add(int $userId, string $question, string $text, int $timeToPublish): void
    {
        $this->db->connect();
        $this->db->runQueryWithParams(
            "INSERT INTO posts (user_id, question, post, publish_unix_timestamp) VALUES (:user_id, :question, :post, :publish_unix_timestamp)",
            [
                ":user_id",
                ":question",
                ":post", ":publish_unix_timestamp"
            ],
            [
                $userId,
                $question,
                $text,
                $timeToPublish
            ],
            false
        );
    }

    public function update(int $id, string $question, string $text, int $timeToPublish): void
    {
        $this->db->connect();
        $this->db->runQueryWithParams(
            "UPDATE posts SET question=:question, post=:post, publish_unix_timestamp=:publish_unix_timestamp WHERE post_id=:post_id",
            [
                ":post_id",
                ":question",
                ":post",
                ":publish_unix_timestamp"
            ],
            [
                $id,
                $question,
                $text,
                $timeToPublish
            ],
            false
        );
    }

    public function delete(int $id): void
    {
        $this->db->connect();
        $this->db->runQueryWithParams(
            "DELETE FROM posts WHERE post_id=:post_id",
            [":post_id"],
            [$id],
            false
        );
    }

    public function isValidUnixTimestamp(int $timestamp): bool
    {
        return $timestamp >= 0 && $timestamp <= 9999999999 && date('U', $timestamp) == $timestamp;
    }
}
