@extends('admin.master')

@section('title', 'Thêm Thành Viên — Phân Quyền')
@section('title-page', 'Thêm Thành Viên & Phân Quyền')

@section('main-content')
<section class="container mt-4">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><i class="bi bi-person-plus me-2"></i>Thêm Thành Viên Mới</h3>
            </div>
            <form action="{{ route('roleUs.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger" style="font-size:13.5px;">{{ $errors->first() }}</div>
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Thành Viên <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Vai Trò</label>
                        <select name="role" id="role" class="form-select">
                            <option value="user"  {{ old('role') !== 'admin' ? 'selected' : '' }}>
                                Thành viên cộng đồng
                            </option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                Quản trị viên
                            </option>
                        </select>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>Mọi thành viên đều được tham gia miễn phí — bất kể vai trò.
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('roleUs.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Quay Lại
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-person-check me-1"></i>Tạo Thành Viên
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
