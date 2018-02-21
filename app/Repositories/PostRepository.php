<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    /**
     * Get highlighted posts
     *
     * @return Collection
     */
    public function getHighlights()
    {
        return Post::where('highlight', 1)->latest('published_at')->take(3)->get();
    }
}
