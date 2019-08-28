<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class System_menu extends Admin_Controller
{

    protected $_data;
    protected $_name_controller;
    protected $_pageModel;
    protected $_postModel;
    protected $_categoryModel;
    protected $_listMenu;
    protected $_dataSelect;

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('system_menu');
        $this->load->model(['system_menu_model']);
        $this->_data = new System_menu_model();
        $this->_name_controller = $this->router->fetch_class();
        $this->_listMenu = array();
        $this->_dataSelect = array(array('id' => 0, 'text' => 'No Parent'));
        $data = $this->_data->getMenu();
        $this->selectParent($data);
    }

    public function index()
    {
        $data['heading_title'] = "Admin_menu";
        $data['heading_description'] = "Danh sách admin menu";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view($this->template_path . $this->_name_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_list()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $lang_code = $this->input->get('lang_code');
            empty($lang_code) ? $lang_code = $this->session->admin_lang : null;
            $data = $this->_data->getMenu();
            echo json_encode($data);
        }
        exit;
    }

    public function ajax_add()
    {
        $data_store = $this->_convertData();
        $data_store['controller'] = $this->getControllerFrommLink($data_store);
        unset($data_store['id']);
        if ($id_post = $this->_data->save($data_store)) {
            // log action
            $action = $this->router->fetch_class();
            $note = "Insert $action: " . $id_post;
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_add_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_add_unsuccess');
        }
        die(json_encode($message));
    }

    public function ajax_update()
    {
        $data_store = $this->_convertData();
        $data_store['controller'] = $this->getControllerFrommLink($data_store);
        $id = $data_store['id'];
        unset($data_store['id']);
        $response = $this->_data->update(array('id' => $id), $data_store, $this->_data->table);
        if ($response != false) {
            // log action
            $action = $this->router->fetch_class();
            $note = "Update $action: " . $id;
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_update_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_update_unsuccess');
        }
        die(json_encode($message));
    }

    public function ajax_delete($id)
    {
        // $childs = $this->_data->child($id);
        // if (!empty($childs)) {
        //     foreach ($childs as $child) {
        //         $response = $this->_data->delete(['id' => $child['id']]);
        //         if ($response != false) {
        //             $action = $this->router->fetch_class();
        //             $note = "Delete $action: $id";
        //             $this->addLogaction($action, $note);
        //         } else {
        //             $message['type'] = 'error';
        //             $message['message'] = $this->lang->line('mess_delete_unsuccess');
        //             $message['error'] = $response;
        //             log_message('error', $response);
        //             break;
        //         }
        //     }
        // }
        $response = $this->_data->delete(['id' => $id]);
        if ($response != false) {
            $action = $this->router->fetch_class();
            $note = "Delete $action: $id";
            $this->addLogaction($action, $note);
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_delete_success');
        } else {
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_delete_unsuccess');
            $message['error'] = $response;
            log_message('error', $response);
        }
        die(json_encode($message));
    }

    /**
     * Gửi ajax dữ liệu option select
     */
    public function ajax_load_parent_menu()
    {
        $this->checkRequestGetAjax();
        $dataJson = [];
        $keyword    = toSlug(toNormal($this->input->get("q")));
        foreach ($this->_dataSelect as $key => $value) {
            if(!empty($keyword)){
                if(strpos(toSlug(toNormal($value['text'])),$keyword)!==false){
                    array_push($dataJson, $value);
                }
            }else{
                array_push($dataJson, $value);
            }
        }
        echo json_encode($dataJson);
    }

    public function ajax_get_parent_menu(){
        $this->checkRequestPostAjax();
        $data = array();
        $post_value = $this->input->post();
        if (!empty($post_value)) {
            foreach ($this->_dataSelect as $key => $value){
                if ($value['id'] == $post_value['parent_id']) {
                    array_push($data, $value);
                    echo json_encode($data);
                }
            }
        }
    }

    /**
     * Lấy danh sách menu append vào select
     * @param $menu
     * @param int $parent
     * @param string $char
     */
    private function selectParent($menu, $char = '')
    {
        if (!empty($menu)) foreach ($menu as $key => $row) {
            $row = (array)$row;
            $data = array();
            $data['id'] = $row['id'];
            $data['text'] = $char . $row['text'];
            array_push($this->_dataSelect, $data);
            if (isset($row['children']) && count($row['children']) > 0)
                // Tiếp tục đệ quy để tìm menu con của menu đang lặp
                $this->selectParent($row['children'], $char . '|--');
        }
    }

    /**
     * Lấy controller từ link người dùng nhập vào
     * @param $data_store
     * @return mixed
     */
    private function getControllerFrommLink($data_store)
    {
        $all_link = glob(APPPATH . "controllers" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "*.php");
        if (isset($data_store['href'])) {
            $controller_link = explode("/", $data_store['href']);
            $controllers = array();
            foreach ($all_link as $controller) {
                $tmpName = pathinfo($controller);
                $name = strtolower($tmpName['filename']);
                $controllers[] = $name;
            }
            foreach ($controller_link as $key => $value) {
                if ($value == null) {
                    continue;
                } else {
                    if (in_array($value, $controllers)) {
                        return $value;
                        break;
                    }
                }
            }
        } else return null;
    }

    /**
     * Kiểm tra thông tin trước khi nhập lên
     */
    private function _validate()
    {
        $this->checkRequestPostAjax();
        $rules = array(
            array(
                'field' => 'text',
                'label' => 'Text ',
                'rules' => 'required|trim|min_length[5]|max_length[255]'
            ),
            array(
                'field' => 'icon',
                'label' => 'Icon ',
                'rules' => 'required'
            )
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            if (!empty($rules)) foreach ($rules as $item) {
                if (!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
            }
            $this->_message = array(
                'validation' => $valid,
                'type'       => 'warning',
                'message'    => $this->lang->line('mess_validation')
            );
            $this->returnJson();
        }
    }

    private function _convertData()
    {
        $this->_validate();
        $data = $this->input->post();
        return $data;
    }

}