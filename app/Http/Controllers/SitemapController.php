<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products   = Product::select('slug', 'updated_at')->orderBy('updated_at', 'desc')->get();
        $categories = Category::select('id', 'slug', 'updated_at')->where('status', 1)->get();

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static pages — canonical English URLs
        $statics = [
            ['url' => url('/'),           'priority' => '1.0', 'freq' => 'daily'],
            ['url' => url('/about'),      'priority' => '0.5', 'freq' => 'monthly'],
            ['url' => url('/community'),  'priority' => '0.5', 'freq' => 'monthly'],
            ['url' => url('/contribute'), 'priority' => '0.5', 'freq' => 'monthly'],
            ['url' => url('/terms'),      'priority' => '0.3', 'freq' => 'yearly'],
            ['url' => url('/privacy'),    'priority' => '0.3', 'freq' => 'yearly'],
        ];

        foreach ($statics as $page) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$page['url']}</loc>\n";
            $xml .= "    <changefreq>{$page['freq']}</changefreq>\n";
            $xml .= "    <priority>{$page['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        // Category pages — use slug URL when available
        foreach ($categories as $cat) {
            $loc = htmlspecialchars(
                $cat->slug
                    ? route('category.slug', $cat->slug)
                    : route('result', $cat->id)
            );
            $lastmod = $cat->updated_at ? $cat->updated_at->toAtomString() : now()->toAtomString();
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$loc}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>daily</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        // Article pages
        foreach ($products as $product) {
            $loc     = htmlspecialchars(route('detail', $product->slug));
            $lastmod = $product->updated_at ? $product->updated_at->toAtomString() : now()->toAtomString();
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$loc}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
