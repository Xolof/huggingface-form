<?php

namespace App\Models;

use App\Helpers\Logger;
use App\Exceptions\CurlErrorException;
use App\Interfaces\HttpClientInterface;
use Exception;

class Api
{
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

        $llmModel = $_ENV["LLM_MODEL"];
        if (!$llmModel) {
            throw new Exception("Could not get the LLM Model.");
        };

        return [
            "messages" => [
                [
                    "role" => "user",
                    "content" => $this->question
                ]
            ],
            "model" => $llmModel,
            "stream" => false
        ];
    }

    public function makeCurlRequest(): string
    {
        $llmUrl = $_ENV["LLM_URL"];
        if (!$llmUrl) {
            throw new Exception("Could not get the LLM URL.");
        };

        $data = $this->getPayload();
        $headers = [
            "Authorization: Bearer $this->hfToken",
            "Content-Type: application/json"
        ];

        try {
            $response = $this->httpClient->post($llmUrl, $headers, $data);
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
