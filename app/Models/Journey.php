<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Ý tưởng từ: github/docs src/journeys
 * Lộ trình đọc có hướng dẫn với prev/next navigation.
 */
class Journey extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'icon', 'color',
        'estimated_minutes', 'category_id', 'is_active', 'created_by',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'journey_article', 'journey_id', 'article_id')
                    ->withPivot('order', 'note')
                    ->orderBy('journey_article.order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Lấy bài trước và bài sau trong lộ trình.
     * Trả về ['prev' => Product|null, 'next' => Product|null]
     */
    public function getNavFor(int $articleId): array
    {
        $ids = $this->articles()->pluck('products.id')->toArray();
        $pos = array_search($articleId, $ids);
        if ($pos === false) {
            return ['prev' => null, 'next' => null];
        }
        return [
            'prev' => $pos > 0 ? Product::find($ids[$pos - 1]) : null,
            'next' => $pos < count($ids) - 1 ? Product::find($ids[$pos + 1]) : null,
        ];
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
