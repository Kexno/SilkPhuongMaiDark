<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// $config['enable_query_strings'] = TRUE;
// /*SET PARAM PAGE*/
// $config['page_query_string'] = FALSE;
// $config['query_string_segment'] = 'page';
// /*SET PARAM PAGE*/
// $config['reuse_query_string'] = TRUE;

// $config['full_tag_open'] = '<ul class="pagination">';
// $config['full_tag_close'] = '</ul>';
// $config['first_tag_open'] = '<li class="">';
// $config['first_tag_close'] = '</li>';
// $config['next_tag_open'] = '<li class="">';
// $config['next_tag_close'] = '</li>';
// $config['prev_tag_open'] = '<li class="">';
// $config['prev_tag_close'] = '</li>';
// $config['cur_tag_open'] = '<li class=""><a href="" title="Page current" class="active">';
// $config['cur_tag_close'] = '</a></li>';
// $config['num_tag_open'] = '<li class="">';
// $config['num_tag_close'] = '</li>';
// $config['last_tag_open'] = '<li class="">';
// $config['last_tag_close'] = '</li>';


// $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
// $config['next_link'] = '<i class="fa fa-angle-right"></i>';
// $config['display_pages'] = true;
$config['last_link'] = FALSE;
$config['first_link'] = FALSE;
$config['use_page_numbers'] = TRUE;
$config['reuse_query_string'] = TRUE;
$config['num_links'] = 3;
$config['full_tag_open'] = '<ul class="blognav col-12 d-flex justify-content-center">';
$config['full_tag_close'] = '</ul>';
$config['first_tag_open'] = '<span class="firstlink">';
$config['first_tag_close'] = '</span>';
$config['next_link'] = '<span class="arrow_right">&#8250;</span>';
$config['next_tag_open'] = '<li class="">';
$config['next_tag_close'] = '</li>';
$config['prev_link'] = '<span class="arrow_left">&#8249;</span>';
$config['prev_tag_open'] = '<li class="">';
$config['prev_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li class="active"><a class="">';
$config['cur_tag_close'] = '</a></li>';
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';