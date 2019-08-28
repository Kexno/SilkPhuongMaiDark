<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// phan quyen
$config['cms_language_role']['account']       		 = 'Quản lý khách hàng';
$config['cms_language_role']['banner']        		 = 'Danh sách banner';
$config['cms_language_role']['contact']       		 = 'Quản lý liên hệ';
$config['cms_language_role']['groups']  			 = 'Danh sách nhóm quyền';
$config['cms_language_role']['newsletter']  	     = 'Danh sách newsletter';
$config['cms_language_role']['order']  	     	     = 'Quản lý đơn hàng';
$config['cms_language_role']['page']  	     	     = 'Quản lý page';
$config['cms_language_role']['post']  	     	     = 'Danh sách tin tức';
$config['cms_language_role']['faqs']  	     	 	 = 'Quản lý câu hỏi';
$config['cms_language_role']['setting']  	     	 = 'Cấu hình chung';
$config['cms_language_role']['system_menu']  	     = 'Quản lý menu';
$config['cms_language_role']['media']  	     	     = 'Quản lý media';
$config['cms_language_role']['users']  	             = 'Danh sách thành viên';
$config['cms_language_role']['logaction']  	         = 'Logs';
$config['cms_language_role']['product']  	         = 'Danh sách sản phẩm';
$config['cms_language_role']['suggest_product']  	 = 'Quản lý hôm nay mua gì / set sơ chế';
$config['cms_language_role']['agency']  	 		 = 'Thông tin cửa hàng';
$config['cms_language_role']['location']  	 		 = 'logs';
$config['cms_language_role']['property']  	 		 = 'Thuộc tính';


$config['cms_check_add'] = array('banner','product','groups','page','faqs','account','banner','post','users','property','agency','suggest_product');

$config['cms_check_edit'] = array('groups','users','account','post','contact','banner','page','faqs','order','property','agency','suggest_product','product');

$config['cms_check_not_delete'] = array('menus','media','setting','order','logaction','logs');