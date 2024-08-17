@extends('layouts.app')

@section('title','Danh sách liên hệ')

@section('content')
<div class="container p-5 mt-3">
    <div class="create-contact d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card p-3 m-2" style="max-width: 600px">
            <div class="d-flex justify-content-between mb-3">
                <p class="h3 bold">
                    Thêm liên hệ mới
                </p>

                <div class="">
                    <a href="{{route('contact.list')}}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Danh sách
                    </a>
                </div>
            </div>
            <hr class="mt-0">
            <form class="row" id="form-add-contact" action="{{ route('contact.add') }}" method="POST">
                @csrf
                @method('POST')
                <div class="mb-3 col-6">
                    <label for="formControlInput" class="form-label">Họ</label>
                    <input type="text" name="name" class="form-control" id="formControlInput" value="{{ old('name') }}">
                </div>
                <div class="mb-3 col-6">
                    <label for="formControlInput" class="form-label">Tên</label>
                    <input type="text" name="last_name" class="form-control" id="formControlInput" value="{{ old('last_name') }}">
                </div>
                <div class="mb-3">
                    <label for="formControlInput" class="form-label">Địa chỉ Email</label>
                    <input type="email" name="email" class="form-control" id="formControlInput" value="{{ old('email') }}" placeholder="exam@example.com">
                </div>
                <div class="mb-3">
                    <label for="formControlInput" class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" id="formControlInput" value="{{ old('phone') }}" placeholder="+84">
                </div>

                {{-- Phần địa chỉ --}}
                <div class="mb-3">
                    <label for="formControlInput" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" name="address" id="formControlInput" value="{{ old('address') }}" placeholder="Xã, Huyện, Tỉnh">
                </div>

                {{-- Phần ngân hàng --}}
                <div class="mb-1 mt-3">
                    <p class="h5 bold">Thông tin ngân hàng</p>
                </div>
                <div class="mb-3">
                    <label for="formControlInput" class="form-label">Tên ngân hàng</label>
                    <input type="text" class="form-control" name="bank_name" id="formControlInput" value="{{ old('bank_name') }}" placeholder="">
                </div>
                <div class="mb-3">
                    <label for="formControlInput" class="form-label">Tên chủ sở hữu</label>
                    <input type="text" class="form-control" name="bank_owner" id="formControlInput" value="{{ old('bank_owner') }}" placeholder="">
                </div>
                <div class="mb-4">
                    <label for="formControlInput" class="form-label">Số tài khoản</label>
                    <input type="text" class="form-control" name="bank_acc_num" id="formControlInput" value="{{ old('bank_acc_num') }}" placeholder="">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
