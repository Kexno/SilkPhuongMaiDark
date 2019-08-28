$(function () {
    //load lang
    load_lang('groups');
    //load table ajax
    init_data_table();
    //bind checkbox table
    init_checkbox_table();
});
//form them moi
function add_form()
{
    save_method = 'add';
    $('#form')[0].reset();
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Thêm nhóm');
	$('#modal_form').trigger("reset");
}
//form sua
function edit_form(id)
{
    save_method = 'update';
    $('#form')[0].reset();
    $('.help-block').empty();

    //Ajax Load data from ajax
    $.ajax({
        url : url_ajax_edit+"/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="name"]').val(data.name);
            $('[name="description"]').val(data.description);
            if(id == 1){
                check_all_per();
            }else{
                if(data.permission){
                    //uncheck_all_per();
                    $.each(JSON.parse(data.permission),function(key,value){
                        //$('#per_'+key+' [name="name"]').prop('checked', true);
                        $.each(value,function(per,val){
                            console.log('#per_'+key+'_'+per);
                            //if(val == 1){
                            $('#per_'+key+'_'+per).prop('checked', true);
                            //}
                        });
                    });
                }
            }
            $('#modal_form').modal('show');
            $('.modal-title').text(language['heading_title_edit']);

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
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
                $.each(data.validation,function (i, val) {
                    $('[name="'+i+'"]').after(val);
                })
            } else {
                $('#modal_form').modal('hide');
                reload_table();
            }
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
            $('#token').val(data.token);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $(".modal-body").prepend(box_alert('alert-danger',language['error_try_again']));
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}

function check_all_per(){
    $('#tbl_per :checkbox').prop('checked', true);
}

function uncheck_all_per(){
    $('#tbl_per :checkbox').prop('checked', false);
}