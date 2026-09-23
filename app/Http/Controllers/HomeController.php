<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache bài nổi bật 10 phút — invalidate khi admin update
        $featuredProduct = Cache::remember('home:featured', 600, function () {
            return Product::where('is_featured', 1)->orderByDesc('created_at')->limit(5)->get();
        });

        $newProduct = Product::with('category')
            ->orderByDesc('created_at')
            ->paginate(5);

        // Breaking ticker — 5 bài mới nhất (dùng chung cache)
        $breakingNews = Cache::remember('home:breaking', 300, function () {
            return Product::orderByDesc('created_at')->limit(5)->get();
        });

        // Category blocks — 4 chuyên mục đầu, mỗi cái 5 bài
        $catBlocks = Cache::remember('home:catblocks', 300, function () {
            return Category::where('status', 1)->limit(4)->get()->map(function ($cat) {
                $cat->blockPosts = Product::where('category_id', $cat->id)
                    ->orderByDesc('created_at')->limit(5)->get();
                return $cat;
            })->filter(fn($cat) => $cat->blockPosts->isNotEmpty());
        });

        // Sidebar: 5 bài đọc nhiều cho widget "Đọc Nhiều Nhất"
        $mostRead = Cache::remember('home:mostread', 300, function () {
            return Product::orderByDesc('view_count')->limit(5)->get();
        });

        // Sidebar: tất cả chuyên mục + số bài cho widget "Chuyên Mục"
        $navCategories = Cache::remember('home:navcats', 600, function () {
            return Category::where('status', 1)
                ->withCount('products')
                ->orderBy('name')
                ->get();
        });

        return view('fe.home', compact(
            'featuredProduct', 'newProduct', 'breakingNews', 'catBlocks', 'mostRead', 'navCategories'
        ));
    }

    public function result(int $id)
    {
        $cat = Category::where('id', $id)->where('status', 1)->first();
        if ($cat && $cat->slug) {
            return redirect()->route('category.slug', $cat->slug, 301);
        }
        return $this->renderCategory(Category::findOrFail($id));
    }

    public function resultBySlug(string $slug)
    {
        $category = Category::where('slug', $slug)->where('status', 1)->firstOrFail();

        // Danh mục đặc biệt → view chuyên biệt
        $specialViews = [
            'thao-luan'        => 'fe.community.discuss',
            'tai-tro-dong-gop' => 'fe.community.sponsor',
            'web3-crypto'      => 'fe.community.web3',
        ];

        if (isset($specialViews[$slug])) {
            $products = \App\Models\Product::with(['category', 'tags', 'author'])
                ->where('category_id', $category->id)
                ->withCount('comments')
                ->orderByDesc('created_at')
                ->paginate(12);
            return view($specialViews[$slug], compact('products', 'category'));
        }

        return $this->renderCategory($category);
    }

    private function renderCategory(Category $category)
    {
        $products = Product::with('category')
            ->where('category_id', $category->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        // Bọc category vào collection để tương thích view cũ
        $categoryCollection = collect([$category]);

        return view('fe.result', [
            'category' => $categoryCollection,
            'products' => $products,
            'currentCategory' => $category,
        ]);
    }
}
