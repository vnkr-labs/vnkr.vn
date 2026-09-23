<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        // Lấy tất cả người dùng và nhóm theo vai trò
        $roles = User::select('role', DB::raw('count(*) as total'))
                      ->groupBy('role')
                      ->get();

        return view('admin.roleUS.index', compact('roles'));
    }


    /**
     * Show the form for creating a new resource.
     */
  public function create()
{
    return view('admin.roleUs.create');
}

public function store(Request $request)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'role'  => 'required|string|in:user,admin',
    ]);

    User::create([
        'name'   => $request->input('name'),
        'email'  => $request->input('email'),
        'role'   => $request->input('role', 'user'),
        'status' => 1,
    ]);

    return redirect()->route('roleUs.index')->with('success', 'Vai trò được tạo thành công.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
