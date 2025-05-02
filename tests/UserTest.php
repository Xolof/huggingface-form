<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Db;
use App\Exceptions\DatabaseQueryException;

require_once __DIR__ . '/../config.php';

final class UserTest extends TestCase
{
    protected function setUp(): void
    {
        $db = new Db();
        $db->connect();
        $db->runQuery("DELETE FROM users WHERE id > 1");
    }

    public function testCanCreate(): void
    {
        $user = new User();

        $email = 'user@user.se';
        $createResult = $user->create(
            'user', $email, 0,
            '$2y$12$Rqq/zGND.26gnXUF03a2DOPfSYk9/ueyHu1ObLM5LYDneVjML45ra'
        );
        var_dump($createResult);
        $getResult = $user->getByEmail($email);
        $this->assertSame("user", $getResult["name"]);
    }

    public function testCanNotCreateIfExists(): void
    {
        $this->expectException(DatabaseQueryException::class);

        $user = new User();
        $email = 'user@user.se';
        
        $createResult = $user->create(
            'user', $email, 0,
            '$2y$12$Rqq/zGND.26gnXUF03a2DOPfSYk9/ueyHu1ObLM5LYDneVjML45ra'
        );
  
        $createResult = $user->create(
            'user', $email, 0,
            '$2y$12$Rqq/zGND.26gnXUF03a2DOPfSYk9/ueyHu1ObLM5LYDneVjML45ra'
        );
    }

    public function testCanGetByEmail(): void
    {
        $email = "oljo@protonmail.ch";

        $user = new User();
        $res = $user->getByEmail($email);

        $this->assertSame("admin", $res["name"]);
    }
}

