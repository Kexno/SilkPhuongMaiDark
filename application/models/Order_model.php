<?php

class Order_model extends APS_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = "order";
    $this->table_detail = "order_items";
    $this->column_search = array("$this->table.code", "$this->table.full_name", "$this->table.phone", "$this->table.email");
    $this->column_order = array("$this->table.id", "$this->table.code", "$this->table.full_name", "$this->table.phone", "$this->table.email", "$this->table.is_status", "$this->table.created_time");
  }

  public function _where_custom($args){
    extract($args);
    if (!empty($customer_id)) $this->db->where('customer_id', $customer_id);
  }

  public function saveOrder($data)
  {
    $order = $data['order_info'];
    $orderDetail = $data['order_detail'];
    $orderDetailData = array();
    if ($this->db->insert($this->table, $order) == false) {
      log_message('info', json_encode($order));
      log_message('error', $this->db->error());
      return false;
    } else {
      $orderId = $this->db->insert_id();
      if (!empty($orderDetail)) foreach ($orderDetail as $item) {
        $orderDetailData['order_id'] = $orderId;
        $orderDetailData['product_id'] = (integer)$item['id'];
        $orderDetailData['quantity'] = $item['qty'];
        $orderDetailData['price'] = $item['price'];
        if ($this->db->insert($this->table_detail, $orderDetailData) == false) {
          log_message('info', json_encode($orderDetailData));
          log_message('error', $this->db->error());
          return false;
        }
      }
      return $orderId;
    }
  }

  public function get_order($id)
  {
    $this->db->select('*');
    $this->db->from($this->table);
    $this->db->where("$this->table.id", $id);
    return $this->db->get()->row();
  }
  public function get_order_detail($order_id)
  {
    $this->db->select('*');
    $this->db->from($this->table_detail);
    $this->db->where(["$this->table_detail.order_id" => $order_id, "$this->table_detail.is_status" => 1]);
    return $this->db->get()->result();
  }

  //check account voucher đã được ăn
  public function receivingedAccount($voucher_id, $account_id)
  {
    $this->db->select('*');
    $this->db->from($this->table);
    $this->db->where('account_id', $account_id);
    $this->db->where('voucher_id', $voucher_id);
    return $this->db->get()->row();
  }

  public function getByIdPayment($id)
  {
    $this->db->select('*');
    $this->db->from($this->table);
    $this->db->where("$this->table.id_payment", $id);
    $query = $this->db->get();
    return $query->row();
  }

  public function getDataOrder($args = array(), $returnType = 'object')
  {
    $this->_whereOrder($args);
    $query = $this->db->get();
    if ($returnType !== 'object') return $query->result_array(); //Check kiểu data trả về
    else return $query->result();
  }

  private function _whereOrder($args, $typeQuery = null)
  {
    $select = "*";
    // $order = $this->order_default;
    $lang_code = $this->session->admin_lang; //Mặc định lấy lang của Admin
    $page = 1; //truyền page active
    $limit = $this->config->item('cms_limit');

    extract($args);
    $this->db->distinct();
    $this->db->select($select);
    $this->db->from($this->table);
    if (isset($is_status) && $is_status != "")
      $this->db->where("$this->table.is_status", $is_status);
    $this->_get_datatables_query();
    if (!empty($search)) {
      $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
      $this->db->like('title', $search);
      $this->db->or_like('description', $search);
      $this->db->group_end(); //close bracket
    }

    $order_default = array('created_time' => 'DESC');
    if ($typeQuery === null) {
      $offset = ($page - 1) * $limit;
      $this->db->limit($limit, $offset);
    }
  }

  public function _get_datatables_query()
  {
    if (!empty($this->input->post('columns'))) {
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

      if ($this->input->post('order')) {
        $this->db->order_by($this->column_order[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
      } else if (isset($this->order_default)) {
        $order = $this->order_default;
        $this->db->order_by(key($order), $order[key($order)]);
      }
    }
  }

  public function checkExitsCode($active_code)
  {
    $this->db->select('1');
    $this->db->from($this->table);
    $this->db->where("$this->table.activation_code", $active_code);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function getCodeActive($active_code, $account_id)
  {
    $this->db->select('status_act');
    $this->db->from($this->table);
    $this->db->where("$this->table.activation_code", $active_code);
    $this->db->where("$this->table.account_id", $account_id);
    $this->db->where("$this->table.is_status", 2);
    $query = $this->db->get();
    return $query->row();
  }

  public function getOrderByActCode($act_code)
  {
    $this->db->select(array("$this->table.id", "$this->table.account_id", "$this->table_detail.course_id", "$this->table_detail.id order_detail_id"));
    $this->db->from($this->table_detail);
    $this->db->join("$this->table", "$this->table.id=$this->table_detail.order_id");
    $this->db->where("$this->table.activation_code", $act_code);
    $this->db->where("$this->table.is_status", 2);
    $data = $this->db->get()->result();
    return $data;
  }

  // tổng tiền lịch sử giao dịch của user
  public function totalOrderDetailByAccount($params=array())
  {
    $this->db->select("COUNT(B.course_id) as total, SUM(B.price) AS sum_price", FALSE);
    $this->db->from("$this->table A");
    $this->db->join("$this->table_detail B", "B.order_id=A.id");
    $this->db->join('course C', "B.course_id=C.id");
    $this->db->join("course_translations E", "E.id=C.id");
    if(!empty($params['is_status'])) $this->db->where_in("A.is_status", $params['is_status']);
    if (!empty($params['account_id'])) $this->db->where("A.account_id", $params['account_id']);
    if (!empty($params['language_code'])) $this->db->where("E.language_code", $params['language_code']);
    if (!empty($params['collaborator_id'])) $this->db->where("A.collaborator_id", $params['collaborator_id']);
    if(!empty($params['start_date'])) $this->db->where("A.updated_time >=",$params['start_date']);
    if(!empty($params['end_date'])) $this->db->where("A.updated_time <=",$params['end_date']);
    $data = $this->db->get()->row();
    return $data;
  }
  public function getOrderDetailByAccount($params = array())
  {
    $this->db->select(array('E.title','C.thumbnail', 'C.id', 'E.slug','B.price', 'A.method', 'A.is_status', 'A.activation_code', 'A.status_act','A.updated_time','A.account_id'));
    $this->db->from("$this->table A");
    $this->db->join("$this->table_detail B", "B.order_id=A.id");
    $this->db->join('course C', "B.course_id=C.id");
    $this->db->join("course_translations E", "E.id=C.id");
    if(!empty($params['is_status'])) $this->db->where_in("A.is_status", $params['is_status']);
    if (!empty($params['account_id'])) $this->db->where("A.account_id", $params['account_id']);
    if (!empty($params['language_code'])) $this->db->where("E.language_code", $params['language_code']);
    if (!empty($params['collaborator_id'])) $this->db->where("A.collaborator_id", $params['collaborator_id']);
    if(!empty($params['start_date'])) $this->db->where("A.updated_time >=",$params['start_date']);
    if(!empty($params['end_date'])) $this->db->where("A.updated_time <=",$params['end_date']);
    $this->db->order_by('A.id','DESC');
    $offset = ($params['page'] - 1) * $params['limit'];
    $this->db->limit($params['limit'], $offset);
    $data = $this->db->get()->result();
    return $data;
  }
}
