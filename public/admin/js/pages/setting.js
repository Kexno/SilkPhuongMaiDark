$(function () {
    $('input[name="check_banner_video"]').on('change', function () {
        if ($(this).is(":checked")) {
            $('input[name="url_video_home"]').attr('readonly', false);
        } else {
            $('input[name="url_video_home"]').attr('readonly', true);
        }
    });
    $('.select2').select2();
    tinymce.init(optionTinyMCE);
    $("[name=is_cache]").bootstrapSwitch();


    $('a.btnClearCache').click(function () {
        var elment = $(this);
        $.ajax({
            type: 'POST',
            url: url_ajax_clear_cache,
            data:{key:'a'},
            dataType: 'json',
            beforeSend: function () {
                elment.find('.fa-spinner').show();
            },
            success: function (response) {
                console.log(response);
                elment.find('.fa-spinner').hide();
                toastr.success('Clear cache thành công !');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                console.log(thrownError);
            }
        });return false;
    });

    $('button.btn-ajax-generate-db').click(function () {
        var elment = $(this);
        $.ajax({
            type: 'POST',
            url: url_ajax_backup_db,
            dataType: 'json',
            beforeSend: function () {
                elment.find('.fa-spinner').show();
            },
            success: function (response) {
                console.log(response);
                elment.find('.fa-spinner').hide();
                location.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                console.log(thrownError);
            }
        });return false;
    });

    $('.btn-ajax-restore-db').click(function () {
        var elment = $(this);
        var db_name = $(this).parent().parent().find('td.file-name').text();
        $.ajax({
            type: 'POST',
            url: url_ajax_restore_db,
            data:{db_name:db_name},
            dataType: 'json',
            beforeSend: function () {
                elment.find('.fa-spinner').show();
            },
            success: function (response) {
                console.log(response);
                if(response.status === 0){
                    $('.alert').addClass('alert-danger');
                    $('.alert .content-msg').html(response.msg);
                }
                elment.find('.fa-spinner').hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                console.log(thrownError);
            }
        });return false;
    });

    $('.btn-ajax-delete-db').click(function () {
        var elment = $(this);
        var db_name = $(this).parent().parent().find('td.file-name').text();
        swal({
            title: language['alert_title'],
            text: language['confirm_delete'],
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: language['btn_yes'],
            cancelButtonText: language['btn_no'],
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        }, function() {
            $.ajax({
                type: 'POST',
                url: url_ajax_delete_db,
                data:{db_name:db_name},
                dataType: 'json',
                beforeSend: function () {
                    elment.find('.fa-spinner').show();
                },
                success: function (response) {
                    console.log(response);
                    elment.find('.fa-spinner').hide();
                    swal.close();
                    location.reload();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    console.log(thrownError);
                }
            })
        });return false;
    })
});
function addInputElementSettings(idElement, i, value, file,addTinymce) {

  var element = $('#' + idElement);
  i = parseInt(i) + 1;
  $.ajax({
    type: "POST",
    url: base_url + "admin/setting/" + file,
    data: {i: i, item: value, meta_key: idElement, id: i},
    dataType: "html",
    success: function (inputImage) {
      element.append(inputImage);
      element.attr('data-id', i);
      element.attr('data-id', i);

      if(typeof addTinymce!='undefined' && addTinymce!=''){
        setTimeout(function () {
          tinymce.init(optionTinyMCEMore);
      }, 100);
    }
}
});
}