$(document).on('click', '.btn-del', function() {
    let contact_id = $(this).data('contact_id');
    let address_id = $(this).data('address_id');
    let requisite_id = $(this).data('requisite_id');
    let bank_id = $(this).data('bank_id');
    let url = $(this).data('url');

    console.log(contact_id, address_id, requisite_id, bank_id, url);
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
            alert(response.message);
        },
        error: function (response) {
            alert(response.message);
        }
    });
});
