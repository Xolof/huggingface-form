<?php

namespace App\Interfaces;

interface HttpClientInterface
{
    public function post(string $url, array $headers, array $data): string;
}
