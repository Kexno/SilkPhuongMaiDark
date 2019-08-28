$(function () {
    //load lang
    load_lang('product');
    //load slug
    init_slug('title', 'slug');
    //load table ajax
    init_data_table();
    //bind checkbox table
    init_checkbox_table();
    tinymce.init(optionTinyMCE);

    var modalContainer = $('.modal');

    modalContainer.on('click', '.btnAddMore', function () {
        let idSelector = $(this).parent().parent().find('.content-image').attr('id');
        let idColor = $(this).parent().parent().data('id');
    });

    $('#price,#price_sale').number(true);
    $('#price,#price_sale').change(function () {
        getSaleUp();
    })
    $('#price_sale').on({
        keypress: function () {
            return isNumberKey(event);
        },
        keyup: function () {
            let value = parseInt($(this).val());
            let price = parseInt($('#price').val());
            if (price >= value) $(this).val(value);
            else $(this).val(price);
        }
    })
});

// tinh gia khuyen mai
function getSaleUp() {
    let price = parseInt($('#price').val());
    let pricesaleUp = parseInt($('#price_sale').val());
    let saleUp = 0;
    if(price>=pricesaleUp){
        saleUp = parseInt((price-pricesaleUp)/price*100);
        $('#sale_up').val(saleUp);
    }else{
        $('#price_sale').val(price);
        $('#sale_up').val(saleUp);
    }
}
// nhập giá trị sale up
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode === 46 || (charCode >= 48 && charCode <= 57))
        return true;
    return false;
}
//form them moi
function add_form() {
    slug_disable = false;
    save_method = 'add';
    loadCategory();
    loadProperty('brand');
    loadProperty('unit');
    $('#modal_form').modal('show');
    $('.modal-title').text(language['heading_title_add']);
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
            $.each(data, function (key, value) {
                $.each(value, function (k, v) {
                    if ($.inArray(k, ['title', 'meta_title', 'description', 'content', 'meta_description', 'slug', 'content_info', 'meta_keyword']) !== -1) {
                        $('[name="' + k + '[' + value.language_code + ']"]').val(v);
                        if (tinymce.get(k + '_' + value.language_code) && v) {
                            tinymce.get(k + '_' + value.language_code).setContent(v);
                        }
                    } else {
                        $('[name="' + k + '"]').val(v);
                    }
                });
                $('[name="meta_keyword[' + value.language_code + ']"]').tagsinput('add', value.meta_keyword);
            });
            getSaleUp();
            loadCategory(data.category_id);
            loadImageThumb(data[0].thumbnail);
            loadMultipleMedia(data[0].album);
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

    for (var j = 0; j < tinyMCE.editors.length; j++) {
        var content = tinymce.get(tinyMCE.editors[j].id).getContent();
        $('#' + tinyMCE.editors[j].id).val(content);
    }

    // ajax adding data to database
    $.ajax({
        url: url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function (data) {
            toastr[data.type](data.message);
            $('span.text-danger').remove();
            if (data.type === "warning") {
                $.each(data.validation, function (i, val) {
                    $('[name="' + i + '"]').closest('.form-group').append(val);
                    if (i === 'album[]') {
                        $('.error-multiple-media').append(val);
                    }
                })
            }
            if (data.type === 'success') {
                $('#modal_form').modal('hide');
                reload_table();
            }

            $('#btnSave').text(language['btn_save']);
            $('#btnSave').attr('disabled', false);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            $('#btnSave').text(language['btn_save']);
            $('#btnSave').attr('disabled', false);
        }
    });
}


function loadCategory(dataSelected) {
    let selector = $('select[name="category_id[]"]');
    selector.select2({
        allowClear: true,
        multiple: true,
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

function loadProperty(type, dataSelected) {
    let selector = $('select[name="property[' + type + '][]"]');
    let url = null;
    let multiple = false;
    switch (type) {
        case 'brand':
        multiple = false;
        url = url_ajax_load_brand;
        selector = $('select[name="property[' + type + ']"]');
        break;
        case 'unit':
        multiple = false;
        url = url_ajax_load_unit;
        selector = $('select[name="' + type + '"]');
        break;
    }
    selector.select2({
        allowClear: true,
        multiple: multiple,
        data: dataSelected,
        ajax: {
            url: url,
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
function loadUnit(dataSelected) {
    let selector = $('select[name="unit"]');
    selector.select2({
        allowClear: true,
        multiple: false,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_unit,
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

