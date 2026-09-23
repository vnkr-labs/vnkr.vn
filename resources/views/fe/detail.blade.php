@extends('fe.index')
@section('title', ($product->name ?? 'Chi Tiết') . ' — VNKR')
@section('meta_description', Str::limit(strip_tags($product->tomtat ?? ''), 160))
@section('og_type', 'article')
@section('og_image', $product->image ? asset('storage/images/'.$product->image) : asset('client/images/favicon.png'))
@section('canonical', route('detail', $product->slug))

@push('jsonld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "{{ addslashes($product->name ?? '') }}",
  "description": "{{ addslashes(Str::limit($product->tomtat ?? '', 160)) }}",
  "image": "{{ asset('storage/images/'.($product->image ?? 'news-placeholder.jpg')) }}",
  "datePublished": "{{ optional($product->created_at)->toIso8601String() }}",
  "dateModified": "{{ optional($product->updated_at)->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": "Phạm Thế Bảo",
    "url": "https://vnkr.vn/about"
  },
  "publisher": {
    "@type": "Organization",
    "name": "VNKR",
    "url": "https://vnkr.vn",
    "logo": {
      "@type": "ImageObject",
      "url": "https://vnkr.vn/client/images/favicon.png"
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ url()->current() }}"
  }
}
</script>
@endpush

@section('main')
@php
// Data passed from controller — no inline queries needed
@endphp

<div class="container" style="padding-top:18px;">
  <div class="main-wrapper">

    {{-- ===== ARTICLE ===== --}}
    <div>
      {{-- Breadcrumb --}}
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:13px;background:none;padding:0;margin:0;">
          <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
          @if($product->category)
          <li class="breadcrumb-item">
            <a href="{{ $product->category->slug ? route('category.slug', $product->category->slug) : route('result', $product->category_id) }}">{{ $product->category->name }}</a>
          </li>
          @endif
          <li class="breadcrumb-item active text-muted" style="font-size:12.5px;">{{ Str::limit($product->name, 45) }}</li>
        </ol>
      </nav>

      <article class="article-detail">
        {{-- Category badge --}}
        @if($product->category)
          <a href="{{ $product->category->slug ? route('category.slug', $product->category->slug) : route('result', $product->category_id) }}" class="cat-badge mb-2 d-inline-block">{{ $product->category->name }}</a>
        @endif

        {{-- Title --}}
        <h1>{{ $product->name }}</h1>

        {{-- Meta --}}
        <div class="article-meta">
          <span>
            <div class="ptb-avatar d-inline-flex me-1" style="width:28px;height:28px;font-size:12px;vertical-align:middle;">P</div>
            <strong style="color:var(--brand);">Phạm Thế Bảo</strong>
            <span class="source-badge ms-2"><i class="bi bi-pencil-fill me-1"></i>Biên tập</span>
          </span>
          <span><i class="bi bi-clock me-1 brand-color"></i>{{ \Carbon\Carbon::parse($product->created_at)->format('H:i, d/m/Y') }}</span>
          @if($product->reading_time)
          <span><i class="bi bi-hourglass-split me-1 brand-color"></i>{{ $product->reading_time }} phút đọc</span>
          @endif
          <span><i class="bi bi-chat-dots me-1 brand-color"></i>{{ $comments->count() }} bình luận</span>
          <span><i class="bi bi-eye me-1 brand-color"></i>{{ number_format($product->view_count) }} lượt xem</span>
          <span><i class="bi bi-globe me-1"></i><a href="https://vnkr.vn" style="color:var(--brand);font-weight:600;">VNKR.VN</a></span>
        </div>

        {{-- Share + Bookmark --}}
        <div class="share-bar" style="flex-wrap:wrap;gap:6px;">
          <span style="font-size:13px;color:#666;font-weight:700;">Chia sẻ:</span>
          <button class="btn-share fb" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(window.location.href))"><i class="bi bi-facebook"></i> Facebook</button>
          <button class="btn-share zalo" onclick="window.open('https://zalo.me/share/link?url='+encodeURIComponent(window.location.href))">Zalo</button>
          <button class="btn-share tw" onclick="window.open('https://twitter.com/intent/tweet?url='+encodeURIComponent(window.location.href)+'&text='+encodeURIComponent(document.title))"><i class="bi bi-twitter-x"></i></button>
          <button class="btn-share copy" onclick="navigator.clipboard.writeText(window.location.href);if(window.VNKRUI)VNKRUI.toast('Đã sao chép link bài viết!','success');else this.textContent='✓ Đã sao chép!'"><i class="bi bi-link-45deg"></i> Sao chép</button>
          {{-- Bookmark button --}}
          @auth
          <form method="POST" action="{{ route('bookmark.toggle', $product->id) }}" style="display:inline;">
            @csrf
            <button type="submit" class="vnkr-btn vnkr-btn--sm {{ $isBookmarked ? 'vnkr-btn--brand' : 'vnkr-btn--ghost' }}">
              <i class="bi bi-bookmark{{ $isBookmarked ? '-fill' : '' }}"></i>
              {{ $isBookmarked ? ' Đã lưu' : ' Lưu bài' }}
            </button>
          </form>
          @endauth
        </div>

        {{-- Image --}}
        @if($product->image)
        <figure style="margin:0 -24px 16px;">
          <img src="{{ asset('storage/images/'.$product->image) }}" alt="{{ $product->name }}"
               loading="eager" decoding="async"
               style="width:100%;max-height:460px;object-fit:cover;">
          <figcaption class="text-center text-muted mt-1" style="font-size:12px;font-style:italic;">
            Ảnh minh họa — Nguồn: VNKR / Tổng hợp
          </figcaption>
        </figure>
        @endif

        {{-- Gallery ảnh phụ (lightbox) --}}
        @if($product->galleryImages->count() > 0)
        <div class="gallery-grid" id="gallery-{{ $product->id }}">
          @foreach($product->galleryImages as $gi)
          <a href="{{ asset('storage/images/'.$gi->image) }}"
             class="gallery-item"
             data-lightbox="article-{{ $product->id }}"
             data-title="{{ $product->name }}">
            <img src="{{ asset('storage/images/'.$gi->image) }}"
                 alt="Ảnh {{ $loop->iteration }}"
                 loading="lazy" decoding="async">
          </a>
          @endforeach
        </div>
        @endif

        {{-- Lead --}}
        @if($product->tomtat)
        <div class="article-lead">{{ $product->tomtat }}</div>
        @endif

        {{-- Body — toàn bộ nội dung mở cho mọi người đọc --}}
        @php
        // Inject {{reusable:slug}} snippets vào nội dung (github/docs Reusables pattern)
        $bodyHtml        = \App\Models\Reusable::render($product->description ?? '');
        $paragraphs      = preg_split('/(<\/p>)/i', $bodyHtml, -1, PREG_SPLIT_DELIM_CAPTURE);
        $adSlotInArticle = \App\Models\AdSlot::getActive('in_article');
        $adInserted      = false;
        @endphp

        <div class="article-body">
          @if($adSlotInArticle && $adSlotInArticle->code)
            @php
            $output = '';
            $pCount = 0;
            foreach ($paragraphs as $chunk) {
                $output .= $chunk;
                if (str_ends_with(strtolower($chunk), '</p>')) {
                    $pCount++;
                    if ($pCount === 3 && !$adInserted) {
                        $output .= '<div class="ad-slot" style="text-align:center;margin:16px 0;">'.$adSlotInArticle->code.'</div>';
                        $adInserted = true;
                    }
                }
            }
            echo $output;
            @endphp
          @else
            {!! $bodyHtml !!}
          @endif
        </div>

        {{-- CTA Tham gia cộng đồng — hiện với người chưa đăng nhập --}}
        @guest
        <div class="vnkr-callout vnkr-callout--info" style="text-align:center;margin:20px 0;padding:22px 24px;">
          <div style="font-size:18px;font-weight:900;color:var(--brand);margin-bottom:8px;">
            <i class="bi bi-people-fill me-2"></i>Tham Gia Cộng Đồng VNKR
          </div>
          <p style="font-size:14px;margin:0 0 14px;line-height:1.7;">
            Đăng ký tài khoản để bình luận, lưu bài yêu thích và trở thành thành viên cộng đồng
            <strong>TheKingBao</strong>. <strong>Hoàn toàn miễn phí — mãi mãi.</strong>
          </p>
          <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('register') }}" class="vnkr-btn vnkr-btn--primary vnkr-btn--sm">
              <i class="bi bi-person-plus me-1"></i>Đăng ký miễn phí
            </a>
            <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" class="vnkr-btn vnkr-btn--ghost vnkr-btn--sm">
              <i class="bi bi-person-circle me-1"></i>Đăng nhập
            </a>
          </div>
          <p style="font-size:12px;margin:10px 0 0;opacity:.8;">
            <i class="bi bi-gift me-1"></i>Đóng góp tích cực → Nhận badge & quyền lợi cộng đồng
          </p>
        </div>
        @endguest

        {{-- Live Blog section --}}
        @if($product->is_live)
        <div id="live-blog-section" class="mt-4"
             data-article-id="{{ $product->id }}"
             data-poll-url="{{ route('live.poll', $product->id) }}">
          <div style="background:var(--accent);color:#fff;padding:8px 14px;border-radius:6px 6px 0 0;display:flex;align-items:center;gap:8px;">
            <span style="font-size:12px;font-weight:900;letter-spacing:.5px;animation:blink-live 1s infinite;display:inline-block;">⬤ LIVE</span>
            <span style="font-size:14px;font-weight:800;">Tường Thuật Trực Tiếp</span>
            <span id="live-last-updated" style="margin-left:auto;font-size:11.5px;opacity:.85;"></span>
          </div>
          <div id="live-feed" style="border:2px solid var(--accent);border-top:none;border-radius:0 0 6px 6px;overflow:hidden;">
            @foreach($product->liveUpdates as $lu)
            <div class="live-item {{ $lu->is_pinned ? 'pinned' : '' }}" data-id="{{ $lu->id }}">
              @if($lu->is_pinned)<span class="live-pin"><i class="bi bi-pin-fill me-1"></i>Tin ghim</span>@endif
              <div class="live-time"><i class="bi bi-clock me-1"></i>{{ $lu->posted_at->format('H:i:s, d/m') }}</div>
              <div class="live-content">{{ $lu->content }}</div>
            </div>
            @endforeach
            @if($product->liveUpdates->isEmpty())
            <div id="live-empty" class="text-center py-3 text-muted" style="font-size:13.5px;">
              <i class="bi bi-hourglass me-1"></i>Đang chờ cập nhật...
            </div>
            @endif
          </div>
        </div>
        @endif

        {{-- Video embed --}}
        @if($product->video_embed_url)
        <div class="mt-4 mb-2" style="position:relative;padding-top:56.25%;border-radius:6px;overflow:hidden;">
          <iframe
            src="{{ $product->video_embed_url }}"
            style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;"
            allowfullscreen
            loading="lazy"
            title="{{ $product->name }}">
          </iframe>
        </div>
        @endif

        {{-- SOURCE CITATION BOX — Bắt buộc theo quy tắc pháp lý --}}
        <div class="vnkr-callout vnkr-callout--info mt-4" style="font-size:13px;">
          <i class="bi bi-link-45deg me-1"></i>
          <strong>Nguồn tham khảo:</strong> Bài viết được biên tập & tổng hợp từ các nguồn tin tức công khai.
          Mọi thông tin chỉ mang tính tham khảo.
          <a href="/terms" style="margin-left:6px;font-weight:600;">Xem điều khoản →</a>
        </div>

        {{-- Editor credit --}}
        <div class="editor-credit">
          <div class="ptb-avatar" style="font-size:14px;">P</div>
          <div>
            <strong>Biên tập: Phạm Thế Bảo</strong> /
            <a href="https://vnkr.vn" style="color:var(--brand);font-weight:700;">VNKR.VN</a>
            <span class="text-muted ms-2" style="font-size:12px;">| Cộng đồng TheKingBao</span>
          </div>
          <a href="{{ route('search') }}?s=goc-nhin" class="ms-auto cat-badge gold" style="font-size:11px;white-space:nowrap;">
            <i class="bi bi-star-fill me-1"></i>Góc Nhìn PTB
          </a>
        </div>

        {{-- Tags --}}
        <div class="divider"></div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <span style="font-size:13px;font-weight:700;color:#555;"><i class="bi bi-tags me-1 brand-color"></i>Tags:</span>
          @if($product->category)
            <a href="{{ $product->category->slug ? route('category.slug', $product->category->slug) : route('result', $product->category_id) }}" class="vnkr-tag">{{ $product->category->name }}</a>
          @endif
          @foreach($product->tags as $tag)
            <a href="{{ route('search') }}?s={{ urlencode($tag->name) }}" class="vnkr-tag">{{ $tag->name }}</a>
          @endforeach
          <span class="vnkr-tag">VNKR</span>
          <span class="vnkr-tag vnkr-tag--gold">👑 TheKingBao</span>
        </div>

        {{-- Bottom share --}}
        <div class="share-bar mt-3">
          <button class="btn-share fb" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(window.location.href))"><i class="bi bi-facebook"></i> Chia sẻ</button>
          <button class="btn-share copy" onclick="navigator.clipboard.writeText(window.location.href)"><i class="bi bi-link-45deg"></i> Copy</button>
        </div>
      </article>

      {{-- Related --}}
      @if($related->count() > 0)
      <div class="cat-block mt-3">
        <div class="cat-block-head"><h3><i class="bi bi-collection me-1"></i>TIN LIÊN QUAN</h3></div>
        <div class="related-grid">
          @foreach($related as $rel)
          <a href="{{ route('detail', $rel->slug) }}" class="related-card d-block">
            <img src="{{ asset('storage/images/'.$rel->image) }}" alt="{{ $rel->name }}" loading="lazy" decoding="async">
            <div class="info">
              <h5>{{ $rel->name }}</h5>
              <div class="meta mt-1" style="font-size:11.5px;color:#888;">
                <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($rel->created_at)->diffForHumans() }}
              </div>
            </div>
          </a>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Comments --}}
      <div class="cat-block mt-3 comment-section">
        <div class="cat-block-head">
          <h3><i class="bi bi-chat-dots me-1"></i>BÌNH LUẬN CỘNG ĐỒNG ({{ $comments->count() }})</h3>
        </div>

        @forelse($comments as $comment)
        <div class="comment-item">
          <div class="comment-avatar"><img src="{{ asset('client/images/author.jpg') }}" alt="avatar"></div>
          <div class="comment-body flex-1" style="flex:1;">
            <div>
              <span class="author">{{ $comment->user->name ?? 'Ẩn danh' }}</span>
              @if($comment->user && $comment->user->role === 'admin')
                <i class="bi bi-patch-check-fill text-primary ms-1" title="Biên tập viên VNKR"></i>
              @endif
              <span class="time">{{ $comment->created_at->format('H:i, d/m/Y') }}</span>
            </div>
            <p class="text mb-1">{{ $comment->content }}</p>
            <div class="comment-actions">
              <form method="POST" action="{{ route('detail.comment.like',['slug'=>$slug,'commentId'=>$comment->id]) }}" style="display:inline;">
                @csrf
                <button type="submit"><i class="bi bi-hand-thumbs-up"></i> {{ $comment->likes }}</button>
              </form>
              @auth
              <a href="javascript:void(0);" onclick="toggleReply({{ $comment->id }})"><i class="bi bi-reply"></i> Phản hồi</a>
              @if(Auth::user()->role === 'admin' || $comment->user_id === Auth::id())
              <form method="POST" action="{{ route('detail.comment.delete',['slug'=>$slug,'commentId'=>$comment->id]) }}" style="display:inline;" onsubmit="return confirm('Xóa bình luận này?')">
                @csrf @method('DELETE')
                <button type="submit" class="vnkr-input-action" style="color:#c0392b;"><i class="bi bi-trash3"></i> Xóa</button>
              </form>
              @endif
              @endauth
            </div>
            @auth
            <div id="reply-form-{{ $comment->id }}" class="mt-2" style="display:none;">
              <form method="POST" action="{{ route('detail.comment.reply',['slug'=>$slug,'commentId'=>$comment->id]) }}">
                @csrf
                <div class="d-flex gap-2">
                  <div class="vnkr-field vnkr-field--textarea" style="flex:1;">
                    <div class="vnkr-input-wrap">
                      <textarea name="reply" class="vnkr-input" rows="2" placeholder="Phản hồi..."></textarea>
                    </div>
                  </div>
                  <button type="submit" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm" style="align-self:flex-end;white-space:nowrap;">Gửi</button>
                </div>
              </form>
            </div>
            @endauth
            @if($comment->replies->count())
            <div class="replies">
              @foreach($comment->replies as $reply)
              <div class="reply-item">
                <img src="{{ asset('client/images/author.jpg') }}" alt="avatar">
                <div>
                  <span style="font-size:13px;font-weight:700;color:var(--brand);">{{ $reply->user->name ?? 'Ẩn danh' }}</span>
                  <span style="font-size:11.5px;color:var(--text-muted);margin-left:6px;">{{ $reply->created_at->format('H:i, d/m/Y') }}</span>
                  <p style="font-size:13.5px;margin:3px 0 0;">{{ $reply->content }}</p>
                </div>
              </div>
              @endforeach
            </div>
            @endif
          </div>
        </div>
        @empty
        <div class="text-center py-4 text-muted" style="font-size:14px;">
          <i class="bi bi-chat-square-dots fs-3 d-block mb-2"></i>
          Chưa có bình luận. Hãy là người đầu tiên của cộng đồng TheKingBao!
        </div>
        @endforelse

        {{-- Comment form --}}
        <div class="mt-4 pt-3 border-top">
          <h5 style="font-size:15px;font-weight:800;margin-bottom:12px;">
            <i class="bi bi-pencil-square me-1 brand-color"></i>Để lại bình luận
          </h5>
          @auth
          <form method="POST" action="{{ route('detail.comment',['slug'=>$slug]) }}">
            @csrf
            @if(session('success'))
            <div class="vnkr-callout vnkr-callout--success" style="font-size:13px;margin-bottom:12px;">
              <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="vnkr-callout vnkr-callout--danger" style="font-size:13px;margin-bottom:12px;">
              <i class="bi bi-exclamation-triangle me-1"></i>{{ $errors->first() }}
            </div>
            @endif
            <div class="d-flex gap-2 mb-2">
              <img src="{{ asset('client/images/author.jpg') }}" class="rounded-circle" width="38" height="38" alt="" style="flex-shrink:0;">
              <div class="vnkr-field vnkr-field--textarea" style="flex:1;">
                <div class="vnkr-input-wrap">
                  <textarea class="vnkr-input" name="comment" rows="3" required
                    placeholder="Chia sẻ góc nhìn của bạn về bài viết này... (bình luận lành mạnh, xây dựng)">{{ old('comment') }}</textarea>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <small class="text-muted" style="font-size:12px;"><i class="bi bi-shield-check me-1"></i>Bình luận được kiểm duyệt bởi VNKR</small>
              <button type="submit" class="vnkr-btn vnkr-btn--primary vnkr-btn--sm">
                <i class="bi bi-send me-1"></i>Gửi bình luận
              </button>
            </div>
          </form>
          @else
          <div class="vnkr-callout vnkr-callout--info" style="font-size:14px;">
            <i class="bi bi-lock me-1"></i>
            Bạn cần <a href="{{ route('login') }}" class="fw-bold">đăng nhập</a>
            để bình luận cùng cộng đồng TheKingBao.
          </div>
          @endauth
        </div>
      </div>
    </div>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
      {{-- PTB box --}}
      <div class="ptb-section mb-4">
        <div class="d-flex align-items-center gap-2 mb-2">
          <div class="ptb-avatar">P</div>
          <div>
            <div style="font-size:14px;font-weight:800;color:var(--brand);">Phạm Thế Bảo</div>
            <div style="font-size:12px;color:var(--text-muted);">Người sáng lập VNKR</div>
          </div>
        </div>
        <p style="font-size:13px;color:#555;margin:0 0 8px;">
          <strong>VNKR</strong> — nền tảng thông tin <strong style="color:var(--brand);">hoàn toàn miễn phí</strong>,
          do cộng đồng <strong style="color:var(--gold);">TheKingBao</strong> cùng xây dựng.
        </p>
        <div style="font-size:12px;color:#27ae60;font-weight:700;margin-bottom:8px;">
          <i class="bi bi-gift me-1"></i>Không thu phí — Đóng góp → Hưởng lợi
        </div>
        <div class="mt-1 d-flex gap-1 flex-wrap">
          @guest
          <a href="{{ route('register') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm">
            <i class="bi bi-person-plus me-1"></i>Tham gia miễn phí
          </a>
          @endguest
          <a href="{{ route('contact.show') }}" class="vnkr-btn vnkr-btn--ghost vnkr-btn--sm">
            <i class="bi bi-envelope me-1"></i>Liên hệ
          </a>
          <a href="{{ route('search') }}?s=goc-nhin" class="vnkr-btn vnkr-btn--sm" style="background:var(--gold);color:#222;">
            <i class="bi bi-star me-1"></i>Góc Nhìn PTB
          </a>
        </div>
      </div>

      @include('fe.partials.ad_slot', ['position' => 'sidebar_top'])

      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-clock-history me-1"></i>Tin Mới Nhất</div>
        @foreach($latestSide as $i => $ls)
        <div class="most-read-item">
          <div class="rank">{{ $i+1 }}</div>
          <h5><a href="{{ route('detail', $ls->slug) }}">{{ $ls->name }}</a></h5>
        </div>
        @endforeach
      </div>

      @if($moreCat->count() > 0 && $product->category)
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-collection me-1"></i>Cùng Chuyên Mục</div>
        @foreach($moreCat as $mc)
        <div class="news-list-item" style="padding:8px 0;">
          <a href="{{ route('detail', $mc->slug) }}" style="flex-shrink:0;">
            <img src="{{ asset('storage/images/'.$mc->image) }}" loading="lazy" decoding="async" style="width:75px;height:55px;object-fit:cover;border-radius:3px;">
          </a>
          <div class="info">
            <h5 style="font-size:13px;"><a href="{{ route('detail', $mc->slug) }}">{{ $mc->name }}</a></h5>
            <div class="meta" style="font-size:11px;">{{ \Carbon\Carbon::parse($mc->created_at)->diffForHumans() }}</div>
          </div>
        </div>
        @endforeach
        <div class="mt-2">
          <a href="{{ $product->category->slug ? route('category.slug', $product->category->slug) : route('result', $product->category_id) }}" class="vnkr-btn vnkr-btn--ghost vnkr-btn--sm vnkr-btn--full">
            Xem tất cả {{ $product->category->name }} <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
      @endif

      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-graph-up me-1"></i>Thị Trường</div>
        <table class="market-table">
          <tr><th>Loại</th><th>Mua</th><th>Bán</th></tr>
          <tr><td>Vàng SJC</td><td class="up">141,9 tr</td><td class="down">144,9 tr</td></tr>
          <tr><td>USD</td><td class="up">25.130</td><td class="down">25.480</td></tr>
          <tr><td>EUR</td><td class="up">27.400</td><td class="down">28.100</td></tr>
        </table>
        <p class="text-muted mt-2 mb-0" style="font-size:11px;"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}</p>
      </div>
    </aside>
  </div>
</div>

{{-- Lightbox overlay (chỉ render nếu có gallery) --}}
@if($product->galleryImages->count() > 0)
<div id="lb-overlay" role="dialog" aria-modal="true" aria-label="Xem ảnh">
  <button id="lb-close" onclick="lbClose()" title="Đóng">×</button>
  <button id="lb-prev" onclick="lbNav(-1)" title="Ảnh trước">&#8249;</button>
  <img id="lb-img" src="" alt="">
  <button id="lb-next" onclick="lbNav(1)" title="Ảnh tiếp">&#8250;</button>
  <div id="lb-caption"></div>
  <div id="lb-counter"></div>
</div>
@endif

<script>
function toggleReply(index) {
  var el = document.getElementById('reply-form-' + index);
  el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

// ===== Lightbox =====
(function() {
  var items = document.querySelectorAll('.gallery-item');
  if (!items.length) return;
  var overlay = document.getElementById('lb-overlay');
  var img     = document.getElementById('lb-img');
  var caption = document.getElementById('lb-caption');
  var counter = document.getElementById('lb-counter');
  var current = 0;
  var srcs    = Array.from(items).map(function(a){ return a.href; });

  function open(i) {
    current = i;
    img.src = srcs[i];
    counter.textContent = (i + 1) + ' / ' + srcs.length;
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  window.lbClose = function() {
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  };
  window.lbNav = function(dir) {
    current = (current + dir + srcs.length) % srcs.length;
    img.src = srcs[current];
    counter.textContent = (current + 1) + ' / ' + srcs.length;
  };

  items.forEach(function(a, i) {
    a.addEventListener('click', function(e) { e.preventDefault(); open(i); });
  });
  overlay.addEventListener('click', function(e) { if (e.target === overlay) lbClose(); });
  document.addEventListener('keydown', function(e) {
    if (!overlay.classList.contains('open')) return;
    if (e.key === 'Escape') lbClose();
    if (e.key === 'ArrowLeft')  lbNav(-1);
    if (e.key === 'ArrowRight') lbNav(1);
  });
})();

// ===== Live Blog Polling =====
(function() {
  var section = document.getElementById('live-blog-section');
  if (!section) return;

  var pollUrl  = section.dataset.pollUrl;
  var feed     = document.getElementById('live-feed');
  var lastTs   = Math.floor(Date.now() / 1000);
  var lastUpd  = document.getElementById('live-last-updated');
  var knownIds = new Set(Array.from(feed.querySelectorAll('[data-id]')).map(function(el){ return el.dataset.id; }));

  function fetchUpdates() {
    fetch(pollUrl + '?after=' + lastTs)
      .then(function(r){ return r.json(); })
      .then(function(data) {
        if (!data.is_live) return;
        lastTs = data.ts;
        if (lastUpd) lastUpd.textContent = 'Cập nhật ' + new Date().toLocaleTimeString('vi-VN');

        data.updates.forEach(function(u) {
          if (knownIds.has(String(u.id))) return;
          knownIds.add(String(u.id));

          var empty = document.getElementById('live-empty');
          if (empty) empty.remove();

          var div = document.createElement('div');
          div.className = 'live-item flash' + (u.is_pinned ? ' pinned' : '');
          div.dataset.id = u.id;

          var pin = u.is_pinned ? '<span class="live-pin"><i class="bi bi-pin-fill me-1"></i>Tin ghim</span>' : '';
          var ts  = new Date(u.posted_at).toLocaleTimeString('vi-VN', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
          div.innerHTML = pin +
            '<div class="live-time"><i class="bi bi-clock me-1"></i>' + ts + '</div>' +
            '<div class="live-content">' + u.content.replace(/</g,'&lt;').replace(/>/g,'&gt;') + '</div>';

          feed.insertBefore(div, feed.firstChild);
        });
      })
      .catch(function(){});
  }

  setInterval(fetchUpdates, 30000); // mỗi 30 giây
})();
</script>
@endsection
