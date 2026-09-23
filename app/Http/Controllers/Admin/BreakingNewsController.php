<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BreakingNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BreakingNewsController extends Controller
{
    public function index()
    {
        $items = BreakingNews::orderByDesc('created_at')->paginate(20);
        return view('admin.breaking_news.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:500',
            'url'        => 'nullable|url|max:500',
            'expired_at' => 'nullable|date',
        ]);

        BreakingNews::create([
            'title'      => $request->title,
            'url'        => $request->url,
            'is_active'  => true,
            'expired_at' => $request->expired_at ?: null,
        ]);

        Cache::forget('breaking_news');
        return redirect()->back()->with('success', 'Đã thêm breaking news.');
    }

    public function toggle(BreakingNews $breakingNews)
    {
        $breakingNews->update(['is_active' => ! $breakingNews->is_active]);
        Cache::forget('breaking_news');
        return redirect()->back()->with('success', 'Đã cập nhật trạng thái.');
    }

    public function destroy(BreakingNews $breakingNews)
    {
        $breakingNews->delete();
        Cache::forget('breaking_news');
        return redirect()->back()->with('success', 'Đã xóa.');
    }
}
