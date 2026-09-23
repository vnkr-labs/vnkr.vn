<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Event;
use App\Models\Journey;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\Redirect;
use App\Models\Reusable;
use App\Models\SearchLog;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashBoardController extends Controller
{
    public function index()
    {
        // === METRIC CARDS ===
        $userCount     = User::count();
        $categoryCount = Category::count();
        $productCount  = Product::count();
        $contactCount  = Contact::count();
        $commentCount  = Comment::count();
        $tagCount      = Tag::count();
        $newsletterCount = NewsletterSubscriber::whereNull('unsubscribed_at')->count();

        // === TOP ARTICLES (24h / 7d / 30d) ===
        $top24h = Product::select('id', 'name', 'slug', 'view_count', 'like_count', 'category_id', 'created_at')
            ->with('category:id,name')
            ->where('created_at', '>=', now()->subHours(24))
            ->orderByDesc('view_count')
            ->limit(10)
            ->get();

        $top7d = Product::select('id', 'name', 'slug', 'view_count', 'like_count', 'category_id', 'created_at')
            ->with('category:id,name')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderByDesc('view_count')
            ->limit(10)
            ->get();

        $top30d = Product::select('id', 'name', 'slug', 'view_count', 'like_count', 'created_at')
            ->orderByDesc('view_count')
            ->limit(10)
            ->get();

        // === BÀI MỚI NHẤT ===
        $recentArticles = Product::with('category:id,name')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get(['id', 'name', 'slug', 'view_count', 'category_id', 'created_at']);

        // === BÌNH LUẬN MỚI NHẤT ===
        $recentComments = Comment::with('user:id,name', 'article:id,name,slug')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        // === TOP CATEGORIES (theo số bài) ===
        $topCategories = Category::withCount('products')
            ->orderByDesc('products_count')
            ->limit(6)
            ->get(['id', 'name', 'slug']);

        // === TOP CATEGORIES (theo view tổng) ===
        $topCatsByView = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name', DB::raw('SUM(products.view_count) as total_views'))
            ->whereNull('products.deleted_at')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_views')
            ->limit(6)
            ->get();

        // === VIEWS CHART — 14 ngày gần nhất (bài đăng theo ngày) ===
        $viewsChart = DB::table('products')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(view_count) as views'), DB::raw('COUNT(*) as articles'))
            ->whereNull('deleted_at')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Điền đủ 14 ngày (kể cả ngày 0 bài)
        $chartLabels = [];
        $chartViews  = [];
        $chartPosts  = [];
        for ($i = 13; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d/m');
            $chartViews[]  = $viewsChart[$d]->views   ?? 0;
            $chartPosts[]  = $viewsChart[$d]->articles ?? 0;
        }

        // === TOP SEARCH KEYWORDS (30 ngày) ===
        $topKeywords = DB::table('search_logs')
            ->select('keyword', DB::raw('COUNT(*) as count'), DB::raw('AVG(results_count) as avg_results'))
            ->where('searched_at', '>=', now()->subDays(30))
            ->groupBy('keyword')
            ->orderByDesc('count')
            ->limit(15)
            ->get();

        // === NEWSLETTER STATS ===
        $newsletterTotal      = NewsletterSubscriber::count();
        $newsletterConfirmed  = NewsletterSubscriber::whereNotNull('confirmed_at')->whereNull('unsubscribed_at')->count();
        $newsletterUnsub      = NewsletterSubscriber::whereNotNull('unsubscribed_at')->count();
        $newsletterThisWeek   = NewsletterSubscriber::where('created_at', '>=', now()->subDays(7))->count();

        // === TRENDING từ cache TrendingArticles command ===
        $trendingCached = Cache::get('trending:24h');

        // === GITHUB/DOCS FEATURES STATS ===
        $journeyCount   = Journey::count();
        $reusableCount  = Reusable::count();
        $redirectCount  = Redirect::count();
        $eventToday     = Event::today()->count();
        $eventThisWeek  = Event::thisWeek()->count();

        // Draft / Review articles needing attention
        $draftCount  = Product::whereNull('deleted_at')->where('status', 'draft')->count();
        $reviewCount = Product::whereNull('deleted_at')->where('status', 'review')->count();

        return view('admin.index', compact(
            'userCount', 'categoryCount', 'productCount', 'contactCount',
            'commentCount', 'tagCount', 'newsletterCount',
            'top24h', 'top7d', 'top30d',
            'recentArticles', 'recentComments',
            'topCategories', 'topCatsByView',
            'chartLabels', 'chartViews', 'chartPosts',
            'topKeywords',
            'newsletterTotal', 'newsletterConfirmed', 'newsletterUnsub', 'newsletterThisWeek',
            'trendingCached',
            'journeyCount', 'reusableCount', 'redirectCount',
            'eventToday', 'eventThisWeek',
            'draftCount', 'reviewCount'
        ));
    }
}
