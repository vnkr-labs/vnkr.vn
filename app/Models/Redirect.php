<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Ý tưởng từ: github/docs src/redirects
 * Redirect được quản lý trong DB — admin thêm không cần deploy.
 */
class Redirect extends Model
{
    protected $fillable = [
        'from_path', 'to_path', 'status_code', 'is_active', 'note', 'hit_count',
    ];

    protected $casts = ['is_active' => 'boolean'];

    /**
     * Tra cứu redirect theo path, cache 10 phút.
     * Trả về ['to' => '/new-path', 'status' => 301] hoặc null.
     */
    public static function resolve(string $path): ?array
    {
        return Cache::remember("redirect:{$path}", 600, function () use ($path) {
            $r = static::where('from_path', $path)->where('is_active', true)->first();
            if (!$r) return null;
            // Tăng hit_count async
            static::where('id', $r->id)->increment('hit_count');
            return ['to' => $r->to_path, 'status' => $r->status_code];
        });
    }

    protected static function booted(): void
    {
        // Xóa cache khi thay đổi
        $clear = fn($r) => Cache::forget("redirect:{$r->from_path}");
        static::saved($clear);
        static::deleted($clear);
    }
}
