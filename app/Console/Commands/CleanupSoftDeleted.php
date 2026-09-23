<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupSoftDeleted extends Command
{
    protected $signature   = 'vnkr:cleanup {--days=30 : Xóa records đã soft-delete quá N ngày}';
    protected $description = 'Xóa vĩnh viễn records soft-deleted quá hạn';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $before = now()->subDays($days);

        $products  = \App\Models\Product::onlyTrashed()->where('deleted_at', '<', $before)->forceDelete();
        $categories = \App\Models\Category::onlyTrashed()->where('deleted_at', '<', $before)->forceDelete();
        $comments  = \App\Models\Comment::onlyTrashed()->where('deleted_at', '<', $before)->forceDelete();

        // Dọn newsletter unsubscribed > 90 ngày
        $unsub = \App\Models\NewsletterSubscriber::whereNotNull('unsubscribed_at')
            ->where('unsubscribed_at', '<', now()->subDays(90))
            ->delete();

        $this->info("Cleanup done (>{$days}d): products={$products}, categories={$categories}, comments={$comments}, unsubscribed={$unsub}");
        return self::SUCCESS;
    }
}
