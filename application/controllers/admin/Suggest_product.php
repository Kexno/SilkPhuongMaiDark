<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Suggest_product extends Admin_Controller
{
    protected $_data;
    protected $_data_product;
    protected $_data_category;
    protected $_name_controller;
    protected $_type;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['suggest_product_model', 'product_model', 'category_model']);
        $this->_data = new Suggest_product_model();
        $this->_data_product = new Product_model();
        $this->_data_category = new Category_model();
        $this->config->load('suggest_product');
        $this->_name_controller = $this->router->fetch_class();
        $this->_type = $this->session->type;
    }

    public function index($data, $type = 'suggest')
    {
        $this->session->type = $type;
        $data['heading_title'] = ucfirst($this->_name_controller);
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/'.$type, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function suggest()
    {
        $params = [
            'category_type' => 'product',
            'is_status' => 1,
            'limit'=> 1000,
            'order' => array('created_time'=>'desc')
        ];
        $list = $this->_data_category->getData($params);
        $data['category'] = $this->_data_category->getListChildLv1($list);
        $data['heading_description'] = "Hôm nay mua gì?";
        $this->index($data, 'suggest');
    }

    public function set()
    {
        $data['heading_description'] = "Set sơ chế tuần này";
        $this->index($data, 'set');
    }

    public function ajax_load_product()
    {
        if (!empty($this->input->post('category_id'))) {
            $allCategory = $this->_data_category->getAll($this->session->admin_lang_code);
            $this->_data_category->_recursive_child_id($allCategory, $this->input->post('category_id'));
            $listCateId = $this->_data_category->_list_category_child_id;
        }
        $params['category_id'] = !empty($listCateId) ? $listCateId : null;
        $params['search'] = !empty($this->input->post('keyword')) ? $this->input->post('keyword') : '';
        $params['limit'] = 1000;
        $list = $this->_data_product->getData($params);
        $data = array();
        $html = null;
        foreach ($list as $value) {
            if (!empty($this->input->post('product'))) {
                if (in_array($value->id, $this->input->post('product'))) {
                    $item = null;
                    $item .= '<li class="select-product" data-id="'.$value->id.'">';
                    $item .= '<div class="imgs"> <img src="'.getImageThumb($value->thumbnail, 50, 50, '100%').'"/> </div>';
                    $item .= '<a>'.$value->title.'</a>';
                    $item .= '<div class="price">';
                    $item .= '<span>Giá&#32;thường:&#32;<b>'.$value->price.'</b>&#32;VNĐ</span>&#32;&mdash;&#32;';
                    $item .= '<span>Giá&#32;khuyến&#32;mãi:&#32;<b>'.$value->price_sale.'</b>&#32;VNĐ</span>';
                    $item .= '</div>';
                    $item .= '</li>';
                    $html .= $item;
                }
            } else {
                $item = null;
                $item .= '<li class="select-product" data-id="'.$value->id.'">';
                $item .= '<div class="imgs"> <img src="'.getImageThumb($value->thumbnail, 50, 50, '100%').'"/> </div>';
                $item .= '<a>'.$value->title.'</a>';
                $item .= '<div class="price">';
                $item .= '<span>Giá&#32;thường:&#32;<b>'.$value->price.'</b>&#32;VNĐ</span>&#32;&mdash;&#32;';
                $item .= '<span>Giá&#32;khuyến&#32;mãi:&#32;<b>'.$value->price_sale.'</b>&#32;VNĐ</span>';
                $item .= '</div>';
                $item .= '</li>';
                $html .= $item;
            }
        }
        $data['data'] = $html;
        die(json_encode($data));
    }

    public function ajax_load_category()
    {
        $this->checkRequestGetAjax();
        $term = $this->input->get("q");
        $params = [
            'category_type' => 'product',
            'is_status'=> 1,
            'search' => $term,
            'limit'=> 1000,
            'order' => array('created_time'=>'desc')
        ];
        $list = $this->_data_category->getData($params);
        $data = $this->_queue_select($list);
        $this->returnJson($data);
    }

    public function _queue_select($all, $parentId = 0){
        $data = array();
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->parent_id == $parentId){
                $row = array();
                $row['id'] = $item->id;
                $row['text'] = $item->title;
                $data[] = $row;
            }
        }
        return $data;
    }

    public function ajax_list()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $length = $this->input->post('length');
            $no = $this->input->post('start');
            $page = $no/$length + 1;
            $params['page'] = $page;
            $params['limit'] = $length;
            $params['order'] = array('updated_time'=>'DESC');
            $params['type'] = $this->_type;
            $list = $this->_data->getData($params);
            $data = array();
            if(!empty($list)) foreach ($list as $item) {
                $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = $item->id;
                $row[] = formatDate($item->displayed_time);
                $row[] = formatDate($item->created_time);
                $row[] = formatDate($item->updated_time);
                //thêm action
                $action = button_action($item->id, ['edit','delete']);
                
                $row[] = $action;
                $data[] = $row;
            }
            $output = array(
                "draw" => $this->input->post('draw'),
                "recordsTotal" => $this->_data->getTotalAll(),
                "recordsFiltered" => $this->_data->getTotal($params),
                "data" => $data,
            );
            //trả về json
            echo json_encode($output);
        }
        exit;
    }

    public function ajax_add()
    {
        $data_store = $this->_convertDataAdd();
        unset($data_store['id']);
        $data_store['type'] = $this->_type;
        $data_store['data'] = json_encode($data_store['data']);
        $data_store['displayed_time'] = date('Y-m-d', strtotime($data_store['displayed_time']));
        if($this->_data->save($data_store)){
            $action = $this->router->fetch_class();
            $note = "Insert $action: ".$this->db->insert_id();
            $this->addLogaction($action,$note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_add_success');
        }else{
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_add_unsuccess');
        }
        die(json_encode($message));
    }

    public function ajax_edit($id)
    {
        $data = (array) $this->_data->getById($id);
        $data['displayed_time'] = date('d-m-Y', strtotime($data['displayed_time']));
        die(json_encode($data));
    }
    public function ajax_update(){
        $data_store = $this->_convertDataEdit();
        $data_store['data'] = json_encode($data_store['data']);
        $data_store['type'] = $this->_type;
        $data_store['displayed_time'] = date('Y-m-d', strtotime($data_store['displayed_time']));
        $data_store['updated_time'] = date('Y-m-d H:i:s', time());
        $response = $this->_data->update(array('id' => $this->input->post('id')), $data_store, $this->_data->table);
        if($response != false){
            // log action
            $action = $this->router->fetch_class();
            $note = "Update $action: ".$data_store['id'];
            $this->addLogaction($action,$note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_update_success');
        }else{
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_update_unsuccess');
        }
        die(json_encode($message));
    }

    public function ajax_delete($id)
    {
        $response = $this->_data->delete(['id'=>$id]);
        if($response != false){
            $this->_data->delete(["id"=>$id],$this->_data->table_trans);
            // log action
            $action = $this->router->fetch_class();
            $note = "Update $action: $id";
            $this->addLogaction($action,$note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_delete_success');
        }else{
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_delete_unsuccess');
            $message['error'] = $response;
            log_message('error',$response);
        }
        die(json_encode($message));
    }

    private function _validateAdd()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->form_validation->set_rules('displayed_time', 'Ngày hiển thị', 'required|callback_check_unique|callback_check_current|xss_clean');
            if ($this->form_validation->run() === false) {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = [];
                $valid["displayed_time"] = form_error("displayed_time");

                $message['validation'] = $valid;
                die(json_encode($message));
            }
        }
    }

    private function _validateEdit()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->form_validation->set_rules('displayed_time', 'Ngày hiển thị', 'required|callback_check_current|callback_check_unique|xss_clean');
            if ($this->form_validation->run() === false) {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = [];
                $valid["displayed_time"] = form_error("displayed_time");

                $message['validation'] = $valid;
                die(json_encode($message));
            }
        }
    }

    public function check_unique($str)
    {
        $id = $this->input->post('id');
        $displayed_time = date('Y-m-d', strtotime($str));
        $params = [
            'type'           => $this->_type,
            'displayed_time' => $displayed_time,
        ];
        $data = $this->_data->getData($params);
        if (isset($id) && !empty($data) && $data[0]->id == $id) {
            return TRUE;
        } else {
            if (!empty($data)) {
                $this->form_validation->set_message('check_unique', 'Trường {field} đã tồn tại trên hệ thống');
                return FALSE;
            }
        }
        return TRUE;
    }

    public function check_current($str)
    {
        if (!empty($str)) {
            if (strtotime(date('Y-m-d')) > strtotime($str)) {
                $this->form_validation->set_message('check_current', 'Trường {field} phải lớn hơn hoặc bằng ngày hiện tại');
                return FALSE;
            }
        }
        return TRUE;
    }

    private function _convertDataAdd(){
        $this->_validateAdd();
        $data = $this->input->post();
        return $data;
    }

    public function _convertDataEdit(){
        $this->_validateEdit();
        $data = $this->input->post();
        return $data;
    }
}