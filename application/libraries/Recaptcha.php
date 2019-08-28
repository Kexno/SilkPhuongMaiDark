<?php (! defined('BASEPATH')) and exit('No direct script access allowed');
require('recaptcha/src/autoload.php');
class ReCaptcha
{

    private $_ci;

    private $_recaptcha;

    const sign_up_url = 'https://www.google.com/recaptcha/admin';
    const site_verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    const api_url = 'https://www.google.com/recaptcha/api.js';


    public function __construct()
    {
        $this->_ci = & get_instance();
        $this->_siteKey = GG_CAPTCHA_SITE_KEY;
        $this->_secretKey = GG_CAPTCHA_SECRET_KEY;

        $this->_recaptcha = new \ReCaptcha\ReCaptcha($this->_secretKey);

        if (empty($this->_siteKey) or empty($this->_secretKey)) {
            die("To use reCAPTCHA you must get an API key from <a href='"
                .self::sign_up_url."'>".self::sign_up_url."</a>");
        }



    }

    public function verify($recaptchaResponse,$remoteIp){
        $resp = $this->_recaptcha->verify($recaptchaResponse, $remoteIp);
        if ($resp->isSuccess()) return true;
        else return false;
    }

    public function showInput(){
        return '<div class="g-recaptcha" data-sitekey="'.GG_CAPTCHA_SITE_KEY.'"></div>
            <script type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js">
            </script>';
    }
}
