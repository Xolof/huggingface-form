<?php

namespace App\Helpers;

class Logger
{
    public function log(mixed $object): void
    {
        if (gettype($object) !== "string" && get_class($object) === "stdClass") {
            $object = json_encode($object);
        }
        file_put_contents(__DIR__ . "/../../huggingface.log", $object . "\n", FILE_APPEND);
    }
}
