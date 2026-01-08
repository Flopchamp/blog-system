<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // Show all posts
    public function index()
    {
        $posts = Post::with(['user', 'category', 'tags'])
            ->latest()
            ->paginate(10);
        
        return view('posts.index', compact('posts'));
    }

    // Show create form
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }

    // Store new post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'nullable|max:500',
            'content' => 'required',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'tags' => 'nullable|array'
        ]);

        // Create slug from title
        $slug = Str::slug($request->title);
        
        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        }

        // Create post
        $post = Post::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $imagePath,
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? now() : null
        ]);

        // Attach tags
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully!');
    }

    // Show single post
    public function show(Post $post)
    {
        $post->load(['user', 'category', 'tags']);
        return view('posts.show', compact('post'));
    }

    // Show edit form
    public function edit(Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    // Update post
    public function update(Request $request, Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'nullable|max:500',
            'content' => 'required',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'tags' => 'nullable|array'
        ]);

        // Create slug from title
        $slug = Str::slug($request->title);

        // Handle file upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($post->featured_image) {
                \Storage::disk('public')->delete($post->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('posts', 'public');
            $post->featured_image = $imagePath;
        }

        // Update post
        $post->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'status' => $request->status,
            'published_at' => $request->status === 'published' && !$post->published_at ? now() : $post->published_at
        ]);

        // Sync tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully!');
    }

    // Delete post
    public function destroy(Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Delete featured image if exists
        if ($post->featured_image) {
            \Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}