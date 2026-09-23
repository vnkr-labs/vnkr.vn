<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveUpdate;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveBlogController extends Controller
{
    /**
     * GET /admin/live-blog — danh sách bài đang live
     */
    public function index()
    {
        $articles = Product::where('is_live', true)
            ->withCount('liveUpdates')
            ->orderByDesc('updated_at')
            ->get(['id', 'name', 'slug', 'is_live', 'updated_at']);

        return view('admin.live_blog.index', compact('articles'));
    }

    /**
     * GET /admin/live-blog/{article} — quản lý updates của 1 bài
     */
    public function manage(Product $article)
    {
        $updates = LiveUpdate::where('article_id', $article->id)
            ->with('admin:id,name')
            ->orderByDesc('posted_at')
            ->get();

        return view('admin.live_blog.manage', compact('article', 'updates'));
    }

    /**
     * POST /admin/live-blog/{article}/updates — đăng update mới
     */
    public function store(Request $request, Product $article)
    {
        $request->validate(['content' => 'required|string|max:3000']);

        LiveUpdate::create([
            'article_id' => $article->id,
            'admin_id'   => Auth::id(),
            'content'    => $request->input('content'),
            'is_pinned'  => $request->boolean('is_pinned'),
            'posted_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Đã đăng live update!');
    }

    /**
     * DELETE /admin/live-blog/{article}/updates/{update}
     */
    public function destroy(Product $article, LiveUpdate $update)
    {
        $update->delete();
        return redirect()->back()->with('success', 'Đã xóa update.');
    }

    /**
     * POST /admin/live-blog/{article}/toggle — bật/tắt live
     */
    public function toggle(Product $article)
    {
        $article->update(['is_live' => !$article->is_live]);
        $msg = $article->is_live ? 'Bài viết đang LIVE!' : 'Đã tắt chế độ live.';
        return redirect()->back()->with('success', $msg);
    }

    /**
     * GET /api/live/{articleId}/updates?after=timestamp — polling endpoint (public)
     */
    public function poll(int $articleId)
    {
        $article = Product::where('id', $articleId)->where('is_live', true)->firstOrFail();
        $after   = request('after');

        $query = LiveUpdate::where('article_id', $articleId)->orderByDesc('posted_at');
        if ($after) {
            $query->where('posted_at', '>', date('Y-m-d H:i:s', (int) $after));
        } else {
            $query->limit(20);
        }

        $updates = $query->get(['id', 'content', 'is_pinned', 'posted_at']);

        return response()->json([
            'is_live' => true,
            'updates' => $updates,
            'ts'      => now()->timestamp,
        ]);
    }
}
