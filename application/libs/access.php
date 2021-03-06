<?php
/**
* /application/libs/access.php
*
* PHP Version 5
*/

/**
* User ACL Permission
*
* @category Access
* @package Roadmap
* @subpackage library
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Access {
    
    public $permissions = array(
        'Code_Base_Ticket' => array('admin', 'limited', 'read_only')
    );

    public static function has_session()
    {
        //can't user Session::get 
        if (empty($_SESSION['user'])) {
            header('location: ' . URL . 'user/login');
            die;
        }
    }
    
    /* user_level_id  */
    /* 1 = admin , 2 = read only */
    public static function get_permission()
    {
        if ($_SESSION['user']['user_level_id'] == 1) {
            return 'admin';
        } elseif ($_SESSION['user']['user_level_id'] == 2) {
            return 'read_only';
        } elseif ($_SESSION['user']['user_level_id'] == 3) {
            return 'limited';
        } else {
            /* no permission page  */
            header('location: ' . URL . 'user/login');
            die;
        }
    }
}