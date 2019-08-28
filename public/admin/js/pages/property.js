$(function () {
    //load lang
    load_lang('property');
    //load table ajax
    if (typeof property_type !== 'undefined') dataFilter = {property_type: property_type};
    init_data_table(100);
    //bind checkbox table
    init_checkbox_table();
    //init colorpicker
    $('.my-colorpicker2').colorpicker();
});

//form them moi
function add_form() {
    slug_disable = false;
    save_method = 'add';
    loadCategory();
    $('input[name="type"]').val(property_type);
    /*Load category parent*/
    $('#modal_form').modal('show');
    $('.modal-title').text('Thêm thuộc tính');
    $('#modal_form').trigger("reset");
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
                    if ($.inArray(k, ['title', 'time', 'meta_title', 'description', 'meta_description', 'slug', 'content', 'meta_keyword', 'address']) !== -1) {
                        $('[name="' + k + '[' + value.language_code + ']"]').val(v);
                    } else {
                        $('[name="' + k + '"]').val(v);
                    }
                });
            });
            loadCategory(data.category_id);
            loadImageThumb(data.data[0].thumbnail);
            $('#modal_form').modal('show');
            $('.modal-title').text(language['heading_title_edit']);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(textStatus);
            console.log(jqXHR);
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
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            $('#btnSave').text(language['btn_save']);
            $('#btnSave').attr('disabled', false);
        }
    });
}

function loadCategory(dataSelected) {
    let selector = $('select[name="category_id"]');
    selector.select2({
        allowClear: true,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_category,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
    if (typeof dataSelected !== 'undefined') selector.find('> option').prop("selected", "selected").trigger("change");
}