$(function () {
    //load lang
    load_lang('module');
    //load table ajax
    init_data_table();
    //bind checkbox table
    init_checkbox_table();
    //load slug
    $('select.user_id').on('change', function () {
        var id = this.value;
        var user_id = {user_id:id};
        filterDatatables(user_id);
    });
    load_users();
});
function load_users(dataSelected) {
    $('select[name="user_id"]').select2({
        allowClear: true,
        data: dataSelected,
        placeholder: 'Thành viên',
        multiple: false,
        ajax: {
            url: url_load_users,
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