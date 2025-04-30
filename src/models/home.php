<?php

$question = filter_input(INPUT_GET, 'question', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

require __DIR__ . "/Api.php";

$res = "";

if (isset($question)) {
    $api = new Api($question);
    $res = $api->makeCurlRequest();
    $markdown = makeMarkdown($res);
}

require __DIR__ . "/../views/homeView.php";
