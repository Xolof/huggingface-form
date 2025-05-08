<?php

namespace App\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public static function blog(): void
    {
        $post = new Post();
        $publishedPosts = $post->getPublished();
        include __DIR__ . "/../views/blogView.php";
    }
}
