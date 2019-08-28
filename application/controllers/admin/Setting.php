<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        //tải file ngôn ngữ
        $this->lang->load('setting');
    }

    public function index()
    {
        $this->load->model(array('category_model', 'post_model', 'page_model'));
        $dataContent = file_get_contents(FCPATH . 'config' . DIRECTORY_SEPARATOR . 'settings.cfg');
        $data = $dataContent ? json_decode($dataContent, true) : array();
        $data['list_cat'] = $this->loadCatPost();
        $data['heading_title'] = ucfirst($this->router->fetch_class());
        $data['heading_description'] = 'Cấu hình chung';
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/

        $data['list_db'] = $this->get_list_db();
        //dd($data);
        $dataPost = $this->input->post();
        if (!empty($dataPost)) {
            $data_store = array();
            if (!empty($this->input->post())) {
                foreach ($dataPost as $k => $item) {
                    $data_store[$k] = !empty($item) ? $item : "";
                }
            }
            $data_store['updated_time'] = date('Y-m-d H:i:s');
            if (!empty($data_store)) {
                if (file_put_contents(FCPATH . 'config' . DIRECTORY_SEPARATOR . 'settings.cfg', json_encode($data_store))) {
                    $message['type'] = "success";
                    $message['message'] = $this->lang->line('mess_update_success');
                    $this->session->set_flashdata('message', $message);
                } else {
                    $message['type'] = 'error';
                    $message['message'] = $this->lang->line('mess_update_unsuccess');
                    $this->session->set_flashdata('message', $message);
                }
                redirect('admin/setting', 'refresh');
            }
        }
        $data['path1'] = glob(FCPATH . 'application/views/templates/*', GLOB_ONLYDIR);
        //dd($data);
        $data['main_content'] = $this->load->view($this->template_path . 'setting/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }


    public function ajax_load_item_slide()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data = $this->input->post();
            //echo("test");exit();
            //var_dump($data['image']); die;
            print $this->load->view($this->template_path . 'setting/items/_item_slide', $data, TRUE);
        }
        exit;
    }

    public function ajax_load_item_contact() {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data = $this->input->post();
//            dd($data);
            print $this->load->view($this->template_path . 'setting/items/_item_contact', $data, TRUE);
        }
        exit;
    }

    public function ajax_load_item_certificate()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data = $this->input->post();
            //echo("test");exit();
            //var_dump($data['image']); die;
            print $this->load->view($this->template_path . 'setting/items/_item_certificate', $data, TRUE);
        }
        exit;
    }

    public function ajax_load_item_page()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data = $this->input->post();
            $this->load->model('page_model');
            $pageModel = new Page_model();
            $data['list_data'] = $pageModel->getAll($this->session->admin_lang);
            print $this->load->view($this->template_path . 'setting/items/_item_page', $data, TRUE);
        }
        exit;

    }

    public function ajax_load_item_cat_product()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data = $this->input->post();
            $this->load->model('category_model');
            $category = new Category_model();
            $data['list_data'] = $category->getAllCategoryByType($this->session->admin_lang, 'product', '');
            print $this->load->view($this->template_path . 'setting/items/_item_page', $data, TRUE);
        }
        exit;
    }

    public function ajax_load_item_chi_nhanh()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data = $this->input->post();
            print $this->load->view($this->template_path . 'setting/items/_item_chi_nhanh', $data, TRUE);
        }
        exit;
    }

    public function ajax_load_item_page_intro_detail()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data = $this->input->post();
            $this->load->model('category_model');
            $category = new Category_model();
            $data['list_data'] = $category->getAllCategoryByType($this->session->admin_lang, 'product', '');
            print $this->load->view($this->template_path . 'setting/items/item_page_intro_detail', $data, TRUE);
        }
        exit;
    }

    public function ajax_load_feedback()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data['data'] = $this->input->post();
            //dump($data['data']); die;
//            dd($data);
            print $this->load->view($this->template_path . 'setting/items/ajax_load_feedback', $data, TRUE);
        }
        exit;
    }

    public function ajax_load_partner()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data['data'] = $this->input->post();
            //dump($data['data']); die;
            print $this->load->view($this->template_path . 'setting/items/_item_partner', $data, TRUE);
        }
        exit;
    }

    public function ajax_load_listpayment()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data['data'] = $this->input->post();
            //dump($data['data']); die;
            print $this->load->view($this->template_path . 'setting/items/_item_payment', $data, TRUE);
        }
        exit;
    }

    public function ajax_load_cat_featured()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data['data'] = $this->input->post();
            $data['list_cat'] = $this->loadCatPost();
            print $this->load->view($this->template_path . 'setting/items/_item_partner', $data, TRUE);
        }
        exit;
    }

    private function get_list_db()
    {
        $this->load->helper('directory');
        $map = directory_map(FCPATH . 'db');
        $data = array();
        if (!empty($map)) foreach ($map as $item) {
            $data[] = get_file_info(FCPATH . 'db/' . $item);
        }
        usort($data, function ($a, $b) {
            return ($a['date'] > $b['date']) ? -1 : 1;
        });
        return $data;
    }

    public function downloadFile()
    {
        $this->load->helper('download');
        $file = $this->input->get('file');
        $data = $this->load->file(FCPATH . 'db/' . $file, true);
        force_download($file, $data);
    }

    public function ajax_backup_db()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // Load the DB utility class
            $this->load->dbutil();
            $prefs = array(
                'tables' => array(),   // Array of tables to backup.
                'ignore' => array(),                     // List of tables to omit from the backup
                'format' => 'text',                       // gzip, zip, txt
                'filename' => DB_DEFAULT_NAME . "_" . date('d_m_y') . ".sql",              // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop' => TRUE,                        // Whether to add DROP TABLE statements to backup file
                'add_insert' => TRUE,                        // Whether to add INSERT data to backup file
                'newline' => "\n"                         // Newline character used in backup file
            );
            // Backup your entire database and assign it to a variable
            $backup = $this->dbutil->backup($prefs);

            $this->load->helper('file');
            $data = write_file(FCPATH . 'db/' . DB_DEFAULT_NAME . "_" . date('d_m_y_H_i') . ".sql", $backup);
            sleep(1);
            $message['type'] = "success";
            $message['message'] = 'Backup successful';
            $this->session->set_flashdata('message', $message);
            print json_encode($data);
        }
        exit;
    }

    public function ajax_restore_db()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $dbFile = $this->input->post('db_name');
            $file = FCPATH . 'db/' . $dbFile;

            $cmd = "mysql -h {$this->db->hostname} -u {$this->db->username} -p{$this->db->password} {$this->db->database} < $file";
            if (function_exists('shell_exec')) {
                print json_encode(['status' => 1, 'msg' => 'Khôi phục data thành công !']);
                shell_exec($cmd);
            } else {
                print json_encode(['status' => 0, 'msg' => 'Server chưa bật hàm shell_exec() !']);
            }
        }
        exit;
    }

    public function ajax_delete_db()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $dbFile = $this->input->post('db_name');
            $file = FCPATH . 'db/' . $dbFile;
            print json_encode(unlink($file));
        }
        exit;
    }

    public function sortdate($a, $b)
    {
        return $a["date"] - $b["date"];
    }

    private function loadCatPost()
    {
        $this->load->model('category_model');
        $categoryModel = new Category_model();
        $params = array(
            'limit' => 1000,
            'category_type' => 'post',
            'is_status' => 1,
            'is_featured' => 1
        );
        $data = $categoryModel->getData($params);
        return $data;
    }
}