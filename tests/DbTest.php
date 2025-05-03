<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\Db;
use App\Exceptions\DatabaseQueryException;

final class DbTest extends TestCase
{
    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    public function testRunQuery(): void
    {
        $db = new Db();
        $db->connect();
        $allPosts = $db->runQuery("SELECT * FROM posts;");
        $this->assertTrue(count($allPosts) > 0);
    }

    public function testInvalidQuery(): void
    {
        $this->expectException(DatabaseQueryException::class);
        $db = new Db();
        $db->connect();
        $allPosts = $db->runQuery("SELECT * FROM invalid_table;");
    }
}
