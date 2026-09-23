<nav class="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}" class="logo" style="display:flex;align-items:center;gap:8px;text-decoration:none;padding:12px 16px;">
            <span style="background:var(--color-primary,#0A3D62);color:#fff;font-size:16px;font-weight:900;padding:3px 10px;border-radius:4px;">VNKR</span>
            <span style="font-size:10px;color:rgba(255,255,255,.55);line-height:1.3;">Cộng đồng<br>phục vụ cộng đồng</span>
        </a>
    </div>
    <div class="sidebar-menu">
        <div class="sidebarMenuScroll">
            <ul>

                {{-- Dashboard --}}
                <li class="sidebar-dropdown active">
                    <a href="{{ route('admin.index') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                {{-- Bài Viết --}}
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="bi bi-newspaper"></i>
                        <span class="menu-text">Bài Viết</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('product.index') }}"><i class="bi bi-list me-1"></i>Danh Sách Bài Viết</a></li>
                            <li><a href="{{ route('product.create') }}"><i class="bi bi-plus me-1"></i>Đăng Bài Mới</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Chuyên Mục --}}
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="bi bi-grid-3x3-gap"></i>
                        <span class="menu-text">Chuyên Mục</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('category.index') }}">Danh Sách Chuyên Mục</a></li>
                            <li><a href="{{ route('category.create') }}">Thêm Chuyên Mục Mới</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Cộng Đồng --}}
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="bi bi-people-fill" style="color:#F0A500;"></i>
                        <span class="menu-text" style="color:#F0A500;font-weight:700;">Cộng Đồng</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('user.index') }}"><i class="bi bi-person-lines-fill me-1"></i>Danh Sách Thành Viên</a></li>
                            <li><a href="{{ route('user.create') }}"><i class="bi bi-person-plus me-1"></i>Thêm Thành Viên</a></li>
                            <li><a href="{{ route('roleUs.index') }}"><i class="bi bi-shield-half me-1"></i>Quản lý Vai Trò</a></li>
                            <li><a href="{{ route('contact.index') }}"><i class="bi bi-envelope me-1"></i>Liên Hệ & Phản Hồi</a></li>
                            <li>
                                <a href="{{ route('admin.comment.index') }}">
                                    <i class="bi bi-chat-dots me-1"></i>Kiểm Duyệt Bình Luận
                                    @php $pendingCount = \App\Models\Comment::where('is_approved',false)->whereNull('deleted_at')->count(); @endphp
                                    @if($pendingCount > 0)
                                        <span class="badge bg-warning text-dark ms-1" style="font-size:10px;">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Nội Dung Đặc Biệt --}}
                <li class="sidebar-item">
                    <a href="{{ route('breaking.index') }}">
                        <i class="bi bi-lightning-charge-fill" style="color:#E84118;"></i>
                        <span class="menu-text">Breaking News</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('live_blog.index') }}">
                        <i class="bi bi-broadcast-pin" style="color:#dc3545;"></i>
                        <span class="menu-text">Live Blog</span>
                    </a>
                </li>

                {{-- Journeys --}}
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="bi bi-map" style="color:#3b82d4;"></i>
                        <span class="menu-text" style="color:#3b82d4;">Journeys</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('admin.journeys.index') }}"><i class="bi bi-list me-1"></i>Danh Sách Lộ Trình</a></li>
                            <li><a href="{{ route('admin.journeys.create') }}"><i class="bi bi-plus me-1"></i>Tạo Lộ Trình Mới</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Redirects --}}
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="bi bi-signpost-2" style="color:#7c5cd8;"></i>
                        <span class="menu-text" style="color:#7c5cd8;">Redirects</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('admin.redirects.index') }}"><i class="bi bi-list me-1"></i>Danh Sách Redirect</a></li>
                            <li><a href="{{ route('admin.redirects.create') }}"><i class="bi bi-plus me-1"></i>Thêm Redirect Mới</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Reusables --}}
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="bi bi-puzzle" style="color:#27ae60;"></i>
                        <span class="menu-text" style="color:#27ae60;">Reusables</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('admin.reusables.index') }}"><i class="bi bi-list me-1"></i>Danh Sách Snippet</a></li>
                            <li><a href="{{ route('admin.reusables.create') }}"><i class="bi bi-plus me-1"></i>Thêm Snippet Mới</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Cài đặt & Công cụ --}}
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="bi bi-gear"></i>
                        <span class="menu-text">Cài Đặt & Công Cụ</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('ad_slots.index') }}"><i class="bi bi-megaphone me-1"></i>Thông Báo Cộng Đồng</a></li>
                            <li><a href="/horizon" target="_blank"><i class="bi bi-speedometer me-1"></i>Queue Monitor</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Đóng góp mã nguồn --}}
                <li class="sidebar-item">
                    <a href="{{ route('contribute') }}" target="_blank">
                        <i class="bi bi-github" style="color:#F0A500;"></i>
                        <span class="menu-text" style="color:#F0A500;font-weight:700;">Đóng Góp Mã Nguồn</span>
                    </a>
                </li>

                {{-- Xem trang chủ --}}
                <li class="sidebar-item" style="margin-top:8px;border-top:1px solid rgba(255,255,255,.1);padding-top:8px;">
                    <a href="{{ url('/') }}" target="_blank">
                        <i class="bi bi-box-arrow-up-right" style="color:#27ae60;"></i>
                        <span class="menu-text" style="color:#27ae60;">Xem Trang Cộng Đồng</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
