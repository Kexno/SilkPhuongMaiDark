<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends Public_Controller
{
  	protected $_data;

  	public function __construct()
 	{
	    parent::__construct();
	    $this->load->model('location_model');
	    $this->_data = new Location_model();
  	}

    public function ajax_get_district()
    {
        $this->checkRequestPostAjax();
        $city_id = $this->input->post('city_id');
        $data = $this->_data->getDataDistrict(array('limit' => 1000, 'city_id' => $city_id,'order'=>['slug'=>'ASC']));
        $this->returnJson($data);
    }

    public function ajax_get_ward()
    {
        $this->checkRequestPostAjax();
        $id_district = $this->input->post('district_id');
        $data = $this->_data->getDataWard(array('limit' => 1000, 'district_id' => $id_district,'order'=>['slug'=>'ASC']));
        $this->returnJson($data);
    }
}
