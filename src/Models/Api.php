<?php

namespace App\Models;

class Api
{
    private $url = "https://router.huggingface.co/nebius/v1/chat/completions";

    private $fetchWentWrongMessage = "Something went wrong when trying to fetch the data.";

    public function __construct(string $question)
    {
        $this->question = $question;
    }

    private function getToken(): string
    {
        $token = file_get_contents(__DIR__ . "/../../API_TOKEN.txt");
        if(!$token) {
            myLog("Could not find the API token file.");
            http_response_code(500);
            exit($this->fetchWentWrongMessage);
        };
        return $token;
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $hfToken",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);

        if ($response === false) {
            throw new Exception(curl_error($ch));
        } else {
            curl_close($ch);
            return $response;
        }
    }

    public function makeCurlRequest(): string
    {
        $hfToken = $this->getToken();
        $data = $this->getPayload();

        try {
            $res = $this->doCurl($this->question, $this->url, $hfToken, $data);
            $res = json_decode($res);
        } catch(Exception $e) {
            myLog($e);
            exit($this->fetchWentWrongMessage);
        }
    
        myLog($res);
    
        if (isset($res->code) && $res->code == 404) {
            exit($this->fetchWentWrongMessage);
        }
    
        return $res->choices[0]->message->content;
    }   
}


