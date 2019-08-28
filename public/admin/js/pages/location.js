$(function () {
    init_data_table(20);
    init_checkbox_table();

    $(document).on('click', ".btnUpdateStatusCountry", function () {
        let status = $(this).data('value');
        let statusValue = 0;
        switch (status) {
            case 0:
                statusValue = 1;
                break;
            case 1:
                statusValue = 0;
                break;
            default:
                statusValue = 1;
        }
        updateField($(this).parent().parent().find('[name="id[]"]').val(), 'status', statusValue);
    });
});

function add_form() {
    slug_disable = false;
    save_method = 'add';
    $('#modal_form').modal('show');
    $('#modal_form').trigger("reset");
    if (location_type=='city') {
        $('.modal-title').text('Thêm Tỉnh / Thành phố');
    }else{
        $('.modal-title').text('Thêm Quận / Huyện');
    }
    $('#tab_image img').attr('src','http://via.placeholder.com/400x200');
}
//form sua
function edit_form(id) {
    save_method = 'update';
    //Ajax Load data from ajax
    $.ajax({
        url : url_ajax_edit+"/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $.each(data.data, function( key, value ) {
                $('[name="'+key+'"]').val(value);
            });
            $('#modal_form').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $(".modal-body").prepend(box_alert('alert-danger',language['error_try_again']));
        }
    });
}
//ajax luu form
function save()
{
    $('#btnSave').text(language['btn_saving']); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    if(save_method == 'add') {
        url = url_ajax_add;
    } else {
        url = url_ajax_update;
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            toastr[data.type](data.message);
            if(data.type === "warning"){
                $('span.text-danger').remove();
                $.each(data.validation, function (i, val) {
                    $('[name="' + i + '"]').closest('.form-group').append(val);
                })
            } else {
                $('#modal_form').modal('hide');
                reload_table();
            }
            $('#btnSave').text(language['btn_save']);
            $('#btnSave').attr('disabled',false);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $(".modal-body").prepend(box_alert('alert-danger',language['error_try_again']));
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}

function import_excel() {
    let selector = $('[name="importExcel"]');
    selector.click();
    selector.change(function () {
        let inputEle = $(this);
        let file_data = inputEle.prop("files")[0];
        let form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
            type: "POST",
            url: url_ajax_import_excel,
            processData: !1,
            contentType: !1,
            data: form_data,
            dataType: "JSON",
            beforeSend: function () {
                inputEle.parent().find('.fa-spinner').show();
                toastr['info']("Hệ thống đang tự động import vui lòng chờ ...");
            },
            success: function (response) {
                toastr[response.type](response.message);
                reload_table();
                inputEle.parent().find('.fa-spinner').hide();
            }
        });
    });
}