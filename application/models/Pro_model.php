<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pro_model extends APS_Model
{
    public $table_log_download;
    public $table_log_collection;
    public function __construct()
    {
        parent::__construct();
        $this->table            = 'product';    // bảng sản phẩm
        $this->table_trans      = 'product_translations';   // bảng 2 ngôn ngữ
        $this->table_category   = 'product_category';   // bảng danh mục sản phẩm
        $this->table_property   = 'product_property';   // bảng tính chất sản phẩm
        $this->column_order     = array("$this->table.id", "$this->table.id", "$this->table_trans.title", "$this->table.price", "$this->table.is_featured", "$this->table.is_status", "$this->table.created_time", "$this->table.updated_time",);  //thiết lập cột sắp xếp
        $this->column_search    = array("$this->table.id", "$this->table_trans.title");  //thiết lập cột search
        $this->order_default    = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định
    }
    // Lọc sản phẩm theo thuộc tính
    public function _where_custom($args)
    {
        extract($args);
        if (isset($is_sale)) $this->db->where("price_sale <> ", 0);

        /*Lọc các thuộc tính của sản phẩm*/
        if (isset($filter_price_min) && !empty($filter_price_max)) {
            $this->db->where("IF(`price_sale` != 0, `price_sale`, `price`) >=", $filter_price_min);
            $this->db->where("IF(`price_sale` != 0, `price_sale`, `price`) <=", $filter_price_max);
        }
        if (!empty($filter_property)) {
            $this->db->join($this->table_property, "$this->table.id = $this->table_property.product_id");
            $this->db->where_in("$this->table_property.property_id", $filter_property);
        }
    }
    // Lấy sản phẩm theo id
    public function getById($id, $select = '*', $lang_code = null)
    {
        $this->db->select("*");
        $this->db->select("IF(`model` IS NULL, 0, `model`) AS `model`");
        $this->db->select("IF(`price` IS NULL, 0, `price`) AS `price`");
        $this->db->select("IF(`price_sale` IS NULL, 0, `price_sale`) AS `price_sale`");

        $this->db->select("IF(`price_sale` IS NULL, `price`, `price_sale`) AS `price_sort`");
        $this->db->select("IF(`price_sale` IS NULL, 0, 1) AS `is_sale`");
        $this->db->select('IF(`created_time` <= DATE_ADD(NOW(), INTERVAL 10 DAY), 1, 0) AS `is_new`');
        $this->db->from($this->table);
        if (!empty($this->table_trans)) $this->db->join($this->table_trans, "$this->table.id = $this->table_trans.id");
        $this->db->where("$this->table.id", $id);
        $this->db->order_by("price", "ASC");
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
    // Lấy danh mục sản phẩm
    public function getCategoryByPostId($productId, $lang_code = null)
    {
        if (empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select();
        $this->db->from($this->table_category);
        $this->db->join("category_translations", "$this->table_category.category_id = category_translations.id");
        $this->db->join("category", "$this->table_category.category_id = category.id");
        $this->db->where('category_translations.language_code', $lang_code);
        $this->db->where($this->table_category . ".product_id", $productId);
        $data = $this->db->get()->result();
        //ddQuery($this->db);
        return $data;
    }
    // Cũng là lấy danh mục sản phẩm 
    public function getCategorySelect2($productId, $lang_code = null)
    {
        if (empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select("$this->table_category.category_id AS id, category_translations.title AS text");
        $this->db->from($this->table_category);
        $this->db->join("category_translations", "$this->table_category.category_id = category_translations.id");
        $this->db->where('category_translations.language_code', $lang_code);
        $this->db->where($this->table_category . ".product_id", $productId);
        $data = $this->db->get()->result();
        return $data;
    }
    // Lấy danh mục theo nhãn hiệu
    public function getCategoryByBrandProduct($brandId, $lang_code = null)
    {
        if (empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select("ap_category.*, ap_category_translations.*");
        $this->db->from($this->table_category);
        $this->db->join($this->table, "$this->table_category.product_id = $this->table.id", "left");
        $this->db->join("category", "$this->table_category.category_id = category.id");
        $this->db->join("category_translations", "$this->table_category.category_id = category_translations.id");
        $this->db->where('category_translations.language_code', $lang_code);
        $this->db->where($this->table . ".brand", $brandId);
        $this->db->group_by("category.id");
        $data = $this->db->get()->result();
        return $data;
    }
    public function getTagsSelect2($productId, $lang_code = null)
    {
        if (empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select("$this->table_tags.tag_id AS id, tags_translations.title AS text");
        $this->db->from($this->table_tags);
        $this->db->join("tags_translations", "$this->table_tags.tag_id = tags_translations.id");
        $this->db->where('tags_translations.language_code', $lang_code);
        $this->db->where($this->table_tags . ".product_id", $productId);
        $data = $this->db->get()->result();
        return $data;
    }

    public function getPropertySelect2($productId, $type, $lang_code = null)
    {
        if (empty($lang_code)) $lang_code = $this->session->admin_lang ? $this->session->admin_lang : $this->session->public_lang_code;
        $this->db->select("$this->table_property.property_id AS id, property_translations.title AS text");
        $this->db->from($this->table_property);
        $this->db->join("property_translations", "$this->table_property.property_id = property_translations.id");
        $this->db->where('property_translations.language_code', $lang_code);
        $this->db->where($this->table_property . ".product_id", $productId);
        $this->db->where($this->table_property . ".type", $type);
        $data = $this->db->get()->result();
        return $data;
    }
    public function listIdByCategory($category_id)
    {
        $this->db->from($this->table_category);
        $this->db->where('category_id', $category_id);
        $result = $this->db->get()->result();
        $listPostId = [];
        if (!empty($result)) foreach ($result as $item) {
            $listPostId[] = $item->product_id;
        }
        return $listPostId;
    }

    public function getOneCateIdById($id)
    {
        $data = $this->getCategoryByPostId($id);
        return !empty($data) ? $data[0] : null;
    }

    public function getCateIdById($id)
    {
        $this->db->select('category_id');
        $this->db->from($this->table_category);
        $this->db->where('product_id', $id);
        $data = $this->db->get()->result();
        $listId = [];
        if (!empty($data)) foreach ($data as $item) {
            $listId[] = $item->category_id;
        }
        return $listId;
    }

    public function countPostByCate($cateId)
    {
        $this->db->from($this->table_category);
        $this->db->where('category_id', $cateId);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function countProductByType($typeId, $cateId)
    {
        $this->db->from($this->table_category);
        $this->db->join($this->table, "$this->table_category.product_id = $this->table.id");
        $this->db->where("$this->table.type", $typeId);
        $this->db->where("$this->table_category.category_id", $cateId);
        $query = $this->db->get();
        return $query->num_rows();
    }
}
