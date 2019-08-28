//file js dinh nghia ham dung chung
var save_method = '',
slug_disable = false,
table = '',
arrId = [],
qCount = 0,
limit = 10,
i = 1,
dataFilter = {};
var optionTinyMCE = {
  height: "250",
  selector: "textarea.tinymce",
  setup: function (ed) {
    ed.on('DblClick', function (e) {
      if (e.target.nodeName == 'IMG') {
        tinyMCE.activeEditor.execCommand('mceImage');
      }
    });
  },
  plugins: [
  "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker template",
  "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
  "table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern moxiemanager link image",
  ],

  toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect template",
  toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
  toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft insertfile link image",

  templates: [
  {
    title: 'Textbox',
    description: 'Tạo Textbox',
    url: base_url + 'public/admin/plugins/tinymce/templates/text-box.html'
  }
  ],

  menubar: false,
  element_format: 'html',
  extended_valid_elements: "iframe[src|width|height|name|align], embed[width|height|name|flashvars|src|bgcolor|align|play|loop|quality|allowscriptaccess|type|pluginspage]",
  toolbar_items_size: 'small',
  relative_urls: false,
  remove_script_host : false,
  convert_urls: true,
  verify_html: false,
  style_formats: [
  {title: 'Bold text', inline: 'b'},
  {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
  {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
  {title: 'Example 1', inline: 'span', classes: 'example1'},
  {title: 'Example 2', inline: 'span', classes: 'example2'},
  {title: 'Table styles'},
  {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
  ],

  external_plugins: {
    "moxiemanager": base_url + "/public/admin/plugins/moxiemanager/plugin.min.js"
  }
};

var optionTinyMCEMore = {
  height: "150",
  selector: "textarea.tinymce",
  setup: function (ed) {
    ed.on('DblClick', function (e) {
      if (e.target.nodeName == 'IMG') {
        tinyMCE.activeEditor.execCommand('mceImage');
      }
    });
  },
  plugins: [
  "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
  "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
  "table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern moxiemanager link image",
  ],

  toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | link unlink anchor  code | insertdatetime ",
  toolbar2: "table | hr removeformat | subscript superscript | charmap emoticons |  fullscreen | ltr rtl | spellchecker | link image",
  menubar: false,
  element_format: 'html',
  extended_valid_elements: "iframe[src|width|height|name|align], embed[width|height|name|flashvars|src|bgcolor|align|play|loop|quality|allowscriptaccess|type|pluginspage]",
  toolbar_items_size: 'small',
  relative_urls: false,
  remove_script_host : false,
  convert_urls: true,
  verify_html: false,
  external_plugins: {
    "moxiemanager": base_url + "/public/admin/plugins/moxiemanager/plugin.min.js"
  }
};
var url_ = document.URL;
var menuElement = $('a[href="' + url_ + '"]');
// SEO Style
var colors = ["#f44336", "#fbc02d", "#4caf50"];
var cgg = $(".gg").text().split("").join("</span><span>");
$(".gg").html(cgg);
var cgg = $(".gg_1").text().split("").join("</span><span>");
$(".gg_1").html(cgg);
$(function () {
  'use strict';
  menuElement.parent().addClass('active');
  menuElement.parent().parent().show();
  menuElement.parent().parent().parent().addClass('menu-open');
  $(document).ajaxStart(function () {
    Pace.restart();
  });
  //load lang
  load_lang('general');
  $('[name="phone"]').keyup(function () {
    var phone = $(this).val().replace(/[^0-9]/g, '');
    $(this).val(phone); 
  });
  $('.select2').select2({
    allowClear: true,
    placeholder: 'Select an item'
  });
  $('.fancybox').fancybox({
    'overlayOpacity': 0.6,
    'autoScale': false,
    'type': 'iframe'
  });
  $('#datepicker').datepicker({
    format: "dd-mm-yyyy",
    // endDate: "+1 days"
  });
  $('[data-toggle="tooltip"]').tooltip();
  $('.datepicker').datepicker({
    format: "yyyy/mm/dd"
  });
  $('.datetimepicker').datetimepicker({
    format: "yyyy-mm-dd HH:ii:ss"
  });
  $('input[name="is_featured"]').bootstrapSwitch();
  //$("input.tagsinput").tagsinput();

  loadFilterCategory();

  //Update Status
  $(document).on('click', ".btnUpdateStatus", function () {
    let status = $(this).data('value');
    let statusValue = 0;
    switch (status) {
      case 1:
      statusValue = 2;
      break;
      case 2:
      statusValue = 0;
      break;
      default:
      statusValue = 1;
    }
    updateField($(this).closest('tr').find('[name="id[]"]').val(), 'is_status', statusValue);
  });
  //Update Status

  //Update Featured
  $(document).on('click', ".btnUpdateFeatured", function () {
    let value = $(this).data('value');
    let updateValue = 0;
    switch (value) {
      case 1:
      updateValue = 0;
      break;
      default:
      updateValue = 1;
    }
    updateField($(this).parent().parent().find('[name="id[]"]').val(), 'is_featured', updateValue);
  });
  //Update BestSeller
  $(document).on('click', ".btnUpdateBestSeller", function () {
    let value = $(this).data('value');
    let updateValue = 0;
    switch (value) {
      case 1:
      updateValue = 0;
      break;
      default:
      updateValue = 1;
    }
    updateField($(this).parent().parent().find('[name="id[]"]').val(), 'best_seller', updateValue);
  });
  //Update Qty (tình trạng hàng hoá còn hay hết)
  $(document).on('click', ".btnUpdateQty", function () {
    let value = $(this).data('value');
    let updateValue = 0;
    switch (value) {
      case 1:
      updateValue = 0;
      break;
      default:
      updateValue = 1;
    }
    updateField($(this).parent().parent().find('[name="id[]"]').val(), 'is_qty', updateValue);
  });
  //Update Qty

  //Event modal
  var modalCms = $('.modal');
  modalCms.modal({backdrop: 'static', keyboard: false, show: false});
  //Event close modal
  modalCms.on('hidden.bs.modal', function (e) {
    window.onbeforeunload = null;
    $(this).find('form').trigger('reset');
    $(this).find('input[type=hidden]').val(0);
    $('.form-group span.text-danger').remove();
    $("#form .select2").empty().trigger('change');
    $('#gallery').html('');
    $('.reset_html').html('').attr('data-id', 0);
    $('.alert').remove();
    $('.help-block').empty();
    $("input.tagsinput").tagsinput('removeAll');
    $(".block_add").children().remove().attr('data-id', 0);
    $('input[name="is_featured"]').bootstrapSwitch('state', false);
    for (var j = 0; j < tinyMCE.editors.length; j++) {
      tinymce.get(tinyMCE.editors[j].id).setContent(' ');
    }
    // $('.box-body img').attr('src','http://via.placeholder.com/200x50');
    loadFilterCategory();
  });

  //Event open modal
  modalCms.on('shown.bs.modal', function (e) {
    initSEO();//Plugin SEO
    btnFly();
  });

  $("input[data-type='currency']").on({
    keyup: function() {
      formatCurrency($(this));
    },
    keypress: function() {
      formatCurrency($(this));
    },
    change: function() {
      formatCurrency($(this));
    },
    paste: function() {
      formatCurrency($(this));
    },
    blur: function() {
      formatCurrency($(this));
    }
  });
});

// Jquery Dependency


function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}


function formatCurrency(input, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.

  // get input value
  var input_val = input.val();

  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // initial caret position
  var caret_pos = input.prop("selectionStart");

  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);



    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = "$" + left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = input_val;

  }

  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}

function checkImageSrc() {
  var image = $('src');
  image.onload = function () {
    cnsole.log(image);
    if (image.length == 0) {
      image.attr('src', base_url + 'img/no-photo.png'); // replace with other image
    }
  };
  image.onerror = function () {
    alert('error image');
    image.attr('src', base_url + 'img/no-photo.png'); // replace with other image
  };
}

function loadImageThumb(url,name) {
  if(typeof name==='undefined' || name===''){
    name='thumbnail';
  }

  var imageThumbnail = $('[name="'+name+'"]');
  // console.log(media_url + url);
  // imageThumbnail.attr('src', media_url + url);
  imageThumbnail.parent().find('a').attr('href', media_url + url);
  imageThumbnail.parent().find('img').attr('src', media_url + url);
}
function loadImageBanner(url,name) {
  console.log(name);
  if(typeof name==='undefined' || name===''){
    name='banner';
  }

  var imageThumbnail = $('[name="'+name+'"]');
  // console.log(media_url + url);
  // imageThumbnail.attr('src', media_url + url);
  imageThumbnail.parent().find('a').attr('href', media_url + url);
  imageThumbnail.parent().find('img').attr('src', media_url + url);
}
function loadMultipleMedia(data) {
  if (data !== null && (data).length > 0) {
    $.each(JSON.parse(data), function (i, v) {
      $('#gallery').append(itemGallery(i + 1, v));
    });
  }
}
function loadMultipleMediaByName(name,element,data) {
  if (data !== null && (data).length > 0) {
    $.each(JSON.parse(data), function (i, v) {
      $('#' + element).append(itemGallery_by_name(name,i + 1, v));
    });
  }
}


//Chọn ảnh
function chooseImage(idElement) {
  moxman.browse({
    view: 'thumbs',
    fields: idElement,
    extensions: 'jpg,jpeg,png,ico, gif,html,htm,txt,docx,doc,pdf,mp3,mp4,flv,xls,xlxs',
    no_host: true,
    oninsert: function (args) {
      let url = args.focusedFile.url;
      let urlImageResponse = url.replace(script_name + media_name, '');
      let image = args.focusedFile.thumbnailUrl;
      console.log(idElement);
      console.log($('#' + idElement).parent());
      console.log(urlImageResponse);
      $('#' + idElement).val(urlImageResponse).parent().find('img').attr('src', image);
    }
  });

}

function chooseMultipleMedia(idElement) {
  var count = parseInt($('#' + idElement).attr('data-id'));
  moxman.browse({
    view: 'thumbs',
    multiple: true,
    extensions: 'jpg,jpeg,gif,png,ico,pdf,doc,docx,xls,xlsx',
    no_host: true,
    oninsert: function (args) {
        // $('#gallery').html('');
        $.each(args['files'], function (i, value) {
          count = count + 1;
          var url = value.url;
          var urlImageResponse = url.replace(script_name + media_name, '');
          var html = itemGallery(count, urlImageResponse);
          $('#' + idElement).append(html);
        });
        $('#' + idElement).attr('data-id', $('#' + idElement + ' .item_gallery:last').data('count'));
      }
    });
}

function chooseMultipleMediaName(idElement,name) {
  var count = parseInt($('#' + idElement).attr('data-id'));
  moxman.browse({
    view: 'thumbs',
    multiple: true,
    extensions: 'jpg,jpeg,gif,png,ico,pdf,doc,docx,xls,xlsx',
    no_host: true,
    oninsert: function (args) {
      $.each(args['files'], function (i, value) {
        count = count + 1;
        var url = value.url;
        var urlImageResponse = url.replace(script_name + media_name, '');
        var html = itemGallery_by_name(name,count, urlImageResponse);
        $('#' + idElement).append(html);
      });
      $('#' + idElement).attr('data-id', $('#' + idElement + ' .item_gallery:last').data('count'));
    }
  });
}

function chooseFiles(idElement) {

  moxman.browse({
    path: '/media/files',
    view: 'thumbs',
    extensions: 'pdf',
    no_host: true,
    oninsert: function (args) {
      var url = args.focusedFile.url;
      var urlImageResponse = url.replace(script_name + media_name, '');
      $('#' + idElement).val(urlImageResponse);
    }
  });
}

function chooseMultipleFiles(idElement) {

  moxman.browse({
    path: '/media/brochure',
    view: 'thumbs',
    multiple: true,
    extensions: 'jpg,jpeg,gif,png,ico,pdf,doc,docx,xls,xlsx',
    no_host: true,
    oninsert: function (args) {
      let arrImage = [];
      $.each(args.files, function (i, val) {
        arrImage[i] = "brochure/" + val.name;
      });
      $('#' + idElement).val(JSON.stringify(arrImage))
    }
  });
}

// add item gallery
function itemGallery(count, urlImageResponse) {
  return html = "<div class='item_gallery item_" + count + "' data-count='" + count + "'>" +
  "<img src='" + media_url + "/" + urlImageResponse + "' id='item_" + count + "' height='120'>" +
  "<input type='hidden' name='album[]' value='" + urlImageResponse + "' >" +
  "<span class='fa fa-times removeInput' onclick='removeInputImage(this)'></span></div>";
}

// add item gallery
function itemGallery_by_name(name,count, urlImageResponse) {
  return html = "<div class='item_gallery item_" + count + "' data-count='" + count + "'>" +
  "<img src='" + media_url + "/" + urlImageResponse + "' id='item_" + count + "' height='120'>" +
  "<input type='hidden' name='"+name+"' value='" + urlImageResponse + "' value='\" + urlImageResponse + \"'  >" +
  "<span class='fa fa-times removeInput' onclick='removeInputImage(this)'></span></div>";
}

//Chọn màu
function chooseColor(idElement, id) {
  $.get(url_ajax_color, function (data) {
    var selectElement = $('select#' + idElement);
    var option = '';
    jQuery.each($.parseJSON(data), function (k, item) {
      var selected = '';
      if (item.id == id) selected = 'selected';
      option += '<option value="' + item.id + '" style="background: ' + item.value + ';" ' + selected + '></option>';

    });
    selectElement.show();
    selectElement.html(option);
    selectElement.css('background', selectElement.find(":selected").css('background'));
  });

  var color = '';
  $('#' + idElement).parent().find('input').val(color);
}

function addInputImage(idElement, i, value) {
  var element = $('#' + idElement);
  i = parseInt(i);
  i += 1;
  $.ajax({
    type: "POST",
    url: base_url + "admin/setting/ajax_load_item_album",
    data: {i: i, item: value},
    dataType: "html",
    success: function (inputImage) {
      element.append(inputImage);
      element.attr('data-id', i + 1);
      $('.fancybox').fancybox();
    }
  });
}

function removeInputImage(_this) {
  $(_this).parent().remove();
}

function load_lang(name) {
  var s = document.createElement("script");
  s.type = "text/javascript";
  s.src = base_url + "lang/load/" + name;
  $("head").append(s);
}

function box_alert(className, content) {
  $('#error-box').remove();
  var html = ' <div class="alert ' + className + ' alert-dismissible" id="error-box">';
  html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
  html += '<h4><i class="icon fa fa-ban"></i> Thông báo</h4>'
  html += content;
  html += '</div>';
  return html;
}

function create_slug(title, ele) {
  if (slug_disable) {
    return;
  }
  slug = title.toLowerCase();
  slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
  slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
  slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
  slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
  slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
  slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
  slug = slug.replace(/đ/gi, 'd');
  //slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
  slug = slug.replace(/[^a-zA-Z0-9 ]/g, "");
  slug = slug.replace(/ /gi, "-");
  slug = slug.replace(/\-\-\-\-\-/gi, '-');
  slug = slug.replace(/\-\-\-\-/gi, '-');
  slug = slug.replace(/\-\-\-/gi, '-');
  slug = slug.replace(/\-\-/gi, '-');
  slug = '@' + slug + '@';
  slug = slug.replace(/\@\-|\-\@|\@/gi, '');
  $(ele).val(slug);
}

function init_slug(listen, target) {
  $.each(lang_cnf, function (code, name) {
    //su kien paste
    $('#' + listen + '_' + code).on('paste', function (e) {
      setTimeout(function () {
        create_slug($('#' + listen + '_' + code).val(), '#' + target + '_' + code);
      }, 500);
    });
    //su kien keyup
    $('#' + listen + '_' + code).on('keyup', function (e) {
      create_slug($('#' + listen + '_' + code).val(), '#' + target + '_' + code);
    });
  });
}

function remove_checked_table() {
  $('#data-table-select-all').attr('checked', false);
  $('.chk_id').attr('checked', false);
}

function CopyHTMLToClipboard(_this) {
  $(_this).focus();
  $(_this).select();
}

function show_preview(url) {
  url = url + '?preview=true';
  window.open(url);
  /*$('#modal_preview iframe').attr("src",url);
  $('#modal_preview').on('show', function () {

  });
  $('#modal_preview').modal({show:true})*/
}

//init datatable
function init_data_table(status_order='') {
    //load table ajax
    var element = $('#data-table');
    table = element.DataTable({
      'ajax': {
        type: "POST",
        url: url_ajax_list+'/'+status_order,
        data: function (d) {
          return $.extend( {}, d, dataFilter );
        }
      },
      fixedHeader: true,
      'bProcessing': true,
      'serverSide': true,
      'dom': 'Bfrtip',
      'buttons': [],
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Vietnamese.json"
      },
      'columnDefs': [
      {
        'targets': 'no-sort',
        "orderable": false,
        'className': 'text-center'
      },
      {
        'targets': 0,
        'visible': element.hasClass("no_check_all") ? false : true,
        'searchable': false,
        'orderable': false,
        'className': 'dt-body-center',
        'render': function (data, type, full, meta){
          return '<input type="checkbox" class="chk_id" name="id[]" value="' + $('<div/>').text(data).html() + '">';
        }
      },
      {
        'targets': -1,
        'searchable': false,
        'orderable': false
      }
      ],
      'order': [[1, 'desc']],
      "fnDrawCallback": function () {
        $("a.fancybox").fancybox();
      }
    });
  }

  function updateSortDatatables() {
    $.extend(
      $.fn.dataTable.RowReorder.defaults,
      {dataSrc: 2, selector: 'td:not(:first-child, :last-child, :nth-child(5), :nth-child(6))'}
      );
    $.fn.dataTable.defaults.rowReorder = true;
    table.on('row-reorder', function (e, diff, edit) {
      var ien = diff.length;
      if(ien>=2){
        var rowData1 = table.row(diff[ien-1].node).data();
        var rowData2 = table.row(diff[ien-2].node).data();
        updateField(rowData1[1], 'order',rowData2[2]);
        updateField(rowData2[1], 'order', rowData1[2]);
      }
    });
  }

  function updateField(id, field, value) {
    $.ajax({
      type: "POST",
      url: url_ajax_update_field,
      data: {id: id, field: field, value: value},
      dataType: "JSON",
      success: function (response) {
        toastr[response.type](response.message);
        console.log(response);
        reload_table();
      }
    });
  }

  function filterDatatables(data) {
    dataFilter = data;
    reload_table();
  }

  function init_checkbox_table() {
  // checkbox check all
  $('#data-table-select-all').on('click', function () {
    var rows = table.rows({'search': 'applied'}).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
  });

  $('#data-table tbody').on('change', 'input[type="checkbox"]', function () {
    if (!this.checked) {
      var el = $('#data-table-select-all').get(0);
      if (el && el.checked && ('indeterminate' in el)) {
        el.indeterminate = true;
      }
    }
  });
}

//reload table
function reload_table() {
  table.ajax.reload(null, false); //reload datatable ajax
  //table.ajax.url('http://localhost/20_Again/admin/post/ajax_list/3').load();
}

//xoa mot ban ghi
function delete_item(id) {
  swal({
    title: language['mess_alert_title'],
    text: language['mess_confirm_delete'],
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: language['btn_yes'],
    cancelButtonText: language['btn_no'],
    closeOnConfirm: false
  }, function () {
    $.ajax({
      url: url_ajax_delete + "/" + id,
      type: "POST",
      dataType: "JSON",
      success: function (data) {
        if (data.type) {
          toastr[data.type](data.message);
        }
        swal.close();
        reload_table();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(textStatus);
        console.log(jqXHR);
      }
    });
  });
}

//xoa nhieu ban ghi
function delete_multiple() {
  swal({
    title: language['mess_alert_title'],
    text: language['mess_confirm_delete'],
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: language['btn_yes'],
    cancelButtonText: language['btn_no'],
    closeOnConfirm: false
  }, function () {
    var tmpArr = $('.chk_id:checkbox:checked').map(function () {
      return this.value;
    }).get();
    if (tmpArr.length > 0) {
      arrId = tmpArr;
      delete_queue(0);
    }
  });
}

//queue xoa tu ban ghi tuan tu
function delete_queue(index) {
  $.ajax({
    url: url_ajax_delete + "/" + arrId[index],
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      index++;
      if (index >= arrId.length) {
        arrId = [];
        remove_checked_table();
        swal.close();
        reload_table();
      } else {
        delete_queue(index);
      }
    }
  });
}

//sao chép nhieu ban ghi
function copy_multiple() {
  swal({
    title: language['mess_alert_title'],
    text: language['mess_confirm_copy'],
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-primary",
    confirmButtonText: language['btn_yes'],
    cancelButtonText: language['btn_no'],
    closeOnConfirm: false
  }, function () {
    var tmpArr = $('.chk_id:checkbox:checked').map(function () {
      return this.value;
    }).get();
    if (tmpArr.length > 0) {
      arrId = tmpArr;
      copy_queue(0);
    }
  });
}

//queue sao chep tu ban ghi tuan tu
function copy_queue(index) {
  $.ajax({
    url: url_ajax_copy + "/" + arrId[index],
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      if (data.type) {
        toastr[data.type](data.message);
      }
      index++;
      if (index >= arrId.length) {
        arrId = [];
        remove_checked_table();
        swal.close();
        reload_table();
      } else {
        copy_queue(index);
      }
    }
  });
}

//format 001
function pad(number, length) {

  var str = '' + number;
  while (str.length < length) {
    str = '0' + str;
  }

  return str;

}

function loadTinyMce() {
  tinymce.init(optionTinyMCE)
};

function initSEO() {
  console.log('Init SEO !');
  //  For title
  $("input[name^='meta_title']").keyup(function () {
    checkSEOTitle($(this));
  });
  //  For slug
  $("input[name^='slug']").keyup(function () {
    $(".gg-url").html(base_url + $(this).val());
  });
  //  For Focus Keywords
  $(".bootstrap-tagsinput input").keyup(function () {
    checkSEOKeyword($(this));
  });
  //  For Decriptions
  $("textarea[name^='meta_description']").keyup(function () {
    checkSEODesc($(this));
  });
  /*//Default check
  var text_ti = "Lorem Ipsum is simply dummy text of the printing happyy";
  var text_fk = "Focus Keyword";
  var text_ur = "http://example.com/your-title-url-<b>focus-keyword</b>-more-description";
  var text_de = "<b>Focus Keyword</b> with Lorem Ipsum is simply dummy text of the printing and typesetting industry. has been the industry's standard dummy text es verynice.";
  $(".gg-result").val(text_fk);
  $(".gg-title").html(text_ti);
  $(".gg-url").html(text_ur);
  $(".gg-desc").html(text_de);

  $("input[name^='meta_title'], .bootstrap-tagsinput input, textarea[name^='meta_title']").blur(function (){
      if($(this).val() == "" || $(this).val() == " "){
          $(".gg-result").val(text_fk);
          $(".gg-title").html(text_ti);
          $(".gg-url").html(text_ur);
          $(".gg-desc").html(text_de);
      }
    });*/

  //Check SEO
  $(".gg-url").html(base_url + $("input[name^='slug']").val());
  if ($("input[name^='meta_title']").length) checkSEOTitle("input[name^='meta_title']");
  if ($("input[name^='meta_description']").length) checkSEODesc("textarea[name^='meta_description']");
}

function checkSEOTitle(_this) {
  _this = $(_this);
  var c_title = _this.val().length;
  var l_title = $("span.count-title");
  $(l_title).html(c_title);
  if (c_title >= 40 && c_title <= 80) {
    _this.css({"color": colors[2], border: "3px solid" + colors[2]});
    $(l_title).css("color", colors[2])
  } else if (c_title >= 25 && c_title < 40) {
    _this.css({"color": colors[1], border: "3px solid" + colors[1]});
    $(l_title).css("color", colors[1])
  } else {
    _this.css({"color": colors[0], border: "3px solid" + colors[0]});
    $(l_title).css("color", colors[0])
  }
  var seo_title = _this.val();
  $(".gg-title").html(seo_title);
}

function checkSEODesc(_this) {
  _this = $(_this);
  var c_desc = _this.val().length;
  var l_desc = $(".count-desc");
  $(l_desc).html(c_desc);
  if (c_desc >= 120 && c_desc <= 150) {
    _this.css({"color": colors[2], border: "3px solid" + colors[2]});
    $(l_desc).css("color", colors[2])
  } else if (c_desc >= 90 && c_desc < 120) {
    _this.css({"color": colors[1], border: "3px solid" + colors[1]});
    $(l_desc).css("color", colors[1])
  } else {
    _this.css({"color": colors[0], border: "3px solid" + colors[0]});
    $(l_desc).css("color", colors[0])
  }
  var seo_desc = _this.val();
  $(".gg-desc").html(seo_desc);
}

function checkSEOKeyword(_this) {
  _this = $(_this);
  var c_key = _this.val().length;
  var l_key = $("span.count-key");
  $(l_key).html(c_key);
  if (c_key >= 10 && c_key <= 15) {
    _this.css({"color": colors[2], border: "3px solid" + colors[2]});
    $(l_key).css("color", colors[2])
  } else if (c_key >= 6 && c_key < 10) {
    _this.css({"color": colors[1], border: "3px solid" + colors[1]});
    $(l_key).css("color", colors[1])
  } else {
    _this.css({"color": colors[0], border: "3px solid" + colors[0]});
    $(l_key).css("color", colors[0])
  }
  var seo_key = _this.val();
  $(".gg-result").val(seo_key);
}

function analyticKeyword(_arrKey) {

}

function getParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
  results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function btnFly() {
  $("body").append('<style>.modal-footer-top-button{position:fixed;z-index:999999;top:0;right:50px;}</style>');
  var diaLogScroll = $('#modal_form'),
  diaLogScrollHeight = diaLogScroll.find('.modal-header').height(),
  diaLogScrollFooter = diaLogScroll.find('.modal-footer');
  diaLogScroll.find('.modal-footer').addClass('modal-footer-top-button');
  diaLogScroll.scroll(function () {
    if (diaLogScroll.scrollTop() <= diaLogScrollHeight + 35) {
      diaLogScrollFooter.addClass('modal-footer-top-button');
    } else {
      diaLogScrollFooter.removeClass('modal-footer-top-button');
    }
  });
}

function loadFilterCategory() {
  $("select.filter_category").select2({
    allowClear: true,
    placeholder: 'Select an item',
    ajax: {
      url: url_ajax_load,
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        console.log(data);
        return {
          results: data
        };
      },
      cache: true,
    }
  });
  $("select.filter_category, select#is_qty").on('change', function () {
    var id = $("select.filter_category").val();
    let id_qty = $('select#is_qty').val();
    var dataCategory = {parent_id: id, category_id: id, is_qty: id_qty};
    filterDatatables(dataCategory);
  });
}

function addBlock(idElement, i, value) {
  if ($('#' + idElement).length > 0) {
    var element = $('#' + idElement);
    i = parseInt(i) + 1;
    $.ajax({
      type: "POST",
      url: ajax_url_add_block + "?id=" + i,
      data: {i: i, item: value},
      dataType: "html",
      success: function (inputImage) {
        element.append(inputImage);
        element.attr('data-id', i);
      }
    });
  }
}
function addquestion(idElement, i, value) {
  if ($('#' + idElement).length > 0) {
    var element = $('#' + idElement);
    i = parseInt(i) + 1;
    $.ajax({
      type: "POST",
      url: ajax_url_load_question + "?id=" + i,
      data: {i: i, item: value},
      dataType: "html",
      success: function (inputImage) {
        element.append(inputImage);
        element.attr('data-id', i);
      }
    });
  }
}

function addBlockTour(idElement, i, value) {
  if ($('#' + idElement).length > 0) {
    var paramCheck = '';

    var element = $('#' + idElement);
    i = parseInt(i) + 1;
    $.ajax({
      type: "POST",
      url: ajax_url_add_block + "?id=" + i + paramCheck,
      data: {i: i, item: value},
      dataType: "html",
      success: function (inputImage) {
        // tinymce.remove();
        // tinymce.init(optionTinyMCE);

        element.append(inputImage);
          // load_media_tour( i,value);
          element.attr('data-id', i);
          setTimeout(function () {
            tinymce.init(optionTinyMCE);
          }, 100);
        }
      });
  }
}

function load_media_tour(i,value) {
  var element = $('#item_tour_' + i);
  $.ajax({
    type: "POST",
    url: ajax_url_add_media,
    data: {i: i,item: value},
    dataType: "html",
    success: function (data) {
      element.append(data);
    }
  });
}

function getYouTubeID(url){
  var ID = '';
  url = url.replace(/(>|<)/gi,'').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
  if(url[2] !== undefined) {
    ID = url[2].split(/[^0-9a-z_\-]/i);
    ID = ID[0];
  }
  else {
    ID = url;
  }
  return ID;
}

//format so 100,000,000
function number_format(number, decimals, dec_point, thousands_sep) {
  var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
  var d = dec_point == undefined ? "," : dec_point;
  var t = thousands_sep == undefined ? "." : thousands_sep, s = n < 0 ? "-" : "";
  var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;

  return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

//init lich date ranger
function initDateRanger() {
  var objDate = $('.date-ranger'),
  curYear = new Date().getFullYear();
  objDate.daterangepicker(
  {
    format: 'DD/MM/YYYY',
    ranges: {
      'Hôm nay': [moment(), moment()],
      'Hôm qua': [moment().subtract('days', 1), moment().subtract('days', 1)],
      'Tháng này': [moment().startOf('month'), moment().endOf('month')],
      'Tháng trước': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
      'Qúy 1': [moment(curYear+'-01-01'), moment(curYear+'-03-01').endOf('month')],
      'Qúy 2': [moment(curYear+'-04-01'), moment(curYear+'-06-01').endOf('month')],
      'Qúy 3': [moment(curYear+'-07-01'), moment(curYear+'-09-01').endOf('month')],
      'Qúy 4': [moment(curYear+'-10-01'), moment(curYear+'-12-01').endOf('month')],
    },
    locale: {
      format: "DD/MM/YYYY",
      separator: " - ",
      applyLabel: "Áp dụng",
      cancelLabel: "Thoát",
      fromLabel: "Từ",
      toLabel: "Đến",
      customRangeLabel: "Tùy chỉnh",
      daysOfWeek: [
      "CN",
      "T2",
      "T3",
      "T4",
      "T5",
      "T6",
      "T7"
      ],
      monthNames: [
      "Tháng 1",
      "Tháng 2",
      "Tháng 3",
      "Tháng 4",
      "Tháng 5",
      "Tháng 6",
      "Tháng 7",
      "Tháng 8",
      "Tháng 9",
      "Tháng 10",
      "Tháng 11",
      "Tháng 12"
      ],
      firstDay: 1
    }
  },
  function(start, end) {
    if(start.format('DD/MM/YYYY') == end.format('DD/MM/YYYY'))
      objDate.val(start.format('DD/MM/YYYY'));
    else
      objDate.val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
  }
  );
}