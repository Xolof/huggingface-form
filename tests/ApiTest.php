<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Exceptions\CurlErrorException;
use App\Helpers\Logger;
use App\Models\Api;
use App\Interfaces\HttpClientInterface;
use \Exception;

class ApiTest extends TestCase
{
    private HttpClientInterface $mockHttpClient;
    private Logger $logger;

    protected function setUp(): void
    {
        $this->mockHttpClient = $this->createMock(HttpClientInterface::class);
        $this->logger = new Logger();
    }

    public function testMakeCurlRequestReturnsApiResponse(): void
    {
        $question = "What is PHP?";
        $hfToken = "dummy-token";
        $url = "https://router.huggingface.co/nebius/v1/chat/completions";
        $mockResponse = json_encode(
            [
                'choices' => [
                    [
                        'message' => [
                            'content' => 'PHP is a server-side scripting language.'
                        ]
                    ]
                ]
            ]
        );

        $this->mockHttpClient->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo($url),
                $this->equalTo(
                    [
                        "Authorization: Bearer $hfToken",
                        "Content-Type: application/json"
                    ]
                ),
                $this->equalTo(
                    [
                        "messages" => [
                            [
                                "role" => "user",
                                "content" => $question
                            ]
                        ],
                        "model" => "google/gemma-3-27b-it",
                        "stream" => false
                    ]
                )
            )
            ->willReturn($mockResponse);

        $api = new Api($question, $this->logger, $this->mockHttpClient, $hfToken);

        $result = $api->makeCurlRequest();

        $this->assertEquals('PHP is a server-side scripting language.', $result);
    }

    public function testMakeCurlRequestThrowsExceptionOn404(): void
    {
        $question = "What is PHP?";
        $hfToken = "dummy-token";
        $url = "https://router.huggingface.co/nebius/v1/chat/completions";
        $mockResponse = json_encode(['code' => 404, 'error' => 'Not found']);

        $this->mockHttpClient->expects($this->once())
            ->method('post')
            ->willReturn($mockResponse);

        $api = new Api($question, $this->logger, $this->mockHttpClient, $hfToken);

        $this->expectException(CurlErrorException::class);
        $this->expectExceptionMessage("Something went wrong when trying to make a call to the API: " . $mockResponse);

        $api->makeCurlRequest();
    }

    public function testMakeCurlRequestHandlesHttpClientException(): void
    {
        $question = "What is PHP?";
        $hfToken = "dummy-token";
        $url = "https://router.huggingface.co/nebius/v1/chat/completions";
        $exceptionMessage = "Network error";

        $this->mockHttpClient->expects($this->once())
            ->method('post')
            ->willThrowException(new Exception($exceptionMessage));

        $api = new Api($question, $this->logger, $this->mockHttpClient, $hfToken);

        $this->expectException(CurlErrorException::class);
        $this->expectExceptionMessage("Something went wrong when trying to make a call to the API: $exceptionMessage");

        $api->makeCurlRequest();
    }
}
