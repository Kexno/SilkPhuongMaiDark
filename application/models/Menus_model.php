<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus_model extends APS_Model
{
  public $listmenu;

  public function __construct()
  {
    parent::__construct();
    $this->table = 'menus';
  }

  public function getMenu($location, $lang_code)
  {
    $this->db->select('*');
    $this->db->from($this->table);
    $this->db->where('location_id', $location);
    $this->db->order_by('order', 'DESC');
    $this->db->where('language_code', $lang_code);
    $query = $this->db->get();
    return $query->result_array();
  }

  // hiển thị dữ liệu
  public function listmenu($menu, $parent = 0)
  {
    foreach ($menu as $key => $row) {
      if ($row['parent_id'] == $parent) {
        $link = ($row['type'] == 'other' || $row['link'] === '#') ? $row['link'] : (($row['link'] === '/') ? BASE_URL : BASE_URL . $row['link']);
        $this->listmenu[] = array(
          'id' => intval($row['id']),
          'name' => $row['title'],
          'class' => $row['class'],
          'icon' => !empty($row['icon']) ? $row['icon'] : '',
          'type' => $row['type'],
          'order' => $row['order'],
          'link' => $link,
          'level' => intval($row['parent_id']),
          'parent' => intval($row['parent_id']));
        unset($menu[$key]);
        // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
        $this->listmenu($menu, $row['id']);
      }
    }
  }

  public function saveMenu($data)
  {
    $this->db->insert($this->table, $data);

    return $this->db->insert_id();
  }

}
