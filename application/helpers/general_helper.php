<?php
/**
 * User: linhth
 * Date: 03/07/2019
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('minifyCSS')) {
	/*
	* hàm nén css
	* assets_file: mảng file css
	* dir_assets:  thư mục css
	* minify: tham số nén
	* inline: hiển thị css inline html
	* version: phiên bản
	*/
    function minifyCSS($assets_file, $dir_assets, $minify = false, $inline = false, $version = false)
    {
        if ($minify) {
            $new_file = md5(implode('_',$assets_file)).'.apsmin.css';
            $new_content = '';
            if(!file_exists(FCPATH.$dir_assets.$new_file)){
				require_once(APPPATH . 'libraries/minify/cssminify.php');
				$css = new cssminify();
                foreach ($assets_file as $file) {
					$tmp_root_dir = substr($file,0,strripos($file,'/')+1);
					$tmp_content = file_get_contents(FCPATH.$dir_assets.$file);
					$tmp_content = str_replace('url("','url("'.base_url().$dir_assets.$tmp_root_dir,$tmp_content);
					$tmp_content = str_replace("url('","url('".base_url().$dir_assets.$tmp_root_dir,$tmp_content);
					$tmp_content = str_replace("url(../","url(".base_url().$dir_assets.$tmp_root_dir."../",$tmp_content);
					$tmp_content = str_replace("url(/","url(".base_url().$dir_assets.$tmp_root_dir."/",$tmp_content);
                    $new_content .= $tmp_content;
					unset($tmp_content);
                }
				$new_content = str_replace('//','/',$new_content);
				$new_content = str_replace(':/','://',$new_content);
				$new_content = $css->compress($new_content);
                file_put_contents(FCPATH.$dir_assets.$new_file, $new_content);
            }
			if($inline){
				echo '<style>'.file_get_contents(FCPATH.$dir_assets.$new_file).'</style>';
			}else{
				echo '<link href="'.base_url().$dir_assets.$new_file.($version ? '?v='.time() : "").'" rel="stylesheet" type="text/css">';
			}
			unset($new_content);
        } else {
            foreach ($assets_file as $file) {
                echo '<link href="'.base_url().$dir_assets.$file.($version ? '?v='.time() : "").'" rel="stylesheet" type="text/css">';
            }
        }
    }
}

if (!function_exists('minifyJS')) {
	/*
	* hàm nén js
	* assets_file: mảng file js
	* dir_assets:  thư mục js
	* minify: tham số nén
	* version: phiên bản
	*/
    function minifyJS($assets_file, $dir_assets, $minify = false, $version = false) {
        if ($minify) {
			$new_file = md5(implode('_',$assets_file)).'.apsmin.js';
            $new_content = '';
            if(!file_exists(FCPATH.$dir_assets.$new_file)){
				require_once(APPPATH.'libraries/minify/JSMin.php');
				foreach ($assets_file as $file) {
                    $new_content .= JSMin::minify(file_get_contents(FCPATH.$dir_assets.$file));
                }
                file_put_contents(FCPATH.$dir_assets.$new_file, $new_content);
            }
            echo '<script type="text/javascript" src="'.base_url().$dir_assets.$new_file.($version ? '?v='.time() : "").'"></script>';
			unset($new_content);
        }else{
            foreach ($assets_file as $file) {
                echo '<script type="text/javascript" src="'.base_url().$dir_assets.$file.($version ? '?v='.time() : "").'"></script>';
            }
        }
    }	
}

if (!function_exists('resJSON')) {
	/*
	* hàm trả về header chuẩn json
	* res: mảng dữ liệu trả về
	*/
    function resJSON($res) {
		header('Content-Type: application/json');
		die(json_encode($res));
    }
}

if (!function_exists('array_value_recursive')) {

    function array_value_recursive($key, array $arr){
        $val = array();
        array_walk_recursive($arr, function($v, $k) use($key, &$val){
            if($k == $key) array_push($val, $v);
        });
        return count($val) > 0 ? array_filter($val, function($value) { return !is_null($value) && $value !== ''; }) : array_pop($val);
    }
}


