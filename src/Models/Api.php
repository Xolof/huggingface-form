<?php

namespace App\Models;

use App\Helpers\Logger;
use App\Exceptions\CurlErrorException;

class Api
{
    private string $url = "https://router.huggingface.co/nebius/v1/chat/completions";

    private string $question;

    private Logger $logger;

    public function __construct(string $question)
    {
        $this->question = $question;
        $this->logger = new Logger();
    }

    private function getToken(): string
    {
        if (!defined("HF_API_TOKEN")) {
            $this->logger->log("Could not get the API token.");
            throw new \Exception("Could not get the API token.");
        };
        return HF_API_TOKEN;
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

    private function doCurl(string $question, string $url, string $hfToken, array $data): string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                "Authorization: Bearer $hfToken",
                "Content-Type: application/json"
            ]
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function makeCurlRequest(): string
    {
        $hfToken = $this->getToken();
        $data = $this->getPayload();

        try {
            $res = $this->doCurl($this->question, $this->url, $hfToken, $data);
            $res = json_decode($res);
        } catch (\Exception $e) {
            $this->logger->log($e);
            throw new CurlErrorException("Something went wrong when trying to make a call to the API: " . $e);
        }

        $this->logger->log($res);

        if (isset($res->code) && $res->code == 404) {
            throw new CurlErrorException("Something went wrong when trying to make a call to the API: " . $res);
        }

        return $res->choices[0]->message->content;
    }
}
