<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    /**
     * Hàng đợi kiểm duyệt — bình luận chưa duyệt.
     */
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'article'])
            ->where('is_approved', false)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at');

        $pending = $query->paginate(20);

        $totalPending = Comment::where('is_approved', false)->whereNull('deleted_at')->count();

        return view('admin.comment.index', compact('pending', 'totalPending'));
    }

    /**
     * Duyệt một bình luận.
     */
    public function approve(int $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->is_approved = true;
        $comment->save();

        if ($comment->article) {
            Cache::forget("comments:{$comment->article_id}");
        }

        return redirect()->back()->with('success', 'Đã duyệt bình luận.');
    }

    /**
     * Duyệt hàng loạt.
     */
    public function approveAll()
    {
        Comment::where('is_approved', false)->update(['is_approved' => true]);
        Cache::flush(); // đơn giản hóa — flush all comment caches
        return redirect()->back()->with('success', 'Đã duyệt tất cả bình luận đang chờ.');
    }

    /**
     * Xóa bình luận không phù hợp.
     */
    public function destroy(int $id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->article) {
            Cache::forget("comments:{$comment->article_id}");
        }
        $comment->delete();
        return redirect()->back()->with('success', 'Đã xóa bình luận.');
    }
}
