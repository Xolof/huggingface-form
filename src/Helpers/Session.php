<?php

namespace App\Helpers;

class Session
{
    public static function start(): void
    {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => $_ENV['APP_DOMAIN'] ?? 'localhost',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
            if (empty($_SESSION['csrf_token'])) {
                self::createCsrfToken();
            }
        }
    }

    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    public static function createCsrfToken(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
