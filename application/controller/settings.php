<?php
/**
* /controller/settings.php
*
* PHP Version 5
*/

/**
* The controller class for Settings
*
* @category Settings
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Settings extends Controller
{

    public function save_setting($roadmap = null)
    {
        if (isset($_POST)) {
            $save_data = '';
            //Delete existing setting
            $this->model->delete_setting($_POST['model_name'], $_SESSION['user']['id'], $_POST['setting_key']);
            if (isset($_POST['model_name'])) {
                $save_data['model_name'] = $_POST['model_name'];
                unset($_POST['model_name']);
            }
            if (isset($_POST['setting_key'])) {
                $save_data['setting_key'] = $_POST['setting_key'];
                unset($_POST['setting_key']);
            }
            if (isset($roadmap)) {
                $save_data['setting_value'] = serialize($_POST['Milestone']);
            } else {
                $save_data['setting_value'] = serialize($_POST);
            }
            $save_data['model_id'] = $_SESSION['user']['id'];
            $save_data['create_at'] = date("Y-m-d H:i:s");

            if ($this->model->save($save_data)) {
                header('location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
    }
}
