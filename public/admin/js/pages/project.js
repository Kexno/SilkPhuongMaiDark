$(function () {
    //load lang
    load_lang('post');
    //load slug
    init_slug('title','slug');
    //load table ajax
    init_data_table();
    //bind checkbox table
    init_checkbox_table();

    tinymce.init(optionTinyMCE);


});

//form them moi
function add_form() {
    slug_disable = false;
    save_method = 'add';
    loadCategory();
    $('#modal_form').modal('show');
	$('#modal_form').trigger("reset");
}

//form sua
function edit_form(id)
{
    slug_disable = true;
    save_method = 'update';

    //Ajax Load data from ajax
    $.ajax({
        url : url_ajax_edit+"/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $.each(data, function( key, value ) {
                $.each(value, function( k, v) {
                    if($.inArray(k,['title','meta_title','description','meta_description','slug','content','meta_keyword']) !== -1){
                        $('[name="'+k+'['+value.language_code+']"]').val(v);
                        if(tinymce.get(k+'_' + value.language_code)) tinymce.get(k+'_' + value.language_code).setContent(v);
                    }else{
                        $('[name="'+k+'"]').val(v);
                    }
                });
                $('[name="meta_keyword['+value.language_code+']"]').tagsinput('add', value.meta_keyword);
            });
            loadCategory(data.category_id);
            //loadCategory();
            loadImageThumb(data[0].thumbnail);
            $('#modal_form').modal('show');
            $('.modal-title').text(language['heading_title_edit']);

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(textStatus);
            console.log(jqXHR);
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

    for (var j = 0; j < tinyMCE.editors.length; j++){
        var content = tinymce.get(tinyMCE.editors[j].id).getContent();
        $('#'+tinyMCE.editors[j].id).val(content);
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data){
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
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            $('#btnSave').text(language['btn_save']);
            $('#btnSave').attr('disabled',false);
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

function loadCategoryProduct(dataSelected) {
    $('select[name="category_product[]"]').select2({
        allowClear: true,
        placeholder: "Chọn danh mục sản phẩm liên quan",
        multiple: true,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_category_product,
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
