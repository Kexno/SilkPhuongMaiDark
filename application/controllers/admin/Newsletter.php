<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('newsletter_model');
        $this->_data = new Newsletter_model();
    }

    public function index()
    {
        $data['heading_title'] = 'Newsletter';
        $data['heading_description'] = "Danh sách newsletter";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $data['main_content'] = $this->load->view($this->template_path . 'newsletter/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_list()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $params['limit'] = $this->input->post('length');
            $params['offset'] = $this->input->post('start');
            $params['search'] = $this->input->post('search[value]');
            $list = $this->_data->getData($params);
            $data = array();
            foreach ($list as $item) {
                $row = array();
                $row[] = $item->id;
                $row[] = $item->id;
                $row[] = $item->fullname;
                $row[] = $item->phone;
                $row[] = formatDate($item->created_time);
                $action = button_action($item->id, ['delete']);
                $row[] = $action;
                $data[] = $row;
            }

            $output = array(
                "draw" => $this->input->post('draw'),
                "recordsTotal" => $this->_data->getTotalAll(),
                "recordsFiltered" => $this->_data->getTotal($params),
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

}