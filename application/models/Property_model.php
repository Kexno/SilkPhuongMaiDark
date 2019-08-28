<?php
defined("BASEPATH") OR exit("No direct script access allowed");
class Property_model extends APS_Model{

    public function __construct()
    {
        parent::__construct();
        $this->table            = "property";
        $this->table_trans      = "property_translations";
        $this->column_order     = array("$this->table.id","$this->table.id","$this->table.order","$this->table_trans.title","$this->table.is_status","$this->table.created_time","$this->table.updated_time"); //thiết lập cột sắp xếp
        $this->column_search    = array("$this->table.id","$this->table_trans.title", "$this->table.color_code"); //thiết lập cột search
        $this->order_default    = array("$this->table.order" => "ASC"); //cột sắp xếp mặc định
    }

    public function _where_custom($args){
        extract($args);
        if(!empty($property_type)) $this->db->where("$this->table.type", $property_type);
    }


    public function getByIdCached($allProperty, $id){
        if(!empty($allProperty)) foreach ($allProperty as $key => $item){
            if($item->id == $id) return $item;
        }
        return false;
    }

    public function getDataByPropertyType($allProperty, $type){
        $dataType = [];
        if(!empty($allProperty)) foreach ($allProperty as $key => $item){
            if($item->type === $type) $dataType[] = $item;
        }
        return $dataType;
    }


    public function getGenre($allProperty, $type, $categoryId){
        $dataType = [];
        if(!empty($allProperty)) foreach ($allProperty as $key => $item){
            if($item->type === $type && $item->category_id == $categoryId) $dataType[] = $item;
        }
        return $dataType;
    }

    public function getRandomId($type = null){
        if(empty($type)) $type = $this->session->property_type;
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
    public function getPropertyId($property_id,$lang_code='')
    {
        $lang = $lang_code ? $lang_code : $this->session->public_lang_code;
        $this->db->select('a.id,b.title,b.description, a.is_status');
        $this->db->from($this->table.' a');
        $this->db->join($this->table_trans.' b','a.id = b.id');
        $this->db->where('b.language_code',$lang);
        $this->db->where('a.id',$property_id);
        $this->db->where('a.is_status',1);
        $data = $this->db->get()->row();
        return $data;
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

    public function getPropertyByType($lang_code = null,$type,$id=0,$valid=false){
        if($valid){
            $pr_property=$this->db->select('*')
            ->where(['type'=>$type,'product_id'=>$id])
            ->get('product_property')->row();
            if(!empty($pr_property)) $id=$pr_property->property_id;
            else return '';
        }
        $this->db->from($this->table);
        if(!empty($this->table_trans)) $this->db->join($this->table_trans,"$this->table.id = $this->table_trans.id");
        if(!empty($lang_code)) $this->db->where([
            'type' =>$type,
            'language_code' => $lang_code,
        ]);
        if(!empty($id)) $this->db->where("$this->table.id",$id);
        $this->db->order_by("$this->table.order", 'ASC');
        $query = $this->db->get();
        return $query->result();
    }


    /*Lấy id thứ tự sắp xếp cuối cùng*/
    public function getLastOrder(){
        $this->db->select('order');
        $this->db->from($this->table);
        $this->db->where([
            'type' => $this->session->property_type,
        ]);
        $this->db->order_by('order','DESC');
        $this->db->limit(1);
        $data = $this->db->get()->row();
        if(!empty($data)) return $data->order;
        return 0;
    }

    public function getPropertyByName($name){
        $this->db->select('id');
        $this->db->from($this->table_trans);
        $this->db->like('title', $name);
        $this->db->group_by('id');
        $res = $this->db->get()->result();
        $data = array();
        foreach ($res as $p){
            $data[] = $p->id;
        }
        return $data;
    }


}