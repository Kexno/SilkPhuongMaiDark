<?php

if (!function_exists('statusOrder')) {
  function statusOrder($status)
  {
    switch ($status) {
      case 1:
        $title = lang('dangcho');
        break;
      case 2:
        $title = lang('success_order');
        break;
      default:
        $title = lang('cancel');
        break;
    }
    return $title;
  }
}
if (!function_exists('methodPayment')) {
    function methodPayment($type){
      switch ($type){
        case 1:
          $title='COD';
          break;
          case 2:
          $title=lang('chuyenkhoan');
          break;
          case 3:
          $title=lang('tructuyen');
          break;
        default:
          $title='Momo';
          break;
      }
      return $title;
    }
}
