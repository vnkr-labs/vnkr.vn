@extends('fe.index')
@section('title', 'Hồ Sơ — ' . auth()->user()->name . ' · VNKR')
@section('meta_description', 'Hồ sơ thành viên cộng đồng VNKR.')

@section('main')
<div class="container" style="padding-top:24px;padding-bottom:48px;max-width:900px;">

    @if(session('success'))
    <div class="vnkr-callout vnkr-callout--success mb-3">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
    </div>
    @endif

    {{-- ===== HERO CARD ===== --}}
    <div class="cat-block mb-4">
        <div class="d-flex align-items-center gap-4 flex-wrap p-2">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 style="width:88px;height:88px;border-radius:50%;object-fit:cover;border:3px solid var(--brand);flex-shrink:0;">
            <div style="flex:1;">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <h1 style="font-size:22px;font-weight:900;color:var(--brand);margin:0;">{{ $user->name }}</h1>
                    <span style="background:{{ $level['color'] }};color:#fff;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;">
                        <i class="bi {{ $level['icon'] }} me-1"></i>{{ $level['label'] }}
                    </span>
                </div>
                @if($user->bio)
                <p style="font-size:14px;color:#555;margin:0 0 8px;">{{ $user->bio }}</p>
                @endif
                <div class="d-flex gap-3 flex-wrap" style="font-size:13px;color:#888;">
                    <span><i class="bi bi-envelope me-1"></i>{{ $user->email }}</span>
                    @if($user->username)
                    <span><i class="bi bi-at me-1"></i>{{ $user->username }}</span>
                    @endif
                    <span><i class="bi bi-calendar me-1"></i>Tham gia {{ $user->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm" style="flex-shrink:0;">
                <i class="bi bi-pencil me-1"></i>Chỉnh sửa hồ sơ
            </a>
        </div>
    </div>

    {{-- ===== STATS ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="fe-stat-block">
                <div class="fe-stat-num">{{ number_format($commentCount) }}</div>
                <div class="fe-stat-label"><i class="bi bi-chat-dots me-1"></i>Bình luận</div>
                @if($commentCount < 20)
                <div class="fe-stat-sub">{{ 20 - $commentCount }} để đạt tích cực</div>
                @else
                <div class="fe-stat-sub fe-stat-sub--green"><i class="bi bi-check-circle me-1"></i>Đủ điều kiện</div>
                @endif
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="fe-stat-block">
                <div class="fe-stat-num fe-stat-num--gold">{{ number_format($bookmarkCount) }}</div>
                <div class="fe-stat-label"><i class="bi bi-bookmark-fill me-1"></i>Bài đã lưu</div>
                <a href="{{ route('bookmarks.index') }}" class="fe-stat-sub fe-stat-sub--brand">Xem tất cả →</a>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="fe-stat-block">
                <div class="fe-stat-num fe-stat-num--green">{{ number_format($articleCount) }}</div>
                <div class="fe-stat-label"><i class="bi bi-pen me-1"></i>Bài đăng</div>
                @if($user->is_author)
                <div class="fe-stat-sub fe-stat-sub--green"><i class="bi bi-check-circle me-1"></i>CTV</div>
                @endif
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="fe-stat-block">
                <div class="fe-stat-num fe-stat-num--muted">{{ number_format($historyCount) }}</div>
                <div class="fe-stat-label"><i class="bi bi-clock-history me-1"></i>Bài đã đọc</div>
                <a href="{{ route('history.index') }}" class="fe-stat-sub fe-stat-sub--brand">Lịch sử →</a>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ===== LỊCH SỬ ĐỌC GẦN ĐÂY ===== --}}
        <div class="col-md-6">
            <div class="cat-block h-100">
                <div class="cat-block-head">
                    <h3><i class="bi bi-clock me-1"></i>VỪA ĐỌC</h3>
                    <a href="{{ route('history.index') }}" class="see-more">Tất cả <i class="bi bi-arrow-right"></i></a>
                </div>
                @forelse($recentHistory as $h)
                @if($h->article)
                <div style="display:flex;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);align-items:center;">
                    @if($h->article->image)
                    <img src="{{ asset('storage/images/'.$h->article->image) }}"
                         style="width:60px;height:42px;object-fit:cover;border-radius:3px;flex-shrink:0;">
                    @endif
                    <div style="flex:1;min-width:0;">
                        <a href="{{ route('detail', $h->article->slug) }}"
                           style="font-size:13px;font-weight:600;color:var(--text);display:block;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                            {{ $h->article->name }}
                        </a>
                        <div style="font-size:11.5px;color:#aaa;">{{ $h->read_at->diffForHumans() }}</div>
                    </div>
                </div>
                @endif
                @empty
                <div class="text-center py-4 text-muted" style="font-size:13.5px;">
                    <i class="bi bi-book d-block mb-2" style="font-size:24px;"></i>Chưa có lịch sử đọc.
                </div>
                @endforelse
            </div>
        </div>

        {{-- ===== BÀI ĐÃ LƯU GẦN ĐÂY ===== --}}
        <div class="col-md-6">
            <div class="cat-block h-100">
                <div class="cat-block-head">
                    <h3><i class="bi bi-bookmark me-1"></i>ĐÃ LƯU GẦN ĐÂY</h3>
                    <a href="{{ route('bookmarks.index') }}" class="see-more">Tất cả <i class="bi bi-arrow-right"></i></a>
                </div>
                @forelse($recentBookmarks as $bm)
                @if($bm->article)
                <div style="display:flex;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);align-items:center;">
                    @if($bm->article->image)
                    <img src="{{ asset('storage/images/'.$bm->article->image) }}"
                         style="width:60px;height:42px;object-fit:cover;border-radius:3px;flex-shrink:0;">
                    @endif
                    <div style="flex:1;min-width:0;">
                        @if($bm->article->category)
                        <span class="cat-badge mb-1" style="font-size:10px;">{{ $bm->article->category->name }}</span>
                        @endif
                        <a href="{{ route('detail', $bm->article->slug) }}"
                           style="font-size:13px;font-weight:600;color:var(--text);display:block;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                            {{ $bm->article->name }}
                        </a>
                    </div>
                </div>
                @endif
                @empty
                <div class="text-center py-4 text-muted" style="font-size:13.5px;">
                    <i class="bi bi-bookmark d-block mb-2" style="font-size:24px;"></i>Chưa lưu bài viết nào.
                </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ===== QUYỀN LỢI CỘNG ĐỒNG ===== --}}
    @if($level['key'] === 'member')
    <div class="cat-block mt-4" style="background:#f9fafb;">
        <div style="font-weight:800;color:#0A3D62;margin-bottom:12px;font-size:15px;">
            <i class="bi bi-award me-2" style="color:#F0A500;"></i>Nâng Cấp Cộng Đồng Của Bạn
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <div style="padding:12px;background:#fff;border-radius:6px;border:1px solid var(--border);text-align:center;">
                    <i class="bi bi-chat-dots-fill" style="font-size:24px;color:#F0A500;"></i>
                    <div style="font-weight:700;font-size:13.5px;margin:6px 0 2px;">Thành viên tích cực</div>
                    <div style="font-size:12px;color:#777;">Bình luận 20 lần có giá trị</div>
                    <div style="font-size:12px;color:var(--brand);font-weight:600;margin-top:4px;">{{ $commentCount }}/20 bình luận</div>
                </div>
            </div>
            <div class="col-md-4">
                <div style="padding:12px;background:#fff;border-radius:6px;border:1px solid var(--border);text-align:center;">
                    <i class="bi bi-pen-fill" style="font-size:24px;color:#218838;"></i>
                    <div style="font-weight:700;font-size:13.5px;margin:6px 0 2px;">Cộng tác viên</div>
                    <div style="font-size:12px;color:#777;">Gửi bài được admin duyệt</div>
                    <a href="{{ route('contact.show') }}" style="font-size:12px;color:var(--brand);font-weight:600;margin-top:4px;display:block;">Liên hệ đăng ký →</a>
                </div>
            </div>
            <div class="col-md-4">
                <div style="padding:12px;background:#fff;border-radius:6px;border:1px solid var(--border);text-align:center;">
                    <i class="bi bi-people-fill" style="font-size:24px;color:#0A3D62;"></i>
                    <div style="font-weight:700;font-size:13.5px;margin:6px 0 2px;">Tất cả đều miễn phí</div>
                    <div style="font-size:12px;color:#777;">Không cần trả phí để tham gia</div>
                    <a href="{{ route('about') }}" style="font-size:12px;color:var(--brand);font-weight:600;margin-top:4px;display:block;">Tìm hiểu thêm →</a>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
