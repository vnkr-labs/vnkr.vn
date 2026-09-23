<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function show(string $username)
    {
        $author = User::where('username', $username)
                      ->where('is_author', true)
                      ->firstOrFail();

        $articles = $author->articles()
                           ->with('category', 'tags')
                           ->paginate(12);

        // Latest articles for sidebar (from all authors)
        $latestArticles = \App\Models\Product::orderByDesc('created_at')->limit(5)->get();

        return view('fe.author', compact('author', 'articles', 'latestArticles'));
    }
}
