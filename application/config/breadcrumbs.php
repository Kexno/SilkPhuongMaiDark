<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| BREADCRUMB CONFIG
| -------------------------------------------------------------------
| This file will contain some breadcrumbs' settings.
|
| $config['crumb_divider']		The string used to divide the crumbs
| $config['tag_open'] 			The opening tag for breadcrumb's holder.
| $config['tag_close'] 			The closing tag for breadcrumb's holder.
| $config['crumb_open'] 		The opening tag for breadcrumb's holder.
| $config['crumb_close'] 		The closing tag for breadcrumb's holder.
|
| Defaults provided for twitter bootstrap 2.0
*/

$config['crumb_divider'] = '';
$config['tag_open'] = '<ul class="breadcrumb">';
$config['tag_close'] = '</ul>';
$config['crumb_open'] = '<li typeof="v:breadcrumb">';
$config['crumb_last_open'] = '<li >';
$config['crumb_close'] = '</li>';

$config['frontend_crumb_divider'] = '';
$config['frontend_tag_open'] = '<nav class="breadcrumb">';
$config['frontend_tag_close'] = '</nav>';
$config['frontend_crumb_open'] = '<li class="breadcrumb-item " typeof="v:breadcrumbs">';
$config['frontend_crumb_last_open'] = '<li class="breadcrumb-item active">';
$config['frontend_crumb_close'] = '</li>';

/* End of file breadcrumbs.php */
/* Location: ./application/config/breadcrumbs.php */