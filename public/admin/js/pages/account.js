$(function () {
    init_data_table();
    init_checkbox_table();
    $('select.is_status').on('change', function () {
        var val = this.value;
        var is_status = {is_status:val};
        filterDatatables(is_status);
    });

});
//form sua
function edit_form(id) {
    save_method = 'update';
    $('.help-component').empty();
    $('#title-form').text('Sửa khách hàng');

    $.ajax({
        url: url_ajax_edit + "/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $.each(data.data, function (key, value) {
                $('[name="' + key + '"]').val(value);
            });
            loadImageThumb(data.data.avatar,'avatar');
            $('[name="username"]').val(data.data.username).attr('readonly', true);
            $('[name="email"]').val(data.data.email).attr('readonly', true);
            $('[name="address"]').val(data.data.address).attr('readonly', true);
            $('[name="job"]').val(data.data.job);
            $('[name="birthday"]').val(data.data.birthday);
            // $('[name="phone"]').attr('readonly', true);
            $('[name="full_name"]').attr('readonly', true);
            $('[name="featured"]').val(data.data.featured).prop("selected", "selected");
            $('#modal_form select[name="active"] > option[value="' + data.data.active + '"]').prop("selected", true);
            $('#modal_form').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
        }
    });
}

//ajax luu form
function save() {
    $('#btnSave').text(language['btn_saving']); //change button text
    $('#btnSave').attr('disabled', true); //set button disable
    var url;

    if (save_method == 'add') {
        url = url_ajax_add;
    } else {
        url = url_ajax_update;
    }

    // ajax adding data to database
    $.ajax({
        url: url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function (data) {
            toastr[data.type](data.message);
            if(data.type === "warning"){
                $('span.text-danger').remove();
                $.each(data.validation,function (i, val) {
                    $('[name="'+i+'"]').closest('.col-xs-6').append(val);
                })
            } else {
                $('#modal_form').modal('hide');
                reload_table();
            }
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled', false); //set button enable
            $('#token').val(data.token);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled', false); //set button enable
        }
    });
}
