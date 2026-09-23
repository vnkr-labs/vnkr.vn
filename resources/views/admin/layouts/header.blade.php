<header>
    <div class="page-header">
        <div class="toggle-sidebar" id="toggle-sidebar"><i class="bi bi-list"></i></div>
        <ol class="breadcrumb d-md-flex d-none">
            <li class="breadcrumb-item"><i class="bi bi-house"></i><a href="{{ route('admin.index') }}">Home</a></li>
            <li class="breadcrumb-item breadcrumb-active" aria-current="page">{{ ucfirst(Request::segment(2)) }}</li>
        </ol>
        <div class="header-actions-container">
            <div class="search-container">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Tìm kiếm bài viết...">
                    <button class="btn" type="button"><i class="bi bi-search"></i></button>
                </div>
            </div>
            <ul class="header-actions">
                <span class="user-name d-none d-md-block" style="font-size:12px;">
                    <i class="bi bi-people-fill me-1" style="color:#F0A500;"></i>
                    Quản trị VNKR
                </span>
                <span class="user-name d-none d-md-block">{{ auth()->user()->name }}</span>
                <li class="dropdown">
                    <a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
                        <span class="avatar">
                            <img src="/assets/images/author.jpg" alt="User Image">
                            <span class="status online"></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userSettings">
                        <div class="header-profile-actions">
                            <a href="#">Profile</a>
                            <a href="#">Settings</a>
                            <a href="{{ route('admin.signout') }}">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
