<?php
defined("BASEPATH") OR exit("No direct script access allowed");
class Category_model extends APS_Model{

    public $_list_category_child;
    public $_list_category_parent;
    public $_list_category_child_id;
    public function __construct()
    {
        parent::__construct();
        $this->table            = "category";
        $this->table_trans      = "category_translations";//bảng bài viết
        $this->column_order     = array("$this->table.id","$this->table.id","$this->table.order","$this->table_trans.title","$this->table.is_status","$this->table.created_time","$this->table.updated_time"); //thiết lập cột sắp xếp
        $this->column_search    = array("$this->table.id","$this->table_trans.title"); //thiết lập cột search
        $this->order_default    = array("$this->table.order" => "ASC"); //cột sắp xếp mặc định
    }

    public function _where_custom($args){
        extract($args);
        if(!empty($this->table_category) && !empty($category_id)){
            $nameModel = str_replace('_model','',$this->table);
            $this->db->join($this->table_category, "$this->table.id = $this->table_category.{$nameModel}_id");
            $this->db->where_in("$this->table_category.category_id", $category_id);
        }
        if(!empty($category_type)) $this->db->where("$this->table.type", $category_type);
        if (isset($parent_id)) $this->db->where("$this->table.parent_id",$parent_id);
        if (isset($type)) $this->db->where("$this->table.type",$type);
    }


    /*Đệ quy lấy record parent id*/
    public function _recursive_one_parent($all, $id){
        if(!empty($all)) foreach ($all as $item){
            if($item->id == $id){
                if($item->parent_id == 0) return $item;
                else return $this->_recursive_one_parent($all, $item->parent_id);
            }
        }
    }
    /*Đệ quy lấy record parent id*/
    /*Đệ quy lấy array list category con*/
    public function _recursive_child($all, $parentId = 0){
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->parent_id == $parentId){
                $this->_list_category_child[] = $item;
                unset($all[$key]);
                $this->_recursive_child($all, $item->id);
            }
        }
    }
    /*Đệ quy lấy array list category con*/
    /*Đệ quy lấy array list category con 1 level*/
    public function getListChildLv1($all, $parentId = 0){
        $data = array();
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->parent_id == $parentId){
                $data[] = $item;
            }
        }
        return $data;
    }
    /*Đệ quy lấy array list category  con 1 level*/
    /*Đệ quy lấy list các ID*/
    public function _recursive_child_id($all, $parentId = 0){
        $this->_list_category_child_id[] = (int)$parentId;
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->parent_id == $parentId){
                $this->_list_category_child_id[] = (int) $item->id;
                unset($all[$key]);
                $this->_recursive_child_id($all, (int) $item->id);
            }
            $this->_list_category_child_id = array_unique($this->_list_category_child_id);
        }
    }
    /*Đệ quy lấy list các ID*/

    /*Đệ quy lấy maps các ID cha*/
    public function _recursive_parent($all, $cateId = 0){
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->id === $cateId){
                $this->_list_category_parent[] = $item;
                unset($all[$key]);
                $this->_recursive_parent($all, $item->parent_id);
            }
        }
    }
    /*Đệ quy lấy maps các ID cha*/

    public function getByIdCached($allCategories, $id){
        if(!empty($allCategories)) foreach ($allCategories as $key => $item){
            if($item->id == $id) return $item;
        }
        return false;
    }

    public function getDataByCategoryType($allCategories, $type){
        $dataType = [];
        if(!empty($allCategories)) foreach ($allCategories as $key => $item){
            if($item->type === $type) $dataType[] = $item;
        }
        return $dataType;
    }

    public function getRandomId($type = null){
        if(empty($type)) $type = $this->session->category_type;
        $this->db->select('id');
        $this->db->from($this->table);
        $this->db->where('type', $type);
        $this->db->order_by('id', 'RANDOM');
        $this->db->limit(1);
        $query = $this->db->get();
        $data = $query->result();
        $result = [];
        if(!empty($data)) foreach ($data as $item) $result[] = $item->id;
        return $result;
    }

    // get data group by
    public function getDataGroupBy()
    {
        $this->db->select('type');
        $this->db->from($this->table);
        $this->db->group_by('type');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function slugToId($slug){
        $this->db->select('tb1.id');
        $this->db->from($this->table.' AS tb1');
        $this->db->join($this->table_trans.' AS tb2','tb1.id = tb2.id');
        $this->db->where('tb2.slug',$slug);
        $data = $this->db->get()->row();
        return !empty($data)?$data->id:null;
    }

    public function getAllCategoryByType($lang_code = null,$type,$parent_id = 0){
        $this->db->from($this->table);
        if(!empty($this->table_trans)) $this->db->join($this->table_trans,"$this->table.id = $this->table_trans.id");
        if(!empty($lang_code)) $this->db->where([
            'type' =>$type,
            'language_code' => $lang_code,
            'parent_id' => $parent_id
        ]);
            $query = $this->db->get();
            return $query->result();
        }

        /*Lấy category cha*/
        public function getOneParent($id){
            $params = [
              'lang_code' => $this->session->public_lang_code,
              'parent_id' => $id,
              'limit' => 1
          ];
          $data = $this->getData($params);
          return !empty($data)?$data[0]:null;
      }

    // Course
      public function get_category_child_by_course_id($id = 0) {
        $this->db->select('A.*, B.parent_id');
        $this->db->from('category_translations A');
        $this->db->join('category B', 'B.id = A.id', 'inner');
        $this->db->join('course_category C', 'C.category_id = B.id', 'inner');
        $this->db->join('course D', 'D.id = C.course_id', 'inner');
        $this->db->where([
            'A.language_code' => $this->session->public_lang_code,
            'B.is_status' => 1,
        ]);
        if($id != 0) {
            $this->db->where_in('C.course_id', $id);
        }
        $this->db->limit(1);
        $data = $this->db->get()->row();
        if(!empty($data)) return $data;
        return 0;
    }

    // Course
    public function get_category_by_id($id, $type = "") {
        $this->db->select('A.*, B.parent_id');
        $this->db->from('category_translations A');
        $this->db->join('category B', 'B.id = A.id', 'inner');
        $this->db->where([
            'A.language_code' => $this->session->public_lang_code,
            'B.is_status' => 1,
            'B.id' => $id,
            'B.type' => $type
        ]);
        $this->db->limit(1);
        $data = $this->db->get()->row();
        if(!empty($data)) return $data;
        return 0;
    }

    /* lấy cate con theo cha */
    public function get_list_category_child($parent_id,$limit=10){
        $this->db->from($this->table);
        if(!empty($this->table_trans)) $this->db->join($this->table_trans,"$this->table.id = $this->table_trans.id");
        $this->db->where([
            'language_code' => $this->session->public_lang_code,
            'parent_id' => $parent_id,
            'is_status' => 1,
        ]);
        $this->db->limit($limit);
        $this->db->order_by("$this->table.order",'ASC');
        return $this->db->get()->result_array();
    }

    public function get_list_cate_video($courseID) {
        $this->db->from("category_translations A");
        $this->db->join("category B","B.id = A.id", "inner");
        $this->db->join("course_category C", "C.category_id = B.id" , "inner");
        $this->db->where([
            'A.language_code' => $this->session->public_lang_code,
            'B.is_status' => 1,
            'B.type' => 'video',
            'C.course_id' => $courseID
        ]);
        $this->db->order_by("B.order",'ASC');
        return $this->db->get()->result_array();
    }

    /* Lấy danh sach category header*/
    public function get_list_category(){
        $param = [
            'language_code' => $this->session->public_lang_code,
            'parent_id' => 0,
            'type' => 'course',
            'is_status' => 1
        ];
        $result = [];
        $this->db->from($this->table);
        if(!empty($this->table_trans)) $this->db->join($this->table_trans,"$this->table.id = $this->table_trans.id");
        $this->db->where($param);
        $this->db->order_by("$this->table.order",'DESC');
        $result = $this->db->get()->result_array();
        $all_category = [];
        if(!empty($result)) foreach ($result as $key => $value) {
            $category_child = $this->get_list_category_child($value['id'], $this->session->public_lang_code);
            array_push($value, $category_child);
            array_push($all_category,$value);
        }
        return $all_category;
    }

    /*Lấy id thứ tự sắp xếp cuối cùng*/
    public function getLastOrder($idParent = 0){
        $this->db->select('order');
        $this->db->from($this->table);
        $this->db->where([
            'type' => $this->session->category_type,
            'parent_id' => $idParent,
        ]);
        $this->db->order_by('order','DESC');
        $this->db->limit(1);
        $data = $this->db->get()->row();
        if(!empty($data)) return $data->order;
        return 0;
    }
    public function getCategoryChild($id, $lang_code,$type=''){
        $this->db->from($this->table);
        if (!empty($this->table_trans)) $this->db->join($this->table_trans, "$this->table.id = $this->table_trans.id");
        $this->db->where([
            'language_code' => $lang_code,
            'parent_id' => $id,
            'type' => $type,
            'is_status' => 1
        ]);
        $query = $this->db->get();
        return $query->result();
    }
    public function getUrl($id,$lang='vi'){
        $this->db->select('slug');
        $this->db->from('ap_post_translations');
        $this->db->where('id',$id);
        $this->db->where('language_code',$lang);
        $data=$this->db->get()->result();
        return $data;
    }
    public function searching($keyword,$type,$_lang_code){
        return $this->db->select('*')
        ->from($this->table)
        ->join($this->table_trans,"$this->table.id=$this->table_trans.id")
        ->where(['is_status'=>1,'type'=>$type,'language_code'=>$_lang_code])
        ->like("$this->table_trans.slug",$keyword)
        ->get()
        ->row_array();
    }
}