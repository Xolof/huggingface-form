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
            "INSERT INTO posts (user_id, question, post, publish_unix_timestamp) VALUES
    (5, 'What is PhpUnit?', '', 1446015394);"
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
        $question = "What is Sqlite?.";
        $post->add(1, $question, "", 2136015394);
        $allPosts = $post->getAll();

        $this->assertSame($question, $allPosts[array_key_first($allPosts)]["question"]);
    }

    public function testCanGetById(): void
    {
        $post = new Post();
        $id = 1;
        $res = $post->getById(1);

        $this->assertSame("What is PHP?", $res["question"]);
    }

    public function testCanUpdate(): void
    {
        $post = new Post();
        $newQuestion = "What is a badger?";
        $newPublishTime = 2036015394;

        $allPosts = $post->getAll();
        $latestPost = $allPosts[array_key_first($allPosts)];

        $post->update($latestPost["post_id"], $newQuestion, "", $newPublishTime);

        $allPosts = $post->getAll();
        $latestPost = $allPosts[array_key_first($allPosts)];

        $this->assertSame($newQuestion, $latestPost["question"]);
        $this->assertSame($newPublishTime, $latestPost["publish_unix_timestamp"]);
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

    public function testIsValidUnixTimestamp(): void
    {
        $post = new Post();

        $this->assertTrue($post->isValidUnixTimestamp(1546015394));
        $this->assertFalse($post->isValidUnixTimestamp(-1));
        $this->assertFalse($post->isValidUnixTimestamp(9999999999999));
    }
}
