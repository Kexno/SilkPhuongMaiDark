$(function () {
    //load lang
    load_lang('page');
    //load slug
    init_slug('title','slug');
    //load table ajax
    init_data_table();
    //bind checkbox table
    init_checkbox_table();

    tinymce.init(optionTinyMCE);
    load_account();
    load_course();
    $('select.account_id').on('change', function () {
        var val = this.value;
        var account_id = {account_id:val};
        filterDatatables(account_id);
    });
    $("select.course_id").on('change',function () {
        let id = $(this).val();
        let course_id = {course_id:id};
        filterDatatables(course_id);
    });
});

function edit_form(id) {
    $('#modal_form').modal('show');
    $('.modal-title').text('Xem bình luận');
    $(".upload_comments").attr('style','display:none');
    $.ajax({
        url : url_ajax_edit+"/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            console.log(data);
            $.each(data.comment, function( key, value) {
                if($.inArray(key,['content','account_id']) !== -1){
                    $('[name="'+key+'"]').html(value);
                }else{
                    $('[name="'+key+'"]').html(value);
                }

            });
            $('.reply-btn').attr("data-id",data.comment.id);
            $('.binhluan').attr("data-course",data.comment.course_id);
            $('.binhluan').attr("data-id",data.comment.id);
            load_rep_comment(data.comment.id);
            $('#modal_form').modal('show');
        },
    });
}
function load_rep_comment(id) {
    $.ajax({
        url: url_ajax_repcomment+"/"+id,
        type: 'GET',
        dataType: 'html',
        success: function (data) {
            $('.reply-list').html(data);
        }
    });
}
function delete_comment(id,_this) {
    var current = $(_this);
    $.ajax({
        url: url_ajax_delete+"/"+id,
        type: 'GET',
        dataType: 'JSON',
        success: function (data) {
            if (data.type === "success") {
                current.closest('li').fadeOut('slow', function () {
                    current.closest('li').remove();
                });
            }
        }
    });
}
function repcomments() {
    $(".upload_comments").attr('style','display:block');
}
$('body').on('click','.rep_cm',function(event) {
    event.preventDefault();
    var thiss           = $(this); 
    var content         = $('.binhluan').val();
    var parent_id       = $('.binhluan').attr('data-id');
    var course_id       = $('.binhluan').attr('data-course');
    $.ajax({
        url: url_ajax_rep,
        type: 'POST',
        dataType: 'JSON',
        data: {
            content: content,
            parent_id:parent_id,
            course_id:course_id
        },
        success :function (data) {
            toastr["success"](data.mess);
            $('.binhluan').val('');
            thiss.closest('.comments-list').find('.comments-list').append(data.html);
        }
    });
});
function load_account(dataSelected) {
    $('select[name="account_id"]').select2({
        allowClear: true,
        data: dataSelected,
        placeholder: 'Lựa chọn tên',
        multiple: false,
        ajax: {
            url: url_load_account,
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
function load_course(dataSelected) {
    $('select[name="course_id"]').select2({
        allowClear: true,
        data: dataSelected,
        placeholder: 'Khóa học',
        multiple: false,
        ajax: {
            url: url_load_course,
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
