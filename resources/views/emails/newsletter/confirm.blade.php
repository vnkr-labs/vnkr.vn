@component('mail::message')
# Xác nhận đăng ký nhận tin VNKR

Xin chào,

Bạn vừa đăng ký nhận bản tin từ **VNKR** — tin tức cộng đồng TheKingBao.

Để xác nhận và bắt đầu nhận tin, hãy nhấn nút bên dưới:

@component('mail::button', ['url' => $confirmUrl, 'color' => 'blue'])
Xác Nhận Đăng Ký
@endcomponent

**Hoàn toàn miễn phí.** Bạn có thể hủy đăng ký bất kỳ lúc nào.

Nếu bạn không yêu cầu điều này, hãy bỏ qua email này.

---

Hủy đăng ký: [nhấn vào đây]({{ $unsubUrl }})

Trân trọng,
**VNKR — Cộng đồng phục vụ cộng đồng**
@endcomponent
