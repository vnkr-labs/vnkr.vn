<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * GET /api/v1/tags
     */
    public function index(): JsonResponse
    {
        $tags = Tag::withCount('articles')
            ->orderByDesc('articles_count')
            ->get(['id', 'name', 'slug']);

        return response()->json(['data' => $tags]);
    }

    /**
     * GET /api/v1/tags/{slug}/articles
     */
    public function articles(string $slug, Request $request): JsonResponse
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $articles = $tag->articles()
            ->with(['category:id,name'])
            ->select('products.id', 'products.name', 'products.tomtat',
                     'products.slug', 'products.image', 'products.category_id',
                     'products.view_count', 'products.created_at')
            ->orderByDesc('products.created_at')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return response()->json([
            'tag'  => $tag->only(['id', 'name', 'slug']),
            'data' => $articles->items(),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page'    => $articles->lastPage(),
                'total'        => $articles->total(),
            ],
        ]);
    }
}
