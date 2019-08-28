<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Public_Controller
{
    protected $_all_category;
    protected $_data_category;
    protected $_data_product;
    protected $_data_banner;
    protected $_data_page;
    protected $_data_post;

    public function __construct()
    {
        parent::__construct();
        if ($this->input->get('lang'))
            $this->_lang_code = $this->input->get('lang');
        $this->load->model(array('category_model', 'product_model','banner_model','post_model','page_model'));
        $this->_data_category = new Category_model();
        $this->_data_page=  new Page_model();
        $this->_data_banner = new Banner_model();
        $this->_data_product = new Product_model();
        $this->_data_post = new Post_model();
    }

    public function index()
    {
        if ($this->input->get('lang')) redirect();
        //banner home
        $data['banner']=$this->_data_banner->getData(['is_status'=>1,'property_id'=>2,'limit'=>1]);
        $data['news_feed']=$this->_data_post->getData(['limit'=>6,'is_status'=>1,'category_id'=>3]);
        $data['review_home']=$this->_data_page->getById(5,'',$this->_lang_code);
        $data['main_content'] = $this->load->view($this->template_path . 'home/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }
}
