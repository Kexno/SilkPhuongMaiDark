<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('add_query_arg')){
    function add_query_arg($args=array()) {
        $args=array_filter($args);
        $url =current_url().'?'. http_build_query(array_merge($_GET,$args));
        return $url;
    }
}

if(!function_exists('remove_query_arg')){
    function remove_query_arg($args) {
        $parsed = http_build_query(array_merge($_GET));

        parse_str($parsed, $params);
        unset($params[$args]);

        $string = http_build_query($params);
        if (!empty($string)){
            $url =current_url().'?'. $string;
        }else{
            $url =current_url();
        }

        return $url;
    }
}
