<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suggest_product_model extends APS_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = "suggest_product";
        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table_trans.displayed_time", "$this->table.created_time", "$this->table.updated_time",); //thiết lập cột sắp xếp
        $this->column_search = array("$this->table.id", "$this->table_trans.displayed_time"); //thiết lập cột search
        $this->order_default = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }

    public function _where_custom($args)
    {
        extract($args);
        if (!empty($type)){
            $this->db->where('type',$type);
        }

        if (!empty($displayed_time)) {
            $this->db->where('displayed_time', $displayed_time);
        }
    }
}