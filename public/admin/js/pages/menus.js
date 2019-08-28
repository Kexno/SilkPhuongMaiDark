$(document).ready(function () {
  var updateOutput = function (e) {
    var list = e.length ? e : $(e.target),
      output = list.data('output');
    if (window.JSON) {
      return (window.JSON.stringify(list.nestable('serialize')));//, null, 2));
    } else {
      return false;
    }
  };


  $('.btnShowMenu').click(function () {
    var locationId = $('#menu_locations').val();
    var lang_code = $('#menu_languages').val();
    var elment = $(this);
    console.log(lang_code);
    $.ajax({
      type: 'POST',
      url: url_ajax_load,
      data: {location_id: locationId, lang_code: lang_code},
      dataType: 'html',
      beforeSend: function () {
        elment.find('.fa-spinner').show();
      },
      success: function (response) {
        $('#listContent').html(response);
        elment.find('.fa-spinner').hide();
        $('.select2').select2({
          allowClear: true,
          placeholder: 'Select an item'
        });
        showmenus(locationId, lang_code);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr);
        console.log(thrownError);
      }
    });
    return false;
  });

  // activate Nestable for list 1
  /*$('#nestable').nestable({
      group: 1
  }).on('change', function(e) {
      var locationId = $('#menu_locations').val();
      var lang_code = $('#menu_languages').val();
      //var structure = updateOutput($('#nestable').data('output', $('#output-menu')));
      var structure = $('#nestable').nestable('serialize');
      saveData(structure,locationId,lang_code);
  });*/

  $('#nestable-menu').on('click', function (e) {
    var target = $(e.target),
      action = target.data('action');
    if (action === 'expand-all') {
      $('.dd').nestable('expandAll');
    }
    if (action === 'collapse-all') {
      $('.dd').nestable('collapseAll');
    }
  });

  $(document).on('click', '.nestledeletedd', function () {
    var element = $(this).parent().parent();
    element.remove();
    element.find('ol.dd-list').remove();
    /*var locationId = $('#menu_locations').val();
    var lang_code = $('#menu_languages').val();
    var structure = $('#nestable').nestable('serialize');
    saveData(structure,locationId,lang_code);*/
  });

  $(document).on('click', '.addtonavmenu', function () {
    var _this = $(this);
    var paneActive = $('#listDataItem .tab-pane.active');
    _this.prop('disabled', true);
    var menu = paneActive.find('select option:selected').val();
    var menuName = $.trim(paneActive.find('select option:selected').text());
    var menuType = paneActive.find('input[name="type"]').val();
    $('#nestable > ol.dd-list').append('<li class="dd-item dd3-item" data-id="0" data-type="'+menuType+'" data-link="' + menu + '" data-label="' + menuName + '" data-cls=""><div class="dd-handle dd3-handle"></div><div class="dd3-content">' + menuName + '</div><div class="action-item"><span class="nestleeditd fa fa-pencil"></span> <span class="nestledeletedd fa fa-trash"></span></div>' + navmenuitemeditor() + '</li>');
    $('#nestable').nestable();
    var locationId = $('#menu_locations').val();
    var lang_code = $('#menu_languages').val();
    var structure = $('#nestable').nestable('serialize');
    //setTimeout(function () {
    //saveData(structure,locationId,lang_code);
    _this.prop('disabled', false);
    //showmenus(locationId,lang_code);
    //},1000);

  });

  $(document).on('click', '.nestleeditd', function () {
    var editmenu = $(this);
    //console.log(editmenu);
    var info = editmenu.closest("li");
    var mname = info.attr('data-label');
    var mlink = info.attr('data-link');
    var mclass = info.attr('data-cls');
    var micon = info.attr('data-icon');

    var editorblock = editmenu.next().next();
    editorblock.find('.mname').val(mname);
    editorblock.find('.mtarget').val(mlink);
    editorblock.find('.mclass').val(mclass);
    editorblock.find('.micon').val(micon);
    var parent = $(this).closest('li');
    parent.children('.menublock').find('.mname').prop('value', mname);
    parent.children('.menublock').find('.mtarget').prop('value', mlink);
    parent.children('.menublock').find('.mclass').prop('value', mclass);
    parent.children('.menublock').find('.micon').prop('value', micon);
    if (parent.children('.menublock').css('display') == 'none') {
      parent.children('.menublock').css('display', 'block');
    } else {
      hidemenueditingblock();
    }
  });

  $(document).on('click', '.updatenavmenu', function () {
    var editmenu = $(this);
    var editorblock = editmenu.closest("div.menublock");
    var mname = editorblock.find('.mname').val().trim();
    var mtarget = editorblock.find('.mtarget').val().trim();
    var mclass = editorblock.find('.mclass').val().trim();
    var micon = editorblock.find('.micon').val().trim();
    if (mname === "") {
      toastr['error']("Tên menu không được rỗng !");
      return false;
    }
    var info = editmenu.closest("li");
    info.data({
      label: mname,
      link: mtarget,
      cls: mclass,
      icon: micon
    });
    var locationId = $('#menu_locations').val();
    var lang_code = $('#menu_languages').val();
    var structure = $('#nestable').nestable('serialize');
    saveData(structure, locationId, lang_code);
  });

  $(document).on('click', '.btnSaveMenu', function () {
    var locationId = $('#menu_locations').val();
    var lang_code = $('#menu_languages').val();
    var structure = $('#nestable').nestable('serialize');
    if (structure.length == 0) {
      toastr['error']("Bạn chưa chọn Menu !");
      return false;
    }
    saveData(structure, locationId, lang_code);
  });

});


function showmenus(locationId, lang_code) {
  $.ajax({
    type: "GET",
    url: url_ajax_load_menu,
    data: {location_id: locationId, lang_code: lang_code},
    dataType: "json",
    cache: "false",
    success: function (result) {
      var webmenus = $('#nestable .dd-list');
      var contentMenu = _recursive_menu(result, 0);
      webmenus.html(contentMenu);
      $('#nestable').nestable();
    }
  });
}

function _recursive_menu(data, parent_id) {
  var contentMenu = '';
  var _child = '';
  if (data) $.each(data, function (i, v) {
    if (v && parseInt(v.level) == parseInt(parent_id)) {
      contentMenu += '<li class="dd-item" data-label="' + v.name + '" data-id="' + v.id + '" data-type="'+v.type+'" data-link="' + v.link + '" data-cls="' + v.class + '" data-icon="' + v.icon + '"><div class="dd-handle dd3-handle"></div> <div class="dd3-content">' + v.name + '</div><div class="action-item"><span class="nestleeditd fa fa-pencil"></span> <span class="nestledeletedd fa fa-trash"></span></div>' + navmenuitemeditor();
      _child = _recursive_menu(data, parseInt(v.id));
      if (_child) contentMenu += '<ol class="dd-list">';
      contentMenu += _child;
      if (_child) contentMenu += '</ol>';
      contentMenu += '</li>';
      delete data[i];
    }

  });
  return contentMenu;
}

function navmenuitemeditor(id) {
  var editorelement = '<div class="menublock" style="display: none"><input type="text" class="form-control requiredfields mname" required="required" value="" maxlength="75" placeholder="aaaa"><input type="text" class="form-control requiredfields mtarget" required="required" value="" maxlength="255" placeholder="target"><input type="text" class="form-control mclass" value="" maxlength="255" placeholder="class" style="margin-bottom: 2px"><input type="text" class="form-control micon" value="" maxlength="255" placeholder="Icon"><br/><input type="button" class="btn btn-theme updatenavmenu" value="Update" /><a href="#" class="cancelnavmenu">Cancel<a/></div>'

  return editorelement;
}


function hidemenueditingblock() {
  $('.menublock').hide();
}

function saveData(structure, loc, lang) {
  console.log(structure);
  console.log(loc);
  console.log(lang);
  $.ajax({
    type: "POST",
    url: url_ajax_save_menu,
    data: {loc: loc, lang: lang, s: structure},
    dataType: "json",
    cache: "false",
    success: function (result) {
      console.log(result);
      if (result == 1) {
        toastr['success']("Lưu thành công !");
      }
      else {
        toastr['error']("Lưu không thành công !");
      }
    }
  });
}