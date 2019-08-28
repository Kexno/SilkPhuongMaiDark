<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends Public_Controller
{
	protected $_website_name;
	protected $_data;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('contact_model'));
		$this->_data 		= new Contact_model();
		$this->_website_name 		= $this->settings['name'];
		if ($this->input->get('lang'))
			$this->_lang_code = $this->input->get('lang');
	}

	public function submit()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$rules = array(
				array(
					'field' => 'fullname',
					'label' => lang('text_fullname'),
					'rules' => 'required|trim'
				),
				array(
					'field' => 'phone',
					'label' => lang('text_phone'),
					'rules' => 'required|trim|min_length[10]|max_length[12]|regex_match[/^(09|012|08|016|03|05|07|08)\d{8,}/]'
				)
			);
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == true) {
				$data=$this->input->post();
				$data['fullname']=strip_tags($data['fullname']);
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

    public function check_recaptcha($str)
    {
        $captcha_key = trim($str);
        if (empty($captcha_key)) {
            $this->form_validation->set_message('check_recaptcha', lang('required_recaptcha'));
            return FALSE;
        } else {
            $userIp=$this->input->ip_address();
            $url="https://www.google.com/recaptcha/api/siteverify?secret=".GG_CAPTCHA_SECRET_KEY."&response=".$captcha_key."&remoteip=".$userIp;
            $response = callCURL($url);

            $result = json_decode($response, true);
            if ($result['success']) {
                return TRUE;
            }else{
                $this->form_validation->set_message('check_recaptcha', lang('verify_recaptcha'));
                return FALSE;
            }
        }
    }

}
