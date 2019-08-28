<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Page extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;
    protected $_master_data = array(5, 3, 2, 1);
    const STATUS_CANCEL = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 2;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        //$this->load->library(array('ion_auth'));
        $this->lang->load('page');
        $this->load->model('page_model');
        $this->_data = new Page_model();
        $this->_name_controller = $this->router->fetch_class();
    }

    public function index()
    {
        
        $data['heading_title'] = ucfirst($this->_name_controller);
        $data['heading_description'] = "Danh sách $this->_name_controller";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller. '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
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
            if(!empty($this->input->post('category_id'))) $params['category_id_page'] = $this->input->post('category_id');
            $params['limit'] = $length;
            $list = $this->_data->getData($params);
            $data = array();
            if(!empty($list)) foreach ($list as $item) {
                $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = $item->id;
                $row[] = $item->title;
                switch ($item->is_status){
                    case self::STATUS_ACTIVE:
                    $row[] = '<span class="label label-success btnUpdateStatus" data-value="'.self::STATUS_ACTIVE.'">'.$this->lang->line('text_status_'.self::STATUS_ACTIVE).'</span>';
                    break;
                    case self::STATUS_DRAFT:
                    $row[] = '<span class="label label-default btnUpdateStatus" data-value="'.self::STATUS_DRAFT.'">'.$this->lang->line('text_status_'.self::STATUS_DRAFT).'</span>';
                    break;
                    default:
                    $row[] = '<span class="label label-danger btnUpdateStatus" data-value="'.self::STATUS_CANCEL.'">'.$this->lang->line('text_status_'.self::STATUS_CANCEL).'</span>';
                    break;
                }
                $row[] = date('d/m/Y H:i',strtotime($item->updated_time));
                $row[] = date('d/m/Y H:i',strtotime($item->created_time));
                //thêm action
                $action = button_action($item->id, ['edit']);

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


    /*
     * Ajax xử lý thêm mới
     * */
    public function ajax_add()
    {
        $data_store = $this->_convertData();
        unset($data_store['product_related']);
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

    /*
     * Ajax copy
     * */
    function ajax_copy($id){
        $data = $this->_data->getById($id);
        $data_store = array();
        $randId = rand(1000,9999);
        foreach ($data as $key => $item){
            unset($item->created_time);
            unset($item->updated_time);
            unset($item->id);
            unset($item->order);

            if(!empty($item)) foreach ($item as $k => $v){
                $data_store[$k] = $v;
            }
            if(!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                if($item->language_code === $lang_code){
                    $data_trans['title'][$lang_code] = $item->title." $randId";
                    $data_trans['meta_title'][$lang_code] = !empty($item->meta_title) ? $item->meta_title : '';
                    $data_trans['language_code'][$lang_code] = $lang_code;
                    $data_trans['slug'][$lang_code] = $item->slug."-$randId";
                    $data_trans['description'][$lang_code] = $item->description;
                    $data_trans['meta_description'][$lang_code] = !empty($item->meta_description) ? $item->meta_description : '';
                    $data_trans['meta_keyword'][$lang_code] = !empty($item->meta_keyword) ? $item->meta_keyword : '';
                }
            }
        }

        $data_store = array_merge($data_store,$data_trans);
        $response = $this->_data->save($data_store);
        if($response !== false){
            $message['type'] = 'success';
            $message['message'] = "Nhân bản thành công !";
        }else{
            $message['type'] = 'error';
            $message['message'] = "Nhân bản thất bại !";
            $message['error'] = $response;
            log_message('error',$response);
        }
        print json_encode($message);

        exit;
    }

    /*
     * Ajax lấy thông tin
     * */
    public function ajax_edit($id)
    {
        $this->load->model('category_model');
        $categoryPage = new Category_model();
        $data['data'] = $this->_data->getById($id);
        if (!empty($data[0]->category_id)){
            $oneCate = $categoryPage->getById($data[0]->category_id,'',$this->session->admin_lang);
            $dataOneCate = ['id' => $oneCate->id ,'text' => $oneCate->title];
            $data['category_id'] = $dataOneCate;
        }
        die(json_encode($data));
    }

    /*
     * Ajax xử lý thêm mới
     * */
    public function ajax_update()
    {
        $data_store = $this->_convertData();
        unset($data_store['product_related']);
        //dd($data_store);
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
        if(in_array($id, $this->_master_data)) {
            $response = false;
        } else {
            $response = $this->_data->delete(['id'=>$id]);
        }
        if($response != false){
            //Xóa translate của post
            $this->_data->delete(["id"=>$id],$this->_data->table_trans);
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
            if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                $this->form_validation->set_rules('title[' . $lang_code . ']', 'tiêu đề' . ' - ' . $lang_name, 'required|trim|min_length[5]|max_length[300]|xss_clean');
                $this->form_validation->set_rules('meta_title[' . $lang_code . ']','meta title' . ' - ' . $lang_name, 'required|trim|min_length[5]|max_length[300]|xss_clean');
                $this->form_validation->set_rules('meta_description[' . $lang_code . ']', 'meta description' . ' - ' . $lang_name, 'required|xss_clean');
                $this->form_validation->set_rules('meta_keyword[' . $lang_code . ']', 'meta keyword' . ' - ' . $lang_name, 'required|xss_clean');
            }
            if ($this->form_validation->run() === false) {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = [];
                if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                    $valid["title[$lang_code]"] = form_error("title[$lang_code]");
                    $valid["meta_title[$lang_code]"] = form_error("meta_title[$lang_code]");
                    $valid["meta_description[$lang_code]"] = form_error("meta_description[$lang_code]");
                    $valid["meta_keyword[$lang_code]"] = form_error("meta_keyword[$lang_code]");
                }
                $message['validation'] = $valid;
                die(json_encode($message));
            }
        }
    }

    private function _convertData(){
        $this->_validate();
        $data = $this->input->post();
        unset($data['seo']);
        unset($data['logan']);
        unset($data['seo']);
        unset($data['color']);
        unset($data['block']);
        return $data;
    }

    public function _404()
    {
        $this->show_404();
    }

}
