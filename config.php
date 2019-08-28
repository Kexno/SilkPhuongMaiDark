<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 05/12/2017
 * Time: 4:34 CH
 */
$root = str_replace('\\','/',dirname(__FILE__));
$domain = $_SERVER['HTTP_HOST'];
$script_name = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
$domain .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
$base = "http://" . $domain;
if (!empty($_SERVER['HTTPS'])) $base = "https://" . $domain;
define('BASE_URL', $base);
define('BASE_ADMIN_URL', $base."admin/");
define('BASE_SCRIPT_NAME', $script_name);
define('MEDIA_NAME',"public/media/"); //Tên đường dẫn lưu media
define('MEDIA_PATH',$root.'/'.MEDIA_NAME); //Đường dẫn lưu media
define('MEDIA_URL',BASE_URL . MEDIA_NAME);

define('CMS_VERSION','3.1');

define('DB_DEFAULT_HOST','localhost'); //DB HOST
define('DB_DEFAULT_USER','theanh'); //DB USER
define('DB_DEFAULT_PASSWORD','32648489'); //DB PASSWORD
define('DB_DEFAULT_NAME','silk'); //DB NAME

define('MAINTAIN_MODE',FALSE); //Bảo trì
define('DEBUG_MODE',TRUE);
define('CACHE_MODE',TRUE);
define('CACHE_TIMEOUT_LOGIN',1800);


define('FB_API','2168950039848509');
define('FB_SECRET','3b75ef0045196e4fdc40973182b5cdd7');
define('FB_VER','v2.9');

define('GG_API','1066768880165-q44kkfhuvtcm648b48kqq1dvtc5hv758.apps.googleusercontent.com');
define('GG_SECRET','gxDTdsa4gdDG3iV2cIZWT1qg');
define('GG_KEY','AIzaSyCbMS05SIcgYTKDQ13WiWz859L1wkk9ogo');//AIzaSyAhR8OG9cUL1jDfAAc6i35nt5Ki1ZJnykA
define('GG_CAPTCHA_SITE_KEY','6Ldo1zUUAAAAAODB1tmJw93WgpHchmhzyjXzumEw');
define('GG_CAPTCHA_SECRET_KEY','6Ldo1zUUAAAAAFtNVYJ_CM4FbqcInUVjD0IaxsW6');