<?php

namespace App\Http\Controllers;

use App\Models\Journey;
use Illuminate\Http\Request;

class JourneyController extends Controller
{
    public function show(string $slug)
    {
        $journey = Journey::with(['articles.category', 'category', 'creator'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('fe.journey.show', compact('journey'));
    }
}
