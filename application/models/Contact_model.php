<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Contact_model extends APS_Model
{
    public function __construct(){
        parent::__construct();
        $this->table = 'contact';
        $this->column_order = array('id','id','email','created_time'); //thiết lập cột sắp xếp
        $this->column_search = array('id','email','phone','fullname','title','content'); //thiết lập cột search
        $this->order_default = array('id' => 'desc'); //cột sắp xếp mặc định
    }

    public function getContact($params){
        if(!empty($this->input->post('columns'))) {
            $i = 0;
            foreach ($this->column_search as $item) // loop column
            {
                if ($this->input->post('search')['value']) // if datatable send POST for search
                {
                    if ($i === 0) // first loop
                    {
                        $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                        $this->db->like($item, $this->input->post('search')['value']);
                    } else {
                        $this->db->or_like($item, $this->input->post('search')['value']);
                    }

                    if (count($this->column_search) - 1 == $i) //last loop
                        $this->db->group_end(); //close bracket
                }
                $i++;
            }

        }
        if (!empty($params['limit']))
            $this->db->limit($params['limit'], $params['offset']);
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->table)->result();
    }

    public function countContact($params = array()){
        if (!empty($params['search'])){
            $this->db->like("$this->table.email", $params['search']);
            $this->db->or_like("$this->table.phone", $params['search']);
        }
        return $this->db->count_all_results($this->table);
    }
}
