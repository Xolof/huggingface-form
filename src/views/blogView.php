<?php

require __DIR__ . "/../templates/header.php";

$allPosts = $allPosts ?? [];

?>

<h2>Blog</h2>

<?php foreach ($allPosts as $post) : ?>
    <?php $timestamp = $post["publish_unix_timestamp"]; ?>
    <?php if (time() > $timestamp) : ?>
        <p><?= date("Y-m-d H:m", $timestamp) ?></p>
        <?= $post["post"] ?>
        <hr>
    <?php endif; ?>
<?php endforeach; ?>

<?php

require __DIR__ . "/../templates/footer.html";
