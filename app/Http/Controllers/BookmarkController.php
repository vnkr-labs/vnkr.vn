<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * POST /bookmark/{article_id} — toggle bookmark
     */
    public function toggle(int $articleId)
    {
        $product = Product::findOrFail($articleId);
        $userId  = Auth::id();

        $existing = Bookmark::where('user_id', $userId)
                            ->where('article_id', $articleId)
                            ->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
        } else {
            Bookmark::create([
                'user_id'    => $userId,
                'article_id' => $articleId,
                'created_at' => now(),
            ]);
            $saved = true;
        }

        if (request()->expectsJson()) {
            return response()->json(['saved' => $saved]);
        }

        $msg = $saved ? 'Đã lưu bài viết vào danh sách!' : 'Đã xóa khỏi danh sách đã lưu.';
        return redirect()->back()->with('success', $msg);
    }

    /**
     * GET /profile/bookmarks
     */
    public function index()
    {
        $bookmarks = Bookmark::with(['article.category'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('fe.profile.bookmarks', compact('bookmarks'));
    }
}
