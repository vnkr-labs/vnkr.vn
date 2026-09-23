@extends('admin.master')

@section('title', 'Liên Hệ & Phản Hồi Cộng Đồng')

@section('title-page', 'Liên Hệ & Phản Hồi Từ Cộng Đồng')

@section('main-content')
    <section class="container">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ $message }}
            </div>
        @endif

        <div class="col-md-12">
            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center">
                    <div style="font-size:13px;color:#555;">
                        <i class="bi bi-envelope me-1" style="color:#0A3D62;"></i>
                        Phản hồi từ cộng đồng — hãy trả lời kịp thời để xây dựng niềm tin.
                    </div>
                    <div class="box-tools">
                        <form action="{{ route('contact.index') }}" method="GET">
                            <div class="input-group input-group-sm" style="width:200px;">
                                <input type="text" name="search" class="form-control" placeholder="Tìm liên hệ..." value="{{ request()->search }}">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Người gửi</th>
                                <th>Email</th>
                                <th>Nội dung</th>
                                <th>Ngày gửi</th>
                                <th>Tùy Chọn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contacts as $contact)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong style="font-size:13px;">{{ $contact->name }}</strong></td>
                                    <td style="font-size:13px;">
                                        <a href="mailto:{{ $contact->email }}" style="color:#0A3D62;">{{ $contact->email }}</a>
                                    </td>
                                    <td style="font-size:13px;max-width:300px;">{{ Str::limit($contact->message, 80) }}</td>
                                    <td style="font-size:12px;color:#888;">{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="mailto:{{ $contact->email }}" class="btn btn-sm btn-success" title="Trả lời">
                                            <i class="bi bi-reply"></i>
                                        </a>
                                        <form action="{{ route('contact.destroy', $contact) }}" method="POST" style="display:inline;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Xóa liên hệ này?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                        Chưa có liên hệ nào từ cộng đồng.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
