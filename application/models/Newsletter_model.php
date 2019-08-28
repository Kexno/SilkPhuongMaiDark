<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Newsletter_model extends APS_Model
{
    public function __construct(){
        parent::__construct();
        $this->table = 'newsletter';
        $this->column_order = array('id','id','email','created_time'); //thiết lập cột sắp xếp
        $this->column_search = array('id','email'); //thiết lập cột search
        $this->order_default = array('id' => 'desc'); //cột sắp xếp mặc định
    }
    public function checkexist($email){
        $this->db->from($this->table);
        $this->db->where('email',$email);
        $query = $this->db->get();
        return $query->row();
    }
        public function getEmail($email){
        return $this->db->select('*')
        ->where(['email'=>$email])
        ->get($this->table)
        ->num_rows();
    }

    public function _where_after($args, $typeQuery){
        $page = 1; //Page default
        $limit = 10;

        extract($args);

        $this->db->group_by("$this->table.id");
        //query for datatables jquery
        $this->_get_datatables_query();

        if(!empty($search)){
            $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
            $this->db->like("$this->table.email", $search);
            $this->db->group_end(); //close bracket
        }

        if($typeQuery === null){
            if(!empty($order) && is_array($order)){
                foreach ($order as $k => $v)
                    $this->db->order_by($k, $v);
            } else if(isset($this->order_default)) {
                $order = $this->order_default;
                $this->db->order_by(key($order), $order[key($order)]);
            }
            $offset = ($page-1)*$limit;
            $this->db->limit($limit,$offset);
        }
    }
}