@extends('admin.master')

@section('title', 'Breaking News')
@section('title-page', 'Quản Lý Breaking News')

@section('main-content')
<section class="container">
  @if(session('success'))
  <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">×</button>
    {{ session('success') }}
  </div>
  @endif

  <div class="row">
    {{-- ADD FORM --}}
    <div class="col-md-5">
      <div class="box box-primary">
        <div class="box-header"><h3 class="box-title">Thêm Breaking News</h3></div>
        <div class="box-body">
          <form method="POST" action="{{ route('breaking.store') }}">
            @csrf
            @if($errors->any())
            <div class="alert alert-danger" style="font-size:13px;">{{ $errors->first() }}</div>
            @endif
            <div class="form-group">
              <label>Tiêu đề <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" required
                     maxlength="500" value="{{ old('title') }}"
                     placeholder="Vd: Kết quả bầu cử vừa được công bố...">
            </div>
            <div class="form-group">
              <label>URL liên kết <small class="text-muted">(tùy chọn)</small></label>
              <input type="url" name="url" class="form-control"
                     value="{{ old('url') }}" placeholder="https://...">
            </div>
            <div class="form-group">
              <label>Hết hạn lúc <small class="text-muted">(để trống = vĩnh viễn)</small></label>
              <input type="datetime-local" name="expired_at" class="form-control"
                     value="{{ old('expired_at') }}">
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fa fa-plus me-1"></i> Thêm Breaking News
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- LIST --}}
    <div class="col-md-7">
      <div class="box">
        <div class="box-header"><h3 class="box-title">Danh sách ({{ $items->total() }})</h3></div>
        <div class="box-body table-responsive p-0">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th>Tiêu đề</th>
                <th>Trạng thái</th>
                <th>Hết hạn</th>
                <th style="width:120px;">Hành động</th>
              </tr>
            </thead>
            <tbody>
              @forelse($items as $item)
              <tr>
                <td>
                  @if($item->url)
                    <a href="{{ $item->url }}" target="_blank" rel="noopener">{{ Str::limit($item->title, 60) }}</a>
                  @else
                    {{ Str::limit($item->title, 60) }}
                  @endif
                </td>
                <td>
                  <form method="POST" action="{{ route('breaking.toggle', $item) }}" style="display:inline;">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-xs {{ $item->is_active ? 'btn-success' : 'btn-default' }}">
                      {{ $item->is_active ? '✅ Đang hiện' : '⏸ Tắt' }}
                    </button>
                  </form>
                </td>
                <td style="font-size:12px;">
                  {{ $item->expired_at ? $item->expired_at->format('d/m/Y H:i') : '—' }}
                </td>
                <td>
                  <form method="POST" action="{{ route('breaking.destroy', $item) }}"
                        onsubmit="return confirm('Xóa breaking news này?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-danger">
                      <i class="fa fa-trash"></i> Xóa
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center text-muted py-3">Chưa có breaking news nào.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if($items->hasPages())
        <div class="box-footer text-center">{{ $items->links() }}</div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection
