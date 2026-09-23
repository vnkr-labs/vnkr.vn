@extends('admin.master')

@section('title', 'Danh Sách Thành Viên Cộng Đồng')

@section('title-page', 'Quản Lý Thành Viên Cộng Đồng')

@section('main-content')
    <section class="container">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ $message }}
            </div>
        @endif

        <div class="col-md-12">

            {{-- Thống kê nhanh --}}
            @php
                $totalUsers    = $users->total() ?? count($users);
                $adminCount    = \App\Models\User::where('role','admin')->count();
                $memberCount   = \App\Models\User::where('role','!=','admin')->count();
                $newThisWeek   = \App\Models\User::where('created_at','>=', now()->subDays(7))->count();
            @endphp
            <div class="row g-2 mb-3">
                <div class="col-sm-3">
                    <div style="background:#f0f7ff;border-radius:6px;padding:12px 16px;text-align:center;border-top:3px solid #0A3D62;">
                        <div style="font-size:22px;font-weight:900;color:#0A3D62;">{{ \App\Models\User::count() }}</div>
                        <div style="font-size:11.5px;color:#555;margin-top:2px;"><i class="bi bi-people me-1"></i>Tổng thành viên</div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div style="background:#fff9e6;border-radius:6px;padding:12px 16px;text-align:center;border-top:3px solid #F0A500;">
                        <div style="font-size:22px;font-weight:900;color:#b8860b;">{{ $adminCount }}</div>
                        <div style="font-size:11.5px;color:#555;margin-top:2px;"><i class="bi bi-shield-fill me-1"></i>Quản trị viên</div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div style="background:#f5fff8;border-radius:6px;padding:12px 16px;text-align:center;border-top:3px solid #27ae60;">
                        <div style="font-size:22px;font-weight:900;color:#218838;">{{ $memberCount }}</div>
                        <div style="font-size:11.5px;color:#555;margin-top:2px;"><i class="bi bi-person me-1"></i>Thành viên</div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div style="background:#fdf8ff;border-radius:6px;padding:12px 16px;text-align:center;border-top:3px solid #6f42c1;">
                        <div style="font-size:22px;font-weight:900;color:#6f42c1;">+{{ $newThisWeek }}</div>
                        <div style="font-size:11.5px;color:#555;margin-top:2px;"><i class="bi bi-person-plus me-1"></i>Gia nhập 7 ngày</div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('user.create') }}" class="btn btn-success"><i class="bi bi-person-plus me-1"></i>Thêm Thành Viên</a>
                    </div>
                    <div class="box-tools">
                        <form action="{{ route('user.index') }}" method="GET">
                            <div class="input-group input-group-sm" style="width:200px;">
                                <input type="text" name="search" class="form-control" placeholder="Tìm thành viên..." value="{{ request()->search }}">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Thành Viên</th>
                                <th>Email</th>
                                <th>Cấp Độ</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Tham Gia</th>
                                <th>Tùy Chọn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong style="font-size:13px;">{{ $user->name }}</strong>
                                        @if($user->role === 'admin')
                                            <span style="display:inline-block;background:#0A3D62;color:#fff;font-size:10px;padding:1px 6px;border-radius:3px;margin-left:4px;font-weight:700;">Admin</span>
                                        @endif
                                    </td>
                                    <td style="font-size:13px;color:#555;">{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span style="background:#e8f2fa;color:#0A3D62;font-size:11.5px;padding:2px 8px;border-radius:3px;font-weight:700;"><i class="bi bi-shield-fill me-1"></i>Quản trị</span>
                                        @elseif($user->is_author)
                                            <span style="background:#d4edda;color:#218838;font-size:11.5px;padding:2px 8px;border-radius:3px;font-weight:700;"><i class="bi bi-pen me-1"></i>CTV</span>
                                        @else
                                            <span style="background:#f4f6f8;color:#555;font-size:11.5px;padding:2px 8px;border-radius:3px;"><i class="bi bi-person me-1"></i>Thành viên</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($user->status))
                                            {!! $user->status == 1
                                                ? '<span style="background:#d4edda;color:#218838;font-size:11.5px;padding:2px 8px;border-radius:3px;">Hoạt động</span>'
                                                : '<span style="background:#f8d7da;color:#721c24;font-size:11.5px;padding:2px 8px;border-radius:3px;">Tạm khóa</span>' !!}
                                        @else
                                            <span style="background:#d4edda;color:#218838;font-size:11.5px;padding:2px 8px;border-radius:3px;">Hoạt động</span>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;color:#888;">{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('user.show', $user) }}" class="btn btn-sm btn-warning"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('user.edit', $user) }}" class="btn btn-sm btn-success"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('user.destroy', $user) }}" method="POST" style="display:inline;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xóa thành viên này?')"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">Chưa có thành viên nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($users, 'links'))
                <div class="box-footer">{{ $users->links() }}</div>
                @endif
            </div>
        </div>
    </section>
@endsection
