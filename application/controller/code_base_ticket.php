<?php
/**
* /controller/code_base_ticket.php
*
* PHP Version 5
*/

/**
* The controlling class for Code base ticket
*
* All Code base ticket that has been imported from Codebase
*
* @category Code_Base_Ticket
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Code_Base_Ticket extends Controller
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
        parent::__construct();
        if (Access::get_permission() != 'admin') {
            header('location: ' . URL . 'home/index');
        }
    }

    /**
    * Providing a list of Code base ticket that has been import from codebase
    *
    * @return void
    */
    public function index()
    {
        $this->setViewVar('tickets', $this->model->get(array(), array('project_id' => array('IN' => array(0, Session::get('current_project_id'))))));
    }
}
