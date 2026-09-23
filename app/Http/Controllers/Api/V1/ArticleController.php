<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * GET /api/v1/articles
     * Danh sách bài viết, hỗ trợ filter & paginate
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category:id,name', 'tags:id,name,slug', 'author:id,name,username,avatar'])
            ->select('id','name','tomtat','slug','image','category_id','author_id',
                     'view_count','like_count','is_featured','created_at','updated_at');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }
        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }
        if ($request->filled('q')) {
            $q = $request->input('q');
            if (mb_strlen($q) >= 3) {
                $query->whereRaw('MATCH(name, tomtat, description) AGAINST(? IN BOOLEAN MODE)', [$q]);
            } else {
                $query->where('name', 'LIKE', "%{$q}%");
            }
        }

        $articles = $query->orderByDesc('created_at')
                          ->paginate($request->integer('per_page', 15))
                          ->withQueryString();

        return response()->json([
            'data'  => $articles->items(),
            'meta'  => [
                'current_page' => $articles->currentPage(),
                'last_page'    => $articles->lastPage(),
                'per_page'     => $articles->perPage(),
                'total'        => $articles->total(),
            ],
            'links' => [
                'prev' => $articles->previousPageUrl(),
                'next' => $articles->nextPageUrl(),
            ],
        ]);
    }

    /**
     * GET /api/v1/search?q=keyword&per_page=15&page=1
     */
    public function search(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        if ($q === '') {
            return response()->json(['error' => 'Query parameter q is required'], 422);
        }

        $query = Product::with(['category:id,name', 'tags:id,name,slug'])
            ->select('id','name','tomtat','slug','image','category_id','view_count','created_at');

        if (mb_strlen($q) >= 3) {
            $query->whereRaw(
                'MATCH(name, tomtat, description) AGAINST(? IN BOOLEAN MODE)',
                [$q . '*']
            );
        } else {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'LIKE', "%{$q}%")
                   ->orWhere('tomtat', 'LIKE', "%{$q}%");
            });
        }

        $results = $query->orderByDesc('created_at')
                         ->paginate($request->integer('per_page', 15))
                         ->withQueryString();

        return response()->json([
            'query' => $q,
            'data'  => $results->items(),
            'meta'  => [
                'current_page' => $results->currentPage(),
                'last_page'    => $results->lastPage(),
                'per_page'     => $results->perPage(),
                'total'        => $results->total(),
            ],
            'links' => [
                'prev' => $results->previousPageUrl(),
                'next' => $results->nextPageUrl(),
            ],
        ]);
    }

    /**
     * GET /api/v1/articles/{slug}
     * Chi tiết bài viết
     */
    public function show(string $slug): JsonResponse
    {
        $article = Product::with(['category:id,name', 'tags:id,name,slug', 'author:id,name,username,avatar'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Tăng view count
        Product::where('id', $article->id)->increment('view_count');

        return response()->json(['data' => $article]);
    }
}
