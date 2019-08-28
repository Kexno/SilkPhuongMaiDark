<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Faqs extends Admin_Controller
{
    var $action = '';
    var $note = '';
    protected $_data;
    protected $_name_controller;
    const STATUS_CANCEL = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 2;
    public function __construct()
    {
        parent::__construct();
        //tải file ngôn ngữ
        $this->lang->load('faqs');
        $this->load->model('Faqs_model');
        $this->_data = new Faqs_model();
        $this->_name_controller = $this->router->fetch_class();
    }

    public function index()
    {
        $data['heading_title'] = 'Câu hỏi';
        $data['heading_description'] = 'Danh sách câu hỏi';
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Load content*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    /*
     * Ajax trả về datatable
     * */
    public function ajax_list()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $length = $this->input->post('length');
            $no = $this->input->post('start');
            $page = $no / $length + 1;
            $params['page'] = $page;
            $params['limit'] = $length;
            $list = $this->_data->getData($params);
            $data = array();
            if (!empty($list)) foreach ($list as $item) {
                $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = $item->id;
                $row[] = $item->title;
                switch ($item->is_status) {
                    case self::STATUS_ACTIVE:
                        $row[] = '<span class="label label-success btnUpdateStatus" data-value="' . self::STATUS_ACTIVE . '">' . $this->lang->line('text_status_' . self::STATUS_ACTIVE) . '</span>';
                        break;
                    case self::STATUS_DRAFT:
                        $row[] = '<span class="label label-default btnUpdateStatus" data-value="' . self::STATUS_DRAFT . '">' . $this->lang->line('text_status_' . self::STATUS_DRAFT) . '</span>';
                        break;
                    default:
                        $row[] = '<span class="label label-danger btnUpdateStatus" data-value="' . self::STATUS_CANCEL . '">' . $this->lang->line('text_status_' . self::STATUS_CANCEL) . '</span>';
                        break;
                }
                $row[] = formatDate($item->created_time);
                $row[] = formatDate($item->updated_time);
                //thêm action
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
            echo json_encode($output);
        }
        exit;
    }

    /*
      * Ajax xử lý thêm mới
      * */
    public function ajax_add()
    {
        $data_store = $this->_convertData();
        if ($id_faqs = $this->_data->save($data_store)) {
            // log action
            $action = $this->router->fetch_class();
            $note = "Insert $action" . $id_faqs;
            $this->addLogaction($action, $note);

            $message['type'] = 'success';
            $message['message'] = 'Thêm mới thành công !';
        } else {
            $message['type'] = 'error';
            $message['message'] = 'Thêm mới thất bại';
        }
        die(json_encode($message));
    }

    /*
     * Ajax lấy thông tin
     * */
    public function ajax_edit($id)
    {
        $data['data'] = $this->_data->getById($id);
        die(json_encode($data));
    }

    /*
     * Xóa một bản ghi
     * */
    public function ajax_delete($id)
    {
        $response = $this->_data->delete(['id' => $id]);
        if ($response != true) {
            $message['type'] = 'error';
            $message['message'] = "Xóa bản ghi thất bại !";
        } else {
            $message['type'] = 'success';
            $message['message'] = "Xóa bản ghi thành công !";
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
    /*
     * Cập nhật thông tin
     * */
    public function ajax_update()
    {
        $data_store = $this->_convertData();
        $response = $this->_data->update(array('id' => $this->input->post('id')), $data_store);
        if ($response == false) {
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
            $message['error'] = $response;
            log_message('error', $response);
        } else {
            // log action
            $action = $this->router->fetch_class();
            $note = "Update $action" . $this->input->post('id');
            $this->addLogaction($action, $note);

            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        }
        die(json_encode($message));
    }

    /*
     * Kiêm tra thông tin post lên
     * */
    private function _validate()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                $this->form_validation->set_rules('title[' . $lang_code . ']', 'tiêu đề' . ' - ' . $lang_name, 'required|trim|min_length[5]|max_length[300]|xss_clean');
                $this->form_validation->set_rules('content[' . $lang_code . ']', 'nội dung' . ' - ' . $lang_name, 'trim|xss_clean');
            }
            if ($this->form_validation->run() === false) {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = [];
                if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                    $valid["title[$lang_code]"] = form_error("title[$lang_code]");
                }
                $message['validation'] = $valid;
                $message['validation_message'] = validation_errors();
                die(json_encode($message));
            }
        }
    }

    private function _convertData()
    {
        $this->_validate();
        $data = $this->input->post();
        unset($data['id']);
        return $data;
    }
}
