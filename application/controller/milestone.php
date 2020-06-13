<?php
/**
* /controller/milestone.php
*
* PHP Version 5
*/

/**
* The controller class for Milestone
*
* @category Milestone
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Milestone extends Controller
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
        if (Access::get_permission() != ('admin' || 'limited')) {
            header('location: ' . URL . 'home/index');
        }
        $this->setViewVar('page_title', 'Milestones');
    }
    
    /**
    * A List Milestone
    *
    * @return void
    */
    public function index()
    {
        $this->setViewVar(
            'milestones',
            $this->model->get(
                array(),
                array('project_id' => array('IN' => array(Session::get('current_project_id'))), 'active' => 'Y')
            )
        );
    }
    
    /**
    * View a milestone
    *
    * @param int $milestone_id used to retrieve milestone data
    * @return void
    */
    public function view($milestone_id)
    {
        if (isset($milestone_id)) {
            $milestone = current($this->model->get($this->model->_fields, array('id' => $milestone_id)));
   
            if ($milestone) {
                $this->setViewVar('milestone', $milestone);
                return;
            }
        }
        header('location: ' . URL . 'milestone/index');
    }

    /**
    * Add Milestone
    *
    * @return void
    */
    public function add()
    {
        if (Access::get_permission() != ('admin' || 'limited')) {
            header('location: ' . URL . 'milestone/index');
        }
        // if we have POST data to create a new user entry
        if (isset($_POST["submit_add_milestone"])) {
            $_POST["create_at"] = date("Y-m-d H:i:s");
            if ($this->model->save($_POST)) {
                header('location: ' . URL . 'milestone/index');
            }
        }
        //add WYSIWYG editor
        $this->setViewVar('controller_css', array('bootstrap-wysihtml5.css'));
        $this->setViewVar('controller_js', array('wysihtml5-0.3.0.min.js', 'bootstrap-wysihtml5.js'));
        $this->setViewVar('priorities', array('1. Must Have', '2. Should Have', '3. Could Have', '4. Would Have'));
        $this->setViewVar('confidence_level', array('1. High', '2. Medium', '3. Low'));
        
        // get all Milestone names
        $Milestone_Model = new Milestone_Model($this->db);
        $this->setViewVar('milestones', $Milestone_Model->get(array('id','name'), array('project_id' => Session::get('current_project_id')), 'AND active = "Y"'));
    }
    
    
    /**
    * Modify existing milestone data
    *
    * @return void
    */
    public function edit($milestone_id = null, $from_view = false)
    { 
        if (isset($_POST["id"])) {
            if ($this->model->save($_POST)) {
                header('location: ' . URL . 'milestone/' . ($from_view ? 'view/' . $_POST['id'] : 'index'));
            }
        }
        // if we have an id of a milestone that should be edited
        if (isset($milestone_id)) {
            $milestone = current($this->model->get($this->model->_fields, array('id' => $milestone_id)));
   
            if ($milestone) {
                //add date time picker and WYSIWYG editor
                $this->setViewVar('controller_css', array('bootstrap-datetimepicker.css', 'bootstrap-wysihtml5.css'));
                $this->setViewVar('controller_js', array('moment.js', 'bootstrap-datetimepicker.js', 'wysihtml5-0.3.0.min.js', 'bootstrap-wysihtml5.js'));
                
                // get all projects names
                $ProjectModel = new Project_Model($this->db);
                $this->setViewVar('projects', $ProjectModel->get(array('id','name')));
                
                $this->setViewVar('milestone', $milestone);
                $this->setViewVar('from_view', $from_view);
                return;
            }
        }
        header('location: ' . URL . 'milestone/index');
    }

    /**
    * Retrieve milestone stone name
    *
    * @return void
    */
    public function get_milestone_name()
    {
        if (isset($_POST['data'])) {
            $milestone_ids = explode(',',$_POST['data']);

            //call save to update each ticket with the $milestone_id and ticket_order then arrive in
            //check if array
            if (is_array($milestone_ids) && count($milestone_ids) > 0) {
                foreach ($milestone_ids as $order => $ticket_id) {
                    $this->model->get($this->model->get($this->model->_fields, array('id' => $milestone_id)));
                }
            }
        }
    }
}
