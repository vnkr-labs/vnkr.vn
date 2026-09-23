@extends('admin.master')

@section('title', 'Kiểm Duyệt Bình Luận')
@section('title-page', 'Kiểm Duyệt Bình Luận Cộng Đồng')

@section('main-content')
<section class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats bar --}}
    <div class="alert d-flex align-items-center justify-content-between"
         style="background:#fff8e1;border:1px solid #ffe082;border-left:4px solid #F0A500;border-radius:4px;padding:12px 16px;">
        <div>
            <i class="bi bi-chat-dots me-2" style="color:#F0A500;font-size:16px;"></i>
            <strong>{{ $totalPending }}</strong> bình luận đang chờ kiểm duyệt
        </div>
        @if($totalPending > 0)
        <form method="POST" action="{{ route('admin.comment.approveAll') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-success"
                    onclick="return confirm('Duyệt tất cả {{ $totalPending }} bình luận?')">
                <i class="bi bi-check-all me-1"></i>Duyệt tất cả
            </button>
        </form>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">
                <i class="bi bi-shield-check me-2" style="color:#0A3D62;"></i>Hàng Đợi Kiểm Duyệt
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:140px;">Thành viên</th>
                            <th>Nội dung</th>
                            <th style="width:160px;">Bài viết</th>
                            <th style="width:100px;">Gửi lúc</th>
                            <th class="text-end" style="width:130px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($pending as $cmt)
                    <tr>
                        <td style="vertical-align:middle;">
                            <div style="font-weight:700;font-size:13px;">{{ $cmt->user->name ?? 'Ẩn danh' }}</div>
                            <div style="font-size:11px;color:#888;">{{ $cmt->user->email ?? '' }}</div>
                            @if($cmt->parent_id)
                                <span class="badge bg-secondary" style="font-size:10px;">Reply</span>
                            @endif
                        </td>
                        <td style="vertical-align:middle;">
                            <div style="font-size:13.5px;color:#333;max-width:340px;word-break:break-word;">
                                {{ $cmt->content }}
                            </div>
                        </td>
                        <td style="vertical-align:middle;">
                            @if($cmt->article)
                            <a href="{{ route('detail', $cmt->article->slug) }}" target="_blank"
                               style="font-size:12px;color:#0A3D62;">
                                <i class="bi bi-box-arrow-up-right me-1"></i>{{ Str::limit($cmt->article->name, 40) }}
                            </a>
                            @else
                            <span class="text-muted" style="font-size:12px;">—</span>
                            @endif
                        </td>
                        <td style="vertical-align:middle;font-size:12px;color:#888;">
                            {{ $cmt->created_at->format('d/m H:i') }}
                        </td>
                        <td class="text-end" style="vertical-align:middle;">
                            {{-- Duyệt --}}
                            <form method="POST" action="{{ route('admin.comment.approve', $cmt->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" title="Duyệt">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            {{-- Xóa --}}
                            <form method="POST" action="{{ route('admin.comment.destroy', $cmt->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa"
                                        onclick="return confirm('Xóa bình luận này?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-check-circle-fill me-2" style="color:#27ae60;font-size:24px;"></i><br>
                            <span style="font-size:15px;">Không có bình luận nào đang chờ kiểm duyệt.</span>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pending->hasPages())
        <div class="card-footer">
            {{ $pending->links() }}
        </div>
        @endif
    </div>

</section>
@endsection
