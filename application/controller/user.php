<?php
/**
* /controller/user.php
*
* PHP Version 5
*/

/**
* The controller class for User
*
* @category User
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class User extends Controller
{
    /**
    * @var array $loggedin required
    */
    protected $loggedin = array('index');
    
    /**
    * User information page
    *
    * @return void
    */
    public function index()
    {
        $error = $this->checkError();

        if (isset($_POST['id'])) {
            if ($_POST["password_match"] == $_POST["password_match2"] && $_POST["password_match"] != '') {
                $_POST["password"] = $_POST["password_match"];
            }
            unset($_POST["password_match"]);
            unset($_POST["password_match2"]);
            $_POST["update_at"] = date('Y-m-d H:i:s');
            
            if ($this->model->save($_POST)) {
                Session::set('user', current($this->model->get(null, array('id' => $_POST['id']))));
                header('location: ' . URL . 'home/index');
            }
            $this->error = 'The information that you have provided contains errors. Please review, revise, and re-submit.';
        }
        // set user level to view
        $this->setViewVar('user_levels', $this->model->raw_query("SELECT * FROM `user_level` WHERE `id` NOT IN (4,5)"));
        $this->setViewVar('error', $this->checkError());
    }
    
    /**
    * Login View
    *
    * @return void
    */
    public function login()
    {
        $this->setViewVar('error', $this->checkError());
        $this->header = 'login_header';
        $this->footer = 'login_footer';
    }
    
    /**
    * Add New User
    */
    public function add()
    {
        if (Access::get_permission() != 'admin') {
            header('location: ' . URL . 'user/index');
        }
        // if we have POST data to create a new user entry
        if (isset($_POST["submit_add_user"])) {
            if (!$this->model->check_user_exist($_POST)) {
                $_POST['create_at'] = date('Y-m-d H:i:s');
                if ($this->model->save($_POST)) {
                    header('location: ' . URL . 'user/manage');
                }
            } else {
                $this->error = 'The account already exist. ';
                unset($_POST);
                $this->add();
            }
        }
        // set user level to view
        if (Access::get_permission() == 'automation' && Access::get_permission() == 'admin') {
            $this->setViewVar('user_level', $this->model->raw_query("SELECT * FROM `user_level`"));
        } else {
            // Hide automation and QA level - 4,5
            $this->setViewVar('user_level', $this->model->raw_query("SELECT * FROM `user_level` WHERE `id` NOT IN (4,5)"));
        }
    }
    
    /**
    * User Management
    *
    * @return void
    */
    public function manage()
    {
        if (Access::get_permission() != 'admin') {
            header('location: ' . URL . 'job/index');
        }
        foreach ($this->model->raw_query("SELECT * FROM `user_level`") as $value) {
            $user_level[$value['id']] = $value['name'];
        }
        $this->setViewVar('user_levels', $user_level);
        $this->setViewVar('users', $this->model->get());
    }
    
    /**
    * Edit User data
    *
    * @return void
    */
    public function edit($id = null)
    {
        if (Access::get_permission() != 'admin') {
            header('location: ' . URL . 'job/index');
        }
        if (isset($_POST["id"])) {
            if ($_POST["password_match"] == $_POST["password_match2"] && $_POST["password_match"] != '') {
                $_POST["password"] = $_POST["password_match"];
                unset($_POST["password_match"]);
                unset($_POST["password_match2"]);
                $_POST["update_at"] = date('Y-m-d H:i:s');
                if ($this->model->save($_POST)) {
                    header('location: ' . URL . 'user/manage');
                }
            }
            $id = $_POST['id'];
            $this->error = 'The information that you have provided contains errors. Please review, revise, and re-submit.';
        }
        
        // set user level to view
        if (Access::get_permission() == 'automation' && Access::get_permission() == 'admin') {
            $userLevel = $this->model->raw_query("SELECT * FROM `user_level`");
        } else {
            // Hide automation and QA level - 4,5
            $userLevel = $this->model->raw_query("SELECT * FROM `user_level` WHERE `id` NOT IN (4,5)");
        }
        
        foreach ($userLevel as $value) {
            $user_level[$value['id']] = $value['name'];
        }
        $this->setViewVar('user_levels', $user_level);
        $this->setViewVar('user', current($this->model->get(null, array('id' => $id))));
        $this->setViewVar('error', $this->checkError());
    }
    
    /**
    * Reset Password
    *
    * @param int $key reset key
    * @return void
    */
    public function reset($key)
    {
        if ($this->checkError()) {
            $error = $this->checkError();
        }
        if (isset($key)) {
            if ($user = $this->model->check_reset_link($key)) {
                return;
            }
        }
        print_r('bad');
    }

    /**
    * Check Password match
    *
    * return void
    */
    public function check()
    {
        if (isset($_POST["submit_reset"])) {
            if ($_POST['password'] == $_POST['password2']) {
                unset($_POST['password2']);
                $this->model->save($_POST);
                $this->info = 'The password has been reset';
                $this->login();
            } else {
                $this->error = 'Those two password wasn\'t catch. ';
                $this->reset($_POST['reset']);
            }
        }
    }
    
    /**
    * Post Login check
    *
    * return void
    */
    public function login_post()
    {
        // if we have POST data to create a new user entry
        if (isset($_POST["submit_compare_user"])) {
            if ($this->model->check_password($_POST)) {
                $user = $this->model->check_password($_POST);
                $this->model->raw_query("
                    UPDATE
                        `user`
                    SET
                        `last_logged_in` = '" . date("Y-m-d H:i:s") . "'
                    WHERE
                        `id` = " . $user['id']
                );
                Session::set('user', $user);
                $user_level = $this->model->raw_query("SELECT * FROM `user_level`");
                Session::set('user_level', $user_level);
                header('location: ' . URL);
            } else {
                $this->error = 'You have entered an invalid username or password';
                $this->render_action = 'login';
                $this->login();
            }
        }
    }

    /**
    * Logout from the system and destory session
    *
    * @return void
    */
    public function logout()
    {
        $this->setViewVar('error', $this->checkError());
        $this->setViewVar('info', 'You have been successfully logged out.');
        $this->header = 'login_header';
        $this->footer = 'login_footer';
        $this->render_action = 'login';
        
        session_destroy();
    }
}
