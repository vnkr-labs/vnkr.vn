<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();

        $commentCount  = $user->comments()->count();
        $bookmarkCount = $user->bookmarks()->count();
        $articleCount  = $user->articles()->count();
        $historyCount  = $user->readingHistory()->count();

        // Badge tự động theo level
        $level = $this->resolveLevel($user, $commentCount, $articleCount);

        // 5 bài mới nhất đã đọc
        $recentHistory = $user->readingHistory()
            ->with('article')
            ->orderByDesc('read_at')
            ->limit(5)
            ->get();

        // 3 bài mới nhất đã lưu
        $recentBookmarks = $user->bookmarks()
            ->with('article.category')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('fe.profile.show', compact(
            'user', 'commentCount', 'bookmarkCount',
            'articleCount', 'historyCount', 'level',
            'recentHistory', 'recentBookmarks'
        ));
    }

    public function edit()
    {
        return view('fe.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'nullable|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'bio'      => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url'  => 'nullable|url|max:255',
        ]);

        $user->name         = $request->input('name');
        $user->username     = $request->input('username') ?: $user->username;
        $user->bio          = $request->input('bio');
        $user->facebook_url = $request->input('facebook_url');
        $user->twitter_url  = $request->input('twitter_url');
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Hồ sơ đã được cập nhật!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Mật khẩu đã được thay đổi!');
    }

    /**
     * Xác định cấp độ thành viên dựa trên hoạt động.
     */
    private function resolveLevel($user, int $comments, int $articles): array
    {
        if ($user->role === 'admin') {
            return ['key' => 'admin',       'label' => 'Quản trị viên',   'color' => '#0A3D62', 'icon' => 'bi-shield-fill'];
        }
        if ($user->is_author && $articles >= 1) {
            return ['key' => 'contributor', 'label' => 'Cộng tác viên',   'color' => '#218838', 'icon' => 'bi-pen-fill'];
        }
        if ($comments >= 20) {
            return ['key' => 'active',      'label' => 'Thành viên tích cực', 'color' => '#F0A500', 'icon' => 'bi-star-fill'];
        }
        return     ['key' => 'member',      'label' => 'Thành viên cộng đồng', 'color' => '#555',  'icon' => 'bi-person-fill'];
    }
}
