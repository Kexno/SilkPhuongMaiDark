<?php
/**
 * User: khuongkoi
 * Date: 19/02/2019
 * Time: 15:23
 */

class VnPay
{
    protected $_vnp_TmnCode = "";
    protected $_vnp_HashSecret = "";
    protected $_url_API_send = "https://pay.vnpay.vn/vpcpay.html";
    protected $_url_API_response = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
    protected $order;
    protected $_url_return;
    protected $_type; //Type payment: Thời trang, mã thẻ, ...

    public function __construct(){
        $this->ci =& get_instance();
        // Load config file
        $this->ci->load->config('payment');
        $this->ci->load->model('order_model');
        // Get breadcrumbs display options
        $this->_vnp_TmnCode = $this->ci->config->item('payment_vnPay_tmnCode');
        $this->_vnp_HashSecret = $this->ci->config->item('payment_vnPay_hashSecret');
        $this->_url_API_send = $this->ci->config->item('payment_vnPay_apiSend');
        $this->_url_API_response = $this->ci->config->item('payment_vnPay_apiResponse');
        $this->_url_return = $this->ci->config->item('payment_vnPay_return');
        $this->_type = $this->ci->config->item('payment_vnPay_type');
    }

    public function send($orderId, $orderInfo, $orderAmount, $bankCode = null){
        $vnp_TxnRef = $orderId; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toan don hang : ' .$orderInfo;
        $vnp_OrderType = $this->_type;
        $vnp_Amount = $orderAmount; //Số tiền đơn hàng
        $vnp_Locale = "vn";
        $vnp_BankCode = $bankCode;
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $this->_vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $this->_url_return,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->_url_API_send . "?" . $query;
        if (isset($this->_vnp_HashSecret)) {
            $vnpSecureHash = md5($this->_vnp_HashSecret . $hashdata);
            $vnp_Url .= 'vnp_SecureHashType=MD5&vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array('code' => '00', 'message' => 'success', 'data' => $vnp_Url);
        return json_encode($returnData);
    }

    public function response($request){
        $vnp_SecureHash = $request['vnp_SecureHash'];
        $inputData = array();
        foreach ($request as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHashType']);
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . $key . "=" . $value;
            } else {
                $hashData = $hashData . $key . "=" . $value;
                $i = 1;
            }
        }

        $secureHash = md5($this->_vnp_HashSecret . $hashData);
        if ($secureHash == $vnp_SecureHash) {
            if ($request['vnp_ResponseCode'] == '00') {
                $message['type'] = "success";
                $message['message'] = "Giao dịch thành công";
                $message['order_id']= $request['vnp_TxnRef'];
                $info_pay['trans_bank'] = $request['vnp_BankTranNo'];
                $info_pay['transaction_vnpay'] = $request['vnp_TransactionNo'];
                $info_pay['bank_code'] = $request['vnp_BankCode'];
                $info_pay['card_type'] = $request['vnp_CardType'];
                $info_pay['response'] = json_encode($request);
                $message['info_pay_bank'] = $info_pay;
                log_message('error',json_encode($request));
            } else {
                $message['type'] = "error";
                $message['message'] = "Giao dịch đã tồn tại hoặc đã được xử lý !";
                $message['order_id']= $request['vnp_TxnRef'];
                log_message('error',json_encode($request));
            }
        } else {
            $message['type'] = "warning";
            $message['message'] = "Chữ ký không hợp lệ";
            $message['order_id']= $request['vnp_TxnRef'];
            log_message('error',json_encode($request));
        }
        return json_encode($message);
    }

    public function ipn($request){
        $vnp_SecureHash = $request['vnp_SecureHash'];
        $inputData = array();
        foreach ($request as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHashType']);
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . $key . "=" . $value;
            } else {
                $hashData = $hashData . $key . "=" . $value;
                $i = 1;
            }
        }

        $secureHash = md5($this->_vnp_HashSecret . $hashData);
        try {
            if ($secureHash == $vnp_SecureHash) {
                $order_model = new Order_model();
                $trans = $order_model->getByIdPayment((int)$request['vnp_TxnRef']);
                if (!empty($trans)) {
                    if($trans->status_payment == 1 || $trans->status_payment == 2) {
                        $returnData['RspCode'] = '02';
                        $returnData['Message'] = 'Order already confirmed';
                        log_message('error', json_encode($request));
                    } else {

                        if ($request['vnp_ResponseCode'] == '00') {
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';
                            $returnData['order_id'] = $request['vnp_TxnRef'];

                            $info_pay['status_payment'] = 1;
                            $info_pay['is_status']      = 2;
                            $info_pay['trans_bank'] = $request['vnp_BankTranNo'];
                            $info_pay['transaction_vnpay'] = $request['vnp_TransactionNo'];
                            $info_pay['bank_code'] = $request['vnp_BankCode'];
                            $info_pay['card_type'] = $request['vnp_CardType'];
                            $info_pay['response'] = json_encode($request);
                            $returnData['info_pay_bank'] = $info_pay;
                            log_message('info', json_encode($request));
                        } else {
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'comfirm success status Fail';
                            log_message('error', json_encode($request));
                            $order_model->update(['id_payment'=>$request['vnp_TxnRef']], array('status_payment' => 2, 'response' => json_encode($request)));
                        }
                    }
                    
                }else{
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                    log_message('error', json_encode($request));
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Chu ky khong hop le';
                log_message('error',json_encode($request));
            }
        } catch (Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
        }
        return json_encode($returnData);
    }
}