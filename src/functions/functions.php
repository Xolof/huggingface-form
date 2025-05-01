<?php

function myLog(mixed $object): void
{
    file_put_contents(__DIR__ . "/../../huggingface.log", json_encode($object) . "\n", FILE_APPEND);
}

function makeMarkdown(string $text): string
{
    $parsedown = new Parsedown();
    // prevent raw HTML in the Markdown from being rendered (for security, e.g., to avoid XSS attacks)
    $parsedown->setSafeMode(true);
    $parsedown->setUrlsLinked(true);
    $html = $parsedown->text($text);
    return $html;
}
