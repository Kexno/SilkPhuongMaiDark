<?php
/**
 * User: linhth
 * Created Date: 23/03/2019
 * Updated Date: 25/03/2019
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class APS_Controller extends CI_Controller
{
    public $template_path = '';
    public $template_main = '';
    public $templates_assets = '';
    public $_message = array();
    public $_redis;
    public $_account;

    public function __construct()
    {
        parent::__construct();

        //Load library
        $this->load->library(array('session', 'form_validation', 'user_agent'));
        $this->load->helper(array('data', 'security', 'url', 'directory', 'file', 'form', 'datetime', 'language', 'debug', 'curl', 'string','general', 'email'));
        $this->config->load('languages');
        $this->lang->load('general');
        //Load database
        $this->load->database();
        if (DEBUG_MODE == TRUE) {
            //Load third party
            $this->load->add_package_path(APPPATH . 'third_party', 'codeigniter-debugbar');
            $this->output->enable_profiler(TRUE);
        }
    }

    public function show_404()
    {
        redirect(site_url('404_override'), 'refresh');
    }

    public function checkRequestGetAjax()
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'))
            die('Not Allow');
    }

    public function checkRequestPostAjax()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST'
            || empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
        )
            die('Not Allow');
    }

    public function returnJson($data = null)
    {
        if (empty($data)) $data = $this->_message;
        if ($this->config->item('csrf_protection') == TRUE) {
            $csrf = [
                'csrf_form' => [
                    'csrf_name'  => $this->security->get_csrf_token_name(),
                    'csrf_value' => $this->security->get_csrf_hash()
                ]
            ];
            $data = array_merge($csrf, (array)$data);
        }
        die(json_encode($data));
    }

    function switchLanguage($language)
    {
        if (!empty($language)) {
            $this->session->set_userdata('public_lang_code', $language);
            if ($this->agent->is_referral()) {
                redirect($this->agent->referrer());
            }
        }
    }
}

class Admin_Controller extends APS_Controller
{

    public function __construct()
    {
        parent::__construct();

        //set đường dẫn template
        $this->template_path = 'admin/';
        $this->template_main = $this->template_path . '_layout';
        $this->templates_assets = base_url() . 'public/admin/';

        $this->load->model('system_menu_model');
        $list_menus = $this->system_menu_model->getMenu();
        $this->load->vars(array('list_menu' => $list_menus));

        //fix language admin tiếng việt
        $this->session->admin_lang = 'vi';
        //tải thư viện
        $this->load->library(array('ion_auth', 'breadcrumbs'));
        //load helper
        $this->load->helper(array('authorization', 'image', 'link', 'format','button'));
        //Load config
        $this->config->load('seo');
        $this->config->load('permission');

        $configMinify['assets_dir'] = 'public/admin';
        $configMinify['assets_dir_css'] = 'public/admin/css';
        $configMinify['assets_dir_js'] = 'public/admin/js';
        $configMinify['css_dir'] = 'public/admin/css';
        $configMinify['js_dir'] = 'public/admin/js';
        $this->load->library('minify', $configMinify);
        $this->minify->enabled = FALSE;

        $this->check_auth();


        //đọc file setting
        $dataSetting = file_get_contents(FCPATH . 'config' . DIRECTORY_SEPARATOR . 'settings.cfg');
        $dataSetting = $dataSetting ? json_decode($dataSetting, true) : array();
        if (!empty($dataSetting)) foreach ($dataSetting as $key => $item) {
            if ($key === 'meta') {
                $oneMeta = $item[$this->config->item('default_language')];
                if (!empty($oneMeta)) foreach ($oneMeta as $keyMeta => $value) {
                    $this->settings[$keyMeta] = str_replace('"', '\'', $value);
                }
            } else
            $this->settings[$key] = $item;
        }
//        dd($this->settings);
    }

    // add log action
    public function addLogaction($action, $note)
    {
        $this->load->model("Log_action_model", "logaction");
        $data['action'] = $action;
        $data['note'] = $note;
        $data['uid'] = $this->session->user_id;
        $dates = "%Y-%m-%d %h:%i:%s";
        $time = time();

        $data['created_time'] = mdate($dates, $time);

        $this->logaction->save($data);
    }

    public function check_auth()
    {
        if (!$this->ion_auth->logged_in()) {
            //chưa đăng nhập thì chuyển về page login
            redirect(BASE_ADMIN_URL . 'auth/login?url=' . urlencode(current_url()), 'refresh');
        } 
        else {
            if ($this->ion_auth->in_group(1) != true) {
                if (!$this->session->admin_permission) {
                    $this->load->model('Groups_model', 'group');
                    $groupModel = new Groups_model();
                    $group = $groupModel->get_group_by_userid((int)$this->session->userdata('user_id'));
                    $data = $groupModel->getById($group->group_id);
                    if (!empty($data)) {
                        $this->session->admin_permission = json_decode($data->permission, true);
                        $this->session->admin_group_id = (int)$group->group_id;
                    }
                }
                $controller = $this->router->fetch_class();
                if (!in_array($controller, array('dashboard'))) {
                        if (!$this->session->admin_permission[$controller]['view']) {//check quyen view
                            redirect('admin/dashboard/notPermission');
                        }
                    }
                }
                else {
                $this->session->admin_group_id = 1;//ID nhóm admin
            }
        }
    }

}

class Public_Controller extends APS_Controller
{
    public $settings = array();
    public $_message = array();
    public $_controller;
    public $_method;
    public $paramsearch;
    public $_account;
    public $_lang_code;

    public function __construct()
    {
        parent::__construct();

        //set đường dẫn template
        $this->template_path = 'public/default/';
        $this->template_main = $this->template_path . '_layout';
        $this->templates_assets = base_url() . 'public/';

        $this->load->model(array('account_model'));
        $data_account = new Account_model();

        //load cache driver
        $this->load->driver('cache', array('adapter' => 'file'));

        //tải thư viện
        $this->load->library(array('minify', 'cart', 'breadcrumbs'));

        //load helper
        $this->load->helper(array('cookie', 'navmenus', 'link', 'title', 'format', 'image', 'status_order'));

        //Detect mobile
        //$this->detectMobile = new Mobile_Detect();

        //Language
        $lang_code = $this->input->get('lang');
        $lang_cnf = $this->config->item('cms_lang_cnf');
        //set session language
        if (!empty($lang_code) && array_key_exists($lang_code, $lang_cnf)) {
            $this->session->public_lang_code = $lang_code;
            $this->session->public_lang_full = $lang_cnf[$lang_code];
        }
        if (empty($this->session->public_lang_code)) {
            //không có lang code thì mặc định hiển thị tiếng việt
            $this->session->public_lang_code = $this->config->item('default_language');
            $this->session->public_lang_full = $this->config->item('cms_lang_cnf')[$this->config->item('default_language')];
        }

        $this->config->set_item('language', $this->session->public_lang_full);
        $this->lang->load(array('auth', 'ion_auth', 'frontend', 'account', 'general'));

        //đọc file setting
        $dataSetting = file_get_contents(FCPATH . 'config' . DIRECTORY_SEPARATOR . 'settings.cfg');
        $dataSetting = $dataSetting ? json_decode($dataSetting, true) : array();
        if (!empty($dataSetting)) foreach ($dataSetting as $key => $item) {
            if ($key === 'meta' || $key === 'home') {
                $oneMeta = !empty($item[$this->session->public_lang_code]) ? $item[$this->session->public_lang_code] : '';
                if (!empty($oneMeta)) foreach ($oneMeta as $keyMeta => $value) {
                    $this->settings[$keyMeta] = str_replace('"', '\'', $value);
                }
            } else
            $this->settings[$key] = $item;
        }
        //Set flash message
        $this->_message = $this->session->flashdata('message');
        if (MAINTAIN_MODE == TRUE) $this->load->view('public/coming_soon');
        $this->minify->enabled = true;

        $this->_controller = $this->router->fetch_class();
        $this->_method = $this->router->fetch_method();
        //check account
        $this->_account = !empty($this->session->userdata('account')) ? $this->session->userdata('account')['user_id'] : '';
        if (!empty($this->_account)) {
            $user_login = $data_account->getById($this->_account);
            if (!empty($user_login)) {
                is_array($user_login) ? $avatar = array('oauth_provider' => $user_login['oauth_provider'], 'avatar' => $user_login['avatar']) : $avatar = array('oauth_provider' => $user_login->oauth_provider, 'avatar' => $user_login->avatar);
            } else {
                $avatar = array('oauth_provider' => null, 'avatar' => null);
            }

            $this->session->set_userdata('avatar', $avatar);
        }
        //
        if ($this->_controller == 'search') {
            $search = base_url(uri_string());
            $paramsearch = explode("/search/", $search);
            if (isset($paramsearch[1])) {
                $this->paramsearch = urldecode($paramsearch[1]);
            }
        }
        $this->_lang_code = $this->session->public_lang_code;
        $configBreadcrumb['crumb_divider'] = $this->config->item('frontend_crumb_divider');
        $configBreadcrumb['tag_open'] = $this->config->item('frontend_tag_open');
        $configBreadcrumb['tag_close'] = $this->config->item('frontend_tag_close');
        $configBreadcrumb['crumb_open'] = $this->config->item('frontend_crumb_open');
        $configBreadcrumb['crumb_last_open'] = $this->config->item('frontend_crumb_last_open');
        $configBreadcrumb['crumb_close'] = $this->config->item('frontend_crumb_close');
        $this->breadcrumbs->init($configBreadcrumb);
    }

    public function getPagination($total, $limit, $base_url, $first_url)
    {
        $this->load->library('pagination');
        $paging['base_url'] = $base_url;
        $paging['first_url'] = $first_url;
        $paging['total_rows'] = $total;
        $paging['per_page'] = $limit;
        $this->pagination->initialize($paging);
        return $this->pagination->create_links();
    }

    public function blockSEO($oneItem, $url)
    {
        $data = [
            'meta_title'       => !empty($oneItem->meta_title) ? $oneItem->meta_title : $oneItem->title,
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : $oneItem->description,
            'meta_keyword'     => !empty($oneItem->meta_title) ? $oneItem->meta_keyword : '',
            'url'              => $url,
            'image'            => getImageThumb($oneItem->thumbnail, 400, 200)
        ];
        return $data;
    }

    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }
}

//Class API chuẩn hóa sủ dụng JWT
class API_Controller extends CI_Controller
{
    public $json_str = '';
    public $token = '';
    public $_redis;

    public function __construct()
    {
        parent::__construct();
        $this->load->library("JWT");
        $this->load->helper(array('validatecard', 'status'));
        $this->token = $this->input->get_request_header('x-token') ? $this->input->get_request_header('x-token') : '';
        $this->json_str = file_get_contents('php://input');
        $this->json_str = $this->json_str ? json_decode($this->json_str) : '';
        //		$this->check_token();
        //connect redis
        $this->load->database();
        //connect redis
        if (ACTIVE_REDIS == TRUE) {
            try {
                $this->_redis = new Redis();
                $this->_redis->pconnect(REDIS_HOST, REDIS_PORT);
                if (REDIS_PASS) {
                    $this->_redis->auth(REDIS_PASS);
                }
            } catch (Exception $e) {
                $this->_redis->close();
                $this->_redis = new Redis();
                $this->_redis->pconnect(REDIS_HOST, REDIS_PORT);
                if (REDIS_PASS) {
                    $this->_redis->auth(REDIS_PASS);
                }
            }
        }
    }

    //kiếm tra token
    public function check_token()
    {
        if ($this->token) {
            $jwt_dec = $this->jwt->decode($this->token, JWT_CONSUMER_SECRET);
            if (((int)$jwt_dec->created_time + (int)$jwt_dec->ttl) < time()) {
                $response['code'] = 403;
                $response['message'] = "Token hết hạn";
                die(json_encode($response));
            }
        } else {
            $response['code'] = 403;
            $response['message'] = "Token không tồn tại";
            die(json_encode($response));
        }
    }
}

//Class cronjob chay ngam
class Console_Controller extends CI_Controller
{
    public $_redis;
    public $time_start;
    public $time_end;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper(array('validatecard', 'status'));

        //tinh thoi gian xu ly
        $this->time_start = $this->microtime_float();

        //connect redis
        $this->load->database();
        //connect redis
        if (ACTIVE_REDIS == TRUE) {
            try {
                $this->_redis = new Redis();
                $this->_redis->pconnect(REDIS_HOST, REDIS_PORT);
                if (REDIS_PASS) {
                    $this->_redis->auth(REDIS_PASS);
                }
            } catch (Exception $e) {
                $this->_redis->close();
                $this->_redis = new Redis();
                $this->_redis->pconnect(REDIS_HOST, REDIS_PORT);
                if (REDIS_PASS) {
                    $this->_redis->auth(REDIS_PASS);
                }
            }
        }
    }

    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    public function __destruct()
    {
        $this->time_end = $this->microtime_float();
        echo '<p>Tong thoi gian xu ly la ' . ($this->time_end - $this->time_start) . ' giay</p>';
    }
}