<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AdSlot extends Model
{
    protected $fillable = ['position', 'label', 'code', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    /**
     * Lấy ad slot theo vị trí, dùng cache 10 phút.
     */
    public static function getActive(string $position): ?self
    {
        return Cache::remember("ad:{$position}", 600, function () use ($position) {
            return self::where('position', $position)->where('is_active', true)->first();
        });
    }

    /**
     * Xóa cache khi slot thay đổi.
     */
    protected static function booted(): void
    {
        static::saved(fn ($slot) => Cache::forget("ad:{$slot->position}"));
        static::deleted(fn ($slot) => Cache::forget("ad:{$slot->position}"));
    }
}
