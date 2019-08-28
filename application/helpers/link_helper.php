<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getUrlCateNews')) {
    function getUrlCateNews($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-c$id";
        if(isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getUrlCatVoucher')) {
    function getUrlCatVoucher($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-cv$id";
        if(isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getYoutubeKey')) {
    function getYoutubeKey($url)
    {
        if(!empty($url)){
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
            $youtube_id = !empty($match[1]) ? $match[1] : '';
            return trim($youtube_id);
        }
        return false;
    }
}


if (!function_exists('getUrlNews')) {
    function getUrlNews($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-d$id";
        if(isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getUrlNextNews')) {
    function getUrlNextNews($idCurrent){
        $_this =& get_instance();
        $_this->load->model('post_model');
        $postModel = new Post_model();
        $oneItem = $postModel->getNextById($idCurrent,"$postModel->table.id, slug",$_this->session->public_lang_code);
        return !empty($oneItem) ? getUrlNews($oneItem) : 'javascript:;';
    }
}

if (!function_exists('getUrlPrevNews')) {
    function getUrlPrevNews($idCurrent){
        $_this =& get_instance();
        $_this->load->model('post_model');
        $postModel = new Post_model();
        $oneItem = $postModel->getPrevById($idCurrent,"$postModel->table.id , slug",$_this->session->public_lang_code);
        return !empty($oneItem) ? getUrlNews($oneItem) : 'javascript:;';
    }
}

if (!function_exists('getUrlProject')) {
    function getUrlProject($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-b$id";
        if(isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}
if (!function_exists('getUrlCateProject')) {
    function getUrlCateProject($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-cb$id";
        if(isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}
if (!function_exists('getUrlVoucher')) {
    function getUrlVoucher($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-dv$id";
        if(isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getUrlPage')) {

  function getUrlPage($optional = []){
    $_this =& get_instance();
        if(is_object($optional)) $optional = (array) $optional;
        $linkReturn = BASE_URL;
        if(!empty($optional['slug'])) {
            $slug = $optional['slug'];
            $linkReturn .= "$slug".'.html';
        }else{
          $_this->load->model('page_model');
          $pageModel=new Page_model();
          $data=$pageModel->getById($optional['id'],'*',$_this->session->public_lang_code);
          $linkReturn .= "$data->slug".'.html';
        }
        return $linkReturn;
    }
}

if (!function_exists('getUrlTag')) {
    function getUrlTag($keyword){
        $_this =& get_instance();
        $_this->load->library('session');
        $_this->load->helper('url');
        $slug = $_this->toSlug($keyword);
        $linkReturn = BASE_URL."search/$slug";
        return $linkReturn;
    }
}

if (!function_exists('getUrlSearch')) {
    function getUrlSearch($keyword){
        $_this =& get_instance();
        $_this->load->library('session');
        $_this->load->helper('url');
        $slug = $_this->toSlug($keyword);
        $linkReturn = BASE_URL."search/$slug";
        return $linkReturn;
    }
}
if (!function_exists('getUrlCateProduct')) {
    function getUrlCateProduct($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-cp$id";
        return $linkReturn;
    }
}

if (!function_exists('getUrlProduct')) {
    function getUrlProduct($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $_this =& get_instance();
        $linkReturn = BASE_URL;
        if(!empty($optional['slug'])) {
            $id = $optional['id'];
            $slug = $optional['slug'];
            $linkReturn .= "$slug-p$id";
        }else{
            $_this->load->model('product_model');
            $productModel=new Product_model();
            $id = $optional['id'];
            $data=$productModel->getById($id,'*',$_this->session->public_lang_code);
            $linkReturn .= "$data->slug-p$id";
        }
        return $linkReturn;
    }
}
