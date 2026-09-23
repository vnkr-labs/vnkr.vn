<?php
// Chạy: php artisan tinker --no-interaction < database/seeders/community_seed.php

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Str;

$adminId = App\Models\User::where('role', 'admin')->first()?->id ?? 1;

$articles = [
    // THẢO LUẬN (cat 9)
    [
        'name'        => 'VNKR cần gì từ cộng đồng? Cùng thảo luận định hướng 2025',
        'tomtat'      => 'Mở thread thảo luận về những tính năng, nội dung và định hướng mà cộng đồng TheKingBao muốn thấy trên VNKR trong năm 2025. Mọi ý kiến đều được lắng nghe.',
        'description' => '<p>VNKR được xây dựng vì cộng đồng. Trong thread này, bạn có thể chia sẻ: Tính năng nào bạn muốn thấy nhất? Chủ đề nội dung nào VNKR nên tập trung? PTB sẽ đọc và phản hồi tất cả bình luận.</p>',
        'category_id' => 9, 'stock' => 0, 'is_featured' => 0, 'image' => 'news-placeholder.jpg',
        'tags' => ['thao-luan', 'cong-dong', 'vnkr'],
    ],
    [
        'name'        => 'Góc nhìn PTB: Vì sao tôi chọn xây dựng nền tảng tin tức miễn phí?',
        'tomtat'      => 'Phạm Thế Bảo chia sẻ câu chuyện phía sau quyết định xây dựng VNKR — nền tảng không thu phí, không quảng cáo thương mại, hoàn toàn phục vụ cộng đồng.',
        'description' => '<p>Nhiều người hỏi tôi: "Anh xây cái này để làm gì?" Câu trả lời đơn giản: Tôi muốn đọc một trang tin như vậy — nhưng nó chưa tồn tại. Một trang tin không bị chi phối bởi quảng cáo. Không clickbait. Không thu phí. Chỉ là thông tin trung thực từ người thật đến người thật.</p>',
        'category_id' => 9, 'stock' => 0, 'is_featured' => 0, 'image' => 'news-placeholder.jpg',
        'tags' => ['thao-luan', 'goc-nhin-ptb', 'vnkr'],
    ],
    [
        'name'        => 'Mô hình "cộng đồng phục vụ cộng đồng" có thể thành công ở Việt Nam không?',
        'tomtat'      => 'Thảo luận về khả năng tồn tại của các nền tảng phi lợi nhuận do cộng đồng vận hành tại Việt Nam — kinh nghiệm từ Wikipedia, Reddit, và các dự án mã nguồn mở.',
        'description' => '<p>Wikipedia tồn tại 23 năm nhờ đóng góp tự nguyện. Reddit xây dựng 50 triệu cộng đồng từ không đồng nào. Linux thống trị 96% server toàn cầu mà không ai trả tiền để viết nó. Liệu mô hình đó có thể hoạt động với nền tảng tin tức Việt Nam?</p>',
        'category_id' => 9, 'stock' => 0, 'is_featured' => 0, 'image' => 'news-placeholder.jpg',
        'tags' => ['thao-luan', 'cong-dong'],
    ],

    // TÀI TRỢ & ĐÓNG GÓP (cat 10)
    [
        'name'        => 'VNKR cần nhà đóng góp nội dung — Viết bài và nhận quyền lợi cộng đồng',
        'tomtat'      => 'VNKR đang mở rộng đội ngũ cộng tác viên. Nếu bạn có góc nhìn độc đáo về bất kỳ chủ đề nào, hãy cùng tham gia xây dựng nền tảng cộng đồng.',
        'description' => '<p>VNKR đang tìm kiếm các nhà đóng góp nội dung cho các chủ đề: Thời sự, Công nghệ, Web3, Kinh tế, Đời sống. Bạn không cần là nhà báo chuyên nghiệp — chỉ cần có góc nhìn thật sự.</p><p>Quyền lợi: Badge CTV, profile tác giả, tiếng nói trong định hướng VNKR.</p>',
        'category_id' => 10, 'stock' => 0, 'is_featured' => 0, 'image' => 'news-placeholder.jpg',
        'tags' => ['dong-gop', 'cong-tac-vien', 'vnkr'],
    ],
    [
        'name'        => 'Cách đóng góp mã nguồn cho VNKR — Hướng dẫn từng bước',
        'tomtat'      => 'Hướng dẫn chi tiết để bắt đầu đóng góp code cho VNKR — từ fork repository đến tạo Pull Request đầu tiên. Dành cho tất cả level developer.',
        'description' => '<p>VNKR là dự án mã nguồn mở viết bằng Laravel 10 + PHP 8.2. Dù bạn là junior hay senior developer, đều có chỗ cho bạn đóng góp. Xem hướng dẫn đầy đủ tại /contribute.</p>',
        'category_id' => 10, 'stock' => 0, 'is_featured' => 0, 'image' => 'news-placeholder.jpg',
        'tags' => ['dong-gop', 'ma-nguon-mo', 'lap-trinh'],
    ],
    [
        'name'        => 'Ủng hộ tự nguyện VNKR — Hoàn toàn không bắt buộc, mọi sự hỗ trợ đều quý giá',
        'tomtat'      => 'VNKR không thu phí và không bao giờ có paywall. Nếu bạn muốn hỗ trợ chi phí vận hành máy chủ, bạn có thể đóng góp tự nguyện — không có áp lực nào.',
        'description' => '<p>VNKR chạy trên server được Phạm Thế Bảo tự chi trả. Nếu nền tảng này mang lại giá trị cho bạn và bạn muốn giúp duy trì chi phí vận hành, bạn có thể ủng hộ tự nguyện qua liên hệ trực tiếp.</p><p>Nhưng nhớ: điều này hoàn toàn không bắt buộc. VNKR sẽ luôn miễn phí.</p>',
        'category_id' => 10, 'stock' => 0, 'is_featured' => 0, 'image' => 'news-placeholder.jpg',
        'tags' => ['dong-gop', 'ung-ho', 'cong-dong'],
    ],

    // WEB3 & CRYPTO (cat 11)
    [
        'name'        => 'Bitcoin vượt 67,000 USD: Điều gì đang thúc đẩy chu kỳ tăng giá lần này?',
        'tomtat'      => 'Phân tích các yếu tố chính đằng sau đà tăng của Bitcoin — từ ETF spot được phê duyệt, halving event, đến dòng tiền tổ chức đang đổ vào thị trường crypto.',
        'description' => '<p>Bitcoin đã vượt mốc 67,000 USD — một cột mốc quan trọng trong chu kỳ tăng trưởng hiện tại.</p><p>Các yếu tố chính: ETF Bitcoin spot tại Mỹ được phê duyệt và thu hút hàng tỷ USD; Halving event dự kiến làm giảm nguồn cung mới; Các tổ chức lớn như BlackRock, Fidelity tham gia thị trường.</p><p>Lưu ý: Đây là bài phân tích thông tin, không phải lời khuyên đầu tư.</p>',
        'category_id' => 11, 'stock' => 0, 'is_featured' => 0, 'image' => 'news-placeholder.jpg',
        'tags' => ['bitcoin', 'crypto'],
    ],
    [
        'name'        => 'Việt Nam và crypto 2025: Khung pháp lý đang đi về đâu?',
        'tomtat'      => 'Tổng hợp những diễn biến mới nhất về việc Việt Nam xây dựng khung pháp lý cho tiền mã hoá và tài sản số — cơ hội và thách thức cho người dùng trong nước.',
        'description' => '<p>Việt Nam nằm trong top 10 quốc gia có tỷ lệ người dùng crypto cao nhất thế giới, nhưng khung pháp lý vẫn đang trong giai đoạn xây dựng. VNKR theo dõi và tổng hợp những thông tin chính thống từ Chính phủ và NHNN về vấn đề này.</p>',
        'category_id' => 11, 'stock' => 0, 'is_featured' => 0, 'image' => 'news-placeholder.jpg',
        'tags' => ['crypto', 'viet-nam'],
    ],
    [
        'name'        => 'DeFi là gì? Giải thích đơn giản về tài chính phi tập trung cho người mới',
        'tomtat'      => 'Hướng dẫn toàn diện về DeFi — từ khái niệm cơ bản đến DEX, lending protocol, yield farming. Được viết cho người chưa biết gì về Web3.',
        'description' => '<p>DeFi (Decentralized Finance) là hệ sinh thái tài chính hoạt động trên blockchain, không cần ngân hàng hay tổ chức trung gian.</p><p>Bài viết này giải thích DeFi theo cách đơn giản nhất có thể — không jargon, không hype.</p><p>Lưu ý: VNKR không khuyến nghị bất kỳ giao thức DeFi cụ thể nào.</p>',
        'category_id' => 11, 'stock' => 0, 'is_featured' => 0, 'image' => 'news-placeholder.jpg',
        'tags' => ['defi', 'web3'],
    ],
    [
        'name'        => 'Ví tiền điện tử là gì và làm thế nào để bảo vệ tài sản số an toàn?',
        'tomtat'      => 'Hướng dẫn cơ bản về ví crypto — hot wallet, cold wallet, seed phrase — và những nguyên tắc bảo mật tối thiểu mọi người dùng Web3 cần biết.',
        'description' => '<p>Mất ví crypto là mất vĩnh viễn — không có ngân hàng nào khôi phục được. Bài viết này hướng dẫn cách bảo vệ tài sản số đúng cách từ cơ bản nhất.</p>',
        'category_id' => 11, 'stock' => 0, 'is_featured' => 0, 'image' => 'news-placeholder.jpg',
        'tags' => ['web3', 'bao-mat'],
    ],
];

$tagCache = [];
$created  = 0;

foreach ($articles as $data) {
    $tagSlugs = $data['tags'];
    unset($data['tags']);
    $data['author_id'] = $adminId;
    $base = Str::slug($data['name']);
    $slug = $base;
    $i    = 1;
    while (Product::where('slug', $slug)->exists()) {
        $slug = $base . '-' . $i++;
    }
    $data['slug'] = $slug;

    $p = Product::create($data);

    $tagIds = [];
    foreach ($tagSlugs as $ts) {
        if (!isset($tagCache[$ts])) {
            $tn = ucwords(str_replace('-', ' ', $ts));
            $tag = Tag::firstOrCreate(['slug' => $ts], ['name' => $tn]);
            $tagCache[$ts] = $tag->id;
        }
        $tagIds[] = $tagCache[$ts];
    }
    $p->tags()->syncWithoutDetaching($tagIds);
    echo "Created [{$p->id}] cat:{$p->category_id} — {$p->name}\n";
    $created++;
}

echo "\nDone: {$created} articles seeded.\n";
echo 'Total products: ' . Product::count() . "\n";
