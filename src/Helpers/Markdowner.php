<?php

namespace App\Helpers;

class Markdowner
{
    public function __construct(\Parsedown $parsedown)
    {
        $this->parsedown = new \Parsedown();
    }

    function print(string $text): string
    {
        // prevent raw HTML in the Markdown from being rendered (for security, e.g., to avoid XSS attacks)
        $this->parsedown->setSafeMode(true);
        $this->parsedown->setUrlsLinked(true);
        $html = $this->parsedown->text($text);
        return $html;
    }
}
