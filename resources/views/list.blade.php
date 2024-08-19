@extends('layouts.app')

@section('title','Danh sách liên hệ')

@section('content')
<div class="my-4 mx-3">
    <div class="d-flex justify-content-between mb-3">
        <p class="h3 bold">
            Danh sách liên hệ
        </p>

        <div class="">
            <a href="{{ route('contact.create') }}" class="btn btn-primary">
                <i class="bi bi-plus bold"></i> Thêm mới liên hệ
            </a>
            <a href="" class="btn btn-secondary">
                <i class="bi bi-arrow-clockwise bold"></i> Làm mới
            </a>
        </div>
    </div>
    <div class="table-main mb-5" >
        <div class="table-responsive" >
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Ảnh đại diện</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col">Địa chỉ liên hệ</th>
                        <th scope="col">Số điện thoại</th>
                        <th scope="col">Địa chỉ Email</th>
                        <th scope="col">Thông tin ngân hàng</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody id="tbody-list">
                    @include('partials.data')
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('js/list.js') }}"></script>
@endsection
