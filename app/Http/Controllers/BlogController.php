<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->orderByDesc('published_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        $posts      = $query->paginate(9)->withQueryString();
        $categories = Post::published()->distinct()->orderBy('category')->pluck('category');

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(string $slug)
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        // Fill up with other posts if not enough in same category
        if ($related->count() < 3) {
            $existing = $related->pluck('id')->push($post->id);
            $more = Post::published()
                ->whereNotIn('id', $existing)
                ->orderByDesc('published_at')
                ->limit(3 - $related->count())
                ->get();
            $related = $related->concat($more);
        }

        return view('blog.show', compact('post', 'related'));
    }
}
