@extends('admin.master')

@section('title', 'Thùng Rác — Bài Viết')

@section('title-page', 'Thùng Rác Bài Viết')

@section('main-content')
<section class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><i class="bi bi-trash me-2"></i>Bài Viết Đã Xóa</h3>
            <a href="{{ route('product.index') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Quay lại
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tiêu Đề</th>
                            <th>Chuyên Mục</th>
                            <th>Xóa lúc</th>
                            <th class="text-end">Tùy chọn</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-size:13.5px;font-weight:600;">{{ Str::limit($item->name, 60) }}</td>
                            <td>
                                <span class="badge" style="background:#e8f2fa;color:#0A3D62;font-weight:500;">
                                    {{ $item->category->name ?? '—' }}
                                </span>
                            </td>
                            <td style="font-size:12px;color:#888;">{{ $item->deleted_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('product.restore', $item->id) }}"
                                   onclick="return confirm('Khôi phục bài viết này?')"
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Khôi phục
                                </a>
                                <a href="{{ route('product.forceDelete', $item->id) }}"
                                   onclick="return confirm('Xóa vĩnh viễn? Hành động này không thể hoàn tác!')"
                                   class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash me-1"></i>Xóa hẳn
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted" style="font-size:14px;">
                                <i class="bi bi-check-circle me-2"></i>Thùng rác trống.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
@endsection
