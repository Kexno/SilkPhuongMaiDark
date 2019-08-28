$(function () {
    //load lang
    load_lang('order');
    //load table ajax
    init_data_table(status_order);
    //bind checkbox table
    init_checkbox_table();
    $("select.is_status").on('change',function () {
        var status_order = $(this).val();
        var is_status = {is_status:status_order};
        filterDatatables(is_status);
    });

});

var get_currency = function(x){
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function edit_form(id) {
    $.ajax({
        url : url_ajax_view+'/'+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            $.each(data,function(key,value){
                if(key=='ward_id'){
                    let html='';
                        html+='<tr>';
                        html+='<td>'+value.title+'</td>';
                        html+='<td>'+value.price+'đ'+'</td>';
                        html+='</tr>';
                    $('#form_order_products').html(html);
                }else if(key=='is_status'){
                    console.log( $('#'+key));
                    $('#'+key).val(value);
                    console.log(value);
                }else if(key=='id'){
                    $('input[name="id"]').val(value);
                }else{
                    console.log(value);
                    $('#'+key).html(value);
                }
            });
            $('#modal_form').modal('show');
            $('.modal-title').text('Thông tin đơn hàng');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $(".modal-body").prepend(box_alert('alert-danger',language['error_try_again']));
        }
    });
}

function chang_addr(type='city'){
    $('#'+type).on('change',function(){
        let type_addr=type;
        if(type=='city') {
             type_addr='district';
        }
        else{
             type_addr='ward';
        } 
        console.log(type_addr);
        let id=$(this).val();
        $.ajax({
            url : base_url + 'admin/order/list_option/0/'+type_addr+'/'+id+'/json',
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#'+type_addr).html(data);
            }
        });
    });
}
chang_addr();
chang_addr('district');

function save(){
    $('#btnSave').text(language['btn_saving']); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    // ajax adding data to database
    $.ajax({
        url : url_ajax_update,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            toastr[data.type](data.message);
            $('#modal_form').modal('hide');
            reload_table();

            $('#btnSave').text(language['btn_save']);
            $('#btnSave').attr('disabled',false);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $(".modal-body").prepend(box_alert('alert-danger',language['error_try_again']));
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}





