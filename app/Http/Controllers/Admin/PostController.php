<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // ── Index ────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Post::orderByDesc('updated_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $posts = $query->paginate(15)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    // ── Create / Store ────────────────────────────────────────────

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePost($request);

        // Remove file object — not a DB column
        unset($validated['thumbnail'], $validated['cta_image']);

        $validated['slug']          = Post::generateUniqueSlug($request->input('title'));
        $validated['thumbnail_url'] = $this->handleThumbnail($request);

        // Handle CTA image upload
        if ($request->hasFile('cta_image')) {
            $validated['cta_image'] = $this->handleUpload($request->file('cta_image'), 'blog/cta');
        }

        // Sanitize HTML content
        $validated['content'] = $this->sanitizeHtml($request->input('content', ''));

        Post::create($validated);

        return redirect()->route('admin.posts.index')
                         ->with('success', 'Artikel berhasil ditambahkan.');
    }

    // ── Edit / Update ─────────────────────────────────────────────

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $this->validatePost($request, $post->id);

        // Remove file objects — not DB columns
        unset($validated['thumbnail'], $validated['cta_image']);

        // Slug: only regenerate if title changed and no manual slug provided
        if ($request->filled('slug') && $request->input('slug') !== $post->slug) {
            $validated['slug'] = Post::generateUniqueSlug($request->input('slug'), $post->id);
        } elseif ($request->input('title') !== $post->title && ! $request->filled('slug')) {
            $validated['slug'] = Post::generateUniqueSlug($request->input('title'), $post->id);
        } else {
            $validated['slug'] = $post->slug;
        }

        // Thumbnail
        if ($request->hasFile('thumbnail')) {
            $this->deleteThumbnail($post->thumbnail_url);
            $validated['thumbnail_url'] = $this->handleThumbnail($request);
        } elseif ($request->boolean('thumbnail_remove')) {
            $this->deleteThumbnail($post->thumbnail_url);
            $validated['thumbnail_url'] = null;
        } else {
            $validated['thumbnail_url'] = $post->thumbnail_url;
        }

        // CTA image
        if ($request->hasFile('cta_image')) {
            $this->deleteUpload($post->cta_image);
            $validated['cta_image'] = $this->handleUpload($request->file('cta_image'), 'blog/cta');
        } elseif ($request->boolean('cta_image_remove')) {
            $this->deleteUpload($post->cta_image);
            $validated['cta_image'] = null;
        } else {
            $validated['cta_image'] = $post->cta_image;
        }

        // Sanitize HTML content
        $validated['content'] = $this->sanitizeHtml($request->input('content', ''));

        $post->update($validated);

        return redirect()->route('admin.posts.edit', $post)
                         ->with('success', 'Artikel berhasil diperbarui.');
    }

    // ── Delete ────────────────────────────────────────────────────

    public function destroy(Post $post)
    {
        $this->deleteThumbnail($post->thumbnail_url);
        $post->delete();

        return redirect()->route('admin.posts.index')
                         ->with('success', 'Artikel berhasil dihapus.');
    }

    // ── Private Helpers ───────────────────────────────────────────

    private function validatePost(Request $request, ?int $excludeId = null): array
    {
        return $request->validate([
            'title'           => 'required|string|max:255',
            'slug'            => 'nullable|string|max:255',
            'category'        => 'nullable|string|max:100',
            'excerpt'         => 'nullable|string|max:500',
            'content'         => 'required|string',
            'thumbnail'       => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'author_name'     => 'required|string|max:100',
            'published_at'    => 'nullable|date',
            'status'          => 'required|in:draft,published',
            'meta_title'      => 'nullable|string|max:255',
            'meta_description'=> 'nullable|string|max:500',
            'cta_enabled'     => 'nullable|boolean',
            'cta_title'       => 'nullable|string|max:255',
            'cta_subtitle'    => 'nullable|string|max:500',
            'cta_button_text' => 'nullable|string|max:100',
            'cta_button_url'  => 'nullable|string|max:255',
            'cta_image'       => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }

    private function handleThumbnail(Request $request): ?string
    {
        if (! $request->hasFile('thumbnail')) {
            return null;
        }
        return $this->handleUpload($request->file('thumbnail'), 'blog');
    }

    private function handleUpload($file, string $folder): string
    {
        $dir = public_path("uploads/{$folder}");
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);
        return "uploads/{$folder}/{$filename}";
    }

    private function deleteThumbnail(?string $path): void
    {
        $this->deleteUpload($path);
    }

    private function deleteUpload(?string $path): void
    {
        if (! $path || ! str_starts_with($path, 'uploads/')) {
            return;
        }
        $full = public_path($path);
        if (file_exists($full)) {
            unlink($full);
        }
    }

    /**
     * Basic HTML sanitization — strips dangerous tags/attributes while
     * preserving rich-text formatting produced by the editor.
     *
     * For production use, swap this for a library like HTMLPurifier or
     * league/html-to-markdown if stricter sanitization is needed.
     */
    private function sanitizeHtml(string $html): string
    {
        // Allow safe formatting tags only
        $allowed = '<p><br><strong><b><em><i><u><s><ul><ol><li>'
                 . '<h2><h3><h4><blockquote><pre><code>'
                 . '<a><img><table><thead><tbody><tr><th><td><figure><figcaption>';

        $html = strip_tags($html, $allowed);

        // Strip event handlers and javascript: hrefs
        $html = preg_replace('/\son\w+\s*=\s*"[^"]*"/i', '', $html);
        $html = preg_replace('/\son\w+\s*=\s*\'[^\']*\'/i', '', $html);
        $html = preg_replace('/href\s*=\s*["\']?\s*javascript:[^"\'>\s]*/i', 'href="#"', $html);

        return $html;
    }
}
