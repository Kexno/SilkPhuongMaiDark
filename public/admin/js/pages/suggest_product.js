$(function () {
    init_data_table();
    //bind checkbox table
    init_checkbox_table();

    let category_id;

    $('select[name="category_id"]').on('change', function (e) {
        category_id = $(this).val();
        if (type === 'suggest') {
            let ul = $('ul.nav-tabs');
            ul.find('li').removeClass('active');
            ul.find('a').attr("href", function (i, val) {
                if (val === '#tab_' + category_id) {
                    $(this).closest('li').addClass('active');
                }
            });
            let tab = $('.tab-content');
            tab.find('.tab-pane').removeClass('active');
            tab.find('.tab-pane').attr('id', function (i, val) {
                if (val === 'tab_' + category_id) {
                    $(this).addClass('active');
                }
            });
            $('#left-product ul').data('category_id', category_id);
        }
        loadProductWithCategory(category_id);
    });
    $('#left-product').on('click', '.select-product', function () {
        let selector = $(this);
        if (type === 'suggest') {
            if ($.inArray(selector.data('id'), arrayRight(category_id)) === -1) {
                $("#tab_" + category_id + " ul").append(selector.clone());
            }
            selector.remove();
        } else {
            let id = $('.tab-content .active').attr('id');
            if ($.inArray(selector.data('id'), arrayRight(id)) === -1) {
                $('.tab-content .active ul').append(selector.clone());
            }
        }

    });
    $('.tab-pane').on('click', '.select-product', function () {
        let selector = $(this);
        if (type === 'suggest') {
            if ($.inArray(selector.data('id'), arrayLeft()) === -1 && typeof $('#left-product ul').data('category_id') != "undefined" && $('#left-product ul').data('category_id') === category_id) {
                $('#left-product ul').append(selector.clone());
            }
        }
        selector.remove();
    });
    $('.btn_search').on('click', function () {
        let keyword = $('#text-search-product').val();
        if (type === 'set') {
            loadProductWithCategory(category_id, keyword);
        } else {
            if (category_id != null) {
                loadProductWithCategory(category_id, keyword);
            }
        }
    });
});

function arrayLeft() {
    let array = [];
    $('#left-product .select-product').each(function () {
        array.push($(this).data('id'));
    });
    return array;
}

function arrayRight(category_id) {
    let array = [];
    if (type === 'suggest') {
        $("#tab_" + category_id + " .select-product").each(function () {
            array.push($(this).data('id'));
        });
    } else {
        $("#" + category_id + " .select-product").each(function () {
            array.push($(this).data('id'));
        });
    }
    return array;
}

function getData() {
    let data = [];
    $('.tab-content .tab-pane').each(function () {
        let id_cate = $(this).data('id');
        let data_row = null;
        let product = [];
        $(this).find('.select-product').each(function () {
            product.push($(this).data('id'));
        });
        if (type === 'suggest') {
            data_row = {category: id_cate, product: product};
        } else {
            data_row = {day: id_cate, product: product};
        }
        data.push(data_row);
    });
    return data;
}

function fillData(data) {
    data = JSON.parse(data);
    $.each(data, function (key, value) {
        if (type === 'suggest') {
            loadProductWithCategory(value.category, null, value.product);
        } else {
            loadProductWithCategory(null, null, value.product, value.day);
        }

    });
}

//form them moi
function add_form() {
    slug_disable = false;
    save_method = 'add';
    loadCategory();
    if (type === 'set') {
        loadProductWithCategory();
    }
    $('.tab-pane').find('ul').each(function () {
        $(this).empty();
    });
    $('ul.nav-tabs').find('li').removeClass('active');
    $('ul.nav-tabs').find('li:first-child').addClass('active');
    $('.tab-content').find('.tab-pane').removeClass('active');
    $('.tab-content').find('.tab-pane:first-child').addClass('active');
    $('#left-product ul').empty();
    $('#modal_form').modal('show');
    $('#modal_form').trigger("reset");
}

//form sua
function edit_form(id) {
    save_method = 'update';
    //Ajax Load data from ajax
    $.ajax({
        url: url_ajax_edit + "/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $.each(data, function (key, value) {
                $('[name="' + key + '"]').val(value);
            });
            fillData(data.data);
            loadCategory();
            if (type === 'set') {
                loadProductWithCategory();
            }
            $('ul.nav-tabs').find('li').removeClass('active');
            $('ul.nav-tabs').find('li:first-child').addClass('active');
            $('.tab-content').find('.tab-pane').removeClass('active');
            $('.tab-content').find('.tab-pane:first-child').addClass('active');
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
    let data = getData();
    let displayed_time = $('input[name="displayed_time"]').val();
    let id = $('input[name="id"]').val();
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
        data: {data: data, displayed_time: displayed_time, id: id},
        dataType: "JSON",
        success: function (data) {
            toastr[data.type](data.message);
            $('span.text-danger').remove();
            if (data.type === "warning") {
                $.each(data.validation, function (i, val) {
                    $('[name="' + i + '"]').closest('.form-group').append(val);
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

function loadProductWithCategory(id, keyword, product_id, set) {
    $.ajax({
        url: url_ajax_load_product,
        type: "POST",
        data: {category_id: id, keyword: keyword, product: product_id},
        dataType: "JSON",
        success: function (data) {
            let selector = $('#left-product ul');
            selector.empty();
            if (product_id != null) {
                if (type === 'suggest') {
                    $("#tab_" + id + " ul").html(data.data);
                } else {
                    $('.tab-pane').each(function () {
                        if ($(this).data('id') === set) {
                            $(this).find('ul').html(data.data);
                        }
                    });
                }
            } else {
                selector.html(data.data);
            }
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
    let selector = $('select[name="category_id"]');
    selector.select2({
        allowClear: true,
        multiple: false,
        placeholder: 'Lựa chọn danh mục sản phẩm',
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
