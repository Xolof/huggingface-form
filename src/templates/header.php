<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>Huggingface AI form</title>
</head>
<body>
    <h1>Huggingface AI form</h1>
    <?php
    $loggedInUser = $_SESSION["username"] ?? null;
    $uri = $uri ?? null;
    ?>
    <nav class="main_nav">
        <ul>
            <li>
                <a href="/"
                class="<?php echo $uri === '/' ? 'activeRoute' : null ?>"
                >Home</a>
            </li>
            <li>
                <a href="/blog"
                class="<?php echo $uri === '/blog' ? 'activeRoute' : null ?>"
                >Blog</a></li>
            <?php if ($loggedInUser) : ?>
                <li>
                    <a href="/admin"
                    class="<?php echo $uri === '/admin' ? 'activeRoute' : null ?>"
                    >Admin</a></li>
            <?php endif; ?>
            <?php if (!$loggedInUser) : ?>
                <li><a href="/login"
                class="<?php echo $uri === '/login' ? 'activeRoute' : null ?>"
                >Login</a></li>
            <?php endif; ?>
            <?php if ($loggedInUser) : ?>
                <li><a href="/logout"
                class="<?php echo $uri === '/logout' ? 'activeRoute' : null ?>"
                >Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>
