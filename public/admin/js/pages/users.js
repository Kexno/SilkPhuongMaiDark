$(function () {

    //load table ajax
    init_data_table();
    //bind checkbox table
    init_checkbox_table();
    $('[name="username"]').keypress(function (e) {
        var txt = String.fromCharCode(e.which);
        if (!txt.match(/[^&\/\\#,+()^!`$~%'":*?<>{} àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ]/g, '_')) {
            return false;
        }
    });
});
//form them moi
function add_form()
{
    save_method = 'add';
    $('#title-form').text('Thêm tài khoản');
    $('[name="username"]').removeAttr('readonly');
    $('[name="email"]').removeAttr('readonly');
    $('.help-component').empty();
    $('#modal_form').modal('show');
	$('#modal_form').trigger("reset");
}

//form sua
function edit_form(id)
{
    save_method = 'update';
    $('.help-component').empty();

    //Ajax Load data from ajax
    $.ajax({
        url : url_ajax_edit+"/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="username"]').val(data.username).attr('readonly',true);
            $('[name="email"]').val(data.email).attr('readonly',true);
            $('[name="first_name"]').val(data.first_name);
            $('[name="last_name"]').val(data.last_name);
            $('[name="company"]').val(data.company);
            $('[name="phone"]').val(data.phone);
            $('select[name="group_id"]').val(data.group_id);
            $('select[name="active"]').val(data.active);
            $('[name="active"]').val(data.active);
            $('#modal_form').modal('show');
            $('.modal-title').text('Sửa tài khoản');

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
            if(data.type === "warning"){
                $('span.text-danger').remove();
                $.each(data.validation,function (i, val) {
                    $('[name="'+i+'"]').after(val);
                })
            } else {
                $('#modal_form').modal('hide');
                $('.text-danger').empty();
                reload_table();
            }
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
            $('#token').val(data.token);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            toastr['error'](language['error_try_again']);
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        }
    });
}
