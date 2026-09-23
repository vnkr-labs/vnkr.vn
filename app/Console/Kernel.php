<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Cập nhật trending articles mỗi giờ
        $schedule->command('vnkr:trending')->hourly();

        // Dọn dẹp soft-deleted records hàng tuần (Chủ nhật 3:00 AM)
        $schedule->command('vnkr:cleanup')->weekly()->sundays()->at('03:00');

        // Ping sitemap Google/Bing hàng ngày lúc 06:00
        $schedule->command('vnkr:ping-sitemap')->dailyAt('06:00');

        // Xóa expired breaking news hàng giờ
        $schedule->call(function () {
            \App\Models\BreakingNews::where('expired_at', '<', now())->delete();
            \Illuminate\Support\Facades\Cache::forget('breaking_news');
        })->hourly()->name('cleanup-expired-breaking-news')->withoutOverlapping();

        // Horizon metrics snapshot mỗi 5 phút
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        // Tính lại reading_time cho bài viết mỗi ngày lúc 2:00 AM (github/docs metrics pattern)
        $schedule->call(function () {
            $svc = new \App\Services\ContentMetricsService();
            \App\Models\Product::whereNull('deleted_at')
                ->where('reading_time', 0)
                ->chunkById(50, fn($chunk) => $chunk->each([$svc, 'updateReadingTime']));
        })->dailyAt('02:00')->name('update-reading-time')->withoutOverlapping();

        // Content lint — log kết quả hàng ngày (github/docs pattern)
        $schedule->command('vnkr:lint --errors --format=summary')
            ->dailyAt('07:00')
            ->name('content-lint-check')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/content-lint.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
