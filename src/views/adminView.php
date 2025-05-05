<?php

require __DIR__ . "/../templates/header.php";

?>

<h2>Admin</h2>

<?php $username = $_SESSION["username"]; ?>

<?php if ($username) : ?>
    <p>You are logged in as <?php echo $username ?>.</p>
<?php endif; ?>

<?php

require __DIR__ . "/../templates/schedulePostForm.php";

$allPosts = $allPosts ?? null;
?>

<h3>Posts</h3>

<table>
<tr>
    <th>Topic</th>
    <th>Published</th>
    <th>Time to publish</th>
    <th></th>
</tr>
<?php foreach ($allPosts as $post) : ?>
    <tr>
        <td><?= $post["question"]; ?></td>
        <td><?= $post["post"] === "" ? "No" : "Yes"; ?></td>
        <td><?= date("Y-m-d H:i", $post["publish_unix_timestamp"]); ?></td>
        <td><a href="/delete-post?id=<?= $post["post_id"]; ?>">Delete</a></td>
    </tr>
<?php endforeach; ?>
</table>

<?php if (count($allPosts) < 1) : ?>
    <p>There are not yet any posts.</p>
<?php endif; ?>

<?php

require __DIR__ . "/../templates/footer.html";
