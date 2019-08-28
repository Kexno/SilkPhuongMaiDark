<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('sendMail')) {
    /*
     * @param
     *$from_mail: Người gửi
     *$to_mail: Gửi tới ai
     *$subject: Tiêu đề mail
     *$template: tên file load nội dung mail
     *$data: Thông tin cần truyền vào nội dung mail VD: {{username}} $data=array('username'=>abc)
     * */
    function sendMail_order($from_mail = '', $to_mail = '', $subject, $template, $data = array(), $emailToCC = '', $emailToBCC = '')
    {
        $_this =& get_instance();
        if (empty($subject)) $subject = 'Thông tin gửi từ ' . BASE_URL;
        $contentHtml = file_get_contents(FCPATH . 'template-mail' . DIRECTORY_SEPARATOR . $template . '.html');
        if (!empty($data)) foreach ($data as $key => $value) {
            $contentHtml = str_replace('{{' . $key . '}}', $value, $contentHtml);
        }
        try {
            $_this->load->library('email');
            if (!empty($_this->settings['smtp_pass'])) {
                $_this->email->protocol = $_this->settings['protocol'];
                $_this->email->smtp_host = $_this->settings['smtp_host'];
                $_this->email->smtp_user = $_this->settings['smtp_user'];
                $_this->email->smtp_port = $_this->settings['smtp_port'];
            }
            if (empty($to_mail)) {
                if (!empty($_this->settings['email_admin'])) {
                    $to_mail = $_this->settings['email_admin'];
                } else {
                    $to_mail = $_this->email->smtp_user;
                }
            }
            if (empty($from_mail)) {
                if (!empty($_this->settings['email_admin'])) {
                    $from_mail = $_this->settings['email_admin'];
                } else {
                    $from_mail = $_this->email->smtp_user;
                }
            }
            $_this->email->from($from_mail, $subject);
            $_this->email->to($to_mail);
            if (!empty($emailToCC)) $_this->email->cc($emailToCC);
            if (!empty($emailToBCC)) $_this->email->bcc($emailToBCC);
            $_this->email->subject($subject);
            $_this->email->message($contentHtml);
            if ($_this->email->send()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            log_message('error', 'ERROR ===> ' . json_encode($e));
            return false;
        }
    }
      function sendMail($from_mail = '',$from_name='', $to_mail = '', $subject, $template, $contentHtml ='', $emailToCC = '', $emailToBCC = '')
  {
    $_this =& get_instance();
    if (empty($subject)) $subject = 'Thông tin gửi từ ' . BASE_URL;
   //  if (!empty($data)) foreach ($data as $key => $value) {
   //   if(!empty($value)) $contentHtml = str_replace('{{' . $key . '}}', $value, $contentHtml);
   //   else $contentHtml = str_replace('<p style="margin-top: 0;">'.$key.'{{' . $key . '}}</p>', $value, $contentHtml);
   // }
   try {
    $_this->load->library('email');
    if (!empty($_this->settings['smtp_pass'])) {
      $_this->email->protocol = $_this->settings['protocol'];
      $_this->email->smtp_host = $_this->settings['smtp_host'];
      $_this->email->smtp_user = $_this->settings['smtp_user'];
      $_this->email->smtp_port = $_this->settings['smtp_port'];
    }
    if (empty($to_mail)) {
      if (!empty($_this->settings['email_admin'])) {
        $to_mail = $_this->settings['email_admin'];
      } else {
        $to_mail = $_this->email->smtp_user;
      }
    }
    if (empty($from_mail)) {
      if (!empty($_this->settings['email_admin'])) {
        $from_mail = $_this->settings['email_admin'];
      } else {
        $from_mail = $_this->email->smtp_user;
      }
    }
    $_this->email->from($from_mail, $from_name);
    $_this->email->to($to_mail);
    if (!empty($emailToCC)) $_this->email->cc($emailToCC);
    if (!empty($emailToBCC)) $_this->email->bcc($emailToBCC);
    $_this->email->subject($subject);
    $_this->email->message($contentHtml);
    if ($_this->email->send()) {
      return true;
    } else {
      return false;
    }
  } catch (Exception $e) {
    log_message('error', 'ERROR ===> ' . json_encode($e));
    return false;
  }
}
}
