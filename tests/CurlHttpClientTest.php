<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Clients\CurlHttpClient;

final class CurlHttpClientTest extends TestCase
{
    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    public function testThrowsException(): void
    {
        $this->expectException(\Exception::class);

        $client = new CurlHttpClient();

        $url = "invalidurl";
        
        $headers = [
            "Authorization: Bearer $this->hfToken",
            "Content-Type: application/json"
        ];

        $data = [
            "messages" => [
                [
                    "role" => "user",
                    "content" => "hello, world"
                ]
            ],
            "model" => "google/gemma-3-27b-it",
            "stream" => false
        ];

        $res = $client->post($url, $headers, $data);

        $this->assertTrue(str_contains($res, "404"));
    }
}
