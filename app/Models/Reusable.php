<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Ý tưởng từ: github/docs — Reusables & Variables
 * Snippet HTML tái sử dụng — nhúng vào nội dung bài viết.
 */
class Reusable extends Model
{
    protected $fillable = ['slug', 'title', 'content', 'type', 'is_active', 'updated_by'];
    protected $casts    = ['is_active' => 'boolean'];

    const TYPE_BLOCK   = 'block';   // block-level HTML
    const TYPE_INLINE  = 'inline';  // inline span
    const TYPE_CALLOUT = 'callout'; // styled callout box

    /**
     * Render toàn bộ {{reusable:slug}} trong 1 chuỗi HTML.
     */
    public static function render(string $html): string
    {
        return preg_replace_callback(
            '/\{\{reusable:([a-z0-9\-]+)\}\}/i',
            function (array $m) {
                $slug    = $m[1];
                $content = Cache::remember("reusable:{$slug}", 300, function () use ($slug) {
                    $r = static::where('slug', $slug)->where('is_active', true)->first();
                    return $r ? $r->renderHtml() : "<!-- reusable:{$slug} not found -->";
                });
                return $content;
            },
            $html
        );
    }

    public function renderHtml(): string
    {
        return match ($this->type) {
            self::TYPE_CALLOUT => sprintf(
                '<div class="reusable-callout" style="background:#f0f7ff;border-left:4px solid #0A3D62;padding:12px 16px;border-radius:0 6px 6px 0;margin:16px 0;">%s</div>',
                $this->content
            ),
            self::TYPE_INLINE  => "<span class=\"reusable-inline\">{$this->content}</span>",
            default            => $this->content,
        };
    }

    protected static function booted(): void
    {
        $clear = fn($r) => Cache::forget("reusable:{$r->slug}");
        static::saved($clear);
        static::deleted($clear);
    }
}
