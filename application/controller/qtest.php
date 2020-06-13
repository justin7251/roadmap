<?php
/**
* /controller/qtest.php
*
* PHP Version 5
*/

/**
* The controller class for Qtest
*
* @category Qtest
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Qtest extends Controller
{
    /**
    * Pre-load method
    * Permission Control
    *
    * @return void
    */
    public function __construct()
    {
        Access::has_session();
        if (Access::get_automation_permission() != 'automation_member') {
            header('location: ' . URL . 'home/index');
        }
        parent::__construct();
    }
}
