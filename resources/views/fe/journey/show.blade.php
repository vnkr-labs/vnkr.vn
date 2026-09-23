@extends('fe.index')
@section('title', $journey->title . ' — Lộ Trình VNKR')
@section('meta_description', Str::limit($journey->description ?? 'Lộ trình đọc có hướng dẫn từ cộng đồng VNKR.', 160))
@section('canonical', url('/journey/' . $journey->slug))

@section('main')
<div class="container" style="padding-top:20px;max-width:860px;">

  {{-- Journey Hero --}}
  <div style="border-radius:8px;overflow:hidden;margin-bottom:24px;
              background:linear-gradient(135deg,#f0f7ff 0%,#f5f0ff 100%);
              border:1px solid #e5e7eb;padding:24px 28px;">
    <div class="d-flex align-items-start gap-4 flex-wrap">
      <div style="width:64px;height:64px;border-radius:50%;background:{{ $journey->color ?? '#3b82d4' }};
                  display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="bi {{ $journey->icon ?? 'bi-map' }}" style="color:#fff;font-size:26px;"></i>
      </div>
      <div style="flex:1;min-width:200px;">
        <div style="font-size:11px;font-weight:700;letter-spacing:.08em;color:#888;text-transform:uppercase;margin-bottom:4px;">
          <i class="bi bi-map me-1"></i>LỘ TRÌNH ĐỌC
        </div>
        <h1 style="font-size:24px;font-weight:900;color:#1f2328;margin:0 0 8px;">{{ $journey->title }}</h1>
        @if($journey->description)
        <p style="font-size:14.5px;color:#57606a;margin:0 0 12px;">{{ $journey->description }}</p>
        @endif
        <div class="d-flex align-items-center gap-3 flex-wrap" style="font-size:13px;color:#57606a;">
          <span><i class="bi bi-collection me-1" style="color:{{ $journey->color ?? '#3b82d4' }};"></i>{{ $journey->articles->count() }} bài viết</span>
          @if($journey->estimated_minutes)
          <span><i class="bi bi-hourglass-split me-1" style="color:{{ $journey->color ?? '#3b82d4' }};"></i>~{{ $journey->estimated_minutes }} phút</span>
          @endif
          @if($journey->category)
          <span><i class="bi bi-grid me-1"></i>{{ $journey->category->name }}</span>
          @endif
          @if($journey->creator)
          <span><i class="bi bi-person me-1"></i>{{ $journey->creator->name }}</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Progress indicator --}}
  <div style="font-size:13px;color:#57606a;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;">
    <span><strong>{{ $journey->articles->count() }}</strong> bài trong lộ trình này</span>
    <a href="{{ route('index') }}" style="font-size:13px;color:#3b82d4;text-decoration:none;">
      <i class="bi bi-arrow-left me-1"></i>Trang chủ
    </a>
  </div>

  {{-- Article list --}}
  <div style="display:flex;flex-direction:column;gap:12px;">
    @foreach($journey->articles as $index => $article)
    @php $num = $index + 1; @endphp
    <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#fff;
                display:flex;align-items:stretch;transition:box-shadow .15s;"
         onmouseover="this.style.boxShadow='0 2px 12px rgba(0,0,0,.08)'"
         onmouseout="this.style.boxShadow=''">

      {{-- Number badge --}}
      <div style="width:54px;flex-shrink:0;background:{{ $journey->color ?? '#3b82d4' }};display:flex;align-items:center;justify-content:center;">
        <span style="color:#fff;font-size:20px;font-weight:900;">{{ $num }}</span>
      </div>

      {{-- Content --}}
      <div style="flex:1;padding:14px 18px;display:flex;align-items:center;gap:14px;">
        @if($article->image)
        <img src="{{ asset('storage/images/' . $article->image) }}" alt=""
             style="width:70px;height:52px;object-fit:cover;border-radius:4px;flex-shrink:0;">
        @endif
        <div style="flex:1;min-width:0;">
          <a href="{{ route('detail', $article->slug) }}"
             style="font-size:15px;font-weight:700;color:#1f2328;text-decoration:none;display:block;
                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"
             title="{{ $article->name }}">
            {{ $article->name }}
          </a>
          @if($article->tomtat)
          <p style="font-size:13px;color:#57606a;margin:3px 0 0;
                    display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
            {{ $article->tomtat }}
          </p>
          @endif
          <div style="font-size:12px;color:#aaa;margin-top:5px;display:flex;gap:12px;">
            @if($article->category)
            <span><i class="bi bi-grid me-1"></i>{{ $article->category->name }}</span>
            @endif
            @if($article->reading_time)
            <span><i class="bi bi-hourglass-split me-1"></i>{{ $article->reading_time }} phút</span>
            @endif
            <span><i class="bi bi-eye me-1"></i>{{ number_format($article->view_count) }} lượt xem</span>
          </div>
        </div>
        <a href="{{ route('detail', $article->slug) }}"
           style="flex-shrink:0;padding:7px 16px;background:{{ $journey->color ?? '#3b82d4' }};color:#fff;
                  border-radius:5px;font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap;">
          Đọc →
        </a>
      </div>

      @if($article->pivot->note)
      <div style="width:100%;padding:6px 18px 10px 72px;font-size:12.5px;color:#666;font-style:italic;">
        💡 {{ $article->pivot->note }}
      </div>
      @endif
    </div>
    @endforeach

    @if($journey->articles->isEmpty())
    <div style="text-align:center;padding:40px;color:#aaa;border:1px dashed #ddd;border-radius:8px;">
      <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px;"></i>
      Lộ trình này chưa có bài viết.
    </div>
    @endif
  </div>

  {{-- Start journey CTA --}}
  @if($journey->articles->isNotEmpty())
  <div style="margin-top:28px;text-align:center;padding:24px;background:#f7f8fa;border-radius:8px;border:1px solid #e5e7eb;">
    <p style="font-size:15px;font-weight:700;color:#1f2328;margin:0 0 12px;">
      <i class="bi bi-rocket-takeoff me-2" style="color:{{ $journey->color ?? '#3b82d4' }};"></i>
      Bắt đầu lộ trình ngay?
    </p>
    <a href="{{ route('detail', $journey->articles->first()->slug) }}"
       style="display:inline-block;padding:10px 32px;background:{{ $journey->color ?? '#3b82d4' }};
              color:#fff;border-radius:6px;font-size:15px;font-weight:800;text-decoration:none;">
      Đọc bài đầu tiên →
    </a>
  </div>
  @endif

</div>
@endsection
