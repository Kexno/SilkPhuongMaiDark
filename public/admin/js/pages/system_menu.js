$(function () {
    //load lang
    load_lang('system_menu');
    loadParentMenu();
    var iconPickerOptions = {searchText: 'Search icon...'};
    var iconClass = 'fa';
    var sortableListOptions = {
        placeholderCss: {'background-color': '#fff'}
    };
    var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions, iconClass: iconClass, labelEdit: 'Edit'});
    editor.setForm($('#frmEdit'));
    editor.setUpdateButton($('#btnUpdate'));
    load_menu(editor);

    $("#btnUpdate").click(function(){
        let data = {};
        $('#frmEdit').find('.item-menu').each(function(){
            data[$(this).attr('name')] = $(this).val();
        });
        save(data, 'update', editor);
    });

    $('#btnAdd').click(function(){
        let data = {};
        $('#frmEdit').find('.item-menu').each(function(){
            data[$(this).attr('name')] = $(this).val();
        });
        save(data, 'add', editor);
    });
    $(document).on('click', '.btnRemove', function () {
        let itemEditing = $(this).closest('li');
        editor.setFormData(itemEditing);
        let id = $('input[name=id]').val();
        swal({
            title: language['mess_alert_title'],
            text: language['mess_confirm_delete'],
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: language['btn_yes'],
            cancelButtonText: language['btn_no'],
            closeOnConfirm: true
        }, function(isConfirm){
            if(isConfirm) {
                delete_item_menu(id);
                editor.remove();
                load_menu(editor);
            } else {
                editor.resetForm();
            }
        });
    });
    $(document).on('click', '.btnEdit', function () {
        let data = $(this).closest('li').data();
        if (data != null) {
            $.ajax({
                url : url_ajax_get_parent_menu,
                type: "POST",
                data: {parent_id: data.parent_id},
                dataType: "JSON",
                success: function(data) {
                    loadParentMenu(data);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert(textStatus);
                    console.log(jqXHR);
                }
            });
        }
    })
});

function load_menu(editor) {
    $.ajax({
        url : url_ajax_list,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            (function filter(obj) {
                $.each(obj, function(key, value){
                    if (value == "" && key === 'children'){
                        delete obj[key];
                    } else if (Object.prototype.toString.call(value) === '[object Object]') {
                        filter(value);
                    } else if ($.isArray(value)) {
                        $.each(value, function (k,v) { filter(v); });
                    }
                });
            })(data);
            editor.update();
            editor.setData(data);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR);
        }
    });
}

//ajax luu form
function save(data, method, editor){
    //ajax adding data to database
    let url = null;
    if (method === 'add') {
        url = url_ajax_add;
    } else {
        url = url_ajax_update;
    }
    $.ajax({
        url : url,
        type: "POST",
        data: data,
        dataType: "JSON",
        success: function(data){
            toastr[data.type](data.message);
            if(data.type === "warning"){
                $('span.text-danger').remove();
                $.each(data.validation, function (i, val) {
                    $('[name="' + i + '"]').closest('.form-group .col-sm-10').addClass('has-error').append(val);
                })
            } else {
                editor.resetForm();
                $("#parent_id").empty().trigger('change');
                $('#icon').val(null);
                load_menu(editor);
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function delete_item_menu(id) {
    $.ajax({
        url : url_ajax_delete+"/"+id,
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            if(data.type){
                toastr[data.type](data.message);
            }
            $('#frmEdit').find('input[name=id]').val('');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(textStatus);
            console.log(jqXHR);
        }
    });
}

function loadParentMenu(dataSelected) {
    let selector = $('#parent_id');
    selector.select2({
        allowClear: true,
        multiple: false,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_parent_menu,
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
