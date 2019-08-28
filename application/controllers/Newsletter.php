<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter extends Public_Controller
{
    protected $_data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('newsletter_model');
        $this->_data = new Newsletter_model();
    }

    public function submit()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $rules = array(
                array(
                    'field' => 'fullname',
                    'label' => 'Họ và tên',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'phone',
                    'label' => lang('text_phone'),
                    'rules' => 'required|trim|min_length[10]|max_length[12]|regex_match[/^(09|012|08|016|03|05|07|08)\d{8,}/]'
                )
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == true) {
              $this->load->model('newsletter_model');
              $newsletterModel = new Newsletter_model();
              $data = $this->input->post();
              $result=$this->_data->insert($data);
              if($result==1){
                $message['type'] = "success";
                $message['message'] = lang('mess_send_success');
            }else{
                $message['type'] = "warning";
                $message['message'] = lang('mess_send_unsuccess');
            }
            die(json_encode($message));
        }else{
            $message['type'] = "warning";
            $message['message'] = lang('mess_validation');
            $valid = array();
            if(!empty($rules)) foreach ($rules as $item){
                if(!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
            }
            $message['validation'] = $valid;
            die(json_encode($message));
        }
    }
}
}
