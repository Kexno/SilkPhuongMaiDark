<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users_model extends APS_Model {

    protected $table_device_logged;
    public function __construct(){
        parent::__construct();
        $this->table = 'users';
        $this->table_device_logged = 'logged_device';//bảng logged device
        $this->column_order = array('id','id','username','email','first_name','active'); //thiết lập cột sắp xếp
        $this->column_search = array('id','username','email','first_name','last_name'); //thiết lập cột search
        $this->order_default = array('id' => 'desc'); //cột sắp xếp mặc định
    }

    public function get_by_id_user($id, $group_id='')
    {
    	$this->db->select('*');
        $this->db->from("users");
    	$this->db->where('id', $id);
        $this->db->limit(1);
    	$query = $this->db->get()->row();
    	return $query;
    }
    public function get_user($id)
    {
        $this->db->select('*');
        $this->db->from("users");
        $this->db->where_in('id', $id);
        $query = $this->db->get()->result();
        return $query;
    }

}