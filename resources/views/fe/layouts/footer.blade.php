@php
$footerCats = \App\Models\Category::where('status',1)
    ->whereNotIn('slug', ['thao-luan','tai-tro-dong-gop','web3-crypto'])
    ->get();
@endphp

<footer class="site-footer">
  <div class="footer-top">
    <div class="container">
      <div class="row g-4">

        {{-- Col 1: About VNKR --}}
        <div class="col-lg-4 col-md-6 footer-col">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div style="background:var(--accent);color:#fff;font-size:20px;font-weight:900;padding:4px 12px;border-radius:4px;">VNKR</div>
            <span style="color:#8fa8be;font-size:12px;font-style:italic;">vnkr.vn</span>
          </div>
          <p style="color:#8fa8be;font-size:13px;line-height:1.75;margin-bottom:10px;">
            <strong style="color:#fff;">VNKR</strong> — nền tảng thông tin cộng đồng do
            <strong style="color:#fff;">Phạm Thế Bảo</strong> sáng lập,
            hoạt động theo tinh thần <em style="color:#F0A500;">"cộng đồng phục vụ cộng đồng"</em>.
          </p>
          <div style="color:#27ae60;font-size:13px;font-weight:700;margin-bottom:8px;">
            <i class="bi bi-gift-fill me-1"></i>Hoàn toàn miễn phí — không thu phí tham gia
          </div>
          <p style="color:#6a8ca5;font-size:12px;line-height:1.65;border-top:1px solid rgba(255,255,255,.1);padding-top:10px;">
            ⚠️ VNKR <strong>không phải cơ quan báo chí</strong>. Nội dung là tổng hợp & biên tập
            từ nguồn công khai, có trích dẫn. Chỉ mang tính tham khảo.
          </p>
          <div class="footer-social mt-2">
            <a href="https://facebook.com/thekingbao" target="_blank" rel="noopener" title="Facebook TheKingBao"><i class="bi bi-facebook"></i></a>
            <a href="#" title="YouTube"><i class="bi bi-youtube"></i></a>
            <a href="#" title="Zalo"><i class="bi bi-chat-dots-fill"></i></a>
            <a href="#" title="Telegram"><i class="bi bi-telegram"></i></a>
            <a href="#" title="GitHub"><i class="bi bi-github"></i></a>
          </div>
        </div>

        {{-- Col 2: Categories --}}
        <div class="col-lg-2 col-md-3 footer-col">
          <h4>Chuyên Mục</h4>
          <ul>
            @foreach($footerCats as $fc)
            <li><a href="{{ $fc->slug ? route('category.slug', $fc->slug) : route('result', $fc->id) }}"><i class="bi bi-chevron-right me-1" style="font-size:10px;"></i>{{ $fc->name }}</a></li>
            @endforeach
            <li><a href="{{ route('search') }}?s=goc-nhin" style="color:#F0A500;"><i class="bi bi-star me-1" style="font-size:10px;"></i>Góc Nhìn PTB</a></li>
          </ul>
        </div>

        {{-- Col 3: Cộng Đồng --}}
        <div class="col-lg-2 col-md-3 footer-col">
          <h4>Cộng Đồng</h4>
          <ul>
            <li>
              <a href="/chuyen-muc/thao-luan" style="color:#a8ffcc;font-weight:700;">
                <i class="bi bi-chat-dots-fill me-1"></i>Thảo Luận
              </a>
            </li>
            <li>
              <a href="/chuyen-muc/tai-tro-dong-gop" style="color:#F0A500;font-weight:700;">
                <i class="bi bi-heart-fill me-1"></i>Tài Trợ & Đóng Góp
              </a>
            </li>
            <li>
              <a href="/chuyen-muc/web3-crypto" style="color:#c8b1ff;font-weight:700;">
                <i class="bi bi-currency-bitcoin me-1"></i>Web3 & Crypto
              </a>
            </li>
            <li><a href="/about">Giới thiệu VNKR</a></li>
            <li><a href="/community">Quy chế cộng đồng</a></li>
            <li>
              <a href="{{ route('contribute') }}" style="color:#F0A500;">
                <i class="bi bi-github me-1"></i>Đóng Góp Code
              </a>
            </li>
          </ul>
        </div>

        {{-- Col 4: Thông Tin --}}
        <div class="col-lg-1 col-md-6 footer-col d-none d-lg-block">
          <h4>Pháp Lý</h4>
          <ul>
            <li><a href="/terms">Điều khoản</a></li>
            <li><a href="/privacy">Bảo mật</a></li>
            <li><a href="{{ route('contact.show') }}">Liên hệ</a></li>
          </ul>
          <div style="margin-top:12px;padding:10px;background:rgba(255,255,255,.05);border-radius:4px;font-size:12px;color:#6a8ca5;line-height:1.7;">
            <div><i class="bi bi-person-badge me-1"></i><strong style="color:#ccc;">Phạm Thế Bảo</strong></div>
            <div><i class="bi bi-people me-1"></i><strong style="color:#F0A500;">TheKingBao</strong></div>
            <div><i class="bi bi-envelope me-1"></i>phamthebao@vnkr.vn</div>
          </div>
        </div>

        {{-- Col 4: Newsletter --}}
        <div class="col-lg-3 col-md-6 footer-col">
          <h4>Nhận Bản Tin</h4>
          <p style="color:#8fa8be;font-size:13px;margin-bottom:10px;">
            Đăng ký để nhận tóm tắt tin tức hàng ngày được biên tập bởi Phạm Thế Bảo.
          </p>
          @if(session('newsletter_success'))
          <div style="background:rgba(39,174,96,.15);border:1px solid #27ae60;border-radius:4px;padding:8px 12px;font-size:13px;color:#a8ffcc;margin-bottom:10px;">
            <i class="bi bi-check-circle me-1"></i>{{ session('newsletter_success') }}
          </div>
          @endif
          <form method="POST" action="{{ route('newsletter.subscribe') }}" class="newsletter-input mb-3">
            @csrf
            <input type="email" name="email" placeholder="Email của bạn..." required>
            <button type="submit"><i class="bi bi-send me-1"></i>Đăng ký</button>
          </form>
          <div style="color:#6a8ca5;font-size:12px;font-style:italic;">
            * Không spam. Huỷ đăng ký bất kỳ lúc nào.
          </div>
          <div style="margin-top:14px;padding:10px;background:rgba(240,165,0,.08);border:1px solid rgba(240,165,0,.2);border-radius:4px;font-size:12px;color:#c9a54a;">
            <i class="bi bi-award me-1"></i><strong>Cộng đồng TheKingBao</strong><br>
            Đóng góp xây dựng VNKR → Nhận quyền lợi xứng đáng.
            <div style="margin-top:6px;"><a href="/about" style="color:#F0A500;font-size:11px;"><i class="bi bi-arrow-right me-1"></i>Tìm hiểu quyền lợi</a></div>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- FOOTER BANNER AD --}}
  @include('fe.partials.ad_slot', ['position' => 'footer_banner'])

  {{-- BOTTOM BAR --}}
  <div class="container footer-bottom">
    <div style="font-size:12px;color:#5a7a92;line-height:1.6;">
      © {{ date('Y') }} <strong style="color:#ccc;">VNKR</strong> (vnkr.vn) — Cộng đồng phục vụ cộng đồng. Hoàn toàn miễn phí.<br>
      Người sáng lập: <strong style="color:#aaa;">Phạm Thế Bảo</strong> |
      <span>Không thu phí tham gia. Nội dung tổng hợp từ nguồn công khai. Không phải cơ quan báo chí.</span>
    </div>
    <div class="d-flex gap-3 align-items-center flex-wrap">
      <a href="/terms" style="color:#5a7a92;font-size:12px;">Điều khoản</a>
      <a href="/privacy" style="color:#5a7a92;font-size:12px;">Bảo mật</a>
      <a href="/about" style="color:#5a7a92;font-size:12px;">Giới thiệu</a>
      <a href="{{ route('contact.show') }}" style="color:#5a7a92;font-size:12px;">Liên hệ</a>
    </div>
  </div>
</footer>

{{-- Back to top --}}
<button id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})"
  title="Về đầu trang"
  style="display:none;position:fixed;bottom:24px;right:24px;z-index:9999;
         background:var(--brand);color:#fff;border:2px solid var(--accent);
         border-radius:50%;width:44px;height:44px;font-size:18px;
         cursor:pointer;box-shadow:0 3px 12px rgba(10,61,98,.3);
         align-items:center;justify-content:center;">
  <i class="bi bi-arrow-up"></i>
</button>
<script>
(function(){
  var btn = document.getElementById('backToTop');
  btn.style.display = 'none';
  btn.style.alignItems = 'center';
  btn.style.justifyContent = 'center';
  window.addEventListener('scroll', function(){
    btn.style.display = window.scrollY > 400 ? 'flex' : 'none';
  });
})();
</script>
