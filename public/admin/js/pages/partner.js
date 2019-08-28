$(function () {
    //load lang
    load_lang('partner');
    //load table ajax
    init_data_table();
    //bind checkbox table
    init_checkbox_table();
});

//form them moi
function add_form() {
    slug_disable = false;
    save_method = 'add';
    loadCity();
    loadArea();
    $('#modal_form').modal('show');
	$('#modal_form').trigger("reset");
}

//form sua
function edit_form(id) {
    slug_disable = true;
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
            loadCity(data.city_id);
            loadArea(data.area_id);
            $('#modal_form').modal('show');
            $('.modal-title').text(language['heading_title_edit']);
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
            if(data.type === "validate_error"){
                $(".modal-body").prepend(box_alert('alert-danger',data.message));
            } else {
                toastr[data.type](data.message);
                $('#modal_form').modal('hide');
                reload_table();
            }
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $(".modal-body").prepend(box_alert('alert-danger',language['error_try_again']));
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}


function loadCity(dataSelected) {
    let selector = $('select[name="city_id"]');
    selector.select2({
        allowClear: true,
        placeholder: "Chọn tỉnh/thành phố",
        data: dataSelected,
        ajax: {
            url: url_ajax_city,
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
    if(typeof dataSelected !== 'undefined')
        selector.find('> option').prop("selected","selected").trigger("change");
    selector.change(function () {
        var city_id = $(this).val();
        loadDistrict(city_id);
    });
}

function loadArea(dataSelected) {
    let selector = $('select[name="area_id"]');
    selector.select2({
        allowClear: true,
        placeholder: "Chọn vùng",
        data: dataSelected,
        multiple: false,
        ajax: {
            url: url_ajax_area,
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
    if(typeof dataSelected !== 'undefined')
        selector.find('> option').prop("selected","selected").trigger("change");
}

function loadDistrict(city_id,dataSelected) {
    $('select[name="district_id"]').select2({
        allowClear: true,
        placeholder: "Chọn quận huyện",
        data: dataSelected,
        ajax: {
            url: url_ajax_district+'/'+city_id,
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
}