<?php

namespace App\Http\Controllers;

use App\Services\BlogRepository;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(private BlogRepository $blog)
    {
    }

    /**
     * List all blog posts.
     */
    public function index(): View
    {
        return view('blog.index', [
            'posts' => $this->blog->all(),
        ]);
    }

    /**
     * Show a single blog post.
     */
    public function show(string $slug): View
    {
        $post = $this->blog->find($slug);

        if (!$post) {
            abort(404);
        }

        return view('blog.show', [
            'post' => $post,
            'slug' => $slug,
        ]);
    }
}
