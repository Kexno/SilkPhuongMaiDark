<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends APS_Model
{

  protected $table_device_logged;
  protected $table_gift;

  public function __construct()
  {
    parent::__construct();
    $this->table = 'account';
    $this->account_group = 'account_groups';
    $this->table_device_logged = 'logged_device';//bảng logged device
    $this->column_order = array("$this->table.id", "$this->table.id", "$this->table.full_name", "$this->table.phone", "$this->table.email", "$this->table.company", "$this->table.active"); //thiết lập cột sắp xếp
    $this->column_search = array("$this->table.full_name", "$this->table.email", "$this->table.phone"); //thiết lập cột search
    $this->order_default = array('id' => 'desc'); //cột sắp xếp mặc định

  }



  public function __where($args, $typeQuery = null)
  {
    $select = "*";
    //$lang_code = $this->session->admin_lang; //Mặc định lấy lang của Admin
    $page = 1; //Page default
    $limit = 10;

    extract($args);
    //$this->db->distinct();
    $this->db->select($select);
    $this->db->from($this->table);
    if (!empty($group_by))
      $this->db->group_by("$this->table.$group_by");

    if (!empty($other_active)) $this->db->where("$this->table.active !=", $other_active);
    if (isset($active)) $this->db->where("$this->table.active", $active);
    if (!empty($group_id)) {
      $this->db->join("account_groups", "account.id = account_groups.user_id");
      $this->db->where("account_groups.group_id", $group_id);
    }
    if (!empty($order_by)) $this->db->order_by('created_time', $order_by);

    if (!empty($search)) {
      $this->db->group_start();
      $this->db->like("$this->table.full_name", $search);
      $this->db->or_like("$this->table.email", $search);
      $this->db->or_like("$this->table.phone", $search);
      $this->db->group_end(); //close bracket
    }
    //query for datatables jquery
    $this->_get_datatables_query();

    $this->db->order_by('created_time', 'DESC');
    if (!empty($search_user)) {
      $this->db->group_start();
      $this->db->like("$this->table.username", $search_user);
      $this->db->group_end(); //close bracket
    }
    if ($typeQuery === null) {
      $offset = ($page - 1) * $limit;
      $this->db->limit($limit, $offset);
    }
  }

  public function getTotalPro($args = [])
  {
    $this->__where($args, "count");
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function get_by_id_account($id)
  {
    $this->db->select(array("$this->table.*", "$this->account_group.user_id", "$this->account_group.group_id"));
    $this->db->from($this->table);
    $this->db->join($this->account_group, "$this->table.id=$this->account_group.user_id");
    $this->db->where("$this->table.id", $id);
    return $this->db->get()->row();
  }

  public function getDataPro($args = array(), $returnType = "object")
  {
    $this->__where($args);
    $query = $this->db->get();
    if ($returnType !== "object") return $query->result_array(); //Check kiểu data trả về
    else return $query->result();
  }

  public function getUserByField($key, $value, $status = '')
  {
    $this->db->select('*');
    $this->db->where($this->table . '.' . $key, $value);
    if (!empty($status)) $this->db->where($this->table . '.active', $status);
    return $this->db->get($this->table)->row();
  }


  public function check_oauth($field, $oauth,$type=true)
  {
    $tablename = $this->table;
    $this->db->select('*');
    $this->db->where($field, $oauth);
    if($type){
      $this->db->where(['oauth_provider'=>NULL,'oauth_uid'=>NULL]);
    }
    return $this->db->get($tablename)->row();
  }


  public function updateField($account_id, $key, $value)
  {
    $this->db->where($this->table . '.id', $account_id);
    $this->db->update($this->table, array($this->table . '.' . $key => $value));
    return true;
  }

  public function get_group_by_account_id($id)
  {
    $this->db->from($this->account_group);
    $this->db->where('user_id', $id);
    $query = $this->db->get();
    return $query->row();
  }


  public function unlinkOld()
  {
    return $this->db->select('avatar')->from($this->table)->where('id', $this->session->account['account_id'])->get()->row();
  }

  public function checkActive($id = 0)
  {
    $this->db->select('active');
    $this->db->from('account');
    $this->db->where(['id' => $id]);
    $resul = $this->db->get()->row()->active;
    if ($resul == 1) return TRUE;
    return FALSE;
  }

  public function getAccount($id)
  {
    $this->db->select('id,email,full_name');
    $this->db->from($this->table);
    $this->db->where_in('id',$id);
    $data = $this->db->get()->result();
    return $data;
  }
}
