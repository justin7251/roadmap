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
        if ($current_user && $current_user[0]['id']) {
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
    public function check_user_exist($post)
    {
        if ($this->get(array('id'), array('email' => $post['email']))) {
            return true;
        } else {
            return false;
        }
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