<?php

namespace App\Helpers;

class FlashMessage
{
    public static function set(string $message, string $status): void
    {
        $_SESSION["message"]["message"] = $message;
        $_SESSION["message"]["status"] = $status;
    }
}
