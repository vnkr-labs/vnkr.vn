<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * GET /api/v1/categories
     */
    public function index(): JsonResponse
    {
        $categories = Category::where('status', 1)
            ->withCount('products')
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id', 'status']);

        return response()->json(['data' => $categories]);
    }

    /**
     * GET /api/v1/categories/{id}/articles
     */
    public function articles(int $id): JsonResponse
    {
        $category = Category::where('status', 1)->findOrFail($id);

        $articles = \App\Models\Product::with(['tags:id,name,slug'])
            ->where('category_id', $id)
            ->select('id','name','tomtat','slug','image','view_count','created_at')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return response()->json([
            'category' => $category->only(['id','name']),
            'data'     => $articles->items(),
            'meta'     => [
                'current_page' => $articles->currentPage(),
                'last_page'    => $articles->lastPage(),
                'total'        => $articles->total(),
            ],
        ]);
    }
}
