<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login()
    {
        return view("fe.login");
    }
    public function register()
    {
        return view("fe.register");
    }
    public function postRegister(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|max:255|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        try {
            User::create([
                'name'      => $request->input('name'),
                'email'     => $request->input('email'),
                'password'  => Hash::make($request->input('password')),
                'role'      => 'user',   // never trust client input for role
                'status'    => 1,
                'is_author' => false,
            ]);
            return redirect()->route('login')->with('success', 'Đăng ký thành công! Hãy đăng nhập để tham gia cộng đồng.');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors('Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại.');
        }
    }
    public function postLogin(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('index');
        } else {
            return redirect()->back()->with('error', 'Bạn Đã Nhập Sai Mật Khẩu!');
        }
    }
    public function logout(Request $request)
    {

        Auth::logout();
        return redirect()->route('index');
    }


    public function index(Request $request)
    {
        $query = User::query();


        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $users = $query->get();

        return view('admin.user.index', compact('users'));
    }




    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'nullable|string|in:user,admin',
            'status'   => 'nullable|boolean',
        ]);

        $user = new User();
        $user->name      = $request->name;
        $user->email     = $request->email;
        $user->password  = Hash::make($request->password);
        $user->role      = $request->input('role', 'user');
        $user->status    = $request->input('status', 1);
        $user->is_author = $request->boolean('is_author');
        $user->save();

        return redirect()->route('user.index')->with('success', 'Thành viên đã được thêm mới.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role'   => 'nullable|string|in:user,admin',
            'status' => 'nullable|boolean',
        ]);

        $user->name      = $request->name;
        $user->email     = $request->email;
        $user->role      = $request->input('role', 'user');
        $user->status    = $request->input('status', 1);
        $user->is_author = $request->boolean('is_author');

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Thành viên đã được cập nhật.');
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('user.index')->with('success', 'Xóa Thành Công');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Xóa Thất Bại');
        }
    }





    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    /**
     * Thay đổi vai trò admin/user nhanh cho thành viên.
     */
    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|in:user,admin',
        ]);

        // Không cho tự xóa quyền admin của chính mình
        if ($user->id === auth()->id() && $request->input('role') !== 'admin') {
            return redirect()->back()->with('error', 'Không thể tự xóa quyền quản trị của chính mình.');
        }

        $user->role = $request->input('role');
        $user->save();

        return redirect()->route('user.index')->with('success', 'Cấp độ thành viên đã được cập nhật.');
    }
}
