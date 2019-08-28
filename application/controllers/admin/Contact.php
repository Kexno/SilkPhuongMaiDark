<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('contact_model');
        $this->_data = new Contact_model();
    }

    public function index()
    {
        $data['heading_title'] = 'Liên hệ';
        $data['heading_description'] = "Danh sách liên hệ";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $data['main_content'] = $this->load->view($this->template_path . 'contact/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_list()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $params['limit'] = $this->input->post('length');
            $params['offset'] = $this->input->post('start');
            $params['search'] = $this->input->post('search[value]');
            $list = $this->_data->getContact($params);
            $data = array();
            foreach ($list as $item) {
                $row = array();
                $row[] = $item->id;
                $row[] = $item->id;
                $row[] = $item->fullname;
                $row[] = $item->phone;
                $row[] = $item->email;
                $row[] = formatDate($item->created_time);
                $action = '<div class="text-center">';
                $action .= '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="' . $this->lang->line('btn_view') . '" onclick="view_item(' . $item->id . ')"><i class="fa fa-search-plus" aria-hidden="true"></i></a>&nbsp;';
                $action .= '&nbsp;<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="' . $this->lang->line('btn_remove') . '" onclick="delete_item(' . "'" . $item->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>';
                $action .= '</div>';
                $action = button_action($item->id, ['view','delete']);
                
                $row[] = $action;
                $data[] = $row;
            }

            $output = array(
                "draw" => $this->input->post('draw'),
                "recordsTotal" => $this->_data->countContact(),
                "recordsFiltered" => $this->_data->countContact($params),
                "data" => $data,
            );
            echo json_encode($output);
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

    public function ajax_view($id)
    {
        $item = $this->_data->getById($id);
        echo json_encode($item);
    }


}