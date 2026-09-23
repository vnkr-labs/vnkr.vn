<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Journey;
use App\Models\Product;
use Illuminate\Http\Request;

class JourneyController extends Controller
{
    public function index()
    {
        $journeys = Journey::with(['category', 'creator'])
            ->withCount('articles')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.journeys.index', compact('journeys'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $articles   = Product::orderByDesc('created_at')->limit(200)->get(['id', 'name']);

        return view('admin.journeys.create', compact('categories', 'articles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'               => 'required|string|max:200',
            'slug'                => 'required|string|max:200|unique:journeys,slug',
            'description'         => 'nullable|string|max:1000',
            'icon'                => 'nullable|string|max:50',
            'color'               => 'nullable|string|max:20',
            'estimated_minutes'   => 'nullable|integer|min:1|max:600',
            'category_id'         => 'nullable|exists:categories,id',
            'article_ids'         => 'nullable|array',
            'article_ids.*'       => 'exists:products,id',
        ]);

        $journey = Journey::create([
            'title'             => $request->title,
            'slug'              => $request->slug,
            'description'       => $request->description,
            'icon'              => $request->input('icon', 'bi-map'),
            'color'             => $request->input('color', '#3b82d4'),
            'estimated_minutes' => $request->estimated_minutes,
            'category_id'       => $request->category_id,
            'is_active'         => $request->boolean('is_active', true),
            'created_by'        => auth()->id(),
        ]);

        $this->syncArticles($journey, $request->input('article_ids', []));

        return redirect()->route('admin.journeys.index')
            ->with('success', 'Lộ trình "' . $journey->title . '" đã được tạo!');
    }

    public function edit(Journey $journey)
    {
        $journey->load('articles');
        $categories      = Category::where('status', 1)->orderBy('name')->get();
        $articles        = Product::orderByDesc('created_at')->limit(200)->get(['id', 'name']);
        $selectedIds     = $journey->articles->pluck('id')->toArray();

        return view('admin.journeys.edit', compact('journey', 'categories', 'articles', 'selectedIds'));
    }

    public function update(Request $request, Journey $journey)
    {
        $request->validate([
            'title'             => 'required|string|max:200',
            'slug'              => 'required|string|max:200|unique:journeys,slug,' . $journey->id,
            'description'       => 'nullable|string|max:1000',
            'icon'              => 'nullable|string|max:50',
            'color'             => 'nullable|string|max:20',
            'estimated_minutes' => 'nullable|integer|min:1|max:600',
            'category_id'       => 'nullable|exists:categories,id',
            'article_ids'       => 'nullable|array',
            'article_ids.*'     => 'exists:products,id',
        ]);

        $journey->update([
            'title'             => $request->title,
            'slug'              => $request->slug,
            'description'       => $request->description,
            'icon'              => $request->input('icon', 'bi-map'),
            'color'             => $request->input('color', '#3b82d4'),
            'estimated_minutes' => $request->estimated_minutes,
            'category_id'       => $request->category_id,
            'is_active'         => $request->boolean('is_active'),
        ]);

        $this->syncArticles($journey, $request->input('article_ids', []));

        return redirect()->route('admin.journeys.index')
            ->with('success', 'Lộ trình đã được cập nhật!');
    }

    public function destroy(Journey $journey)
    {
        $journey->articles()->detach();
        $journey->delete();

        return redirect()->route('admin.journeys.index')
            ->with('success', 'Đã xóa lộ trình!');
    }

    private function syncArticles(Journey $journey, array $ids): void
    {
        $sync = [];
        foreach (array_values($ids) as $i => $id) {
            $sync[$id] = ['order' => $i + 1, 'note' => null];
        }
        $journey->articles()->sync($sync);
    }
}
