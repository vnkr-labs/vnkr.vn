<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{
    public function logon(){
        return view('admin.logon');
    }

    public function postlogon(Request $request){
        // Try string role 'admin' first, then legacy integer 1 for backward compat
        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            return redirect()->route('admin.index');
        }
        // Fallback for any existing record with role = 1
        Auth::logout();
        if (Auth::attempt(array_merge($credentials, ['role' => 1]))) {
            return redirect()->route('admin.index');
        }
        return redirect()->back()->with('error', 'Email hoặc mật khẩu không hợp lệ');
    }

    public function signOut(){
        Auth::logout();
        return redirect()->route('admin.logon');
    }
}
