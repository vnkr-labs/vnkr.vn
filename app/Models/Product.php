<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'image',
        'video_url',
        'category_id',
        'tomtat',
        'stock',
        'view_count',
        'like_count',
        'is_featured',
        'is_published',
        'is_live',
        'published_at',
        'author_id',
        'status',
        'reading_time',
        'meta_title',
        'meta_description',
        'allow_comments',
    ];

    protected $casts = [
        'is_featured'    => 'boolean',
        'is_published'   => 'boolean',
        'is_live'        => 'boolean',
        'published_at'   => 'datetime',
        'reading_time'   => 'integer',
        'allow_comments' => 'boolean',
    ];

    // Status constants (staged publishing — ý tưởng từ github/docs)
    const STATUS_DRAFT     = 'draft';
    const STATUS_REVIEW    = 'review';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED  = 'archived';

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'article_id')
                    ->whereNull('parent_id')
                    ->where('is_approved', true)
                    ->with('user', 'replies')
                    ->orderByDesc('created_at');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag', 'article_id', 'tag_id');
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(ImgProduct::class, 'product_id');
    }

    public function liveUpdates(): HasMany
    {
        return $this->hasMany(LiveUpdate::class, 'article_id')->orderByDesc('posted_at');
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Chuyển YouTube/Vimeo URL thành embed URL
     */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) return null;

        $url = $this->video_url;

        // YouTube: watch?v=ID hoặc youtu.be/ID hoặc shorts/ID
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1] . '?rel=0';
        }

        // Vimeo: vimeo.com/ID
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return null;
    }
}
