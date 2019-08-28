<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('dump')) {
    function dump($data){
        echo '<pre style="border: solid #ff2222 1px;">';
        var_dump($data);
        echo '</pre>';
    }
}

if ( ! function_exists('dd')) {
    function dd($data){
        echo '<pre style="border: solid #ff2222 1px;">';
        print_r($data);
        echo '</pre>';
        exit;
    }
}

if ( ! function_exists('dumpQuery')) {
    function dumpQuery($db){
        echo '<!--<pre style="border: solid #ff2222 1px;">';
        echo $db->last_query();
        echo '</pre>-->';
    }
}

if ( ! function_exists('ddQuery')) {
    function ddQuery($db){
        echo '<pre style="border: solid #ff2222 1px;">';
        echo $db->last_query();
        echo '</pre>';
        exit;
    }
}