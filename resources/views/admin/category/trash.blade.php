@extends('admin.master')

@section('title', 'Thùng Rác')

@section('title-page', 'Những Thứ Đã Xóa')

@section('main-content')
    <section class="container">

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                {{ $message }}
            </div>
        @endif
        <!-- Default box -->
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                        {{-- <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div> --}}
                    </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Stt</th>
                                <th>Tên Loại Tin</th>
                                {{-- <th>LT-Cha</th> --}}
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Tùy chọn</th>
                            </tr>
                            @forelse ($categories as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    {{-- <td>{{ $item->parent_id }}</td> --}}
                                    <td>{!! $item->status
                                        ? '<span class="label label-success">Hiển Thị</span>'
                                        : '<span class="label label-danger">Ẩn</span>' !!}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <a href="{{ route('category.restore', $item->id) }}"
                                            onclick="return confirm('Xác Nhận Khôi Phục Từ Thùng Rác?')"
                                            class="btn btn-success">
                                            <i class="fa fa-undo"></i> Khôi Phục</a>
                                        <a href="{{ route('category.forceDelete', $item->id) }}"
                                            onclick="return confirm('Bạn có trắc chắn muốn xoá chứ ?')" class="btn btn-danger"><i
                                                class="fa fa-trash"></i> Xóa Bỏ</a>
                                    </td>
                                </tr>
                            @empty
                                <div class="text-center">
                                    <span>Chưa Có Dữ Liệu</span>
                                </div>
                            @endforelse


                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.box -->

    </section>
@endsection
