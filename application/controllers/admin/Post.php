<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Post extends Admin_Controller
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
        $this->lang->load('post');
        $this->load->model('post_model');
        $this->_data = new Post_model();
        $this->_name_controller = $this->router->fetch_class();
        $this->session->category_type = $this->_name_controller;
    }

    public function index()
    {
        $data['heading_title'] = 'Bài viết';
        $data['heading_description'] = "Danh sách bải viết";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller. '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }
    public function _queue_select($categories, $parent_id = 0, $char = ''){
        foreach ($categories as $key => $item)
        {
            if ($item->parent_id == $parent_id)
            {
                $tmp['name'] = $parent_id ? $char.'|----'.$item->title : $char.$item->title;
                $tmp['id'] = $item->id;
                $this->category_tree[] = $tmp;
                unset($categories[$key]);
                $this->_queue_select($categories,$item->id,$char.'----');
            }
        }
    }
    public function ajax_list()
    {
        if(!empty($this->input->post('category_id'))){
            $this->load->model('category_model');
            $categoryModel = new Category_model();
            $allCategory = $categoryModel->getAll($this->session->admin_lang_code);
            $categoryModel->_recursive_child_id($allCategory,$this->input->post('category_id'));
            $listCateId = $categoryModel->_list_category_child_id;
        }
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $length = $this->input->post('length');
            $no = $this->input->post('start');
            $page = $no/$length + 1;
            $params['category_id'] = !empty($listCateId)?$listCateId:null;
            $params['page'] = $page;
            $params['lang_code'] = !empty($this->input->post('filter_language_code'))?$this->input->post('filter_language_code'):$this->session->admin_lang_code;
            $params['limit'] = $length;
            $list = $this->_data->getData($params);
            $data = array();
            if(!empty($list)) foreach ($list as $item) {
                $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = $item->id;
                $row[] = $item->title;
                $row[] = ($item->is_featured == true)?'<i data-value="1" class="text-primary fa fa-lg fa-star btnUpdateFeatured"></i>':'<i data-value="0" class="text-primary fa fa-lg fa-star-o btnUpdateFeatured"></i>';
                // switch ($item->is_status){
                //     case self::STATUS_ACTIVE:
                //     $row[] = '<span class="label label-success btnUpdateStatus" data-value="'.self::STATUS_ACTIVE.'">'.$this->lang->line('text_status_'.self::STATUS_ACTIVE).'</span>';
                //     break;
                //     case self::STATUS_DRAFT:
                //     $row[] = '<span class="label label-default btnUpdateStatus" data-value="'.self::STATUS_DRAFT.'">'.$this->lang->line('text_status_'.self::STATUS_DRAFT).'</span>';
                //     break;
                //     default:
                //     $row[] = '<span class="label label-danger btnUpdateStatus" data-value="'.self::STATUS_CANCEL.'">'.$this->lang->line('text_status_'.self::STATUS_CANCEL).'</span>';
                //     break;
                // }
                $row[] = formatDate($item->created_time,'d/m/Y H:i');
                $row[] = formatDate($item->updated_time,'d/m/Y H:i');
                //thêm action
                $action = button_action($item->id,['send_mail','edit', 'delete']);
                
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
    public function ajax_add()
    {
      $data_store = $this->_convertData();
      unset($data_store['id']);
      if($id_post = $this->_data->save($data_store)){
			// log action
			// ddQuery($this->db);
        $action = $this->router->fetch_class();
        $note = "Insert $action: ".$id_post;
        $this->addLogaction($action,$note);
        $message['type'] = 'success';
        $message['message'] = $this->lang->line('mess_add_success');
    }else{
        $message['type'] = 'error';
        $message['message'] = $this->lang->line('mess_add_unsuccess');
    }
    die(json_encode($message));
}
function ajax_copy($id){
    $this->load->model('category_model');
    $categoryModel = new Category_model();
    $data = $this->_data->getById($id);
    $data_store = array();
    $data_trans = array();
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
                $data_trans['title'][$lang_code] = "Tin tức demo $randId";
                $data_trans['meta_title'][$lang_code] = "Tin tức demo $randId";
                $data_trans['language_code'][$lang_code] = $lang_code;
                $data_trans['slug'][$lang_code] = $this->toSlug("Tin tức demo $randId");
                $data_trans['description'][$lang_code] = $item->description;
                $data_trans['meta_description'][$lang_code] = !empty($item->meta_description) ? $item->meta_description : '';
                $data_trans['meta_keyword'][$lang_code] = !empty($item->meta_keyword) ? $item->meta_keyword : '';
                $data_trans['content'][$lang_code] = !empty($item->content) ? $item->content : '';
            }
        }
    }

    $data_store = array_merge($data_store,$data_trans);
    $data_store['category_id'] = [3];
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

public function send_mail($id){
    $this->checkRequestGetAjax();
    $oneItem=$this->_data->getById($id,'','vi');
    // get list email
    $this->load->model('newsletter_model');
    $newsletModel = new Newsletter_model();
    $data=$newsletModel->getAll();
    if(!empty($data)){
      $arrMail=[];
      foreach ($data as $key => $value) {
        $arrMail[]=$value->email;
    }
    $content='<div style="text-align:center;"><img src="'.getImageThumb($oneItem->thumbnail,370,280).'"></div><h1>'.$oneItem->title.'</h1><div>Chi tiết xem <a href="'.getUrlNews($oneItem).'">Tại đây</a></div>';
    $this->send_mail_all('BCC',$this->settings['email_admin'],'Admin website Tomita Mart','Tomita['.$oneItem->title.']',$content,$arrMail);
}else{
  die(json_encode(['type'=>'warning','message'=>'Chưa có ai đăng ký nhận tin!']));
}
    //end get list mail
}

private function send_mail_all($emailTo,$emailFrom,$nameFrom,$subject,$content,$bccmail){
    $this->load->library('email');
    $this->email->from($emailFrom, $nameFrom);
    //$this->email->to($emailTo);
    $this->email->bcc($bccmail);
    $this->email->subject($subject);
    $this->email->message(!empty($content) ? $content : 'error');
    if($this->email->send()){
      die(json_encode(['type'=>'success','message'=>'Gửi thành công']));
  }else{  
      die(json_encode(['type'=>'warning','message'=>'Kiểm trả lại thông tin']));
  }
}

public function ajax_edit($id)
{
    $data = (array) $this->_data->getById($id);
    $data['category_id'] = $this->_data->getCategorySelect2($id);
    die(json_encode($data));
}
public function ajax_update(){
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
            //Xóa translate của post
        $this->_data->delete(["id"=>$id],$this->_data->table_trans);
            //Xóa category của post
        $this->_data->delete(["{$this->_name_controller}_id"=>$id],$this->_data->table_category);
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
                    $this->form_validation->set_rules('title[' . $lang_code . ']', 'tiêu đề' . ' - ' . $lang_name, 'required|trim|min_length[5]|max_length[300]|xss_clean');
                    $this->form_validation->set_rules('meta_title[' . $lang_code . ']', 'meta title' . ' - ' . $lang_name, 'required|trim|min_length[5]|max_length[300]|xss_clean');
                    $this->form_validation->set_rules('description[' . $lang_code . ']', 'tóm tắt' . ' - ' . $lang_name, 'required|xss_clean');
                    $this->form_validation->set_rules('meta_description[' . $lang_code . ']', 'meta description' . ' - ' . $lang_name, 'required|xss_clean');
                    $this->form_validation->set_rules('meta_keyword[' . $lang_code . ']', 'meta keyword' . ' - ' . $lang_name, 'required|xss_clean');
                }
            }
            $this->form_validation->set_rules('category_id[]', $this->lang->line('from_category'), 'required');

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
                        $valid["meta_keyword[$lang_code]"] = form_error("meta_keyword[$lang_code]");
                    }
                }
                $valid["category_id[]"] = form_error("category_id[]");
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
