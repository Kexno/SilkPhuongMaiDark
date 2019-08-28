<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('getCatById')) {
  function getCatById($cateId)
  {
    $_this =& get_instance();
    $_this->load->model('category_model');
    $catModel = new Category_model();
    $_all_category = $catModel->getAll($_this->session->public_lang_code);
    $data = $catModel->getByIdCached($_all_category, $cateId);
    return $data;
  }
}

if (!function_exists('getListCate')) {
  //id product
  function getListCate()
  {
    $_this =& get_instance();
    $_this->load->model('category_model');
    $cate = new Category_model();
    $listCate=$cate->getData(['is_status'=>1,'type'=>'product']);
    return !empty($listCate)?$listCate:'';
  }
}

if (!function_exists('getPropertyByIdUnit')) {
  //id product
  function getPropertyByIdUnit($id)
  {
    $_this =& get_instance();
    $_this->load->model('property_model');
    $Property = new Property_model();
    $title = $Property->getPropertyId($id,$_this->session->public_lang_code);
    if(!empty($title)) return $title->title;
    else return '';
  }
}

if (!function_exists('gender')) {
  function gender($arg)
  {
    switch ($arg) {
      case '1':
      $data = 'Nam';
      break;
      case '2':
      $data = 'Nữ';
      break;
      default:
      $data = 'Khác';
      break;
    }
    return $data;
  }
}
if (!function_exists('is_status_order')) {
  function is_status_order($arg)
  {
      $_this =& get_instance();
    switch ($arg) {
      case '1':
      $data = lang('processing');
      break;
      case '2':
      $data = lang('processed');
      break;
      default:
      $data = lang('cencel');
      break;
    }
    return $data;
  }
}
if (!function_exists('show_name_products')) {
  function show_name_products($arg)
  {
    $count_arg=count($arg);
    $tail_str=$count_arg>3?'...  '.lang('and').($count_arg-3).lang('other_products') :'';
    $html='';
    foreach ($arg as $key => $value) {
        if ($key == $count_arg-1) {
            $html.=$value->title;
        } else {
            $html.=$value->title.', ';
        }
        if ($key == 2) {
            $html.=$tail_str;
            break;
        }
    }
    return $html.$tail_str;
  }
}
if (!function_exists('getNumberics')) {
  function getNumberics($arr)
  {
    $arrnews = end($arr);
    $arrnews = key($arr);

    preg_match_all('/\d+/', $arrnews, $matches);
    return (int)end($matches[0]);
  }
}
