<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\Post;
use App\Models\Db;

#[CoversClass(Post::class)]
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
}
