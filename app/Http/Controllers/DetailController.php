<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Event;
use App\Models\Product;
use App\Models\ReadingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DetailController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with(['category', 'tags', 'galleryImages'])->where('slug', $slug)->firstOrFail();

        // Tăng view count (không block request — dùng DB increment)
        Product::where('id', $product->id)->increment('view_count');

        // Ghi event page_view (github/docs events pattern)
        Event::record(Event::TYPE_PAGE_VIEW, ['article_id' => $product->id]);

        // Ghi lịch sử đọc nếu đã đăng nhập — dedup: chỉ 1 bản ghi mỗi (user, article, ngày)
        if (Auth::check()) {
            $today = now()->toDateString();
            ReadingHistory::where('user_id', Auth::id())
                ->where('article_id', $product->id)
                ->whereDate('read_at', $today)
                ->exists()
                ?: ReadingHistory::create([
                    'user_id'    => Auth::id(),
                    'article_id' => $product->id,
                    'read_at'    => now(),
                ]);
        }

        // Kiểm tra bookmark trạng thái
        $isBookmarked = Auth::check()
            ? Bookmark::where('user_id', Auth::id())->where('article_id', $product->id)->exists()
            : false;

        $comments   = $product->comments()->get();
        $related    = Product::where('category_id', $product->category_id)
                              ->where('id', '!=', $product->id)
                              ->orderByDesc('created_at')
                              ->limit(3)
                              ->get();
        $moreCat    = Product::where('category_id', $product->category_id)
                              ->where('id', '!=', $product->id)
                              ->orderByDesc('created_at')
                              ->limit(5)
                              ->get();
        $latestSide = Product::where('id', '!=', $product->id)
                              ->orderByDesc('created_at')
                              ->limit(6)
                              ->get();

        return view('fe.detail', compact('product', 'slug', 'comments', 'related', 'moreCat', 'latestSide', 'isBookmarked'));
    }

    public function storeComment(Request $request, string $slug)
    {
        $request->validate(['comment' => 'required|string|max:2000']);

        $product = Product::where('slug', $slug)->firstOrFail();

        // is_approved = false → chờ admin duyệt
        Comment::create([
            'article_id' => $product->id,
            'user_id'    => Auth::id(),
            'parent_id'  => null,
            'content'    => $request->input('comment'),
            'is_approved'=> false,
        ]);

        Cache::forget("comments:{$product->id}");

        return redirect()->back()->with('success', 'Bình luận đã gửi — đang chờ kiểm duyệt. Cảm ơn bạn!');
    }

    public function deleteComment(string $slug, int $commentId)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $comment = Comment::where('id', $commentId)
                          ->where('article_id', $product->id)
                          ->firstOrFail();

        // Chỉ admin hoặc chủ comment được xóa
        if (Auth::user()->role !== 'admin' && $comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();
        Cache::forget("comments:{$product->id}");

        return redirect()->back()->with('success', 'Đã xóa bình luận!');
    }

    public function likeComment(string $slug, int $commentId)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $comment = Comment::where('id', $commentId)
                          ->where('article_id', $product->id)
                          ->firstOrFail();

        Comment::where('id', $commentId)->increment('likes');

        return redirect()->back()->with('success', 'Đã thích bình luận!');
    }

    public function replyComment(Request $request, string $slug, int $commentId)
    {
        $request->validate(['reply' => 'required|string|max:2000']);

        $product = Product::where('slug', $slug)->firstOrFail();
        $parent  = Comment::where('id', $commentId)
                          ->where('article_id', $product->id)
                          ->whereNull('parent_id')  // chỉ reply 1 cấp
                          ->firstOrFail();

        Comment::create([
            'article_id'  => $product->id,
            'user_id'     => Auth::id(),
            'parent_id'   => $parent->id,
            'content'     => $request->input('reply'),
            'is_approved' => false,
        ]);

        Cache::forget("comments:{$product->id}");

        return redirect()->back()->with('success', 'Phản hồi đã gửi — đang chờ kiểm duyệt!');
    }
}
