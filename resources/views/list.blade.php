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
        {{-- style="border-radius: 6px; border: 1px solid #000;" --}}
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
    <script>
        $(document).on('click', '.btn-edit', function() {
            let contact_id = $(this).data('contact_id');
            let address_id = $(this).data('address_id');
            let requisite_id = $(this).data('requisite_id');
            let bank_id = $(this).data('bank_id');
            let url = $(this).data('url');

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    contact_id: contact_id,
                    address_id: address_id,
                    requisite_id: requisite_id,
                    bank_id: bank_id
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });
    </script>
@endsection
