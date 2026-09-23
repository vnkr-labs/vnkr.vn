<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RedirectController extends Controller
{
    public function index(Request $request)
    {
        $query = Redirect::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('from_path', 'like', "%{$search}%")
                  ->orWhere('to_path',   'like', "%{$search}%");
            });
        }

        $redirects = $query->orderByDesc('hit_count')->orderByDesc('created_at')->paginate(20);

        return view('admin.redirects.index', compact('redirects'));
    }

    public function create()
    {
        return view('admin.redirects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_path'   => 'required|string|max:500|unique:redirects,from_path',
            'to_path'     => 'required|string|max:500',
            'status_code' => 'required|in:301,302',
            'is_active'   => 'nullable|boolean',
        ]);

        Redirect::create([
            'from_path'   => '/' . ltrim($request->from_path, '/'),
            'to_path'     => $request->to_path,
            'status_code' => (int) $request->status_code,
            'is_active'   => $request->boolean('is_active', true),
            'hit_count'   => 0,
        ]);

        Cache::flush(); // xóa cache redirect

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Redirect đã được tạo!');
    }

    public function destroy(Redirect $redirect)
    {
        $redirect->delete();
        Cache::flush();

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Đã xóa redirect!');
    }

    public function toggle(Redirect $redirect)
    {
        $redirect->update(['is_active' => ! $redirect->is_active]);
        Cache::flush();

        return redirect()->back()
            ->with('success', 'Đã ' . ($redirect->is_active ? 'bật' : 'tắt') . ' redirect!');
    }
}
