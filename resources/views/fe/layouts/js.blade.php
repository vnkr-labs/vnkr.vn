{{-- VNKR: Apply theme TRƯỚC paint để tránh flash trắng --}}
<script>
(function(){try{var t=localStorage.getItem('vnkr-theme')||
(window.matchMedia('(prefers-color-scheme:dark)').matches?'dark':'light');
document.documentElement.dataset.theme=t;}catch(e){}})();
</script>
<script src="/client/plugins/jQuery/jquery.min.js"></script>
<script src="/client/plugins/bootstrap/bootstrap.min.js"></script>
<script src="/client/plugins/slick/slick.min.js"></script>

{{-- ===== GOOGLE ANALYTICS 4 =====
     Thay G-XXXXXXXXXX bằng Measurement ID thật từ analytics.google.com
     Hướng dẫn: https://vnkr.vn/about --}}
@if(config('app.env') === 'production')
{{-- Uncomment và thay ID sau khi đăng ký Google Analytics:
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
</script>
--}}
@endif

{{-- VNKR UI Library — micro-interactions, toast, modal, theme toggle --}}
<script src="/assets/js/vnkr-ui.js" defer></script>

@stack('scripts')
