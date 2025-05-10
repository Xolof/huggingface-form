<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Routers\Router;
use App\Helpers\Session;

final class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    protected function tearDown(): void
    {
        unset($this->router);
    }

    public function testCanGet(): void
    {
        $this->router->get(
            "/", function () {
                return "Hello, World!";
            }
        );

        $method = "GET";
        $uri = parse_url("/", PHP_URL_PATH);

        $res = $this->router->dispatch($method, $uri);
        $this->assertSame($res, "Hello, World!");
    }

    public function testCanPost(): void
    {
        $this->router->post(
            "/my-endpoint", function () {
                return "POST request received";
            }
        );

        $method = "POST";
        $uri = parse_url("/my-endpoint", PHP_URL_PATH);

        Session::start();

        $_POST["csrf_token"] = $_SESSION["csrf_token"];

        $res = $this->router->dispatch($method, $uri);
        $this->assertSame($res, "POST request received");
    }

    public function test404NotFound(): void
    {
        $message = "My custom 404 message";

        $this->router->setNotFound(function () use ($message) {
            return $message;
        });

        $method = "GET";
        $uri = parse_url("/non-existing-url", PHP_URL_PATH);

        $res = $this->router->dispatch($method, $uri);
        $this->assertSame($res, $message);
    }
}
