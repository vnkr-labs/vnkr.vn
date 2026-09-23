<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SearchLog;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Ý tưởng từ: github/docs src/search — AI search autocomplete
 * GET /api/search/autocomplete?q=viet+nam
 * Trả về gợi ý từ khóa từ SearchLog + tiêu đề bài viết phù hợp.
 */
class SearchAutocompleteController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $q = trim($request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json(['suggestions' => [], 'articles' => []]);
        }

        $cacheKey = 'autocomplete:' . md5(strtolower($q));

        $result = Cache::remember($cacheKey, 120, function () use ($q) {
            // 1. Gợi ý từ khóa từ lịch sử search (popular queries)
            $suggestions = SearchLog::where('keyword', 'LIKE', "{$q}%")
                ->where('results_count', '>', 0)
                ->groupBy('keyword')
                ->selectRaw('keyword, COUNT(*) as freq')
                ->orderByDesc('freq')
                ->limit(5)
                ->pluck('keyword')
                ->toArray();

            // 2. Bài viết khớp tiêu đề (instant results)
            $articles = Product::where('name', 'LIKE', "%{$q}%")
                ->whereNull('deleted_at')
                ->select('id', 'name', 'slug', 'image', 'category_id')
                ->with('category:id,name')
                ->limit(4)
                ->get()
                ->map(fn($a) => [
                    'id'       => $a->id,
                    'title'    => $a->name,
                    'url'      => route('detail', $a->slug),
                    'category' => $a->category?->name,
                    'image'    => $a->image ? asset('storage/images/' . $a->image) : null,
                ]);

            return compact('suggestions', 'articles');
        });

        return response()->json($result);
    }
}
