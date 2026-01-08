<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // A post belongs to a user (author)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A post belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // A post can have many tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Check if post is published
    public function isPublished()
    {
        return $this->status === 'published';
    }

    // Get only published posts
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}