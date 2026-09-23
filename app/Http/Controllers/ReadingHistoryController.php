<?php

namespace App\Http\Controllers;

use App\Models\ReadingHistory;
use Illuminate\Support\Facades\Auth;

class ReadingHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * GET /profile/history
     */
    public function index()
    {
        $history = ReadingHistory::with(['article.category'])
            ->where('user_id', Auth::id())
            ->orderByDesc('read_at')
            ->paginate(15);

        return view('fe.profile.history', compact('history'));
    }
}
