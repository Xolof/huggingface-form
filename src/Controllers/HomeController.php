<?php

namespace App\Controllers;

use App\Helpers\Logger;
use App\Models\Api;
use App\Clients\CurlHttpClient;
use App\Helpers\Markdowner;
use \Exception;

class HomeController extends Controller
{
    public static function home(): void
    {
        $question = filter_input(INPUT_GET, 'question', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (isset($question)) {

            $token = $_ENV["HF_API_TOKEN"];
            if (!$token) {
                throw new Exception("Could not get the API token.");
            };

            $logger = new Logger();
            $curlHttpClient = new CurlHttpClient();

            $api = new Api($question, $logger, $curlHttpClient, $token);
            $res = $api->makeCurlRequest();
            $markdowner = new Markdowner();
            $markdown = $markdowner->print($res);
        }
        include __DIR__ . "/../views/homeView.php";
    }
}
