<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System_menu_model extends APS_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'system_menu';
        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table.text", "$this->table.icon", "$this->table.href", "$this->table.order", "$this->table.class"); //thiết lập cột sắp xếp
        $this->column_search = array("$this->table.id", "$this->table.text"); //thiết lập cột search
        $this->order_default = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }


    public function getMenu()
    {
        $data = $this->getSystemMenu();
        $list = array();
        foreach ($data as $a){
            $list[$a['parent_id']][] = $a;
        }
        $tree = $this->recursive_array($list, $list[0]);
        return $tree;
    }

    private function recursive_array(&$list, $parent)
    {
        $tree = array();
        foreach ($parent as $key => $value){
            if(isset($list[$value['id']])){
                $value['children'] = $this->recursive_array($list, $list[$value['id']]);
            } else {
                $value['children'] = [];
            }
            $tree[] = $value;
        }
        return $tree;
    }

    public function getSystemMenu() {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('order', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
