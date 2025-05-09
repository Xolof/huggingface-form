<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Db;

class BlogController extends Controller
{
    public function blog(): void
    {
        $post = new Post(new Db());
        $publishedPosts = $post->getPublished();
        include __DIR__ . "/../views/blogView.php";
    }
}
