<?php

class User_Model extends Model
{
    public $_fields = array('id', 'first_name', 'last_name', 'email', 'user_level_id', 'password', 'last_logged_in', 'reset_link', 'create_at');
    private $_salt_font = 'angularjsrws2';
    private $_salt_end = 'thisisasupperpassword';
    protected $_table = 'user';

    //check login crediencial
    public function check_password($post)
    {
        $current_user = $this->get(array(''), array('email' => $post['email'], 'password' => $this->encryption($post['password'])));
        
        if ($current_user[0]['id']) {
            $current_user = current($current_user);
            $user = array(
                'id' => $current_user['id'],
                'first_name' => $current_user['first_name'],
                'last_name' => $current_user['last_name'],
                'user_level_id' => $current_user['user_level_id'],
                'email' => $current_user['email']
            );
            return $user;
        } else {
            return false;
        }
    }
    
    //check user in database
    function check_user_exist($post)
    {
        if ($this->get(array('id'), array('email' => $post['email']))) {
            return true;
        } else {
            return false;
        }
    }   

    function check_reset_link($post)
    {
        $check = $this->get(array('id'), array('reset_link' => $post));
        if ($check) {
            return $check;
        } else {
            return false;
        }
    }
    
    function sent_reset($post, $check_email = array())
    {
        $email_from = 'notifications@live.s2riskwise.com';
        $email_to = $post['email'];
        $email_subject= 'Reset Password';
        $url = md5($_POST['email']. time());
        $email_message = '/user/reset/' . $url ;

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'To: '. $email_to;
        $headers[] = 'From: '. $email_from;

        // $result = mail($email_to, $email_subject, $email_message, implode("\r\n", $headers));
        // if (!$result) {
            // $error = '<span class="error">There is a problem from sending email.</span>';
        // }
        
        Email::sent();

        // parent::save(array('id' => $check_email->id , 'reset_link' => $url));
        return 'success';
    }
    
    public function save($post)
    {
        if (isset($post['password'])) {
            $post['password'] = $this->encryption($post['password']);
        }
        return parent::save($post);
    }

    private function encryption($value)
    {
        return substr(openssl_digest($this->_salt_font . $value . $this->_salt_end, 'sha512'), 0, 50);
    }
}
?>