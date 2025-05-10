<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Markdowner;
use App\Helpers\Logger;
use App\Models\Api;
use App\Models\Post;
use App\Clients\CurlHttpClient;
use \Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$token = $_ENV["HF_API_TOKEN"];
if (!$token) {
    throw new Exception("Could not get the API token.");
};

$logger = new Logger();
$curlHttpClient = new CurlHttpClient();
$markdowner = new Markdowner();

$postObject = new Post();
$publishedPosts = $postObject->getAll();

date_default_timezone_set("Europe/Stockholm");
$date = new DateTime();

foreach($publishedPosts as $post) {
    $question = $post["question"];
    $id = $post["post_id"];
    $timestamp = $post["publish_unix_timestamp"];
    
    if (time() > $timestamp && $post["post"] === "") {
        echo "Publishing the post with question '$question' and id $id.\n\n";

        $api = new Api($question, $logger, $curlHttpClient, $token);
        $content = $api->makeCurlRequest();
        $html = "<h3>Topic: $question</h3>" . $markdowner->print($content);
        $postObject->update(
            $post["post_id"],
            $post["question"],
            $html,
            $post["publish_unix_timestamp"]
        );
        continue;
    }

    if ($post["post"] !== "") {
        echo "The post with question '$question' and id $id is already published.\n\n";
        continue;
    }

    echo "Waiting to publish the post with question '$question' and id $id.\n\n";
}
