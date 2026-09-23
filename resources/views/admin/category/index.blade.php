@extends('admin.master')

@section('title', 'Quản Lý Chuyên Mục')

@section('title-page', 'Quản Lý Chuyên Mục')

@section('main-content')
    <section class="container">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ $message }}
            </div>
        @endif

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('category.create') }}" class="btn btn-success">
                            <i class="bi bi-plus me-1"></i>Thêm Chuyên Mục Mới
                        </a>
                        <a href="{{ route('category.trash') }}" class="btn btn-secondary">
                            <i class="bi bi-trash me-1"></i>Thùng Rác
                        </a>
                    </div>
                    <div class="box-tools">
                        <form action="{{ route('category.index') }}" method="GET">
                            <div class="input-group input-group-sm" style="width:180px;">
                                <input type="text" name="search" class="form-control" placeholder="Tìm chuyên mục..." value="{{ request()->search }}">
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
                                <th>Tên Chuyên Mục</th>
                                <th>Slug</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Tùy chọn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong style="font-size:13px;">{{ $item->name }}</strong></td>
                                    <td style="font-size:12px;color:#777;">{{ $item->slug }}</td>
                                    <td>
                                        {!! $item->status
                                            ? '<span style="background:#d4edda;color:#218838;font-size:11.5px;padding:2px 8px;border-radius:3px;">Hiển thị</span>'
                                            : '<span style="background:#f8d7da;color:#721c24;font-size:11.5px;padding:2px 8px;border-radius:3px;">Ẩn</span>' !!}
                                    </td>
                                    <td style="font-size:12px;color:#888;">{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('category.edit', $item) }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('category.destroy', $item) }}" method="POST" style="display:inline;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Xóa chuyên mục này?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Chưa có chuyên mục nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
