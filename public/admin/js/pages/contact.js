$(function () {

    //load table ajax
    init_data_table();
    //bind checkbox table
    init_checkbox_table();
});

function view_item(id) {
    $.ajax({
        url : url_ajax_view+'/'+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            $('#modal_form').modal('show');
            $('.modal-title').text(language['heading_title_view']);
            $.each(data, function (k, v) {
                $('#'+ k + '').val(v);
            })

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $(".modal-body").prepend(box_alert('alert-danger',language['error_try_again']));
        }
    });
}