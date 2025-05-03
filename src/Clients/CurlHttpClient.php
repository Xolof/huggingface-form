<?php

namespace App\Clients;

use App\Interfaces\HttpClientInterface;
use \Exception;

class CurlHttpClient implements HttpClientInterface
{
    public function post(string $url, array $headers, array $data): string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: $error");
        }
        curl_close($ch);
        return $response;
    }
}
