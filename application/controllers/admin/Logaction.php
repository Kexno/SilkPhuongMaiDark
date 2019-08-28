<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Logaction extends Admin_Controller {

    protected $_data;
    protected $_name_controller;
    public function __construct()
    {
        parent::__construct();
        //tải file ngôn ngữ
        $this->lang->load('module');

        $this->load->model(['Log_action_model','users_model']);
        $this->_data = new Log_action_model();
        $this->user = new Users_model();
        $this->_name_controller = $this->router->fetch_class();
    }

    public function index()
    {
        $data['heading_title'] = "Quản lý hành vi";
        $data['heading_description'] = "Danh sách hành vi";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    /*
     * Ajax trả về datatable
     * */
    public function ajax_list()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $length = $this->input->post('length');
            $no = $this->input->post('start');
            $page = $no / $length + 1;
            $params['page'] = $page;
            $params['limit'] = $length;
            if ($this->input->post('user_id')) $params['user_id'] = $this->input->post('user_id');
            $list = $this->_data->getData($params);
            $data = array();
            if (!empty($list)) foreach ($list as $item) {
                $user = $this->user->get_by_id_user($item->uid);
                $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = $item->id;
                $row[] = $item->action;
                $row[] = $item->note;
                $row[] = !empty($user) ? $user->full_name : '';
                $row[] = $item->created_time;
                $data[] = $row;
            }
            $output = array(
                "draw" => $this->input->post('draw'),
                "recordsTotal" => $this->_data->getTotalAll(),
                "recordsFiltered" => $this->_data->getTotal(),
                "data" => $data,
            );
            //trả về json
            echo json_encode($output);
        }
        exit;
    }
    public function load_users(){
        $account_id = [];
        $dataJson   = [];
        $keyword    = $this->toSlug($this->input->get("q"));
        $keyword    = $this->toSlug($this->toNormal($keyword));
        $list_users = $this->_data->getData(['limit'=>1000]);
        if (!empty($list_users)) foreach ($list_users as $value) {
            $account_id[] = $value->uid;
        }
        $array_ac = array_unique($account_id);
        $data = $this->user->get_user($array_ac);
        if (!empty($data)) foreach ($data as $value) {
            if(!empty($keyword)){
                if(strpos($value->email,$keyword)!==false){
                    $dataJson[] = ['id'=>$value->id, 'text'=>$value->email.' ('.$value->full_name.')'];
                }
            }else{
                $dataJson[] = ['id'=>$value->id, 'text'=>$value->email.' ('.$value->full_name.')'];
            }
        }
        die(json_encode($dataJson));
    }
}