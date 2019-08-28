<?php 
/**
 * User: linhth
 * Date: 13/03/2019
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('formatMoney')) {
	/*
	* hàm định dạng tiền tệ
	* number: số tiền
	* unit:   đơn vị
	* prefix: đợn vị đặt ở đầu hay cuối ví dụ như $ đặt ở đầu
	* decimals: số sau dấu phảy
	*/
    function formatMoney($number, $unit = 'đ', $prefix = false, $decimals = 2){
		return $prefix ? number_format($number,$decimals,'.',',').' '.$unit : $unit.number_format($number,$decimals,'.',',');
	}
}