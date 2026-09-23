@extends('admin.master')

@section('title', 'Đăng Bài Viết Mới')

@section('title-page', 'Đăng Bài Viết Mới cho Cộng Đồng')

@section('main-content')
    <section class="container mt-4">

        {{-- Thông điệp cộng đồng --}}
        <div class="alert" style="background:#f0f7ff;border:1px solid #c5d9ea;border-left:4px solid #0A3D62;border-radius:4px;padding:10px 16px;margin-bottom:16px;font-size:13px;">
            <i class="bi bi-people-fill me-2" style="color:#F0A500;"></i>
            <strong>Nội dung VNKR là hoàn toàn miễn phí</strong> — tất cả bài viết đều mở cho toàn bộ cộng đồng đọc không hạn chế.
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><i class="bi bi-newspaper me-2"></i>Đăng Bài Viết Mới</h3>
            </div>
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                    <label for="productName" class="form-label">Tiêu Đề Bài Viết</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="productName" name="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="productTomtat" class="form-label">Tóm tắt</label>
                                <input type="text" class="form-control @error('tomtat') is-invalid @enderror" id="productTomtat" name="tomtat">
                                @error('tomtat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug <small class="text-muted">(tự động, có thể chỉnh)</small></label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug">
                                <div id="slug-hint" class="form-text text-warning" style="display:none;">
                                    ⚠️ Slug được tạo từ tiêu đề — hãy chỉnh thành tiếng Anh có nghĩa trước khi lưu.
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="photo" class="form-label">Hình Ảnh</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Chọn Loại Tin</label>
                                <select name="category_id" class="form-select">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">
                                    <i class="bi bi-layers me-1"></i>Trạng Thái Xuất Bản
                                    <small class="text-muted">(Staged Publishing)</small>
                                </label>
                                <select name="status" id="status" class="form-select">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📝 Nháp (Draft)</option>
                                    <option value="review" {{ old('status') == 'review' ? 'selected' : '' }}>🔍 Chờ duyệt (Review)</option>
                                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>✅ Đã xuất bản (Published)</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>🗄️ Lưu trữ (Archived)</option>
                                </select>
                                <div class="form-text">Chỉ bài <strong>Published</strong> mới hiển thị với độc giả.</div>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="stock" name="stock" value="1">
                                <label class="form-check-label" for="stock">
                                    <i class="bi bi-star me-1" style="color:#F0A500;"></i>Bài Nổi Bật
                                    <small class="text-muted d-block">Hiển thị ở khu vực nổi bật trang chủ</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editor" class="form-label">Content</label>
                                <textarea name="description" id="editor" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tags <small class="text-muted">(phân cách bằng dấu phẩy)</small></label>
                                <input type="text" name="tags" class="form-control"
                                    placeholder="vd: bóng đá, thể thao, world cup"
                                    value="{{ old('tags') }}">
                                <div class="form-text">Mỗi tag cách nhau bằng dấu phẩy. Tự động tạo nếu chưa tồn tại.</div>
                            </div>

                            <div class="mb-3">
                                <label for="video_url" class="form-label">
                                    <i class="bi bi-play-circle me-1"></i>URL Video
                                    <small class="text-muted">(YouTube / Vimeo — tùy chọn)</small>
                                </label>
                                <input type="url" name="video_url" id="video_url" class="form-control"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                    value="{{ old('video_url') }}">
                                <div class="form-text">Nhúng video YouTube hoặc Vimeo vào cuối bài.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </div>
            </form>
        </div>

    </section>
    <!-- /.content -->

@endsection

@section('custom-js')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor');
    </script>
    <script>
    /**
     * Chuyển tiêu đề tiếng Việt → slug Latin không dấu.
     * Admin cần chỉnh sửa lại thành slug tiếng Anh có nghĩa trước khi lưu.
     */
    function toSlugBase(str) {
        const map = {
            'à|á|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ': 'a',
            'è|é|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ': 'e',
            'ì|í|ỉ|ĩ|ị': 'i',
            'ò|ó|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ': 'o',
            'ù|ú|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự': 'u',
            'ỳ|ý|ỷ|ỹ|ỵ': 'y',
            'đ': 'd',
        };
        let s = str.toLowerCase();
        for (const [pattern, replacement] of Object.entries(map)) {
            s = s.replace(new RegExp(pattern, 'gi'), replacement);
        }
        return s
            .replace(/[^a-z0-9\s-]/g, '')   // chỉ giữ a-z, 0-9, space, gạch ngang
            .trim()
            .replace(/[\s]+/g, '-')          // space → gạch ngang
            .replace(/-{2,}/g, '-')          // nhiều gạch ngang → 1
            .replace(/^-+|-+$/g, '');        // xóa gạch ngang đầu/cuối
    }

    var slugManuallyEdited = false;

    document.addEventListener('DOMContentLoaded', function () {
        const titleEl = document.getElementById('productName');
        const slugEl  = document.getElementById('slug');

        if (!titleEl || !slugEl) return;

        // Đánh dấu nếu admin tự sửa slug
        slugEl.addEventListener('input', function () {
            slugManuallyEdited = true;
        });

        // Khi admin sửa title: gợi ý slug tự động (chỉ khi chưa tự sửa slug)
        titleEl.addEventListener('input', function () {
            if (!slugManuallyEdited) {
                slugEl.value = toSlugBase(this.value);
                slugEl.classList.add('border-warning');
                slugEl.classList.remove('border-success');
                document.getElementById('slug-hint').style.display = 'block';
            }
        });

        // Normalize slug khi blur
        slugEl.addEventListener('blur', function () {
            this.value = toSlugBase(this.value) || toSlugBase(titleEl.value);
            slugEl.classList.remove('border-warning');
            slugEl.classList.add('border-success');
        });
    });

    // Backward compat (nếu gọi trực tiếp từ onkeyup)
    function ChangeToSlug() {}
    </script>
@endsection
