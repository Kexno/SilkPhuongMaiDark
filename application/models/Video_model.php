<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Video_model extends APS_Model {

    protected $table_device_logged;
    public function __construct(){
        parent::__construct();
        $this->table = 'video';
        $this->column_order = array('id','id','link_video','status'); //thiết lập cột sắp xếp
        $this->column_search = array('id','link_video','status'); //thiết lập cột search
        $this->order_default = array('id' => 'desc'); //cột sắp xếp mặc định
    }

    public function get_by_id_video($id, $group_id='')
    {
    	$this->db->select('*');
        $this->db->from("video");
    	$this->db->where('id', $id);
        $this->db->limit(1);
    	$query = $this->db->get()->row();
    	return $query;
    }
    public function get_video($id)
    {
        $this->db->select('*');
        $this->db->from("video");
        $this->db->where_in('id', $id);
        $query = $this->db->get()->result();
        return $query;
    }

}