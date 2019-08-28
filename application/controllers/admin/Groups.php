<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Groups extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;
    protected $category_tree;

    const STATUS_CANCEL = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 2;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        //$this->load->library(array('ion_auth'));
        $this->lang->load('groups');
        $this->load->model('groups_model');
        $this->_data = new Groups_model();
        $this->_name_controller = $this->router->fetch_class();
    }

    public function index()
    {
        $data['heading_title'] = 'Nhóm';
        $data['heading_description'] = "Danh sách nhóm";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['controllers'] = glob(APPPATH."controllers".DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR."*.php");
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_load(){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $term = $this->input->get("q");
            $id = $this->input->get('id')?$this->input->get('id'):0;
            $params = [
                'search' => $term,
                'limit'=> 1000
            ];
            $list = $this->_data->getData($params);
            $json = [];
            if(!empty($list)) foreach ($list as $item) {
                $item = (object) $item;
                $json[] = ['id'=>$item->id, 'text'=>$item->name];
            }
            print json_encode($json);
        }
        exit;
    }
    /*
     * Ajax trả về datatable
     * */
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
            if(!empty($list)) foreach ($list as $item) {
                $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = $item->id;
                $row[] = $item->name;
                $row[] = $item->description;
                $action = '<div class="text-center">';
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
        $data_store = $this->_convertData();
        if($this->_data->save($data_store)){
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
    public function ajax_edit($id)
    {
        $data = (array) $this->_data->getById($id);
        die(json_encode($data));
    }
    public function ajax_get_auth($id){
        $data = $this->_data->getById($id);
        die($data->permission);
    }
    public function ajax_update()
    {
        $data_store = $this->_convertData();
        if (empty($data_store['permission'])) {
            $data_store['permission'] = '';
        }
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

    public function ajax_delete($id)
    {
        $response = $this->_data->delete(['id'=>$id]);
        if($response != false){
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

    /*
     * Kiêm tra thông tin post lên
     * */
    private function _validate()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            $this->form_validation->set_rules('name', 'tên nhóm', 'required|trim|min_length[3]|max_length[50]');

            if ($this->form_validation->run() === false) {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = [];
                $valid["name"] = form_error("name");
                $message['validation'] = $valid;
                die(json_encode($message));
            }
        }
    }

    private function _convertData(){
        $this->_validate();
        $data = $this->input->post();
        $data_store = [];
        if(!empty($data)) foreach ($data as $key => $item){
            if(is_array($item)) $data_store[$key] = json_encode($item);
            else $data_store[$key] = $item;
        }
        return $data_store;
    }

}
