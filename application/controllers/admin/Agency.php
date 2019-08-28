<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Agency extends Admin_Controller {
    protected $_data;
    protected $_name_controller;
    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->lang->load('agency');
        $this->load->model('agency_model');
        $this->_data = new Agency_model();
        $this->_name_controller = $this->router->fetch_class();
    }
    public function index()
    {
        $data['heading_title'] = "Quản lý cửa hàng";
        $data['heading_description'] = "Thông tin cửa hàng";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path.$this->_name_controller.'/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_list()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $length = $this->input->post('length');
            $no = $this->input->post('start');
            $page = $no/$length + 1;
            $params['page'] = $page;
            $params['limit'] = $length;
            $list = $this->_data->getData($params);
            $data = array();
            $no = $this->input->post('start');
            if(!empty($list)) foreach ($list as $item) {
                $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = $item->id;
                $row[] = $item->name;
                $row[] = $item->address;
                $row[] = $item->phone;              
                $row[] = $item->lat;
                $row[] = $item->lng;
                switch ($item->is_status) {
                    case '1':
                    $row[] = '<div class="text-center"><span class="label label-success btnUpdateStatus" data-value="1" >Hiển thị</span></div>';
                    break;
                    case '2':
                    $row[] = '<div class="text-center"><span class="label label-default btnUpdateStatus" data-value="2" >Nháp</span></div>';
                    break;
                    case '0':
                    $row[] = '<div class="text-center"><span class="label label-danger btnUpdateStatus" data-value="0">Hủy</span></div>';
                    break;
                }
                //thêm action
                $action = button_action($item->id, ['edit','delete']);
                $row[] = $action;
                $data[] = $row;
            }
            $output = array(
                "draw" => $this->input->post('draw'),
                "recordsTotal" => $this->_data->getTotalAll(),
                "recordsFiltered" => $this->_data->getTotal($params),
                "data" => $data
            );
            //trả về json
            die(json_encode($output));
        }
        exit;
    }

    public function ajax_add()
    {
        $this->_validate();
        // $data = $this->input->post();
        $data = $this->_convertData();
        if($id_ace = $this->_data->save($data)){
            // log action
            $action = $this->router->fetch_class();
            $note = "Insert $action: ".$id_ace;
            $this->addLogaction($action,$note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_add_success');
        }else{
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_add_unsuccess');
        }
        die(json_encode($message));
    }

    /*
     * Ajax lấy thông tin
     * */
    public function ajax_edit($id)
    {
        $data = (array) $this->_data->getById($id);
        die(json_encode($data));
    }

    /*
     * Cập nhật thông tin
     * */
    public function ajax_update()
    {
        $this->_validate();
        $id = $this->input->post('id');
        $data = $this->input->post();
        $response = $this->_data->update(array('id'=> $id), $data);
        if($response != false){
            // log action
            $action = $this->router->fetch_class();
            $note = "Update $action: ".$this->input->post('id');
            $this->addLogaction($action,$note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_update_success');
        }else{
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_update_unsuccess');
        }
        die(json_encode($message));
    }


        /*
     * Xóa một bản ghi
     * */
        public function ajax_delete($id)
        {
            $response = $this->_data->delete(['id'=>$id]);
            if($response != false){
            // log action
                $action = $this->router->fetch_class();
                $note = "delete $action: $id";
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

        private function _validate(){
            if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                $this->form_validation->set_rules('name[' . $lang_code . ']', 'tên cửa hàng' . ' - ' . $lang_name, 'required|trim|min_length[5]|max_length[300]');
                $this->form_validation->set_rules('address[' . $lang_code . ']', 'địa chỉ' . ' - ' . $lang_name, 'required|trim|min_length[5]|max_length[300]');
                $this->form_validation->set_rules('phone', 'số điện thoại', 'required|trim|numeric');
                $this->form_validation->set_rules('lat', 'vĩ độ', 'required|trim|min_length[3]|max_length[300]');
                $this->form_validation->set_rules('lng', 'kinh độ', 'required|trim|min_length[3]|max_length[300]');
            }

            if ($this->form_validation->run() === false) {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = [];
                if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {  
                    $valid["name[$lang_code]"]       = form_error("name[$lang_code]");
                    $valid["address[$lang_code]"]    = form_error("address[$lang_code]");
                    $valid["phone"]                  = form_error("phone");
                    $valid["lat"]                    = form_error("lat");
                    $valid["lng"]                    = form_error("lng");           
                }
                $message['validation'] = $valid;
                $message['validation_message'] = validation_errors();
                die(json_encode($message));
            }
        }

        public function ajax_update_field()
        {
            if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $id = $this->input->post('id');
                $field = $this->input->post('field');
                $value = $this->input->post('value');
                $response = $this->_data->update(['id' => $id], [$field => $value]);

                if($response != false){
                    $message['type'] = 'success';
                    $message['message'] = $this->lang->line('mess_update_success');
                }else{
                    $message['type'] = 'error';
                    $message['message'] = $this->lang->line('mess_update_unsuccess');
                }
                print json_encode($message);
            }
            exit;
        }

        private function _convertData()
        {
            $this->_validate();
            $data = $this->input->post();
            unset($data['id']);
            return $data;
        }

    }