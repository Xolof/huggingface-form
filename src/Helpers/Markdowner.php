<?php

namespace App\Helpers;

use Parsedown;

class Markdowner
{
    private Parsedown $parsedown;

    public function __construct()
    {
        $this->parsedown = new Parsedown();
    }

    public function print(string $text): string
    {
        // Prevent raw HTML in the Markdown from being rendered (for security, e.g., to avoid XSS attacks).
        $this->parsedown->setSafeMode(true);
        $this->parsedown->setUrlsLinked(true);
        $html = $this->parsedown->text($text);
        return $html;
    }
}
