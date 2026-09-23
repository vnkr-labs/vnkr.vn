<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reusable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReusableController extends Controller
{
    public function index()
    {
        $reusables = Reusable::orderByDesc('updated_at')->paginate(20);

        return view('admin.reusables.index', compact('reusables'));
    }

    public function create()
    {
        return view('admin.reusables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:200',
            'slug'    => 'required|string|max:200|unique:reusables,slug|regex:/^[a-z0-9\-]+$/',
            'content' => 'required|string',
            'type'    => 'required|in:html,markdown,text',
        ]);

        Reusable::create([
            'title'      => $request->title,
            'slug'       => $request->slug,
            'content'    => $request->content,
            'type'       => $request->type,
            'is_active'  => $request->boolean('is_active', true),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.reusables.index')
            ->with('success', 'Reusable "{{reusable:' . $request->slug . '}}" đã được tạo!');
    }

    public function edit(Reusable $reusable)
    {
        return view('admin.reusables.edit', compact('reusable'));
    }

    public function update(Request $request, Reusable $reusable)
    {
        $request->validate([
            'title'   => 'required|string|max:200',
            'slug'    => 'required|string|max:200|unique:reusables,slug,' . $reusable->id . '|regex:/^[a-z0-9\-]+$/',
            'content' => 'required|string',
            'type'    => 'required|in:html,markdown,text',
        ]);

        $reusable->update([
            'title'     => $request->title,
            'slug'      => $request->slug,
            'content'   => $request->content,
            'type'      => $request->type,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.reusables.index')
            ->with('success', 'Reusable đã được cập nhật!');
    }

    public function destroy(Reusable $reusable)
    {
        $reusable->delete();

        return redirect()->route('admin.reusables.index')
            ->with('success', 'Đã xóa reusable!');
    }
}
