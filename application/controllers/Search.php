<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Search extends Public_Controller {

    protected $_data_cate;
    protected $_data_product;
    protected $_data_post;
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model','product_model','post_model']);
        $this->_data_cate=new Category_model;
        $this->_data_product=new Product_model;
        $this->_data_post=new Post_model;
        $this->_lang_code=$this->session->public_lang_code;
    }
    public function index(){   
        $keyword=$this->input->post('txt');
        if(!empty($keyword)){
            //check category product
            $cateProduct=$this->_data_cate->searching($keyword,'product',$this->_lang_code);
            if(!empty($cateProduct)){
                $parentCate=$this->getIdParentCate($cateProduct['parent_id']);
                if(!empty($parentCate)) die(json_encode(getUrlCateProduct($parentCate)));
                else die(json_encode(getUrlCateProduct($cateProduct)));
            }else{  
                //check product
                $product=$this->_data_product->searching($keyword,$this->_lang_code);
                if(!empty($product)){
                    if(count($product)==1){
                        die(json_encode(getUrlProduct($product[0])));
                    }else{
                        //show all result searching
                        $content=$this->load->view($this->template_path.'product/view_searching.php',['data'=>$product],true);
                        die(json_encode(['type'=>'success','content'=>$content]));
                    }
                }else{
                    //check cate news
                    $cateNews=$this->_data_cate->searching($keyword,'post',$this->_lang_code);
                    if(!empty($cateNews)){
                        die(json_encode(getUrlCateNews($cateNews)));
                    }else{
                        //check news
                        $news=$this->_data_post->searching($keyword,$this->_lang_code);
                        if(!empty($news)){
                            if(count($news)==1){
                                die(json_encode(getUrlNews($news[0])));
                            }else{
                                                       //show all result searching
                                $content=$this->load->view($this->template_path.'news/view_searching.php',['data'=>$news],true);
                                die(json_encode(['type'=>'success','content'=>$content])); 
                            }
                        }else{
                            die(json_encode(['type'=>'warning','message'=>lang('not_seek_keyword_fit')]));
                        }
                    }
                }
            }
        }else{
            die(json_encode(['type'=>'warning','message'=>lang('pls_enter_keyword')]));
        }
    }
    private function getIdParentCate($parent_id){
        if(!empty($parent_id)){
            $parent_Cate=$this->_data_cate->getById($parent_id,'',$this->_lang_code);
            if(!empty($parent_Cate->parent_id)){
                $parent_Cate=$this->getIdParentCate($parent_Cate->parent_id);
            }
            return $parent_Cate;
        }
    }
}
