<?php

namespace App\Models;

use App\Helpers\Logger;
use App\Exceptions\CurlErrorException;
use App\Clients\CurlHttpClient;
use App\Interfaces\HttpClientInterface;
use Exception;

class Api
{
    private string $url = "https://router.huggingface.co/nebius/v1/chat/completions";
    private string $question;
    private Logger $logger;
    private HttpClientInterface $httpClient;
    private string $hfToken;

    public function __construct(
        string $question,
        Logger $logger,
        HttpClientInterface $httpClient,
        string $hfToken
    ) {
        $this->question = $question;
        $this->logger = $logger;
        $this->httpClient = $httpClient;
        $this->hfToken = $hfToken;
    }

    private function getPayload(): array
    {
        return [
            "messages" => [
                [
                    "role" => "user",
                    "content" => $this->question
                ]
            ],
            "model" => "google/gemma-3-27b-it",
            "stream" => false
        ];
    }

    public function makeCurlRequest(): string
    {
        $data = $this->getPayload();
        $headers = [
            "Authorization: Bearer $this->hfToken",
            "Content-Type: application/json"
        ];

        try {
            $response = $this->httpClient->post($this->url, $headers, $data);
            $decodedResponse = json_decode($response, false, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            $this->logger->log("Something went wrong when trying to make a call to the API: " . $e->getMessage());
            throw new CurlErrorException("Something went wrong when trying to make a call to the API: " . $e->getMessage());
        }

        $this->logger->log($response);

        if (isset($decodedResponse->code) && $decodedResponse->code == 404) {
            throw new CurlErrorException("Something went wrong when trying to make a call to the API: " . json_encode($decodedResponse));
        }

        return $decodedResponse->choices[0]->message->content;
    }
}
