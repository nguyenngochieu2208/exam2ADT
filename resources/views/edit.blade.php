@extends('layouts.app')

@section('title','Danh sách liên hệ')

@section('content')
<div class="container p-5 mt-3">
    <div class="edit-contact d-flex justify-content-center align-items-center">
        <div class="card p-3 m-2" style="max-width: 600px">
            <div class="d-flex justify-content-between mb-3">
                <p class="h3 bold">
                    Cập nhật liên hệ
                </p>

                <div class="">
                    <a href="{{route('contact.list')}}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Danh sách
                    </a>
                </div>
            </div>
            <hr class="mt-0">
            <form class="row" id="form-edit-contact" action="{{ route('contact.update') }}" method="POST">
                @csrf
                <input type="hidden" name="contact_id" value="{{$contact_id}}">
                <input type="hidden" name="requisite_id" value="{{@$requisite_id}}">
                <input type="hidden" name="bank_id" value="{{@$bank_id}}">

                <input type="hidden" name="phone_id" value="{{@$data['PHONE'][0]['ID']}}">
                <input type="hidden" name="email_id" value="{{@$data['EMAIL'][0]['ID']}}">
                <div class="mb-3 col-6">
                    <label for="formControlInput" class="form-label">Họ</label>
                    <input type="text" name="name" class="form-control" id="formControlInput" value="{{ $data['NAME'] }}">
                    @error('name')
                        <div class="text-danger mt-1">
                            * {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3 col-6">
                    <label for="formControlInput" class="form-label">Tên</label>
                    <input type="text" name="last_name" class="form-control" id="formControlInput" value="{{ $data['LAST_NAME'] }}">
                    @error('last_name')
                        <div class="text-danger mt-1">
                            * {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="formControlInput" class="form-label">Địa chỉ Email</label>
                    <input type="email" name="email" class="form-control" id="formControlInput" value="{{ @$data['EMAIL'][0]['VALUE'] }}" placeholder="exam@example.com">
                    @error('email')
                        <div class="text-danger mt-1">
                            * {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="formControlInput" class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" id="formControlInput" value="{{ @$data['PHONE'][0]['VALUE'] }}" placeholder="+84">
                    @error('phone')
                        <div class="text-danger mt-1">
                            * {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Phần địa chỉ --}}
                <div class="mb-3">
                    <label for="formControlInput" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" name="address" id="formControlInput" value="{{ @$data['ADDRESS']['ADDRESS_2'] }}" placeholder="Xã, Huyện, Tỉnh">
                    @error('address')
                        <div class="text-danger mt-1">
                            * {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Phần ngân hàng --}}
                <div class="mb-1 mt-3">
                    <p class="h5 bold">Thông tin ngân hàng</p>
                </div>
                <div class="mb-3">
                    <label for="formControlInput" class="form-label">Tên ngân hàng</label>
                    <input type="text" class="form-control" name="bank_name" id="formControlInput" value=" {{ @$data['REQUISITE']['BANK']['RQ_BANK_NAME'] }} ">
                    @error('bank_name')
                        <div class="text-danger mt-1">
                            * {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="formControlInput" class="form-label">Tên chủ sở hữu</label>
                    <input type="text" class="form-control" name="bank_owner" id="formControlInput" value="{{ @$data['REQUISITE']['BANK']['RQ_ACC_NAME'] }}">
                    @error('bank_owner')
                        <div class="text-danger mt-1">
                            * {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="formControlInput" class="form-label">Số tài khoản</label>
                    <input type="text" class="form-control" name="bank_acc_num" id="formControlInput" value="{{ @$data['REQUISITE']['BANK']['RQ_ACC_NUM'] }}">
                    @error('bank_acc_num')
                        <div class="text-danger mt-1">
                            * {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
