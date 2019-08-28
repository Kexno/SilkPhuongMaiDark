<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_model extends APS_Model
{

  public function __construct()
  {
    parent::__construct();
    $this->table = "page";
    $this->table_tore = "store";
    $this->table_trans = "page_translations";//bảng bài viết
    $this->table_category = "category";//bảng bài viết

    $this->column_order = array("$this->table.id", "$this->table.id", "$this->table_trans.title", "$this->table.is_status", "$this->table.created_time", "$this->table.updated_time",); //thiết lập cột sắp xếp
    $this->column_search = array("$this->table.id", "$this->table_trans.title"); //thiết lập cột search
    $this->order_default = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
  }

  public function normal_page_all()
  {
    $this->db->from($this->table);
    if (!empty($this->table_trans)) $this->db->join($this->table_trans, "$this->table.id = $this->table_trans.id");
    $this->db->where("$this->table.style", "");
  }

  public function total_store()
  {
    $this->db->from($this->table_tore);
    return $this->db->count_all_results();
  }

  public function getBySlug($slug, $select = '*', $lang_code = null)
  {

    $this->db->select($select);
    $this->db->from($this->table);
    if (!empty($this->table_trans)) $this->db->join($this->table_trans, "$this->table.id = $this->table_trans.id");
    $this->db->where("$this->table_trans.slug", $slug);
    if (empty($this->table_trans)) {
      $query = $this->db->get();
      return $query->row();
    }

    if (!empty($lang_code)) {
      $this->db->where("$this->table_trans.language_code", $lang_code);
      $query = $this->db->get();
      return $query->row();
    } else {
      $query = $this->db->get();
      return $query->result();
    }
  }

  public function slugToId($slug)
  {
    $this->db->select('tb1.id');
    $this->db->from($this->table . ' AS tb1');
    $this->db->join($this->table_trans . ' AS tb2', 'tb1.id = tb2.id');
    $this->db->where('tb2.slug', $slug);
    $data = $this->db->get()->row();
    //ddQuery($this->db);
    return !empty($data) ? $data->id : null;
  }

  public function getPageByLayout($layout)
  {
    $this->db->select(array('tb1.id', 'tb2.slug'));
    $this->db->from($this->table . ' AS tb1');
    $this->db->join($this->table_trans . ' AS tb2', 'tb1.id = tb2.id');
    $this->db->where('tb1.style', $layout);
    $this->db->where('tb2.language_code', $this->session->public_lang_code);
    $data = $this->db->get()->row();
    return $data;
  }


}