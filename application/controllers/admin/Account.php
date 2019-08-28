<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends Admin_Controller
{
    protected $_data;
    protected $_data_auth;
    protected $_data_group;
    protected $_name_controller;
    protected $_type_account;
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('account');

        $configIonAuth['users'] = 'account';
        $configIonAuth['groups'] = 'account_type';
        $configIonAuth['users_groups'] = 'account_groups';
        $configIonAuth['login_attempts'] = 'login_attempts';
        $this->load->library('ion_account');
        $this->load->model(array('account_model','location_model'));
        $this->_data         = new Account_model();
        $this->locationModel = new Location_model();

        $this->_name_controller = $this->router->fetch_class();
        $this->ion_account->_init($configIonAuth);
    }

    public function index()
    {

        $data['heading_title'] = "Khách hàng";
        $data['heading_description'] = "Quản lý khách hàng";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

  /*
   * Ajax trả về datatable
   * */
  public function ajax_list(){
    if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $length                 = $this->input->post('length');
        $no                     = $this->input->post('start');
        $page                   = $no / $length + 1;
        $params['page']         = $page;
        $params['search']       = $this->input->post('search')['value'];
        $params['limit']        = $length;
        if($this->input->post('is_status') !='') $params['active'] = $this->input->post('is_status');
        $list = $this->_data->getDataPro($params);
        $data = array();
        if (!empty($list)) foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->id;
            $row[] = $item->id;
            $row[] = $item->full_name;
            $row[] = $item->phone;
            $row[] = $item->email;
            $row[] = gender($item->gender);
            $row[] = formatDate($item->created_time);
            switch ($item->active) {
                case 1:
                $row[] = '<span class="label label-success " data-value="1">Đang hoạt động</span>';
                break;
                case 2:
                $row[] = '<span class="label label-default " data-value="2">Chờ kích hoạt</span>';
                break;
                default:
                $row[] = '<span class="label label-danger " data-value="0">Ngừng hoạt động</span>';
                break;
            }
            $action = button_action($item->id, ['edit','delete']);

            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->_data->getTotalPro($params),
            "recordsFiltered" => $this->_data->getTotalPro($params),
            "data" => $data,
        );
    }
    die(json_encode($output));
}
  /*
   * Ajax lấy thông tin
   * */
  public function ajax_edit($id){
    $data['data'] = $oneItem = (array)$this->_data->getById($id);
    if(!empty($data['data']['address'])){
        $addr=json_decode($data['data']['address'])[0];
        $data['data']['address']=$addr->address.', '.$this->locationModel->getWardById($addr->ward)->title.', '.$this->locationModel->getDistrictById($addr->district)->title.', '.$this->locationModel->getCityById($addr->province)->title;
    }
    unset($data['data']['password']);
    die(json_encode($data));
}

public function ajax_update(){
    $this->_validate();
    $data_store = [];
    if ($this->input->post('full_name')) {
        $data_store['full_name'] = strip_tags(trim($this->input->post('full_name')));
    }
    if ($this->input->post('job')) {
        $data_store['job'] = strip_tags(trim($this->input->post('job')));
    }
    if ($this->input->post('birthday')) {
        $data_store['birthday'] = strip_tags(trim($this->input->post('birthday')));
    }
    if ($this->input->post('gender')) {
        $data_store['gender'] = strip_tags(trim($this->input->post('gender')));
    }
    if ($this->input->post('phone')) {
        $data_store['phone'] = strip_tags(trim($this->input->post('phone')));
    }
    if ($this->input->post('password')) {
        $data_store['password'] = strip_tags(trim($this->input->post('password')));
    }
    $data_store['introduce_yourself'] = $this->input->post('introduce_yourself');
    // $data_store['address'] = strip_tags(trim($this->input->post('address')));

    $data_store['avatar'] = trim($this->input->post('avatar'));
    $data_store['active'] = trim($this->input->post('active'));
    $response = $this->ion_account->update($this->input->post('id'), $data_store);
    if ($response != false) {
          // log action
        $action = $this->router->fetch_class();
        $note = "Update $action: " . $this->input->post('id');
        $this->addLogaction($action, $note);
        $message['type'] = 'success';
        $message['message'] = $this->lang->line('mess_update_success');
    } else {
        $message['type'] = 'error';
        $message['message'] = $this->lang->line('mess_update_unsuccess');
    }
    die(json_encode($message));
} 


public function ajax_update_field()
{
    if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      $id = $this->input->post('id');
      $field = $this->input->post('field');
      $value = $this->input->post('value');
      $response = $this->_data->update(['id' => $id], [$field => $value]);
      if ($response != false) {
        $this->_data->resetAllCache($id);
        $message['type'] = 'success';
        $message['message'] = $this->lang->line('mess_update_success');
    } else {
        $message['type'] = 'error';
        $message['message'] = $this->lang->line('mess_update_unsuccess');
    }
    print json_encode($message);
}
exit;
}
  /*
   * Xóa một bản ghi
   * */
  public function ajax_delete($id){
    $response = $this->_data->delete(['id' => $id]);
    if ($response != false) {
        $action = $this->router->fetch_class();
        $note = "delete $action: $id";
        $this->addLogaction($action, $note);
        $message['type'] = 'success';
        $message['message'] = $this->lang->line('mess_delete_success');
    } else {
        $message['type'] = 'error';
        $message['message'] = $this->lang->line('mess_delete_unsuccess');
        $message['error'] = $response;
    }
    die(json_encode($message));
}

private function _validate(){
    if (!empty($this->input->post('password'))) {
        $this->form_validation->set_rules('password', 'mật khẩu', 'required|trim');
        $this->form_validation->set_rules('repassword', 'nhập lại mật khẩu', 'trim|matches[password]|min_length[6]|max_length[32]|required');
    }
    if (!empty($this->input->post('password'))) {
        if ($this->form_validation->run() === false) {
            $message['type'] = "warning";
            $message['message'] = $this->lang->line('mess_validation');
            $valid = [];

            $valid["password"]          = form_error("password");
            $valid["repassword"]        = form_error("repassword");
            $message['validation'] = $valid;
            die(json_encode($message));
        }
    }

}
public function load_city(){
    $data = $this->locationModel->loadCity();
    $keyword = $this->toSlug($this->input->get("q"));
    $keyword=$this->toSlug($this->toNormal($keyword));
    $dataJson = [];
    if(!empty($data)) foreach ($data as $key => $item){
        if(!empty($keyword)){
            if(strpos($item->slug,$keyword)!==false){
                $dataJson[] = ['id'=>$item->code, 'text'=>$item->name];
            }
        }else{
            $dataJson[] = ['id'=>$item->code, 'text'=>$item->name];
        }
    }
    echo json_encode($dataJson);exit;
}
public function load_district($city_id){
    $dataJson = [];
    $keyword = $this->toSlug($this->input->get("q"));
    $keyword=$this->toSlug($this->toNormal($keyword));
    $data = $this->locationModel->loadDistrict($city_id);
    if(!empty($data)) foreach ($data as $key => $item){
        if(!empty($keyword)){
            if(strpos($item->slug,$keyword)!==false){
                $dataJson[] = ['id'=>$item->code, 'text'=>$item->name];
            }
        }else{
            $dataJson[] = ['id'=>$item->code, 'text'=>$item->name];
        }
    }
    echo json_encode($dataJson);exit;
}


}