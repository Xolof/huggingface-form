<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\Post;
use App\Models\Db;

final class PostTest extends TestCase
{
    protected function setUp(): void
    {
        $db = new Db();
        $db->connect();
        $db->runQuery(
            "INSERT INTO posts (user_id, post, publish_unix_timestamp) VALUES
    (5, 'This is a test post.', 1446015394);"
        );
    }

    protected function tearDown(): void
    {
        $db = new Db();
        $db->connect();
        $db->runQuery("DELETE FROM posts WHERE post_id > 2");
    }

    public function testCanAdd(): void
    {
        $post = new Post();
        $text = "This is some text.";
        $post->add(1, $text, 1736015394);
        $allPosts = $post->getAll();

        $this->assertSame($text, $allPosts[array_key_last($allPosts)]["post"]);
    }

    public function testCanGetById(): void
    {
        $post = new Post();
        $id = 1;
        $res = $post->getById(1);

        $this->assertSame("Hello, World! This is some text.", $res["post"]);
    }

    public function testCanUpdate(): void
    {
        $post = new Post();
        $newContent = "This is the new content of the post.";
        $newPublishTime = 1555555555;

        $allPosts = $post->getAll();
        $lastPost = $allPosts[array_key_last($allPosts)];

        $post->update($lastPost["post_id"], $newContent, $newPublishTime);

        $allPosts = $post->getAll();
        $lastPost = $allPosts[array_key_last($allPosts)];

        $this->assertSame($newContent, $lastPost["post"]);
        $this->assertSame($newPublishTime, $lastPost["publish_unix_timestamp"]);
    }

    public function testCanDelete(): void
    {
        $post = new Post();

        $allPosts = $post->getAll();
        $countPostsBefore = count($allPosts);
        $lastPost = $allPosts[array_key_last($allPosts)];

        $post->delete($lastPost["post_id"]);

        $allPosts = $post->getAll();
        $countPostsAfter = count($allPosts);

        $this->assertSame($countPostsAfter, $countPostsBefore - 1);
    }
}
