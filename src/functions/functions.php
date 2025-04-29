<?php

function myLog($text): void
{
    file_put_contents(__DIR__ . "/../../huggingface.log", json_encode($text) . "\n", FILE_APPEND);
}

function doCurl($question, $url, $hfToken, $data): string
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $hfToken",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);

    if ($response === false) {
        throw new Exception(curl_error($ch));
    } else {
        curl_close($ch);
        return $response;
    }
};

function makeMarkdown($text): string
{
    $parsedown = new Parsedown();
    // prevent raw HTML in the Markdown from being rendered (for security, e.g., to avoid XSS attacks)
    $parsedown->setSafeMode(true);
    $parsedown->setUrlsLinked(true);
    $html = $parsedown->text($text);
    return $html;
}
