<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LanguageLoader
{
    function initialize() {
        $ci =& get_instance();
        $ci->load->helper('language');
        $ci->load->config('languages');
        $siteLang = $ci->session->userdata('public_lang_code');
        if ($siteLang) {
            $ci->lang->load('message',$siteLang);
        } else {
            $ci->lang->load('message','english');
        }
    }
}