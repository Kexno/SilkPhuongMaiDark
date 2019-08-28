<?php
/**
 * User: khuongkoi
 * Date: 19/02/2019
 * Time: 15:23
 */
//Cấu hình thanh toán VNPAYMENT
$config['payment_vnPay_tmnCode'] = 'KOWON001';
$config['payment_vnPay_hashSecret'] = 'PJPFNZSBRFRNJXPJEMONTRIYEOXDKTTI';
$config['payment_vnPay_apiSend'] = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
$config['payment_vnPay_apiResponse'] = 'http://sandbox.vnpayment.vn/merchant_webapi/merchant.html';
// $config['payment_vnPay_return'] = 'http://'.$_SERVER['HTTP_HOST'].'/checkout/response_callback';
$config['payment_vnPay_return'] = 'http://localhost:8080/newtech/checkout/response_callback';
$config['payment_vnPay_type'] = 190002;