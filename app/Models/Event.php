<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Ý tưởng từ: github/docs src/events
 * Immutable event log — không update, chỉ insert.
 */
class Event extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type', 'request_uuid', 'path', 'referrer', 'user_agent',
        'user_id', 'session_hash',
        'search_query', 'link_url', 'article_id',
        'survey_rating', 'survey_comment',
        'time_on_page', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Event types (tương tự EventType enum trong github/docs)
    const TYPE_PAGE_VIEW  = 'page_view';
    const TYPE_SEARCH     = 'search';
    const TYPE_LINK_CLICK = 'link_click';
    const TYPE_SURVEY     = 'survey';
    const TYPE_EXIT       = 'exit';

    /**
     * Ghi 1 event nhanh — dùng ở khắp nơi.
     */
    public static function record(string $type, array $payload = []): void
    {
        try {
            static::create(array_merge([
                'type'         => $type,
                'request_uuid' => request()->attributes->get('request_uuid', (string) \Illuminate\Support\Str::uuid()),
                'path'         => request()->path(),
                'referrer'     => request()->header('Referer'),
                'user_id'      => auth()->id(),
                'created_at'   => now(),
            ], $payload));
        } catch (\Throwable) {
            // Events không được làm crash ứng dụng
        }
    }

    // Scopes tiện ích
    public function scopePageViews($q)  { return $q->where('type', self::TYPE_PAGE_VIEW); }
    public function scopeSearches($q)   { return $q->where('type', self::TYPE_SEARCH); }
    public function scopeToday($q)      { return $q->whereDate('created_at', today()); }
    public function scopeThisWeek($q)   { return $q->whereBetween('created_at', [now()->startOfWeek(), now()]); }
}
