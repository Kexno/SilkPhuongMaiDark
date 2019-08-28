<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Lang extends APS_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function load($files)
    {
        $files = explode('-', $files);
        if(count($files) > 0){
            $lang_text = '';
            foreach ($files as $file){
                $this->lang->load(trim($file));
                foreach ($this->lang->language as $key => $lang){
                    $lang_text .= "language['".$key."'] = '".$lang."';";
                }
            }
            print $lang_text;exit;
        }
    }
}
