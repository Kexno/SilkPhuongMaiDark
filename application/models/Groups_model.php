<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Groups_model extends APS_Model {

    public function __construct(){
        parent::__construct();
        $this->table = 'groups';
        $this->table_relation = 'users_groups';
        $this->column_order = array('id','id','name','description'); //thiết lập cột sắp xếp
        $this->column_search = array('id','name','description'); //thiết lập cột search
        $this->order_default = array('id' => 'desc'); //cột sắp xếp mặc định
    }

    public function get_group_by_userid($id){
        $this->db->from($this->table_relation);
        $this->db->where('user_id',$id);
        $query = $this->db->get();
        return $query->row();
    }
    public function get_all_group(){
        $this->db->from($this->table);
        $query=$this->db->get();
        return $query->result();
    }
}