<?php

function myLog($text): void
{
    file_put_contents(__DIR__ . "/../../huggingface.log", json_encode($text) . "\n", FILE_APPEND);
}

function makeMarkdown($text): string
{
    $parsedown = new Parsedown();
    // prevent raw HTML in the Markdown from being rendered (for security, e.g., to avoid XSS attacks)
    $parsedown->setSafeMode(true);
    $parsedown->setUrlsLinked(true);
    $html = $parsedown->text($text);
    return $html;
}
