$(function () {
    //load table ajax
    init_data_table();
    //bind checkbox table
    init_checkbox_table();
});
//form them moi
function add_form()
{
    save_method = 'add';
    $('#title-form').text('Thêm Video');
    $('#modal_form').modal('show');
}

//form sua
function edit_form(id)
{
    save_method = 'update';
    //Ajax Load data from ajax
    $.ajax({
        url : url_ajax_edit+"/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
//              //console.log(data);
            $.each(data,function(key,value){
                //console.log(value);
                    if ($.inArray(key,['id','link_video','status']) !== -1) {
                        $('[name="'+key+'"]').val(value);
                    }
            });
            $('.modal-title').text('Sửa link');
            $('#title-form').text('Sửa video');
            $('#modal_form').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            toastr['error'](language['error_try_again']);
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
            $('span.text-danger').remove();
            if(data.type === "warning"){
                $.each(data.validation,function (i, val) {
                    $('[name="'+i+'"]').after(val);
                })
            } else {
                $('#modal_form').modal('hide');
                reload_table();
            }
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            toastr['error'](language['error_try_again']);
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        }
    });
}

function view_item(id) {
    let html='https://www.youtube.com/embed/';
    let link_video=$('#link_video'+id).val();
    html+=link_video;
    $('.box-body iframe').attr('src',html);
}