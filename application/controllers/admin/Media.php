<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends Admin_Controller
{

    public function index(){
        $data['heading_title'] = ucfirst($this->router->fetch_class());
        $data['heading_description'] = 'Quản lý Media: Ảnh, Video, Files,...';
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['iframe'] = site_url('admin/media/iframe');
        $data['main_content'] = $this->load->view($this->template_path.'media/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function iframe(){
        $this->load->view($this->template_path.'media/iframe');
    }

}