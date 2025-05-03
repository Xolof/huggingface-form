<?php

require __DIR__ . "/../templates/header.php";

?>

<h2>Admin</h2>

<?php $username = $_SESSION["username"]; ?>

<?php if ($username) : ?>
    <p>You are logged in as <?php echo $username ?>.</p>
<?php endif; ?>

<?php
require __DIR__ . "/../templates/footer.html";
