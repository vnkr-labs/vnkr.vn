<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class TrendingArticles extends Command
{
    protected $signature   = 'vnkr:trending';
    protected $description = 'Cập nhật cache danh sách bài trending (top view_count 24h)';

    public function handle(): int
    {
        $trending = Product::where('created_at', '>=', now()->subHours(24))
            ->orderByDesc('view_count')
            ->limit(10)
            ->get(['id', 'name', 'slug', 'image', 'view_count', 'category_id']);

        Cache::put('trending:24h', $trending, 3600); // cache 1 giờ

        // Fallback nếu 24h không có bài: lấy 7 ngày
        if ($trending->isEmpty()) {
            $fallback = Product::where('created_at', '>=', now()->subDays(7))
                ->orderByDesc('view_count')
                ->limit(10)
                ->get(['id', 'name', 'slug', 'image', 'view_count', 'category_id']);
            Cache::put('trending:24h', $fallback, 3600);
        }

        $this->info('Trending cache updated: ' . $trending->count() . ' articles.');
        return self::SUCCESS;
    }
}
