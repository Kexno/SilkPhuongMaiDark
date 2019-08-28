<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends Public_Controller
{
    protected $cid = 0;
    protected $_data;
    protected $_data_category;
    protected $_data_banner;
    protected $_data_or;
    protected $_all_category;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'product_model','banner_model','order_model']);
        $this->_data = new Product_model();
        $this->_data_banner = new Banner_model();
        $this->_data_category = new Category_model();
        $this->_data_or = new Order_model();
        if ($this->input->get('lang'))
            $this->_lang_code = $this->input->get('lang');
    }


    public function category($id)
    {
        $this->session->cate=$id;
        $oneItem = $this->_data_category->getById($id, '', $this->_lang_code);
        $data['oneItem']=$oneItem;
        if (empty($oneItem)) show_404();
        if ($this->input->get('lang')) {
            redirect(getUrlCateProduct(['slug' => $oneItem->slug, 'id' => $oneItem->id]));
        }
        $data['data']=$this->_data->getData(['is_status'=>1,'limit'=>100,'category_id'=>$id]);
        $data['pricemax']=$this->_data->getPrice($id);
        $data['pricemin']=$this->_data->getPrice($id,'min');
        //SEO
        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : $oneItem->title,
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : $oneItem->description,
            'meta_keyword' => !empty($oneItem->meta_title) ? $oneItem->meta_keyword : '',
            'url' => getUrlCateProduct($oneItem),
            'image' => getImageThumb($oneItem->thumbnail, 400, 200)
        ];
        $data['main_content'] = $this->load->view($this->template_path . 'product/category', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function getpr($min,$max){
        $data=$this->_data->getpr($this->session->cate,$min,$max);
        $result=$this->load->view($this->template_path . 'product/viewpr', ['data'=>$data], TRUE);
        die(json_encode($result));
    }

    public function getProducts($page=1){
        $data=$this->input->post();
        $limit=12;
        $params=array(
            'limit'=>$limit,
            'page'=>$page,
            'is_status'=>1,
            'lang_code' => $this->_lang_code
        );
        //get data cate
        $this->_list_child_cate_id[]=$data['cate'];
        //get all category product
        $cate_all=$this->_data_category->getData(['is_status'=>1,'type'=>'product','limit'=>100]);
        //recursive get cate children
        $this->get_all_cate_children($cate_all,$data['cate']);
        $params['category_id']=$this->_list_child_cate_id;
        if(!empty($data['type'])){
            //get all category product
            $cate_all=$this->_data_category->getData(['is_status'=>1,'type'=>'product','limit'=>100]);
            //recursive get cate children
            $this->get_all_cate_children($cate_all,$data['cate']);
            $params['category_id']=$this->_list_child_cate_id;
        }
        $order_by=['updated_time'=>'DESC'];
        if(!empty($data['order_by'])){
            switch ($data['order_by']) {
                case '0':
                break;
                case '1':
                $order_by=['best_seller'=>'DESC'];
                break;
                case '2':
                $order_by=['sale_up'=>'DESC'];
                break;
                case '3':
                $order_by=['is_featured'=>'DESC'];
                break;
            }
        }
        $params['order']=$order_by;
        $data_pr=$this->_data->getData($params);
        $html='';
        foreach ($data_pr as $key => $value) {
            $val=$this->load->view($this->template_path . 'product/view_detail_product',['value'=>$value], TRUE);
            $html.=$val;
        }
        $total=$this->_data->getTotal($params);
        /*Pagination*/
        $this->load->library('pagination');
        $paging['base_url'] =base_url('product/getProducts');
        $paging['first_url'] = base_url('product/getProducts');
        $paging['total_rows'] = $total;
        $paging['per_page'] = $limit;
        $paging['attributes'] = array('class'=>"page-link",'id'=>$data['type']);
        $this->pagination->initialize($paging);
        $pagination = $this->pagination->create_links();
        /*Pagination*/
        die(json_encode(['data'=>$html,'pagination'=>$pagination]));
    }

    private function get_all_cate_children($all_cate,$parent_id){
        foreach ($all_cate as $key => $value) {
            if($value->parent_id==$parent_id){
                $this->_list_child_cate_id[]=$value->id;
                $this->get_all_cate_children($all_cate,$value->id);
            }
        }
    }

    public function detail($id)
    {
        $oneItem = $this->_data->getById($id, '', $this->_lang_code);
        if (empty($oneItem)) redirect('404');
        //Check xem co chuyen lang hay khong thi redirect ve lang moi
        if ($this->input->get('lang')) {
            redirect(getUrlProduct(['slug' => $oneItem->slug, 'id' => $oneItem->id]));
        }
        $oneCategory = $this->_data->getOneCateIdById($id);
        $oneCategoryParent = $this->_data_category->_recursive_one_parent($this->_all_category, $oneCategory->id);
        $data['oneItem'] = $oneItem;
        $data['checkpr']=true;
        /*Get product related*/
        $limit=10;
        $params = [
            'is_status' => 1, //0: Huỷ, 1: Hiển thị, 2: Nháp
            'lang_code' => $this->_lang_code,
            'limit' => $limit,
            'category_id' => $oneCategory->id,
            'not_in' => $id,
            'order' => array('updated_time' => 'DESC')
        ];
        $data['list_related'] = $this->_data->getData($params);
        $this->breadcrumbs->push($this->lang->line('home'), base_url());
        $this->_data_category->_recursive_parent($this->_all_category, $oneCategory->id);
        if(!empty($this->_data_category->_list_category_parent)) foreach (array_reverse($this->_data_category->_list_category_parent) as $item){
            $this->breadcrumbs->push($item->title, getUrlCateProduct($item));
        }
        $this->breadcrumbs->push($oneItem->title, getUrlProduct($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();
        //SEO
        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : $oneItem->title,
            'meta_description' => !empty($oneItem->meta_title) ? $oneItem->meta_description : $oneItem->description,
            'meta_keyword' => !empty($oneItem->meta_title) ? $oneItem->meta_keyword : '',
            'url' => getUrlProduct(['slug' => $oneItem->slug, 'id' => $oneItem->id]),
            'image' => getImageThumb($oneItem->thumbnail, 400, 200)
        ];
        if (!empty($oneCategoryParent->style)) $layoutView = '-' . $oneCategoryParent->style;
        else $layoutView = '';
        $data['main_content'] = $this->load->view($this->template_path . 'product/detail' . $layoutView, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function get_data_popup($id)
    {
        $this->checkRequestPostAjax();
        $data['product'] = $this->_data->getById($id, '', $this->_lang_code);
        $data['content'] = $this->load->view($this->template_path . 'product/view_popup_detail', $data, TRUE);
        $data = $data['content'];
        $this->returnJson($data);
    }
    public function order(){
        $data=$this->_convertData();
        if($this->_data_or->save($data)){
            $message['type'] = 'success';
            $message['message'] = $this->lang->line('mess_add_success');
        }else{
            $message['type'] = 'error';
            $message['message'] = $this->lang->line('mess_add_unsuccess');
        }
        die(json_encode($message));
    }

    /*
     * Kiêm tra thông tin post lên
     * */
    private function _validate()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->form_validation->set_rules('full_name', 'Họ và tên','required|trim|min_length[5]|max_length[300]|xss_clean');
            $this->form_validation->set_rules('phone', 'Số điện thoại','required');
            $this->form_validation->set_rules('note','Ghi chú', 'required');
            $this->form_validation->set_rules('address','Địa chỉ', 'required');

            if ($this->form_validation->run() === false) {
                $message['type'] = "warning";
                $message['message'] = $this->lang->line('mess_validation');
                $valid = [];
                $valid["full_name"] = form_error("full_name");
                $valid["phone"] = form_error("phone");
                $valid["note"] = form_error("note");
                $valid["address"] = form_error("address");
                $message['validation'] = $valid;
                die(json_encode($message));
            }
        }
    }

    private function _convertData(){
        $this->_validate();
        $data = $this->input->post();
        return $data;
    }

}
