<?php
/**
 * Created by PhpStorm.
 * User: askeyh3t
 * Date: 8/12/2018
 * Time: 9:44 PM
 */
if (!function_exists('checkDateOrder')) {
//  So sánh 2 date
  /**
   * @param $date1 : thời đặt hàng
   * @param $date2 : thời gian hiện tại
   * @return bool
   */
  function checkDateOrder($date1, $date2)
  {
//    dd(strtotime($date2).'__'.strtotime('+1 day',strtotime($date1)));
    if (strtotime('+1 day',strtotime($date1)) >= strtotime($date2)) {
      return true;
    }else{
      return false;
    }
  }
}