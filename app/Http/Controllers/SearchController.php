<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Product;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->input('s', ''));

        if ($query === '') {
            return view('fe.search.results', [
                'products' => collect(),
                'query'    => $query,
                'total'    => 0,
            ]);
        }

        // FULLTEXT Boolean mode — ưu tiên match trong name > tomtat > description
        // Fallback sang LIKE nếu query quá ngắn (< 3 ký tự, FULLTEXT không nhận)
        if (mb_strlen($query) >= 3) {
            $products = Product::select('*')
                ->selectRaw('MATCH(name, tomtat, description) AGAINST(? IN BOOLEAN MODE) AS relevance', [$query])
                ->whereRaw('MATCH(name, tomtat, description) AGAINST(? IN BOOLEAN MODE)', [$query])
                ->with('category')
                ->orderByDesc('relevance')
                ->orderByDesc('created_at')
                ->paginate(12)
                ->withQueryString();
        } else {
            $products = Product::with('category')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('tomtat', 'LIKE', "%{$query}%");
                })
                ->orderByDesc('created_at')
                ->paginate(12)
                ->withQueryString();
        }

        // Ghi log từ khóa tìm kiếm
        SearchLog::create([
            'keyword'       => mb_strtolower($query),
            'results_count' => $products->total(),
            'searched_at'   => now(),
        ]);

        // Ghi event search (github/docs events pattern)
        Event::record(Event::TYPE_SEARCH, ['search_query' => $query]);

        return view('fe.search.results', [
            'products' => $products,
            'query'    => $query,
            'total'    => $products->total(),
        ]);
    }
}
