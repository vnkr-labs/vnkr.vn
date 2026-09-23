<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class RssFeedController extends Controller
{
    public function index(): Response
    {
        $articles = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        $siteUrl   = rtrim(config('app.url'), '/');
        $buildDate = now()->toRfc2822String();

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">' . "\n";
        $xml .= "  <channel>\n";
        $xml .= "    <title>VNKR — Tin Tức Cộng Đồng TheKingBao</title>\n";
        $xml .= "    <link>{$siteUrl}</link>\n";
        $xml .= "    <description>Tin tức được biên tập bởi Phạm Thế Bảo — Dành cho cộng đồng TheKingBao</description>\n";
        $xml .= "    <language>vi</language>\n";
        $xml .= "    <lastBuildDate>{$buildDate}</lastBuildDate>\n";
        $xml .= "    <atom:link href=\"{$siteUrl}/feed\" rel=\"self\" type=\"application/rss+xml\"/>\n";
        $xml .= "    <image>\n";
        $xml .= "      <url>{$siteUrl}/client/images/favicon.png</url>\n";
        $xml .= "      <title>VNKR</title>\n";
        $xml .= "      <link>{$siteUrl}</link>\n";
        $xml .= "    </image>\n";

        foreach ($articles as $article) {
            $link        = htmlspecialchars(route('detail', $article->slug));
            $title       = htmlspecialchars($article->name ?? '');
            $description = htmlspecialchars(Str::limit(strip_tags($article->tomtat ?? ''), 200));
            $pubDate     = $article->created_at->toRfc2822String();
            $category    = htmlspecialchars($article->category->name ?? 'Tin tức');
            $guid        = htmlspecialchars(route('detail', $article->slug));

            $xml .= "    <item>\n";
            $xml .= "      <title>{$title}</title>\n";
            $xml .= "      <link>{$link}</link>\n";
            $xml .= "      <description>{$description}</description>\n";
            $xml .= "      <pubDate>{$pubDate}</pubDate>\n";
            $xml .= "      <category>{$category}</category>\n";
            $xml .= "      <guid isPermaLink=\"true\">{$guid}</guid>\n";

            if ($article->image) {
                $imgUrl = htmlspecialchars("{$siteUrl}/storage/images/{$article->image}");
                $xml .= "      <enclosure url=\"{$imgUrl}\" type=\"image/jpeg\" length=\"0\"/>\n";
            }

            $xml .= "    </item>\n";
        }

        $xml .= "  </channel>\n";
        $xml .= '</rss>';

        return response($xml, 200, [
            'Content-Type' => 'application/rss+xml; charset=utf-8',
        ]);
    }
}
