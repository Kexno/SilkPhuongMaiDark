<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_model extends APS_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table            = "banner";
		$this->table_trans      = "banner_translations";
		$this->column_order     = array("$this->table.id","$this->table.id"/*,"$this->table.position_id"*/,"$this->table_trans.title","$this->table.thumbnail","$this->table.is_status","$this->table.created_time","$this->table.updated_time"); //thiết lập cột sắp xếp
		$this->column_search    = array("$this->table.id","$this->table_trans.title"); //thiết lập cột search
		$this->order_default    = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
	}

	public function _where_custom($args){
		extract($args);
		if(!empty($property_id)) $this->db->where("$this->table.property_id", $property_id);
	}
	public function getBanner($property_id,$limit=1)
	{
		$this->db->select("$this->table.id,$this->table.url,$this->table.thumbnail,$this->table_trans.title,$this->table_trans.description");
		$this->db->from($this->table);
		$this->db->join("$this->table_trans", "$this->table_trans.id = $this->table.id");
		$this->db->where("$this->table_trans.language_code", $this->session->public_lang_code);
		$this->db->where("$this->table.property_id", $property_id);
		$this->db->where("$this->table.is_status", 1);
		$this->db->order_by("$this->table.created_time", 'desc');
		$this->db->limit($limit);
		$data = $this->db->get()->result();
		return $data;
	}

	public function get_banner($property_id,$id)
	{
		$this->db->select("$this->table.id,$this->table.url,$this->table.thumbnail,$this->table_trans.title,$this->table_trans.description");
		$this->db->from($this->table);
		$this->db->join("$this->table_trans", "$this->table_trans.id = $this->table.id");
		$this->db->where("$this->table_trans.language_code", $this->session->public_lang_code);
		$this->db->where("$this->table.property_id", $property_id);
		$this->db->where("$this->table.is_status", 1);
		$this->db->where("$this->table.id", $id);
		$this->db->limit(1);
		$data = $this->db->get()->row();
		if(!empty($data)) return $data;
		return 0;
	}
}
