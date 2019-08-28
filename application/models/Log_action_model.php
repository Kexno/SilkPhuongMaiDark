<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Log_action_model extends APS_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'log_action';
        $this->column_order     = array("$this->table.id","$this->table.id","$this->table.action","$this->table.note","$this->table.uid","$this->table.created_time"); //thiết lập cột sắp xếp
        $this->column_search    = array('action','note'); //thiết lập cột search
        $this->order_default    = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }

}
