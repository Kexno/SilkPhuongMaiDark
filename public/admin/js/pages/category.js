$(function () {
	//load lang
	load_lang('category');
	//load slug
	init_slug('title','slug');
	//load table ajax
	init_data_table(100);
	//bind checkbox table
	init_checkbox_table();
	//Bật kéo thả sắp xếp record
	updateSortDatatables();
});

//form them moi
function add_form() {
	slug_disable = false;
	save_method = 'add';
	$('input[name="type"]').val(category_type);
	/*Load category parent*/
	loadParentCategory();
    $('.modal-title').text('Thêm danh mục');
	
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
			$.each(data.data, function( key, value ) {
				$.each(value, function( k, v) {
					if($.inArray(k,['title','meta_title','description','meta_description','slug','content','meta_keyword','address']) !== -1){
						$('[name="'+k+'['+value.language_code+']"]').val(v);
					}else{
						$('[name="'+k+'"]').val(v);
					}
				});
				$('[name="meta_keyword['+value.language_code+']"]').tagsinput('add', value.meta_keyword);
			});
			loadImageThumb(data.data[0].thumbnail);
			loadImageThumb(data.data[0].i_con,'i_con');
			loadImageThumb(data.data[0].banner,'banner');
			loadParentCategory(id, data.parent_id);
			/*Load category parent*/
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

	// ajax adding data to database
	$.ajax({
		url : url,
		type: "POST",
		data: $('#form').serialize(),
		dataType: "JSON",
		success: function(data){
			$('#modal_form .text-danger').remove();
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

function loadParentCategory(id = 0, dataSelected) {
	let selector = $('select[name="parent_id"]');
	selector.select2({
		allowClear: true,
		placeholder: language['text_category'],
        data: dataSelected,
		ajax: {
			url: url_ajax_load,
			data: function (params) {
				return {
					id: id,
					q: params.term,
					type: 'query'
				};
			},
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
	if(typeof dataSelected !== 'undefined') selector.find('> option').prop("selected","selected").trigger("change");
}
