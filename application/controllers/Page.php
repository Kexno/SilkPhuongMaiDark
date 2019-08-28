<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends Public_Controller
{
    protected $_data;
    protected $_data_agency;
    protected $_data_banner;
    protected $_suggest_product;
    protected $_data_faqs;
    protected $_data_pr;
    protected $_data_post;
    protected $_data_category;
    public $_lang_code;

    public function __construct()
    {
        parent::__construct();
        //tải model
        $this->load->model(['page_model','banner_model','product_model', 'category_model','post_model']);
        $this->_data = new Page_model();
        $this->_data_post = new Post_model();
        $this->_data_banner = new Banner_model();
        $this->_data_category = new Category_model();
        $this->_data_pr = new Product_model();
        if ($this->input->get('lang'))
            $this->_lang_code = $this->input->get('lang');
    }
    public function index($slug = null){
        $id = $this->_data->slugToId($slug);
        $oneItem = $this->_data->getById($id, '', $this->_lang_code);
        if (empty($oneItem)) show_404();
        if ($this->input->get('lang')) redirect(getUrlPage($oneItem));
        $data['oneItem'] = $oneItem;
        $data['checknews']=true;
        if($oneItem->style=='about'){
            $data['newsfeed']=$this->_data_post->getData(['limit'=>100,'category_id'=>1,'is_status'=>1]);
        }else if($oneItem->style=='overview_product'){
            $data['banner']=$this->_data_banner->getData(['is_status'=>1,'property_id'=>1]);
            $data['about']=$this->_data->getById(3, '', $this->_lang_code);
            $data['news_feed']=$this->_data_post->getData(['limit'=>6,'is_status'=>1,'category_id'=>3]);
            $data['cate_product']=$this->_data_category->getData(['limit'=>3,'is_status'=>1,'parent_id'=>0,'type'=>'product']);
            $data['product_featured']=$this->_data_pr->getData(['is_status'=>1,'is_featured'=>1,'limit'=>4]);
        }
        $data['SEO'] = $this->blockSEO($oneItem, getUrlPage($oneItem));
        //view layout
        $data['main_content'] = $this->load->view($this->template_path . 'page/page' . $oneItem->style, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function _404(){
        $data['main_content'] = $this->load->view($this->template_path . 'page/_404', NULL, TRUE);
        $this->load->view($this->template_main, $data);
    }
}
