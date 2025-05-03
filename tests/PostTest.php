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
}
