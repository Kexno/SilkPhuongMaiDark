<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends Admin_Controller {
	var $action = '';
	var $note = '';
	protected $_dataCategory;
	protected $_data;
	protected $_name_controller;
	protected $category_tree;
	const STATUS_CANCEL = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_DRAFT = 2;
	public function __construct()
	{
		parent::__construct();
		//tải file ngôn ngữ
		$this->lang->load('banner');
		$this->load->model(['category_model','banner_model','property_model']);
		$this->_data = new Banner_model();
		$this->_dataCategory = new Category_model();
		$this->property = new Property_model();
		$this->_name_controller = $this->router->fetch_class();
	}

	public function _queue_select($categories, $parent_id = 0, $char = ''){
		foreach ($categories as $key => $item)
		{
			if ($item->parent_id == $parent_id)
			{
				$tmp['name'] = $parent_id ? $char.'&nbsp;|--&nbsp;'.$item->title : $char.$item->title;
				$tmp['id'] = $item->id;
				$this->category_tree[] = $tmp;
				unset($categories[$key]);
				$this->_queue_select($categories,$item->id,$char.'&nbsp;&nbsp;');
			}
		}
	}


	public function index()
	{
		$data['heading_title'] = ucfirst($this->_name_controller);
		$data['heading_description'] = "Danh sách $this->_name_controller {$this->session->category_type}";
		/*Breadcrumbs*/
		$this->breadcrumbs->push('Home', base_url());
		$this->breadcrumbs->push($data['heading_title'], '#');
		$data['breadcrumbs'] = $this->breadcrumbs->show();
		/*Breadcrumbs*/
		$data['main_content'] = $this->load->view($this->template_path.$this->_name_controller.'/index', $data, TRUE);
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
			$params['property_id'] = $this->input->post('category_id');
			$params['page'] = $page;
			$params['limit'] = $length;
			$list = $this->_data->getData($params);
			$data = array();
			if(!empty($list)) foreach ($list as $item) {
				$cate = $this->property->getById($item->property_id,'*','vi');
				$no++;
				$row = array();
				$row[] = $item->id;
				$row[] = $item->id;
				$row[] = $item->order;
				$row[] = !empty($cate->title) ? $cate->title : '';
				$row[] = $item->title;
				$row[] = '<a class="fancybox" href="'.getImageThumb($item->thumbnail,200,50).'"><img src="'.getImageThumb($item->thumbnail,200,50).'" style="background-color:#0c7ede"></a>';
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
				//thêm action
				$action = button_action($item->id, ['edit','delete']);
				$row[] = $action;
				$data[] = $row;
			}

			$output = array(
				"draw" => $this->input->post('draw'),
				"recordsTotal" => $this->_data->getTotal($params),
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
		if($id_banner = $this->_data->save($data_store)){
			// log action
			$action = 'post';
			$note = 'Thêm Banner có id là '.$id_banner;
			$this->addLogaction($action,$note);

			$message['type'] = 'success';
			$message['message'] = 'Thêm mới thành công !';
		}else{
			$message['type'] = 'error';
			$message['message'] = 'Thêm mới thất bại';
		}
		die(json_encode($message));
	}

	/*
     * Ajax copy
     * */
	function ajax_copy($id){
		$data = $this->_data->getById($id);
		$data_store = [];
		if(!empty($data)) foreach ($data as $value) {
			$data_store['title'][$value->language_code] = $value->title;
			$data_store['description'][$value->language_code] = $value->description;
			$data_store['is_status'] = $value->is_status;
			$data_store['thumbnail'] = $value->thumbnail;
			$data_store['property_id'] = $value->property_id;
			$data_store['url'] = $value->url;
		}
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
		die(json_encode($message));
	}

	/*
     * Ajax lấy thông tin
     * */
	public function ajax_edit($id)
	{
		$this->load->model('property_model');
		$propertyModel = new Property_model();
		$data['data'] = $this->_data->getById($id);
		if(!empty($data['data'][0]->property_id)) $data['property_id'] = $propertyModel->getSelect2($data['data'][0]->property_id);
		die(json_encode($data));
	}

	/*
     * Xóa một bản ghi
     * */
	public function ajax_delete($id)
	{
		$response = $this->_data->delete(['id'=>$id]);
		if($response != true){
			$message['type'] = 'error';
			$message['message'] = "Xóa bản ghi thất bại !";
		}else{
			$message['type'] = 'success';
			$message['message'] = "Xóa bản ghi thành công !";
		}
		die(json_encode($message));
	}

	/*
     * Cập nhật thông tin
     * */
	public function ajax_update()
	{
		$data_store = $this->_convertData();
		$response = $this->_data->update(array('id' => $this->input->post('id')), $data_store);
		if($response == false){
			$message['type'] = 'error';
			$message['message'] = "Cập nhật thất bại !";
			$message['error'] = $response;
			log_message('error',$response);
		}else{
			// log action
			$action = 'banner';
			$note = 'Sửa Banner có id là '.$this->input->post('id');
			$this->addLogaction($action,$note);

			$message['type'] = 'success';
			$message['message'] = "Cập nhật thành công !";
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
     * Kiêm tra thông tin post lên
     * */
	private function _validate()
	{
		if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
				$this->form_validation->set_rules('title[' . $lang_code . ']', 'tiêu đề' . ' - ' . $lang_name, 'required|trim|min_length[5]|max_length[300]|xss_clean');
				$this->form_validation->set_rules('property_id', $this->lang->line('form_position'), 'required|xss_clean');
			}
			if ($this->form_validation->run() === false) {
				$message['type'] = "warning";
				$message['message'] = $this->lang->line('mess_validation');
				$valid = [];
				if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
					$valid["title[$lang_code]"] = form_error("title[$lang_code]");
				}
				$valid["property_id"] = form_error("property_id");
				$message['validation'] = $valid;
				$message['validation_message'] = validation_errors();
				die(json_encode($message));
			}
		}
	}

	private function _convertData(){
		$this->_validate();
		$data = $this->input->post();
		unset($data['id']);
		return $data;
	}
}
