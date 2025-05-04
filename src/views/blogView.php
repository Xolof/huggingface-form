<?php

require __DIR__ . "/../templates/header.php";

$publishedPosts = $publishedPosts ?? [];

?>

<h2>Blog</h2>

<?php if (count($publishedPosts) < 1) : ?>
    <p>There are not yet any posts.</p>
<?php endif; ?>

<?php foreach ($publishedPosts as $post) : ?>
    <?php
        $timestamp = $post["publish_unix_timestamp"];
        $text = $post["post"];
    ?>
    <?php if ($text !== "") : ?>
        <p><?= date("Y-m-d H:i", $timestamp) ?></p>
        <?= $post["post"] ?>
        <hr>
    <?php endif; ?>
<?php endforeach; ?>

<?php

require __DIR__ . "/../templates/footer.html";
