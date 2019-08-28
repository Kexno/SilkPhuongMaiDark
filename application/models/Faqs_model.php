<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Faqs_model extends APS_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table            = "faqs";
        $this->table_trans      = "faqs_translations";
        $this->column_order     = array("$this->table.id", "$this->table.id" , "$this->table_trans.title", "$this->table.created_time", "$this->table.updated_time", "$this->table.is_status");
        $this->column_search    = array("$this->table.id", "$this->table_trans.title", "$this->table.created_time", "$this->table.updated_time", "$this->table.is_status"); 
        $this->order_default    = array("$this->table.id" => "DESC"); 
    }
}
