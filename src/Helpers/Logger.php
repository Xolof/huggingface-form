<?php

namespace App\Helpers;

class Logger
{
    public function log(mixed $object): void
    {
        file_put_contents(__DIR__ . "/../../huggingface.log", json_encode($object) . "\n", FILE_APPEND);
    }
}
