<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('action')) {
    function action($method){
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), []);
        }
    }
}