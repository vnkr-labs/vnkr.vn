@extends('admin.master')

@section('title', 'Chỉnh Sửa')

@section('title-page', 'Chỉnh Sửa Loại Tin')

@section('main-content')
    <!-- Main content -->
    <section class="container mt-4">

        <!-- Default box -->
        <div class="col-md-6 mx-auto">
            <!-- general form elements -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Chỉnh Sửa Loại Tin: {{ $category->name }}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ route('category.update', $category->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="id" value="{{ $category->id }}">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên Loại Tin</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="catName" name='name'
                                value="{{ old('name', $category->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 @error('slug') has-error @enderror">
                            <label for="slug" class="form-label">Slug <small class="text-muted">(URL của chuyên mục)</small></label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="catSlug" name="slug"
                                value="{{ old('slug', $category->slug) }}" placeholder="vd: the-thao">
                            <div id="cat-slug-hint" class="form-text text-warning" style="display:none;">
                                ⚠️ Hãy chỉnh slug thành tiếng Anh có nghĩa.
                            </div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="mb-3">
                            <label for="parent_id" class="form-label">Chọn LT-Cha</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">Chọn LT-Cha</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" {{ $category->parent_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="mb-3">
                            <label class="form-label">Chọn trạng thái</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status1" value="1"
                                    {{ $category->status == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="status1">
                                    Hiện
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status0" value="0"
                                    {{ $category->status == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="status0">
                                    Ẩn
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </section>
    <!-- /.content -->
@endsection

@section('custom-js')
<script>
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
    return s.replace(/[^a-z0-9\s-]/g, '').trim().replace(/[\s]+/g, '-').replace(/-{2,}/g, '-').replace(/^-+|-+$/g, '');
}
document.addEventListener('DOMContentLoaded', function () {
    const nameEl = document.getElementById('catName');
    const slugEl = document.getElementById('catSlug');
    const hintEl = document.getElementById('cat-slug-hint');
    if (!nameEl || !slugEl) return;
    var manualSlug = slugEl.value.trim() !== '';  // already has a slug on edit
    slugEl.addEventListener('input', function () { manualSlug = true; });
    nameEl.addEventListener('input', function () {
        if (!manualSlug) {
            slugEl.value = toSlugBase(this.value);
            if (hintEl) hintEl.style.display = 'block';
        }
    });
    slugEl.addEventListener('blur', function () {
        this.value = toSlugBase(this.value) || toSlugBase(nameEl.value);
        if (hintEl) hintEl.style.display = 'none';
    });
});
</script>
@endsection
