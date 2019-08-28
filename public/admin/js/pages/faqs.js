$(function () {
    // load lang
    load_lang('faqs');
    // load table ajax
    init_data_table();
    // bind checkbox table
    init_checkbox_table();
    // load tinymce
    tinymce.init(optionTinyMCE);
});

//form them moi
function add_form() {
    slug_disable = false;
    save_method = 'add';
    $('#modal_form').modal('show');
    $('.modal-title').text(language['heading_title_add']);
}

//form sua
function edit_form(id) {
    slug_disable = true;
    save_method = 'update';
    //Ajax Load data from ajax
    $.ajax({
        url: url_ajax_edit + "/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $.each(data.data, function (key, value) {
                $.each(value, function (k, v) {
                    if ($.inArray(k, ['title', 'content']) != -1) {
                        $('[name="' + k + '[' + value.language_code + ']"]').val(v);
                        if(tinymce.get(k+'_' + value.language_code)) tinymce.get(k+'_' + value.language_code).setContent(v);
                    } else {
                        $('[name="' + k + '"]').val(v);
                    }
                });
            });
            $('#modal_form').modal('show');
            $('.modal-title').text(language['heading_title_edit']);
        },
        error: function (jqXHR, textStatus, errorThrown) {
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
    // Convert Tinymce
    for (var j = 0; j < tinyMCE.editors.length; j++){
        var content = tinymce.get(tinyMCE.editors[j].id).getContent();
        $('#'+tinyMCE.editors[j].id).val(content);
    }
    // ajax adding data to database
    $.ajax({
        url: url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function (data) {
            toastr[data.type](data.message);
            if (data.type === "warning") {
                $('span.text-danger').remove();
                $.each(data.validation, function (i, val) {
                    $('[name="' + i + '"]').closest('.form-group').append(val);
                })
            } else {
                $('#modal_form').modal('hide');
                reload_table();
            }
            $('#btnSave').text(language['btn_save']);
            $('#btnSave').attr('disabled', false);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled', false); //set button enable
        }
    });
}