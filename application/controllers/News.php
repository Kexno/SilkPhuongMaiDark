<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Public_Controller
{
    protected $cid = 0;
    protected $_data;
    protected $_data_category;
    protected $_data_banner;
    protected $_all_category;

    public function __construct()
    {
        parent::__construct();
        //tải model
        $this->load->model(['category_model', 'post_model', 'banner_model']);
        $this->_data_banner = new Banner_model();
        $this->_data = new Post_model();
        $this->_data_category = new Category_model();
        //$this->session->category_type = 'post';
        //Check xem co chuyen lang hay khong thi set session lang moi
        if ($this->input->get('lang'))
            $this->_lang_code = $this->input->get('lang');

        if(!$this->cache->get('_all_category_'.$this->session->public_lang_code)){
            $this->cache->save('_all_category_'.$this->session->public_lang_code,$this->_data_category->getAll($this->session->public_lang_code,1),60*60*30);
        }
        $this->_all_category = $this->cache->get('_all_category_'.$this->session->public_lang_code);
    }

    public function category($id, $page = 1)
    {
        $oneItem = $this->_data_category->getById($id,'', $this->_lang_code);
        if (empty($oneItem)) show_404();

        if ($this->input->get('lang')) {
            redirect(getUrlCateNews(['slug' => $oneItem->slug, 'id' => $oneItem->id]));
        }
        $data['oneItem'] = $oneItem;
        $data['checknews']=true;
        /*Lay list id con của category*/
        $limit = 16;
        $params = array(
            'is_status' => 1, //0: Huỷ, 1: Hiển thị, 2: Nháp
            'lang_code' => $this->_lang_code,
            'category_id' => $id,
            'limit' => $limit,
            'page' => $page
        );
        $data['data'] = $this->_data->getData($params);
        $data['total'] = $this->_data->getTotal($params);
        /*Pagination*/
        $this->load->library('pagination');
        $paging['base_url'] = getUrlCateNews(['slug' => $oneItem->slug, 'id' => $oneItem->id, 'page' => 1]);
        $paging['first_url'] = getUrlCateNews(['slug' => $oneItem->slug, 'id' => $oneItem->id]);
        $paging['total_rows'] = $data['total'];
        $paging['per_page'] = $limit;
        $this->pagination->initialize($paging);
        $data['pagination'] = $this->pagination->create_links();
        /*Pagination*/

        //add breadcrumbs
        // $this->breadcrumbs->push(lang('home'), base_url());
        // $this->breadcrumbs->push($oneItem->title, getUrlCateNews($oneItem));
        // $data['breadcrumb'] = $this->breadcrumbs->show();
        //SEO Meta
        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : $oneItem->title,
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : $oneItem->description,
            'meta_keyword' => !empty($oneItem->meta_title) ? $oneItem->meta_keyword : '',
            'url' => getUrlCateNews($oneItem),
            'image' => getImageThumb($oneItem->thumbnail, 400, 200)
        ];
        if(!empty($oneItem->style)) $layoutView = '-'.$oneItem->style;
        else $layoutView = '';
        $data['main_content'] = $this->load->view($this->template_path . 'news/category'.$layoutView, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function detail($id)
    {
        $oneItem = $this->_data->getById($id,'', $this->_lang_code);
        if (empty($oneItem)) show_404();
        //Check xem co chuyen lang hay khong thi redirect ve lang moi
        if ($this->input->get('lang')) {
            redirect(getUrlNews(['slug' => $oneItem->slug, 'id' => $oneItem->id]));
        }
        $data['oneItem'] = $oneItem;
        $data['oneCategory'] = $oneCategory = $this->_data->getOneCateIdById($id);

        $data['oneParent'] = $oneCategoryParent = $this->_data_category->_recursive_one_parent($this->_all_category,$data['oneCategory']->id);
        if(!empty($data['oneParent'])){
            $data['list_category_child'] = $this->_data_category->getCategoryChild($data['oneParent']->id,$this->session->public_lang_code);
        }
        /*Get news related*/
        $this->_data_category->_recursive_child_id($this->_all_category,$oneCategoryParent->id);
        $listCateId = $this->_data_category->_list_category_child_id;
        $params = array(
            'is_status' => 1, //0: Huỷ, 1: Hiển thị, 2: Nháp
            'lang_code' => $this->_lang_code,
            'category_id' => $listCateId,
            'limit' => 4,
            'order'=>['created_time'=>'DESC'],
            'not_in' => $id,
        );
        $data['list_news'] = $this->_data->getData($params);
        $params['category_id']=$oneCategory->id;
        $data['list_related'] = $this->_data->getData($params);
       
        //SEO Meta
        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : $oneItem->title,
            'meta_description' => !empty($oneItem->meta_title) ? $oneItem->meta_description : $oneItem->description,
            'meta_keyword' => !empty($oneItem->meta_title) ? $oneItem->meta_keyword : '',
            'url' => getUrlNews(['slug' => $oneItem->slug, 'id' => $oneItem->id]),
            'image' => getImageThumb($oneItem->thumbnail, 400, 200)
        ];
        if(!empty($oneCategoryParent->style)) $layoutView = '-'.$oneCategoryParent->style;
        else $layoutView = '';
        $data['main_content'] = $this->load->view($this->template_path . 'news/detail'.$layoutView, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }
    private function cateNew_Event(){
        $data = $this->_data_category->getListChildLv1($this->_all_category,119);
        return $data;
    }

    private function compare($a, $b) {
        is_array($a) ? $value_a = $a['order'] : $value_a = $a->order;
        is_array($b) ? $value_b = $b['order'] : $value_b = $b->order;
        if ($value_a == $value_b) {
            return 1;
        }
        return ($value_a < $value_b) ? -1 : 1;
    }
    
}
