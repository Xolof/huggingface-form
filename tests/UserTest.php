<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Db;
use App\Exceptions\DatabaseQueryException;

require_once __DIR__ . '/../config.php';

#[CoversClass(User::class)]
final class UserTest extends TestCase
{
    protected function setUp(): void
    {
        $db = new Db();
        $db->connect();
        $db->runQuery("DELETE FROM users WHERE id > 1");

        if (session_status() != 2) {
            session_start();
        }
    }

    protected function tearDown(): void
    {
        session_unset();
        session_destroy();
    }

    public function testCanCreate(): void
    {
        $user = new User();

        $email = 'user@user.se';
        $createResult = $user->create(
            'user', $email, 0,
            '$2y$12$Rqq/zGND.26gnXUF03a2DOPfSYk9/ueyHu1ObLM5LYDneVjML45ra'
        );
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

    public function testCanGetAll(): void
    {
        $user = new User();
        $allUsers = $user->getAll();
        $this->assertSame("admin", $allUsers[0]["name"]);
    }

    public function testCanGetByEmail(): void
    {
        $email = "oljo@protonmail.ch";

        $user = new User();
        $res = $user->getByEmail($email);

        $this->assertSame("admin", $res["name"]);
    }

    public function testLogin(): void
    {
        $user = new User();
        $user->login("5", "olof");
        $this->assertSame($_SESSION["user_id"], "5");
    }

    public function testLogout(): void
    {
        $user = new User();
        $allUsers = $user->logout();
        $this->assertTrue(!isset($_SESSION["user_id"]));
    }

}

