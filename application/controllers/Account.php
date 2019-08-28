<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends Public_Controller
{
    protected $cid = 0;
    protected $_data;
    protected $_data_location;
    protected $_data_order;
    protected $_data_product;
    protected $_all_agency;
    protected $user_login;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_account');
        $this->lang->load('account');
        $this->load->library(array('ion_account', 'hybridauth'));
        $this->load->model(array('account_model', 'location_model', 'order_model', 'product_model'));
        $this->_data = new Account_model();
        $this->_data_product = new Product_model();
        $this->_data_location = new Location_model();
        $this->_data_order = new Order_model();
        if ($this->input->get('lang'))
            $this->_lang_code = $this->input->get('lang');

        $this->user_login = $this->_data->getById($this->_account);
        if (empty($this->user_login)) redirect(site_url());
    }

    public function index()
    {
        if ($this->input->get('lang')) {
            redirect(base_url($this->uri->uri_string));
        }
        $data['heading_title'] = $this->lang->line('pagePersonal');
        $data['oneAccount'] = $this->user_login;
        $user_address = json_decode($data['oneAccount']->address);
        if (!empty($user_address)) {
            foreach ($user_address as $key => $value) {
                $data_province = $this->_data_location->getCityById($value->province);
                $data_district = $this->_data_location->getDistrictById($value->district);
                $data_ward = $this->_data_location->getWardById($value->ward);
                $value->full_address = (!empty($value->address) ? $value->address . ', ' : '') . $data_ward->name_with_type . ', ' . $data_district->name_with_type . ', ' . $data_province->name_with_type;
            }
            $data['address'] = $user_address;
        }
        $data['avatar'] = array('avatar' => $this->user_login->avatar, 'oauth_provider' => $this->user_login->oauth_provider);
        /*Breadcrumbs*/
        $this->breadcrumbs->push(" <i class='fa fa-home'></i>", base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . 'account/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function history_order()
    {
        if ($this->input->get('lang')) {
            redirect(base_url($this->uri->uri_string));
        }
        $data['data'] = $this->_data_order->getData(['customer_id' => $this->_account]);
        if (!empty($data['data'])) {
            $this->getDataOrder($data['data']);
        }
        /*Breadcrumbs*/
        $this->breadcrumbs->push(lang('text_home'), base_url());
        $this->breadcrumbs->push(lang('account_text_my_account'), base_url('account'));
        $this->breadcrumbs->push(lang('account_text_order_history'), '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . 'account/history_order', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function detail_history_order($id)
    {
        if ($this->input->get('lang')) {
            redirect(base_url($this->uri->uri_string));
        }
        $data['data'] = $this->_data_order->get_order($id);
        if (!empty($data['data'])) {
            $this->getDataOrder($data['data']);
            if ($data['data']->customer_id != $this->user_login->id) {
                redirect(base_url('account/history_order'));
            }
        } else{
            redirect(base_url('account/history_order'));
        }
        $data['data']->city_id = $this->_data_location->getCityById($data['data']->city_id)->name_with_type;
        $data['data']->district_id = $this->_data_location->getDistrictById($data['data']->district_id)->name_with_type;
        $data['data']->ward_id = $this->_data_location->getWardById($data['data']->ward_id)->name_with_type;
        /*Breadcrumbs*/
        $this->breadcrumbs->push(lang('home'), base_url());
        $this->breadcrumbs->push('Tài khoản', '#');
        $this->breadcrumbs->push('Lịch sử mua hàng', base_url('account/history_order'));
        $this->breadcrumbs->push('Chi tiết đơn hàng', '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . 'account/detail_history_order', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function cancel_item($id, $id_item_order)
    {
        $data = $this->_data_order->getById($id);
        if ($data->is_status != 1 || empty($data) || $data->customer_id != $this->user_login->id) {
            redirect(base_url('account/history_order'));
        } else {
            $this->_data_order->update(['order_id' => $id, 'product_id' => $id_item_order], ['is_status' => 0] , 'ap_order_items');
            //get id_item_order
            $data_product=$this->_data_product->getById($id_item_order,'',$this->_lang_code);
            $this->send_mail_cancel($this->settings['email_admin'],'(Tomita-mart)Một sản phẩm thuộc đơn hàng '.$data->code.' đã bị hủy','<h2>Thông tin đơn hàng bị huỷ</h2>Sản phẩm: '.$data_product->title.'(thuộc đơn hàng: <b style="color: #ffa800;">'.$data->code.'</b>) đã bị hủy vào lúc:'. date("Y-m-d H:i:s").'<br>Vui lòng xem chi tiết <a href="'.base_url('admin/order').'">tại đây</a>');
            $_order=$this->_data_order->get_order_detail($id);
            if (empty($_order)) {
                $this->cancel_order($id);
            }
            redirect(base_url('account/detail_history_order/' . $id));
        }
    }

    public function cancel_order($id)
    {
        $data = $this->_data_order->getById($id);
        if ($data->is_status != 1) {
            redirect(base_url('account/history_order'));
        } else {
            $this->_data_order->update(['id' => $id], ['is_status' => 0]);
            $this->send_mail_cancel($this->settings['email_admin'],'(Tomita-mart)Một đơn hàng đã bị hủy('.date("Y-m-d H:i:s").')','<h2>Thông tin đơn hàng bị huỷ</h2>Đơn hàng <b style="color: #ffa800;">'.$data->code.'</b> đã bị hủy'.'<br>Vui lòng xem chi tiết <a href="'.base_url('admin/order').'">tại đây</a>');
            redirect(base_url('account/history_order'));
        }
    }


    private function send_mail_cancel($email, $subject, $content)
    {
        $this->load->library('email');
        $emailTo = $email;
        $emailFrom = 'apecsoft@gmail.com';
        $nameFrom = 'Tomita-mart';
        $this->email->from($emailFrom, $nameFrom);
        $this->email->to($emailTo);
        $this->email->subject($subject);
        $this->email->message(!empty($content) ? $content : 'error');
        $this->email->send();
    }

    public function detail()
    {
        if ($this->input->get('lang')) {
            redirect(base_url($this->uri->uri_string));
        }
        $data_user = json_decode($this->user_login->address);
        if (!empty($this->uri->segment(3))) {
            $id = $this->uri->segment(3);
            if (array_key_exists(($id - 1), $data_user)) {
                $data['oneAccount'] = $data_user[($id - 1)];
                $data['id'] = ($id - 1);
            } else {
                return redirect(base_url('/account'));
            }
            $data['province'] = $this->_data_location->getDataCity(array('limit' => 1000));
            $data['district'] = $this->_data_location->getDataDistrict(array('limit' => 1000, 'city_id' => $data['oneAccount']->province));
            $data['ward'] = $this->_data_location->getDataWard(array('limit' => 1000, 'district_id' => $data['oneAccount']->district));
            $data['button_title'] = lang('account_text_update');
            $data['detail_title'] = lang('account_detail_text_update_title');
        } else {
            if (empty($data_user)) {
                $data['oneAccount'] = $this->user_login;
            }
            $data['province'] = $this->_data_location->getDataCity(array('limit' => 1000));
            $data['button_title'] = lang('account_text_add');
            $data['detail_title'] = lang('account_detail_text_add_title');
        }
        $data['heading_title'] = $this->lang->line('pagePersonal');
        /*Breadcrumbs*/
        $this->breadcrumbs->push(lang('text_home'), base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . 'account/detail', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    private function compare($a, $b)
    {
        is_array($a) ? $value_a = $a['default'] : $value_a = $a->default;
        is_array($b) ? $value_b = $b['default'] : $value_b = $b->default;
        if ($value_a == $value_b) {
            return 1;
        }
        return ($value_a > $value_b) ? -1 : 1;
    }

    public function update_address()
    {
        $this->checkRequestPostAjax();
        $status = $this->input->post('status');
        $store_address = json_decode($this->user_login->address);
        if (empty($status)) {
            $data_store = $this->_convertAddress();
            if (empty($store_address)) {
                $store_address = array();
                $data_store['default'] = 1;
            } else {
                if (isset($data_store['default'])) {
                    foreach ($store_address as $value) {
                        $value->default = 0;
                    }
                    $data_store['default'] = 1;
                } else {
                    $data_store['default'] = 0;
                }
            }
            if (isset($data_store['id']) && $data_store['id'] == null) {
                unset($data_store['id']);
                array_push($store_address, $data_store);
                usort($store_address, array($this, "compare"));
                $res = $this->_data->update(array('id' => $this->user_login->id), array('address' => json_encode($store_address)));
                if ($res) {
                    $message['type'] = "success";
                    $message['message'] = lang('add_address_success');
                } else {
                    $message['type'] = 'warning';
                    $message['message'] = lang('add_address_error');
                }
            } else {
                $id = $data_store['id'];
                unset($data_store['id']);
                if (count($store_address) <= 1) {
                    $data_store['default'] = 1;
                }
                $store_address[$id] = $data_store;
                usort($store_address, array($this, "compare"));
                $res = $this->_data->update(array('id' => $this->user_login->id), array('address' => json_encode($store_address)));
                if ($res) {
                    $message['type'] = "success";
                    $message['message'] = lang('update_address_success');
                } else {
                    $message['type'] = 'warning';
                    $message['message'] = lang('update_address_error');
                }
            }
        } else {
            $data_store = $this->input->post('id');
            unset($store_address[$data_store]);
            $store_address = array_values($store_address);
            usort($store_address, array($this, "compare"));
            $res = $this->_data->update(array('id' => $this->user_login->id), array('address' => json_encode($store_address)));
            if ($res) {
                $message['type'] = "success";
                $message['message'] = lang('delete_address_success');
            } else {
                $message['type'] = 'warning';
                $message['message'] = lang('delete_address_error');
            }
        }

        $this->returnJson($message);
    }
    //
    //profile_
    public function change_password()
    {
        if ($this->session->is_account_logged != true) redirect();
        $data['heading_title'] = $this->lang->line('pagePersonal');

        $data['oneAccount'] = getUserAccountById($this->session->userdata('account')['account_id'], '', $this->session->public_lang_code);
        /*Breadcrumbs*/
        $this->breadcrumbs->push(" <i class='fa fa-home'></i>", base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/

        $data['main_content'] = $this->load->view($this->template_path . 'account/change_password', $data, TRUE);

        $this->load->view($this->template_main, $data);
    }

    public function update_password()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $pass_old = $this->input->post('pass_old');
            $pass = $this->input->post('password');

            $rules = array(
                array(
                    'field' => 'pass_old',
                    'label' => lang('old_password'),
                    'rules' => 'required|trim|min_length[6]'
                ),
                array(
                    'field' => 'password',
                    'label' => lang('new_password'),
                    'rules' => 'required|trim|min_length[6]|max_length[32]'
                ),
                array(
                    'field' => 'pass',
                    'label' => lang('re-password'),
                    'rules' => 'required|trim|matches[password]|min_length[6]|max_length[32]'
                )
            );

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() === TRUE) {
                if ($pass_old == $pass) {
                    $message['type'] = 'warning';
                    $message['message'] = lang('cannot_password');
                    die(json_encode($message));
                }
                $identity = $this->session->userdata['account']['account_identity'];
                $change = $this->ion_account->change_password($identity, $pass_old, $pass);
                if (!empty($change)) {
                    $message['type'] = "success";
                    $message['message'] = lang('change_password_s');
                } else {
                    $message['type'] = 'warning';
                    $message['message'] = lang('Please_check_password');
                }
            } else {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = array();
                if (!empty($rules)) foreach ($rules as $item) {
                    if (!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
                }
                $message['validation'] = $valid;
            }
            die(json_encode($message));
        }
    }

    public function update_profile()
    {
        $this->checkRequestPostAjax();
        $data_store = $this->_convertProfile();
        if (!empty($data_store['birthday'])) {
            $array = explode('/', $data_store['birthday']);
            $array = array_reverse($array);
            $data_store['birthday'] = implode('-', $array);
        }
        $response = $this->_data->update(array('id' => $this->user_login->id), $data_store);
        if ($response == false) {
            $message['type'] = 'warning';
            $message['message'] = lang('update_profile_error');
            $message['error'] = $response;
            log_message('error', $response);
        } else {
            $message['data'] = $data_store;
            $message['type'] = 'success';
            $message['message'] = lang('update_profile_successfully');
        }
        $this->returnJson($message);
    }

    public function _convertProfile()
    {
        $this->_validateProfile();
        $data = $this->input->post();
        return $data;
    }

    public function _convertAddress()
    {
        $this->_validateAddress();
        $data = $this->input->post();
        return $data;
    }

    public function _validateProfile()
    {
        $this->checkRequestPostAjax();
        $this->form_validation->set_rules('full_name', lang('text_fullname'), 'required|trim|max_length[300]');
        $this->form_validation->set_rules('phone', lang('text_phone'), 'required|trim|min_length[10]|max_length[12]|regex_match[/^(09|012|08|016|03|05|07|08)\d{8,}/]');
        if ($this->form_validation->run() === false) {
            $message['type'] = "warning";
            $message['message'] = $this->lang->line('mess_validation');
            $valid['full_name'] = form_error('full_name');
            $valid['phone'] = form_error('phone');
            $message['validation'] = $valid;
            $this->returnJson($message);
        }
    }

    public function _validateAddress()
    {
        $rules = array(
            array(
                'field' => 'full_name',
                'label' => lang('text_fullname'),
                'rules' => 'required|trim|min_length[5]|max_length[255]'
            ),
            array(
                'field' => 'phone',
                'label' => lang('text_phone'),
                'rules' => 'required|trim|min_length[10]|max_length[12]|regex_match[/^(09|012|08|016|03|05|07|08)\d{8,}/]'
            ),
            array(
                'field' => 'province',
                'label' => lang('account_text_province'),
                'rules' => 'required'
            ),
            array(
                'field' => 'district',
                'label' => lang('account_text_district'),
                'rules' => 'required'
            ),
            array(
                'field' => 'ward',
                'label' => lang('account_text_ward'),
                'rules' => 'required'
            ),
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === false) {
            $message['type'] = "warning";
            $message['message'] = $this->lang->line('mess_validation');
            $valid['full_name'] = form_error('full_name');
            $valid['phone'] = form_error('phone');
            $valid['province'] = form_error('province');
            $valid['district'] = form_error('district');
            $valid['ward'] = form_error('ward');
            $message['validation'] = $valid;
            $this->returnJson($message);
        }
    }

    public function logout()
    {
        $this->cart->destroy();
        $this->session->unset_userdata(array('account', 'avatar'));
        redirect('/', 'refresh');
    }

    private function getDataOrder(&$orders)
    {
        if (is_array($orders)) {
            foreach ($orders as $value) {
                $product_id = array();
                $total = 0;
                $products = $this->_data_order->get_order_detail($value->id);
                if (!empty($products))foreach ($products as $product) {
                    $total += $product->price * $product->quantity;
                    array_push($product_id, $product->product_id);
                }
                if (!empty($product_id))$value->products = $this->_data_product->getById($product_id, '*', $this->_lang_code);
                $value->total = $total;
            }
        } else {
            $total = 0;
            $products = $this->_data_order->get_order_detail($orders->id);
            if (!empty($products))foreach ($products as $product) {
                $total += $product->price * $product->quantity;
                $product->product = $this->_data_product->getById($product->product_id, '*', $this->_lang_code);
                $orders->product_item[] = $product;
            }
            $orders->total = $total;
        }

    }

}
