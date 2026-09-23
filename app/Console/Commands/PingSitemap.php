<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PingSitemap extends Command
{
    protected $signature   = 'vnkr:ping-sitemap';
    protected $description = 'Gửi ping sitemap tới Google & Bing';

    public function handle(): int
    {
        $sitemapUrl = urlencode(config('app.url') . '/sitemap.xml');

        $targets = [
            'Google' => "https://www.google.com/ping?sitemap={$sitemapUrl}",
            'Bing'   => "https://www.bing.com/ping?sitemap={$sitemapUrl}",
        ];

        foreach ($targets as $engine => $url) {
            try {
                $response = Http::timeout(10)->get($url);
                $this->info("{$engine}: HTTP {$response->status()}");
            } catch (\Throwable $e) {
                $this->warn("{$engine}: {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}
