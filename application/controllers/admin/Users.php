<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends Admin_Controller {
    protected $_data;
    protected $_data_group;
    protected $_name_controller;
    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->load->library(array('ion_auth'));
        $this->lang->load('user');
        $this->load->model(['users_model','groups_model']);
        $this->_data = new Users_model();
        $this->_data_group = new Groups_model();
        $this->_name_controller = $this->router->fetch_class();
    }
    public function index()
    {
        $data['heading_title'] = "Quản lý thành viên";
        $data['heading_description'] = "Thông tin thành viên";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['group_user']=$this->_data_group->get_all_group();
        $data['main_content'] = $this->load->view($this->template_path.$this->_name_controller.'/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }
    public function logged(){
        $data = [];
        $data['main_content'] = $this->load->view($this->template_path.$this->_name_controller.'/logged', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }
    public function profile()
    {
        $data['data'] = (array) $this->_data->getById($this->session->user_id);
        $data['main_content'] = $this->load->view($this->template_path.$this->_name_controller.'/profile', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }
    /*
     * Ajax trả về datatable
     * */
    public function ajax_list()
    {
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
            $row[] = $item->username;
            $row[] = $item->email;
            $row[] = $item->last_name.' '.$item->first_name;
            $row[] = $item->active ? '<div class="text-center"><span class="label label-success">Đang hoạt động</span></div>' : '<div class="text-center"><span class="label label-danger">Ngừng hoạt động</span></div>';
            //thêm action
            $action = '<div class="text-center">';
            $action .= '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="'.$this->lang->line('btn_edit').'" onclick="edit_form('."'".$item->id."'".')"><i class="glyphicon glyphicon-pencil"></i></a>';
            if($item->id == 1) {
                $action .= '&nbsp;<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="' . $this->lang->line('btn_remove') . '"><i class="glyphicon glyphicon-lock"></i></a>';
            }else{
                $action .= '&nbsp;<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="' . $this->lang->line('btn_remove') . '" onclick="delete_item(' . "'" . $item->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>';
            }
            $action .= '</div>';
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
        die(json_encode($output));
    }
    //Load user logged devide recently
    public function ajax_list_logged(){
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $length = $this->input->post('length');
            $no = $this->input->post('start');
            $page = $no/$length + 1;
            $params['page'] = $page;
            $params['Product'] = ['updated_time'=>'DESC'];
            $params['limit'] = $length;
            $list = $this->_data->getDataLoggedDevice($params);
            $data = array();
            foreach ($list as $item) {
                $oneUser = getUser($item->user_id);
                $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = isset($oneUser->username)?$oneUser->username:'';
                $row[] = $item->ip_address;
                $row[] = $item->user_agent;
                $row[] = date('d/m/Y H:i',strtotime($item->created_time));
                $row[] = date('d/m/Y H:i',strtotime($item->updated_time));
                $data[] = $row;
            }

            $output = array(
                "draw" => $this->input->post('draw'),
                "recordsTotal" => $this->_data->getTotalAllLoggedDevice(),
                "recordsFiltered" => $this->_data->getTotalLoggedDevice($params),
                "data" => $data,
            );
            //trả về json
            die(json_encode($output));
        }
        exit;
    }

    /*
     * Ajax lấy thông tin
     * */
    public function ajax_edit($id)
    {
        $data = (array) $this->_data->getById($id);
        $data['group_id'] = $this->ion_auth->get_users_groups($id)->row()->id;
        die(json_encode($data));
    }

    /*
     * Ajax xử lý thêm mới
     * */
    public function ajax_add()
    {
        $this->_validate();
        $identity = strip_tags(trim($this->input->post('username')));
        $password = strip_tags(trim($this->input->post('password')));
        $email    = strip_tags(trim($this->input->post('email')));
        $data_store['last_name'] = $this->input->post('last_name');
        $data_store['first_name'] = $this->input->post('first_name');
        $data_store['phone'] = $this->input->post('phone');
        $data_store['company'] = $this->input->post('company');
        $group_id = 2;
        if($this->input->post('group_id')){
            $group_id = strip_tags(trim($this->input->post('group_id')));
        }
        $data_store['active']=1;
        if($this->ion_auth->register($identity, $password, $email, $data_store, array('group_id' => $group_id)) !== false){
            // log action
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

    /*
     * Cập nhật thông tin
     * */
    public function ajax_update()
    {
        $this->_validate();
        $data_store = $this->input->post();
        $data_store['active'] = $this->input->post('active');
        $response = $this->ion_auth->update($this->input->post('id'), $data_store);
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
        if($id == 1) return;
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
            if ($lang_code === $this->config->item('default_language')) {
                if (empty($this->input->post('id')) || !empty($this->input->post('username'))) {
                    $this->form_validation->set_rules('username', 'tài khoản', 'required|trim|min_length[3]|max_length[300]');
                }else{
                    $this->form_validation->set_rules('username', 'tài khoản', 'trim|min_length[3]|max_length[300]');
                }
                $this->form_validation->set_rules('first_name', 'tên', 'trim');
                if (empty($this->input->post('id')) || !empty($this->input->post('email'))) {
                    $this->form_validation->set_rules('email', 'email', 'required|trim|valid_email');
                }else{
                    $this->form_validation->set_rules('email', 'email', 'trim|valid_email');
                }
                if(empty($this->input->post('id')) || !empty($this->input->post('password'))){
                    $this->form_validation->set_rules('password', 'mật khẩu', 'required|trim|min_length[8]|max_length[20]');
                    $this->form_validation->set_rules('repassword', 'nhập lại mật khẩu', 'required|trim|min_length[8]|max_length[20]|matches[password]');
                    
                }
                $this->form_validation->set_rules('phone', 'số điện thoại', 'trim|required|min_length[6]|max_length[20]|regex_match[/^[0-9]{10,11}$/]');
                if($this->input->post('group_id')) {
                    $group_id = strip_tags(trim($this->input->post('group_id')));
                    $this->ion_auth->remove_from_group(NULL,$this->input->post('id'));
                    if(!empty($this->input->post('id'))){
                        $this->ion_auth->add_to_group($group_id,$this->input->post('id'));
                    }
                }
            }
        }

        if ($this->form_validation->run() === false) {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = [];
                if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                    if ($lang_code === $this->config->item('default_language')) {
                        $valid["username"]       = form_error("username");
                        $valid["phone"]          = form_error("phone");
                        if(empty($this->input->post('id')) || !empty($this->input->post('password'))){
                            $valid["password"]       = form_error("password");
                            $valid["repassword"]     = form_error("repassword");
                        }
                        $valid["email"]          = form_error("email");
                    }
                }
            $message['validation'] = $valid;
            die(json_encode($message));
        }
    }
}