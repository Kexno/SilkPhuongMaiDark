<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends Public_Controller
{
    protected $order;
    protected $_data_pr;
    protected $_data_ac;
    protected $_data_location;
    protected $_data_account;
    protected $_account_id;
    protected $_website_name;

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('cart');
        $this->load->library('cart');
        $this->load->model(['order_model', 'account_model', 'product_model', 'location_model']);
        $this->order = new Order_model();
        $this->_data_pr = new Product_model();
        $this->_data_ac = new Account_model();
        $this->_data_location = new Location_model();
        $this->_lang_code = $this->session->public_lang_code;
        $this->_account_id = $this->_account;
        if ($this->input->get('lang'))
            $this->_lang_code = $this->input->get('lang');
        if (!empty($this->_account_id)) {
            $this->_data_account = $this->_data_ac->getById($this->_account_id);
            if (!empty($this->_data_account->address)) {
                $this->_data_account->address = json_decode($this->_data_account->address)[0];
            }
        }
        $this->_website_name = $this->settings['name'];
    }

    public function ordered()
    {
        $data_order = $this->_convertData();
        $data_detail = $this->cart->contents();
        $data_order['customer_id'] = $this->_account_id;
        $data_order['code'] = 'TM' . mt_rand(100000, 999999);
        $result = $this->order->saveOrder(['order_info' => $data_order, 'order_detail' => $data_detail]);
        $data_order['address'] = $data_order['address'] . ', ' . $this->_data_location->getWardById($data_order['ward_id'])->name_with_type
            . ', ' . $this->_data_location->getDistrictById($data_order['district_id'])->name_with_type
            . ', ' . $this->_data_location->getCityById($data_order['city_id'])->name_with_type;
        $email_admin = $this->settings['email_admin'];
        $data_order['link'] = base_url('admin/order');
        $response = sendMail_order('', $email_admin, '[' . $this->_website_name . '] - THÔNG BÁO ĐƠN HÀNG', 'order', $data_order);
        $data_order['link']=base_url();
        $response_cus = sendMail_order('', $data_order['email'], '[' . $this->_website_name . '] - THÔNG BÁO ĐƠN HÀNG', 'order', $data_order);
        $this->cart->destroy();
        if (!empty($result) && $response&&$response_cus) {
            $message = ['type' => 'success', 'message' => lang('send_order')];

        } else {
            $message = ['type' => 'warning', 'message' => lang('mess_validation')];
        }
        $this->returnJson($message);
    }

    private function _validate()
    {
        $this->checkRequestPostAjax();
        $rules = array(
            array(
                'field' => 'email',
                'label' => lang('text_email'),
                'rules' => 'required|trim|valid_email'
            ),
            array(
                'field' => 'full_name',
                'label' => lang('text_fullname'),
                'rules' => 'required|trim|min_length[6]|max_length[100]|xss_clean'
            ),
            array(
                'field' => 'phone',
                'label' => lang('text_phone'),
                'rules' => 'required|trim|min_length[9]|max_length[12]|regex_match[/^(09|03|012|08|016)\d{8,}/]|xss_clean'
            ),
            array(
                'field' => 'address',
                'label' => lang('text_address'),
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'city_id',
                'label' => lang('account_text_province'),
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'district_id',
                'label' => lang('account_text_district'),
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'ward_id',
                'label' => lang('account_text_ward'),
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'note',
                'label' => '',
                'rules' => 'xss_clean'
            )
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === false) {
            $message['type'] = "warning";
            $message['message'] = $this->lang->line('mess_validation');
            $valid = array();
            if (!empty($rules)) foreach ($rules as $item) {
                if (!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
            }
            $message['validation'] = $valid;
            die(json_encode($message));
        }
    }

    private function _convertData()
    {
        $this->_validate();
        $data = $this->input->post();
        unset($data['id']);
        return $data;
    }

    public function payment()
    {
        if ($this->input->get('lang')) redirect(base_url('cart/payment'));
        $data['content_cart'] = $this->cart->contents();
        foreach ($data['content_cart'] as $key => $value) {
            $product = $this->_data_pr->getById($value['id'], '*', $this->_lang_code);
            $data['content_cart'][$key]['name'] = $product->title;
        }
        $data['total_price'] = $this->cart->total();
        $data['province'] = $this->_data_location->getDataCity(array('limit' => 1000,'order'=>['slug'=>'ASC']));
        if (!empty($this->_data_account->address)) {
            $data['district'] = $this->_data_location->getDataDistrict(array('limit' => 1000, 'city_id' => $this->_data_account->address->province,'order'=>['slug'=>'ASC']));
            $data['ward'] = $this->_data_location->getDataWard(array('limit' => 1000, 'district_id' => $this->_data_account->address->district,'order'=>['slug'=>'ASC']));
        }
        if (!empty($this->_account)) $data['account'] = $this->_data_account;
        $data['main_content'] = $this->load->view($this->template_path . 'cart/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function add()
    {
        $data = $this->input->post();
        $product = $this->_data_pr->getById($data['id'], 'id,title,price_sale,thumbnail,price', $this->_lang_code);
        $data = array(
            'id'      => $product->id,
            'qty'     => $data['num'],
            'price'   => $product->price_sale,
            'name'    => 'aa',
            'options' => array(
                'thumbnail' => $product->thumbnail,
                'price'     => $product->price,
                'sale_up'   => $product->sale_up,
                'slug'      => $product->slug
            )
        );
        $result = $this->cart->insert($data);
        $html = $this->load_pop_cart();
        if (!empty($result)) die(json_encode(['type' => 'success', 'message' => lang('add_ss_cart'), 'data_cart' => $html]));
        else die(json_encode(['type' => 'warning', 'message' => lang('mess_validation')]));
    }

    public function update()
    {
        $this->checkRequestPostAjax();
        $data = $this->input->post();
        $result = $this->cart->update(['rowid' => $data['id'], 'qty' => $data['num']]);
        $html = $this->load_pop_cart();
        if (!empty($result)) die(json_encode(['type' => 'success', 'message' => lang('update_cart'), 'data_cart' => $html]));
        else die(json_encode(['type' => 'warning', 'message' => lang('mess_validation')]));
    }

    public function delete()
    {
        $this->checkRequestPostAjax();
        $data = $this->input->post();
        $result = $this->cart->remove($data['id']);
        $html = $this->load_pop_cart();
        if (!empty($result)) die(json_encode(['type' => 'success', 'message' => lang('update_cart'), 'data_cart' => $html]));
        else die(json_encode(['type' => 'warning', 'message' => lang('mess_validation')]));
    }

    public function load_pop_cart($_valid = '')
    {
        $data_cart = $this->cart->contents();
        foreach ($data_cart as $key => $value) {
            $product = $this->_data_pr->getById($value['id'], '*', $this->_lang_code);
            $data_cart[$key]['name'] = $product->title;
        }
        $data_total_price = $this->cart->total();
        $data_total = $this->cart->total_items();
        $html = $this->load->view($this->template_path . 'cart/view_detail_cart', ['data_cart' => $data_cart, 'data_total' => $data_total, 'data_total_price' => $data_total_price], TRUE);
        if (!empty($_valid)) die(json_encode($html));
        return $html;
    }

    public function total_item_cart()
    {
        $data = $this->cart->total_items();
        die(json_encode($data));
    }

}