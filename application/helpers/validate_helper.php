<?php
/**
 * Created by PhpStorm.
 * User: doan_du
 * Date: 05/03/2019
 * Time: 14:16
 */

if (!function_exists('validateEmail')) {
    function validateEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
            return true;
        return false;
    }
}

if (!function_exists('validateUsername')) {
    function validateUsername($username, $minLength = 5, $maxLength = 20) {
        $lengthUsername = mb_strlen($username, 'UTF-8');
        if(preg_match("/^([a-zA-Z0-9_]+)$/",$username) && $lengthUsername >= $minLength && $lengthUsername <= $maxLength)
            return true;
        return false;
    }
}

if (!function_exists('validatePhone')) {
    function validatePhone($phone) {
        if(!preg_match("/^\d{10,11}/", $phone))
            return false;

        $first_phone = substr($phone, 0, 2);
        $length_phone = strlen($phone);

        if(in_array($first_phone, array('09', '03'))){

            if($length_phone != 10)
                return false;
            return true;

        } elseif (in_array($first_phone, array('01'))) {
            if ($length_phone != 11)
                return false;
            return true;
        }

        return true;

    }
}

if (!function_exists('validatePrice')) {
    function validatePrice($price) {
        if(is_int($price))
            return true;
        return false;
    }
}

if (!function_exists('validateName')) {
    function validateName($name, $minLength = 2, $maxLength = 20) {
        $lengthName = mb_strlen($name, 'UTF-8');
        if(preg_match("/^([^0-9@!#$%^&*()\-+={}\|:;.>,<?\/]+)$/", $name) && $lengthName >= $minLength && $lengthName <= $maxLength)
            return true;
        return false;
    }
}


if (!function_exists('validateNameCard')) {
    function validateNameCard($name) {
        if(preg_match("/^([^@!#$%^&*()\-+={}\|:;.>,<?\/ ]+)$/", $name))
            return true;
        return false;
    }
}


if (!function_exists('validateCode')) {
    function validateCode($code, $minLength = 3, $maxLength = 20) {
        $lengthCode = mb_strlen($code, 'UTF-8');
        if(preg_match("/^([a-zA-Z0-9]+)$/",$code) && $lengthCode >= $minLength && $lengthCode <= $maxLength)
            return true;
        return false;
    }
}

if (!function_exists('validateIPV4')) {
    function validateIPV4($ip_v4) {
        if(preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/",$ip_v4))
            return true;
        return false;
    }
}


if (!function_exists('validateMAC')) {
    function validateMAC($mac) {
        if(preg_match("/^([a-zA-Z0-9:-]+)$/",$mac))
            return true;
        return false;
    }
}

if (!function_exists('validateCMND')) {
    function validateCMND($cmnd, $minLength = 3, $maxLength = 20) {
        $lengthCmnd = mb_strlen($cmnd, 'UTF-8');
        if(preg_match("/^([0-9]+)$/",$cmnd) && $lengthCmnd >= $minLength && $lengthCmnd <= $maxLength)
            return true;
        return false;
    }
}

if (!function_exists('validateNoHouse')) {
    function validateNoHouse($no_house) {
        if(preg_match("/^([^@!#$%^&*()\-+={}\|:;.>,<?\/ ]+)$/", $no_house))
            return true;
        return false;
    }
}

if (!function_exists('validatePassword')) {
    function validatePassword($password, $minLength = 6, $maxLength = 20) {
        $lengthPw =  strlen($password);
        if(preg_match("/^([a-zA-Z0-9]+)$/",$password) && $lengthPw >= $minLength && $lengthPw <= $maxLength)
            return true;
        return false;
    }
}