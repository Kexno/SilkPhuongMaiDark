/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/
$(function () {
    init_dash();
});

function init_dash() {
    $.ajax({
        url : url_ajax_total,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            // console.log(data);return false;
            $.each(data, function( key, value ) {
                $('#'+key).html(value);
            });
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $(".modal-body").prepend(box_alert('alert-danger',language['error_try_again']));
        }
    });
}