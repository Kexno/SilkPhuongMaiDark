<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Public_Controller
{
    protected $cid = 0;
    protected $_all_agency;
    protected $zalo;
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('ion_account', 'hybridauth'));
        $this->load->model(array('account_model', 'category_model'));
        $this->_data = new Account_model();

    }

    public function login()
    {
        $data = array();
        $this->sb_login();
        $data['main_content'] = $this->load->view($this->template_path . 'account/login', $data, TRUE);
        $this->load->view($this->template_account, $data);
    }

    public function register()
    {
        $data = array();
        $this->sb_register();
        $data['link_zalo'] = $this->getUrlLogin();
        $data['main_content'] = $this->load->view($this->template_path . 'auth/register', $data, TRUE);
        $this->load->view($this->template_account, $data);
    }

    public function forget_pass()
    {
        $email = $this->input->post('email');
        $rules = array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|trim|valid_email'
            )
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            //check mail 
            $check_mail = $this->_data->check_oauth('email', $email);
            if (empty($check_mail)) {
                die(json_encode(['type' => 'warning', 'message' => lang('email_not_pass')]));
            }
            //send mail
            $created_time = time();
            $strEncrypted = md5($email . $created_time);
            $link_forget = base_url('?token=' . $strEncrypted);
            //import into the database
            $this->_data->update(['email' => $email, 'oauth_provider' => NULL, 'oauth_uid' => NULL], ['forgotten_password_code' => $strEncrypted, 'forgotten_password_time' => $created_time]);
            $content = 'Ấn vào link để xác thực <a href="' . $link_forget . '" target="_blank" >Ấn vào đây</a>';
            $this->send_mail_forget($email, 'Tomita-mart gửi thông tin xác thực tài khoản', $content);
        } else {
            $message['type'] = "warning";
            $message['message'] = $this->lang->line('mess_validation');
            $message['validation'] = form_error($rules[0]['field']);;
            die(json_encode($message));
        }
    }

    private function send_mail_forget($email, $subject, $content, $type = '')
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('email');
            $emailTo = $email;
            $emailFrom = 'apecsoft@gmail.com';
            $nameFrom = 'Tomita-mart';
            $this->email->from($emailFrom, $nameFrom);
            $this->email->to($emailTo);
            $this->email->subject($subject);
            $this->email->message(!empty($content) ? $content : 'error');
            if (empty($type)) {
                if (!$this->email->send()) {
                    $message['type'] = 'warning';
                    $message['message'] = 'Gửi thông tin thất bại !';
                } else {
                    $message['type'] = 'success';
                    $message['message'] = lang('check_send_mail');
                }
                die(json_encode($message));
            } else {
                $this->email->send();
            }
        }
    }

    public function updatePassword()
    {
        $pass_forget_code = $this->input->post('check_forget');
        $account = $this->_data->check_oauth('forgotten_password_code', $pass_forget_code);
        $pass = mt_rand(100000, 999999);
        $hashPass = $this->ion_account->hash_password($pass, true);
        $verify = $this->_data->update(['id' => $account->id], ['password' => $hashPass, 'forgotten_password_code' => '']);
        $this->send_mail_forget($account->email, 'Cập nhật mật khẩu mới', 'Mật khẩu mới của bạn là: ' . $pass, 'send_mail');
        if ($verify) {
            die(json_encode(['type' => 'success', 'message' => lang('successful_authentication'), 'pass' => $pass]));
        } else {
            die(json_encode(['type' => 'warning', 'message' => lang('mess_validation')]));
        }
    }

    public function sb_login()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->library('ion_account');
            $this->load->model('Account_model');
            $accountModel = new Account_model();
            $message = array();
            $rules = array(
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'required|trim|valid_email'
                ),
                array(
                    'field' => 'password',
                    'label' => lang('text_password'),
                    'rules' => 'required|trim'
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() === TRUE) {
                $remember = $this->input->post('remember');
                if ($account = $this->ion_account->login($this->input->post('email'), $this->input->post('password'), $remember)) {
                    //if the login is successful
                    //redirect them back to the home page
                    $account = $accountModel->getById($account->id, '', $this->session->public_lang_code);
                    // $rule = $accountModel->getRule($account->id);
                    switch ($account->active) {
                        case '1':
                        $account = array(
                            'account' => array(
                                'full_name' => $account->full_name,
                                'email'     => $account->email,
                                'id'        => $account->id,
                                'user_id'   => $account->id
                            )
                        );
                        $this->session->set_userdata($account);
                        die(json_encode(array(
                            'message' => lang('successfully'),
                            'type'    => 'success'
                        )));
                        break;
                        case '2':
                        die(json_encode(array(
                            'message' => lang('pending_approval'),
                            'type'    => 'warning'
                        )));
                        break;
                        default:
                        die(json_encode(array(
                            'message' => lang('locked_'),
                            'type'    => 'warning'
                        )));
                        break;
                    }
                } else {
                    // if the login was un-successful
                    die(json_encode(array(
                        'message' => strip_tags($this->ion_account->errors()),
                        'type'    => 'warning'
                    )));
                }
            } else {
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
    }

    public function sb_register()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $rules = array(
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'required|trim|valid_email'
                ),
                array(
                    'field' => 'full_name',
                    'label' => lang('text_fullname'),
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'password',
                    'label' => lang('text_password'),
                    'rules' => 'required|trim'
                ),
                array(
                    'field' => 're-password',
                    'label' => lang('re-password'),
                    'rules' => 'trim|matches[password]|min_length[6]|max_length[32]|required'
                )
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() === TRUE) {
                $remoteIp = $this->input->ip_address();
                $identity = strip_tags(trim($this->input->post('email')));
                $password = strip_tags(trim($this->input->post('password')));
                $email = strip_tags(trim($this->input->post('email')));
                $check_email = $this->_data->checkExistByField('email', $email);
                if ($check_email != "") {
                    $message['type'] = 'warning';
                    $message['message'] = lang('email_exists');
                    echo json_encode($message);
                    exit;
                }
                if ($this->input->post('full_name')) {
                    $data_store['full_name'] = strip_tags(trim($this->input->post('full_name')));
                }
                $full_name = strip_tags(trim($this->input->post('full_name')));
                $phone_number = strip_tags(trim($this->input->post('phone')));

                $data_store['full_name'] = $full_name;
                $data_store['phone'] = $phone_number;
                $data_store['gender'] = $this->input->post('gender');
                $data_store['active'] = 1;
                $id_user = $this->ion_account->register('', $password, $email, $data_store, ['group_id' => 1]);
                if ($id_user !== false) {
                    $account = array(
                        'account'=>array(
                            'full_name' => $full_name,
                            'email'     => $email,
                            'id'        => $id_user,
                            'user_id'   => $id_user
                        )
                    );
                    $this->session->set_userdata($account);
                    die(json_encode(array(
                        'message' => lang('sign_up'),
                        'type'    => 'success',
                        'status'  => 200
                    )));
                } else {
                    die(json_encode(array(
                        'message' => lang('mess_validation'),
                        'type'    => 'warning'
                    )));
                }
            } else {
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
    }

    public function forgotPassword()
    {
        $data = array();
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->sbForgotPassword();
        }
        $data['main_content'] = $this->load->view($this->template_path . 'auth/forgot_password', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function resetPassword()
    {

        $code = $this->input->get('key_forgotten');

        $user = $this->ion_account->forgotten_password_check($code);

        if ($user) {
            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_account');
            $this->data['new_password'] = array(
                'name'        => 'new',
                'id'          => 'new',
                'class'       => 'form-control',
                'type'        => 'password',
                'placeholder' => 'Mật khẩu mới',
                'pattern'     => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['new_password_confirm'] = array(
                'name'        => 'new_confirm',
                'id'          => 'new_confirm',
                'class'       => 'form-control',
                'type'        => 'password',
                'placeholder' => 'nhập lại mật khẩu mới',
                'pattern'     => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['user_id'] = array(
                'name'  => 'user_id',
                'id'    => 'user_id',
                'type'  => 'hidden',
                'value' => $user->id,
            );
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['code'] = $code;
        }
        echo $this->load->view($this->template_path . 'account/reset_password', $this->data, TRUE);
        exit();
    }


    public function logout($calback = '')
    {
        if (!empty($calback)) $calback = base_url();
        $this->ion_account->logout();
        // redirect them to the login page
//        $account = $this->_data->getById($this->session->userdata['account']['account_id']);
//        if (!empty($account->oauth_provider) && $account->oauth_provider != 'Zalo') $this->hybridauth->HA->logoutAllProviders();
//        if ($account->oauth_provider == 'Zalo') {
//            delete_cookie("call_back_url");
//            delete_cookie("access_token");
//        }
        $this->session->set_flashdata('message', $this->ion_account->messages());
        $this->session->set_flashdata('type', 'success');
        redirect($calback, 'refresh');
    }

    public function forgot_password()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $message = array();
            // setting validation rules by checking whether identity is username or email
            $this->form_validation->set_rules('identity', 'Email', 'required|valid_email');

            if ($this->form_validation->run() === FALSE) {
                // set any errors and display the form
                $message['type'] = 'warning';
                $message['message'] = 'Vui lòng kiểm tra lại thông tin!';
                $message['validation']['identity'] = validation_errors();
            } else {
                $data_input = $this->input->post('identity');
                if (is_numeric($data_input)) {
                    $identity_column = 'phone';
                    $identity_des = lang('text_phone');
                } else {
                    $identity_column = 'email';
                    $identity_des = 'Email';
                }
                $user_data = $this->_data->getUserByField($identity_column, $data_input, 1);
                if (empty($user_data)) {
                    $message['type'] = 'warning';
                    $message['message'] = $identity_des . lang('does_not_exist');
                    die(json_encode($message));
                }
                // run the forgotten password method to email an activation code to the user
                $this->forgot($identity_column, $data_input, $user_data);
            }
            die(json_encode($message));
        }
    }

    private function forgot($identity_column, $data_input, $data_user)
    {
        $data_mess = array();
        if ($identity_column === 'phone') {
            $pass = rand(1000, 9999);
            //      Lấy mk đã được mã hóa và update mk mới
            $hashPass = $this->ion_account->hash_password($pass, true);
            $this->_data->updateField($data_user->id, 'password', $hashPass);

        } else {
            $forgotten = $this->ion_account->forgotten_password($data_user->{$identity_column});
            if ($forgotten) {
                // if there were no errors
                $data_mess['type'] = 'success';
                $data_mess['message'] = 'Vui lòng kiểm tra hòm thư để reset mật khẩu';
                //                redirect(BASE_ADMIN_URL."auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
            } else {
                $data_mess['type'] = 'error';
                $data_mess['message'] = $this->ion_account->errors();
            }
        }
        die(json_encode($data_mess));
    }

    public function ajax_reset_password()
    {
        $code = $this->input->post('key_forgotten');
        $user = $this->ion_account->forgotten_password_check($code);
        $message = array();
        if ($user) {
            $rules = array(
                array(
                    'field' => 'new',
                    'label' => $this->lang->line('reset_password_validation_new_password_label'),
                    'rules' => 'required|min_length[' . $this->config->item('min_password_length', 'ion_account') . ']|max_length[' . $this->config->item('max_password_length', 'ion_account') . ']|matches[new_confirm]'
                ),
                array(
                    'field' => 'new_confirm',
                    'label' => $this->lang->line('reset_password_validation_new_password_confirm_label'),
                    'rules' => 'required'
                )

            );
            // if the code is valid then display the password reset form

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() === TRUE) {
                // display the form
                // set the flash data error message if there is one
                // finally change the password
                $identity = $user->{$this->config->item('identity', 'ion_account')};
                $change = $this->ion_account->reset_password($identity, $this->input->post('new'));
                if ($change) {
                    // if the password was successfully changed
                    $message['type'] = 'success';
                    $message['message'] = lang('change_password');
                } else {
                    $message['type'] = 'warning';
                    $message['message'] = lang('mess_validation');
                }
            } else {
                $message['type'] = "warning";
                $message['message'] = lang('mess_validation');
                $valid = array();
                if (!empty($rules)) foreach ($rules as $item) {
                    if (!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
                }
                $message['validation'] = $valid;
                die(json_encode($message));
            }

        } else {
            $message['type'] = 'warning';
            $message['message'] = $this->ion_account->errors();
        }
        die(json_encode($message));
    }

    /**
     * Try to authenticate the user with a given provider
     *
     * @param string $provider_id Define provider to login
     */
    public function window($provider_id)
    {
        $data_store = array();
        $params = array(
            'hauth_return_to' => site_url("auth/window/{$provider_id}"),
        );
        if (isset($_REQUEST['openid_identifier'])) {
            $params['openid_identifier'] = $_REQUEST['openid_identifier'];
        }
        try {
            $adapter = $this->hybridauth->HA->authenticate($provider_id, $params);
            $profile = $adapter->getUserProfile();
            $check_auth = $this->_data->check_oauth('oauth_uid', $profile->identifier, false);
            $data_store['oauth_provider'] = $provider_id;
            $data_store['oauth_uid'] = $profile->identifier;
            $data_store['full_name'] = $profile->displayName;
            $data_store['avatar'] = $profile->photoURL;
            $data_store['email'] = !empty($profile->email) ? $profile->email : '';
            switch ($profile->gender) {
                case 'male':
                $data_store['gender'] = 2;
                break;
                case 'female':
                $data_store['gender'] = 1;
                break;
                default:
                $data_store['gender'] = 3;
                break;
            }
            $data_store['birth_day'] = $profile->birthYear . '-' . $profile->birthMonth . '-' . $profile->birthDay;
            $data_store['phone'] = $profile->phone;
            if (empty($check_auth)) {
                $group_id = 1;
                $data_store['active'] = 1;
                // End avatar
                $id_user = $this->ion_account->register('', $profile->identifier, $data_store['email'], $data_store, ['group_id' => $group_id]);
                //setup logged
                $account = array(
                    'account' => array(
                        'full_name' => $data_store['full_name'],
                        'email'     => $data_store['email'],
                        'id'        => $id_user,
                        'user_id'   => $id_user
                    )
                );
                $this->session->set_userdata($account);
            } else {
                $account = $this->_data->getUserByField('oauth_uid', $profile->identifier);
                //setup logged
                $account = array(
                    'account' => array(
                        'full_name' => $account->full_name,
                        'email'     => $account->email,
                        'id'        => $account->id,
                        'user_id'   => $account->id
                    )
                );
                $this->session->set_userdata($account);
                //logged successfully
            }
            //logged successfully
            $this->session->set_flashdata('message', lang('successfully'));
            $this->session->set_flashdata('type', 'success');
            redirect(base_url(), 'refresh');
        } catch (Exception $e) {
            show_error($e->getMessage());
        }
    }

    /**
     * Handle the OpenID and OAuth endpoint
     */
    public function endpoint()
    {
        $data = $this->input->get();
        if ($data['hauth_done'] == 'Facebook' && $data['error'] == 'access_denied') {
            redirect(site_url(), 'refresh');
        }
        $this->hybridauth->process();
    }

    public function getUrlLogin()
    {
        $url = $this->zalo->getUrlLogin();
        return $url;
    }


}
