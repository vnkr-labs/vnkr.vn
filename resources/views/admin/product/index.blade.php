@extends('admin.master')

@section('title', 'Danh Sách Bài Viết')

@section('title-page', 'Quản Lý Bài Viết Cộng Đồng')

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
            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        <a href="{{ route('product.create') }}" class="btn btn-success btn-sm"><i class="bi bi-plus me-1"></i>Đăng Bài Mới</a>
                        <a href="{{ route('product.trash') }}" class="btn btn-secondary btn-sm"><i class="bi bi-trash me-1"></i>Thùng Rác</a>
                        {{-- Status filter tabs --}}
                        <a href="{{ route('product.index') }}"
                           class="btn btn-sm {{ !$statusFilter ? 'btn-primary' : 'btn-outline-secondary' }}">Tất cả</a>
                        <a href="{{ route('product.index') }}?status=published"
                           class="btn btn-sm {{ $statusFilter === 'published' ? 'btn-success' : 'btn-outline-success' }}">✅ Published</a>
                        <a href="{{ route('product.index') }}?status=draft"
                           class="btn btn-sm {{ $statusFilter === 'draft' ? 'btn-secondary' : 'btn-outline-secondary' }}">📝 Draft</a>
                        <a href="{{ route('product.index') }}?status=review"
                           class="btn btn-sm {{ $statusFilter === 'review' ? 'btn-info' : 'btn-outline-info' }}">🔍 Review</a>
                        <a href="{{ route('product.index') }}?status=archived"
                           class="btn btn-sm {{ $statusFilter === 'archived' ? 'btn-dark' : 'btn-outline-dark' }}">🗄 Archived</a>
                    </div>
                    <form action="{{ route('product.index') }}" method="GET" class="d-flex gap-1">
                        @if($statusFilter)<input type="hidden" name="status" value="{{ $statusFilter }}">@endif
                        <input type="text" name="search" class="form-control form-control-sm" style="width:180px;"
                               placeholder="Tìm tiêu đề..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
                    </form>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tiêu Đề Bài Viết</th>
                                <th>Tóm Tắt</th>
                                <th>Ảnh</th>
                                <th>Chuyên Mục</th>
                                <th>Ngày Đăng</th>
                                <th>Tùy Chọn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('detail', $item->slug) }}" target="_blank" style="font-weight:600;font-size:13px;">{{ Str::limit($item->name, 60) }}</a>
                                        @if($item->stock)<span class="badge bg-warning text-dark ms-1" style="font-size:10px;">⭐ Nổi bật</span>@endif
                                        @if($item->is_live)<span class="badge bg-danger ms-1" style="font-size:10px;">⬤ LIVE</span>@endif
                                        @php $st = $item->status ?? 'published'; @endphp
                                        @if($st === 'draft')
                                            <span class="badge bg-secondary ms-1" style="font-size:10px;">📝 Draft</span>
                                        @elseif($st === 'review')
                                            <span class="badge bg-info text-dark ms-1" style="font-size:10px;">🔍 Review</span>
                                        @elseif($st === 'archived')
                                            <span class="badge bg-dark ms-1" style="font-size:10px;">🗄 Archived</span>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;color:#666;">{{ Str::limit($item->tomtat, 60) }}</td>
                                    <td><img src="{{ asset('storage/images') }}/{{ $item->image }}" width="80px" style="border-radius:3px;"></td>
                                    <td><span style="font-size:12px;background:#e8f2fa;color:#0A3D62;padding:2px 7px;border-radius:3px;">{{ $item->category->name ?? '—' }}</span></td>
                                    <td style="font-size:12px;color:#888;">{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('product.edit', $item) }}" class="btn btn-sm btn-success"><i class="bi bi-pencil"></i></a>
                                        <a href="{{ route('detail', $item->slug) }}" target="_blank" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                        <form action="{{ route('product.destroy', $item) }}" method="POST" style="display:inline;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xóa bài viết này?')"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">Chưa có bài viết nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
                <!-- /.box-body -->
                {{ $products->links() }}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.box -->
    </section>
@endsection
