$(document).on('click', '.btn-del', function() {
    let contact_id = $(this).data('contact_id');
    let address_id = $(this).data('address_id');
    let requisite_id = $(this).data('requisite_id');
    let bank_id = $(this).data('bank_id');
    let url = $(this).data('url');

    Swal.fire({
        title: 'Bạn có chắc muốn xóa liên hệ này ?',
        type: 'warning',
        showCancelButton: true,
        cancelButtonText:'Hủy',
        confirmButtonText: 'Xóa',
        confirmButtonColor:"#ef4444",
    }).then((result) => {
        if (result.value) {
            console.log(result.value);
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
                    $('#record_'+contact_id).remove();
                    toastr.success(response.message);
                },
                error: function (response) {
                    toastr.error(response.message);
                }
            });
        }
    })

});
