<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Category extends Admin_Controller
{
    protected $_data;
    protected $_name_controller;
    protected $category_tree;

    const STATUS_CANCEL = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 2;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->lang->load('category');
        $this->load->model('category_model');
        $this->_data = new Category_model();
        $this->_name_controller = $this->router->fetch_class();
        $this->category_tree = array();
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

    public function post(){
        $data['heading_title'] = "Bài viết";
        $data['heading_description'] = "Danh sách danh mục bài viết";
        $this->session->category_type = $data['category_type'] = $this->router->fetch_method();
        $this->get_list($data);
    }

    public function product(){
        $data['heading_title'] = "Sản phẩm";
        $data['heading_description'] = "Danh sách danh mục sản phẩm";
        $this->session->category_type = $data['category_type'] = $this->router->fetch_method();
        $this->get_list($data);
    }

    public function _queue($categories, $parent_id = 0, $char = ''){
        if(!empty($categories)) foreach ($categories as $key => $item)
        {
            if ($item->parent_id == $parent_id)
            {
                $tmp['title'] = $char.$item->title;
                $tmp['value'] = $item;
                $this->category_tree[] = $tmp;
                unset($categories[$key]);
                $this->_queue($categories,$item->id,$char.'  '.$item->title.' <i class="fa fa-fw fa-caret-right"></i> ');
            }

        }
    }

    public function _queue_select($categories, $parent_id = 0, $char = ''){
        foreach ($categories as $key => $item)
        {
            if ($item->parent_id == $parent_id)
            {
                $tmp['title'] = $parent_id ? '  |--'.$char.$item->title : $char.$item->title;
                $tmp['id'] = $item->id;
                $tmp['thumbnail'] = $item->thumbnail;
                $this->category_tree[] = $tmp;
                unset($categories[$key]);
                $this->_queue_select($categories,$item->id,$char.'--');
            }
        }
    }

    /*
     * Ajax trả về datatable
     * */
    public function ajax_list()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data = array();
            // $length =  $this->input->post('length');
            // $no = $this->input->post('start');
            // $page = $no/$length + 1;
            $params['parent_id'] = $this->input->post('parent_id');
            $params['category_type'] = $this->session->category_type;
            $params['limit'] = 100;
            $list = $this->_data->getData($params);
            if(empty($this->input->post('parent_id'))){
                $this->_queue($list);
                $list = $this->category_tree;
            }
            if(!empty($list)) foreach ($list as $category) {
                if(empty($this->input->post('parent_id'))){
                    $item = $category['value'];
                    $title = $category['title'];
                }else{
                    $item = $category;
                    $title = $category->title;
                }
                // $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = $item->id;
                $row[] = $item->order;
                $row[] = $title;
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
                $row[] = formatDate($item->created_time);
                $row[] = formatDate($item->updated_time);
                //thêm action
                $action = button_action($item->id, ['edit']);
                
                $row[] = $action;
                $data[] = $row;
            }
            $output = array(
                "draw" => $this->input->post('draw'),
                "recordsTotal" => $this->_data->getTotalAll('','product'),
                "recordsFiltered" => $this->_data->getTotal($params),
                "data" => $data,
            );
            //trả về json
            die(json_encode($output));
        }
        exit;
    }

    public function ajax_load($type){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $term = $this->input->get("q");
            $id = $this->input->get('id')?$this->input->get('id'):0;
            $params = [
                'category_type' => !(empty($type)) ? $type : null,
                'is_status'=> 1,
                'not_in' => ['id' => $id],
                'search' => $term,
                'limit'=> 1000,
                'order' => array('created_time'=>'desc')
            ];
            $list = $this->_data->getData($params);
            $this->_queue_select($list);
            $json = [];
            if(!empty($this->category_tree)) foreach ($this->category_tree as $item) {
                $item = (object) $item;
                if($type === 'color') $json[] = ['id'=>$item->id, 'text'=>$item->title];
                else $json[] = ['id'=>$item->id, 'text'=>$item->title];
            }
            echo json_encode($json);
        }
        exit;
    }


    public function ajax_load_lv1($type){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $term = $this->input->get("q");
            $params = [
                'category_type' => !(empty($type)) ? $type : null,
                'parent_id' => 0,
                'is_status'=> 1,
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



    /*
     * Ajax xử lý thêm mới
     * */
    public function ajax_add()
    {
        $data_store = $this->_convertData();
        $parentId = !empty($data_store['parent_id']) ? $data_store['parent_id'] : 0;
        $data_store['order'] = $this->_data->getLastOrder($parentId) + 1;
        if($id_category = $this->_data->save($data_store)){
            // log action
            $action = $this->router->fetch_class();
            $note = "Insert $action: ".$id_category;
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
        $data['data'] = $this->_data->getById($id);
        if(!empty($data['data'][0]->parent_id)) $data['parent_id'] = $this->_data->getSelect2($data['data'][0]->parent_id);
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
                if ($lang_code === $this->config->item('default_language')) {
                    $this->form_validation->set_rules('title[' . $lang_code . ']', 'Tiêu đề' . ' - ' . $lang_name, 'required|trim|min_length[2]|max_length[300]|xss_clean');
                    $this->form_validation->set_rules('meta_title[' . $lang_code . ']', 'Meta title' . ' - ' . $lang_name, 'required|trim|min_length[2]|max_length[300]|xss_clean');
                    $this->form_validation->set_rules('description[' . $lang_code . ']', 'Tóm tắt' . ' - ' . $lang_name, 'required|xss_clean');
                    $this->form_validation->set_rules('meta_description[' . $lang_code . ']', 'Meta description' . ' - ' . $lang_name, 'required|xss_clean');
                }

            }
            if ($this->form_validation->run() === false) {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = [];
                if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                    if ($lang_code === $this->config->item('default_language')) {
                        $valid["title[$lang_code]"] = form_error("title[$lang_code]");
                        $valid["meta_title[$lang_code]"] = form_error("meta_title[$lang_code]");
                        $valid["description[$lang_code]"] = form_error("description[$lang_code]");
                        $valid["meta_description[$lang_code]"] = form_error("meta_description[$lang_code]");
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
}
