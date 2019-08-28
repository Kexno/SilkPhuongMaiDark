<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('authorization')) {
    function authorization($controller,$method="view"){
        $_method=$method.'_'.$controller;
        $_this =& get_instance();
        $_this->load->library('session');
        $_this->load->model('module_model','module');
        //$module=$_this->module->get_datatables();
        if($_this->session->userdata('user_id') == 1) return true;
        $group = $_this->ion_auth->get_users_groups()->result();
        $listAuth=array();
        if(!empty($group)) foreach ($group as $key => $value){
            if(isset($value->authorization)){
                $auth = $value->authorization;
                $decode=JSON_decode($auth,true);
                $listAuth = array_merge_recursive($listAuth,$decode);
            }
        }
        $listAuth = array_filter($listAuth);
        if(!empty($listAuth)) foreach ($listAuth as $cont=> $action){
            if(key_exists($controller,$action) ==true && key_exists($_method,$action[$controller]) == true)
                return true;
        }
    }
}
if (!function_exists('authLogin')) {
    function authLogin(){
        $_this =& get_instance();
        $_this->load->model('module_model','module');
        $_this->load->library('session');
        $module=$_this->module->get_datatables();
        $auth=array();
        if($module){
            foreach ($module as $list){
                $row=array();
                if(authorization($list->controller)==true){
                    $row=1;
                }else{
                    $row=0;
                }
                $auth[]=$row;
            }
        }
        if(in_array(1,$auth,true)==false){
            $_this->session->set_flashdata('error_authorization', 'Bạn không có quyền truy cập vào trang này');
            redirect(site_url('/admin/auth/login'),'refresh');
        }
    }
}


