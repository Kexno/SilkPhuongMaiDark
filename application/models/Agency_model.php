<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Agency_model extends APS_Model {

    protected $table_device_logged;
    public function __construct(){
        parent::__construct();
        $this->table            = "agency";
        $this->table_trans      = "agency_translations";
        $this->column_order     = array("$this->table.id","$this->table.id","$this->table_trans.name","$this->table_trans.address","$this->table.phone","$this->table.status","$this->table.lat","$this->table.lng"); //thiết lập cột sắp xếp
        $this->column_search    = array("$this->table.id","$this->table_trans.name","$this->table_trans.address","$this->table.phone","$this->table.lat","$this->table.lng"); //thiết lập cột search
        $this->order_default    = array("$this->table.id" => 'desc'); //cột sắp xếp mặc định
    }

}