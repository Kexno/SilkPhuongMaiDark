<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Property extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;

    const STATUS_CANCEL = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 2;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->lang->load('property');
        $this->load->model('property_model');
        $this->_data = new Property_model();
        $this->_name_controller = $this->router->fetch_class();
    }

    public function get_list($data)
    {
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_load($type = ''){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $term = $this->input->get("q");
            $id = $this->input->get('id')?$this->input->get('id'):0;
            $params = [
                'property_type' => !(empty($type)) ? $type : $this->session->property_type,
                'is_status'=> 1,
                'not_in' => ['id' => $id],
                'search' => $term,
                'limit'=> 1000
            ];
            $list = $this->_data->getData($params);
            $json = [];
            if(!empty($list)) foreach ($list as $item) {
                $item = (object) $item;
                $json[] = ['id'=>$item->id, 'text'=>$item->title];
            }
            print json_encode($json);
        }
        exit;
    }

    public function banner(){
        $data['heading_title'] = "Vị trí banner / slider";
        $data['heading_description'] = "Danh sách vị trí banner / slider";
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $this->get_list($data);
    }

    public function brand(){
        $data['heading_title'] = "Xuất xứ";
        // nhãn hiệu
        $data['heading_description'] = "Danh sách xuất xứ";
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $this->get_list($data);
    }
    public function unit(){
        $data['heading_title'] = "Đơn vị tính";
        $data['heading_description'] = "Danh sách đơn vị tính";
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $this->get_list($data);
    }

    public function import() {
        $data['heading_title'] = "Lịch nhập hàng tươi";
        $data['heading_description'] = "Danh sách lịch nhập hàng tươi";
        $this->session->property_type = $data['property_type'] = $this->router->fetch_method();
        $data['total'] = $this->_data->getTotal(['property_type'=>$data['property_type']]);
        $this->get_list($data);
    }


    /*
     * Ajax trả về datatable
     * */
    public function ajax_list()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data = array();
            $length =  $this->input->post('length');
            $no = $this->input->post('start');
            $page = $no/$length + 1;
            $params['property_type'] = $this->input->post('property_type'); //Truyền vào js page
            $params['page'] = $page;
            $params['limit'] = $length;
            $list = $this->_data->getData($params);
            if(!empty($list)) foreach ($list as $item) {
                $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = $item->id;
                $row[] = $item->title;
                if (!empty($this->session->property_type) && $this->session->property_type == 'import') {
                    $row[] = $item->order;
                    $row[] = $item->time;
                }
                if (!empty($this->session->property_type) && $this->session->property_type == 'color') {
                    $row[] = $item->color_code;
                    $row[] = '<i style="background-color:'. $item->color_hex .'; height: 15px; width: 15px; display: inline-block; vertical-align: text-top;"></i>';
                }
                switch ($item->is_status){
                    case self::STATUS_ACTIVE:
                    $row[] = '<span class="label label-success btnUpdateStatus" data-value="'.self::STATUS_ACTIVE.'">'.$this->lang->line('text_status_'.self::STATUS_ACTIVE).'</span>';
                    break;
                    default:
                    $row[] = '<span class="label label-danger btnUpdateStatus" data-value="'.self::STATUS_CANCEL.'">'.$this->lang->line('text_status_'.self::STATUS_CANCEL).'</span>';
                    break;
                }
                $row[] = formatDate($item->created_time);
                $row[] = formatDate($item->updated_time);
                //thêm action
                $arr_but=['edit','delete'];
                if($params['property_type']=='banner') unset($arr_but[1]);
                $action = button_action($item->id, $arr_but);
                $action .= '</div>';
                $row[] = $action;
                $data[] = $row;
            }

            $output = array(
                "draw" => $this->input->post('draw'),
                "recordsTotal" => $this->_data->getTotal(['property_type'=>$params['property_type']]),
                "recordsFiltered" => $this->_data->getTotal($params),
                "data" => $data,
            );
            //trả về json
            die(json_encode($output));
        }
        exit;
    }

    /*
     * Ajax xử lý thêm mới
     * */
    public function ajax_add()
    {
        if (!empty($this->session->property_type) && $this->session->property_type == 'import') {
            if ($this->_data->getTotal(['property_type'=>$this->session->property_type]) >=10 ) {
                $message['type'] = 'warning';
                $message['message'] = $this->lang->line('mess_max_record_import');
            } else {
                $message = $this->saveProperty();
            }
        } else {
            $message = $this->saveProperty();
        }

        die(json_encode($message));
    }

    protected function saveProperty() {
        $data_store = $this->_convertData();
        $data_store['is_status'] = 1;
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
        return $message;
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
        $categoryModel = new Category_model();
        $data['data'] = $this->_data->getById($id);
        if(!empty($data['data'][0]->category_id)) $data['category_id'] = $categoryModel->getSelect2($data['data'][0]->category_id);
        die(json_encode($data));
    }

    /*
     * Ajax xử lý thêm mới
     * */
    public function ajax_update()
    {
        $data_store = $this->_convertData();
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

    /*
     * Kiêm tra thông tin post lên
     * */
    private function _validate()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                $this->form_validation->set_rules('title[' . $lang_code . ']', $this->lang->line('error_title') . ' - ' . $lang_name, 'required|trim|min_length[1]|max_length[300]|xss_clean');
                if (!empty($this->session->property_type) && $this->session->property_type == 'import') {
                    $this->form_validation->set_rules('time[' . $lang_code . ']', $this->lang->line('text_time') . ' - ' . $lang_name, 'required|trim|min_length[1]|max_length[300]|xss_clean');
                }
            }
            
            if ($this->form_validation->run() === false) {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = [];
                if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                    $valid["title[$lang_code]"] = form_error("title[$lang_code]");
                    if (!empty($this->session->property_type) && $this->session->property_type == 'import') {
                        $valid["time[$lang_code]"] = form_error("time[$lang_code]");
                    }
                }
                $message['validation'] = $valid;
                die(json_encode($message));
            }
        }
    }

    private function _convertData(){
        $this->_validate();
        $data = $this->input->post();
        return $data;
    }
    // load feature

    public function update_check_video(){
        $val=$this->input->post('val');
        $this->_data->update(['id'=>3],['check_video'=>$val]);
    }
    public function get_check_video(){
        $data=$this->_data->getById(3,'',$this->session->public_lang_code);;
        die(json_encode((int)$data->check_video));
    }
}