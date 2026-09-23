<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ContentMetricsService;
use Illuminate\Console\Command;

/**
 * Ý tưởng từ: github/docs src/metrics — docstat CLI tool
 * Xem metrics của 1 bài viết từ terminal.
 *
 * Chạy: php artisan vnkr:docstat
 *       php artisan vnkr:docstat --id=5
 *       php artisan vnkr:docstat --slug=ten-bai-viet
 *       php artisan vnkr:docstat --top=10  (top 10 bài xem nhiều nhất)
 */
class DocStat extends Command
{
    protected $signature   = 'vnkr:docstat
                                {--id=      : ID bài viết}
                                {--slug=    : Slug bài viết}
                                {--top=     : Top N bài xem nhiều nhất}
                                {--format=  : json | table (mặc định)}';
    protected $description = 'Xem content metrics của bài viết (lấy ý tưởng từ github/docs docstat)';

    public function handle(ContentMetricsService $metrics): int
    {
        if ($top = $this->option('top')) {
            return $this->showTop((int) $top, $metrics);
        }

        $article = null;
        if ($id = $this->option('id')) {
            $article = Product::find($id);
        } elseif ($slug = $this->option('slug')) {
            $article = Product::where('slug', $slug)->first();
        }

        if (!$article) {
            // Không có tham số → show tổng quan
            return $this->showOverview($metrics);
        }

        $stat = $metrics->statFor($article);

        if ($this->option('format') === 'json') {
            $this->line(json_encode($stat, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return self::SUCCESS;
        }

        $this->line('');
        $this->line("<fg=cyan>━━━ VNKR DocStat ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</>");
        $this->table(['Chỉ số', 'Giá trị'], [
            ['📰 Tiêu đề',     $stat['title']],
            ['🔗 URL',         $stat['url']],
            ['👁  Lượt xem',    number_format($stat['view_count'])],
            ['❤️  Lượt thích',  number_format($stat['like_count'])],
            ['💬 Bình luận',   $stat['comment_count']],
            ['⏱  Reading time', "{$stat['reading_time']} phút"],
            ['📝 Số từ',        number_format($stat['word_count'])],
            ['🖼  Có ảnh',      $stat['has_image'] ? '✅' : '❌'],
            ['🏷  Có tags',     $stat['has_tags'] ? '✅' : '❌'],
            ['✍️  Có tác giả',  $stat['has_author'] ? '✅' : '❌'],
            ['📌 Trạng thái',   strtoupper($stat['status'])],
            ['📅 Tạo lúc',      $stat['created_at']],
            ['🔄 Cập nhật',     $stat['updated_at']],
        ]);

        return self::SUCCESS;
    }

    private function showTop(int $n, ContentMetricsService $metrics): int
    {
        $articles = Product::whereNull('deleted_at')
            ->orderByDesc('view_count')
            ->limit($n)
            ->get();

        $rows = $articles->map(fn($a) => [
            $a->id,
            \Illuminate\Support\Str::limit($a->name, 50),
            number_format($a->view_count ?? 0),
            number_format($a->like_count ?? 0),
            ($a->reading_time ?? 0) . ' phút',
            $a->status ?? 'published',
        ]);

        $this->line('');
        $this->line("<fg=cyan>━━━ Top {$n} bài xem nhiều nhất ━━━━━━━━━━━━━━━━━━━━━━━━━━━━</>");
        $this->table(['ID', 'Tiêu đề', 'Lượt xem', 'Thích', 'Đọc', 'Status'], $rows->toArray());
        return self::SUCCESS;
    }

    private function showOverview(ContentMetricsService $metrics): int
    {
        $total    = Product::whereNull('deleted_at')->count();
        $noImage  = Product::where('image', 'news-placeholder.jpg')->orWhereNull('image')->count();
        $noTags   = Product::whereNull('deleted_at')->whereDoesntHave('tags')->count();
        $noAuthor = Product::whereNull('deleted_at')->whereNull('author_id')->count();
        $totalViews = Product::whereNull('deleted_at')->sum('view_count');

        $this->line('');
        $this->line('<fg=cyan>━━━ VNKR Content Overview ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</>');
        $this->table(['Chỉ số', 'Giá trị'], [
            ['📰 Tổng bài viết',     $total],
            ['👁  Tổng lượt xem',    number_format($totalViews)],
            ['🖼  Bài chưa có ảnh',  $noImage],
            ['🏷  Bài chưa có tags', $noTags],
            ['✍️  Bài chưa có tác giả', $noAuthor],
        ]);
        $this->line("  Chạy <fg=green>php artisan vnkr:lint</> để kiểm tra chất lượng chi tiết.");
        $this->line('');
        return self::SUCCESS;
    }
}
