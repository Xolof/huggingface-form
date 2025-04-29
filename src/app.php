<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . "/functions/functions.php";

$question = filter_input(INPUT_GET, 'question', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

require __DIR__ . "/templates/header.php";

$hfToken = file_get_contents(__DIR__ . "/../API_TOKEN.txt");

$url = "https://router.huggingface.co/nebius/v1/chat/completions";

$data = [
    "messages" => [
        [
            "role" => "user",
            "content" => $question
        ]
    ],
    "model" => "google/gemma-3-27b-it",
    "stream" => false
];

if (isset($question)) {
    try {
        $res = doCurl($question, $url, $hfToken, $data);
        $res = json_decode($res);
    } catch(Exception $e) {
        myLog($e);
        exit("Something went wrong when trying to fetch the data.");
    }

    myLog($res);

    if (isset($res->code) && $res->code == 404) {
        exit("Something went wrong when trying to fetch the data.");
    }

    $res = $res->choices[0]->message->content;

    echo makeMarkdown($res);
}

require __DIR__ . "/templates/footer.html";
