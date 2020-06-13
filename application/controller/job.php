<?php
/**
* /controller/job.php
*
* PHP Version 5
*/

/**
* The controller class for Job - Epics
*
* @category Job
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Job extends Controller
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
        $this->setViewVar('page_title', 'Epic User Stories');
        
        $priority_type = array(
            '1. Must Have' => '1. Must Have',
            '2. Should Have' => '2. Should Have',
            '3. Could Have' => '3. Could Have',
            '4. Would Have' => '4. Would Have'
        );
        
        $status_type = array(
            'No Tickets' => 'No Tickets',
            'Not Started' => 'Not Started',
            'In Progress' => 'In Progress',
            'Completed' => 'Completed'
        );
        
        $confidence_level_type = array (
            '1. High' => '1. High',
            '2. Medium' => '2. Medium',
            '3. Low' => '3. Low'
        );
        
        $active_type = array(
            1 => 'Active',
            0 => 'Inactive'
         );
        
        $this->setViewVar('priority_type', $priority_type);
        $this->setViewVar('active_type', $active_type);
        $this->setViewVar('status_type', $status_type);
        $this->setViewVar('confidence_level_type', $confidence_level_type);
    }

    /**
    * List of Job in a table form
    *
    * @return void
    */
    public function index()
    {
        $JobViewModel = new job_View_Model($this->db);
        
        if (isset($_POST["filter"])) {
            unset($_POST["filter"]);
            $filter = $_POST;
            
            foreach ($filter as $key => $value) {
                if ($value == '') {
                    unset($filter[$key]);
                }
            }
            
            $this->setViewVar('jobs', $JobViewModel->get(array(), $filter));
            
            if (!isset($filter['project_id'])) {
                $filter['project_id'] = -1;
            }
            
            // set filter value to view $confidence_level, $status, $priority
            foreach ($filter as $key => $value) {
                $this->setViewVar($key, $value);
            }
        } else {
            $this->setViewVar('jobs', $JobViewModel->get(array(), array('project_id' => Session::get('current_project_id'), 'active_milestone' => 1)));
            $this->setViewVar('active_milestone', 1);
        }
    }
    
    /**
    * Gathering information of a job, its include linked code base ticket information
    *
    * @return void
    */
    public function view($job_id)
    {
        if (isset($job_id)) {
            $JobViewModel = new job_View_Model($this->db);
            $job = current($JobViewModel->get(array(), array('id' => $job_id)));
            //goto Milestone Model
            $MilestoneModel = new Milestone_Model($this->db);
            // get Milestone
            if ($job['milestone_id'] != 0) {
                if ($related_milestone = $MilestoneModel->get(array('id', 'name', 'description', 'start_date', 'actual_date', 'project_id'), array('id' => $job['milestone_id']))) {
                    $this->setViewVar('related_milestone', current($related_milestone));
                }
            }
            if (isset($job['code_base_tag'])) {
                $sql = "
                    SELECT
                        `Ticket`.*
                    FROM
                        `job_ticket_mappings` AS `Mapping`
                    INNER JOIN `code_base_ticket` AS `Ticket` ON
                    (
                        `Ticket`.`ticket-id` = `Mapping`.`code_base_ticket_id`
                    )
                    WHERE
                        `Mapping`.`job_id` = " . $job['id'] . ";";
                        
                $code_base_data = $this->model->raw_query($sql);
            }

            if ($job) {
                $this->setViewVar('job', $job);
                $this->setViewVar('code_base_data', $code_base_data);
            }
            return;
        }
        header('location: ' . URL . 'job/index');
    }
    
    /**
    * Modifies an existing Job record
    *
    * @return void
    */
    public function edit($job_id = null, $from_view = false)
    {
        if (Access::get_permission() != ('admin' || 'limited')) {
            header('location: ' . URL . 'job/index');
        }
        if (isset($_POST["id"])) {
            if ($this->model->save($_POST)) {
                $this->importCodeBaseTickets($_POST);
                header('location: ' . URL . 'job/' . ($from_view ? 'view/' . $_POST['id'] : 'index'));
            }
        }
        // if we have an id of a job that should be edited
        if (isset($job_id)) {
            $job = current($this->model->get($this->model->_fields, array('id' => $job_id)));
            
            if ($job) {
                $this->setViewVar('job', $job);
                
                // get all Milestone names
                $Milestone_Model = new Milestone_Model($this->db);
                $this->setViewVar('milestone_names', $Milestone_Model->get(array('id', 'name'), array('project_id' => Session::get('current_project_id')), 'AND `active` = "Y"'));

                //add WYSIWYG editor
                $this->setViewVar('controller_css', array('bootstrap-wysihtml5.css'));
                $this->setViewVar('controller_js', array('wysihtml5-0.3.0.min.js', 'bootstrap-wysihtml5.js'));
                $this->setViewVar('priorities', array('1. Must Have', '2. Should Have', '3. Could Have', '4. Would Have'));
                $this->setViewVar('confidence_level', array('1. High', '2. Medium', '3. Low'));
                $this->setViewVar('from_view', $from_view);
                return;
            }
        }
        header('location: ' . URL . 'job/index');
    }
    
    /**
    * Copy an existing Job record
    *
    * @return void
    */
    public function copy($job_id = null, $from_view = false)
    {
        if (Access::get_permission() != ('admin' || 'limited')) {
            header('location: ' . URL . 'job/index');
        }
        if (isset($_POST["name"])) {
            $_POST["create_at"] = date("Y-m-d H:i:s");
            if ($this->model->save($_POST)) {
                $this->importCodeBaseTickets($_POST);
                header('location: ' . URL . 'job/' . ($from_view ? 'view/' . $_POST['id'] : 'index'));
            }
        }
        // if we have an id of a job that should be edited
        if (isset($job_id)) {
            $job = current($this->model->get($this->model->_fields, array('id' => $job_id)));
            if ($job) {
                $this->setViewVar('job', $job);
                
                // get all Milestone names
                $Milestone_Model = new Milestone_Model($this->db);
                $this->setViewVar('milestone_names', $Milestone_Model->get(array('id', 'name'), array('project_id' => Session::get('current_project_id')), 'AND `active` = "Y"'));

                //add WYSIWYG editor
                $this->setViewVar('controller_css', array('bootstrap-wysihtml5.css'));
                $this->setViewVar('controller_js', array('wysihtml5-0.3.0.min.js', 'bootstrap-wysihtml5.js'));
                $this->setViewVar('priorities', array('1. Must Have', '2. Should Have', '3. Could Have', '4. Would Have'));
                $this->setViewVar('confidence_level', array('1. High', '2. Medium', '3. Low'));
                $this->setViewVar('from_view', $from_view);
                return;
            }
        }
        header('location: ' . URL . 'job/index');
    }
    
    /**
    * Add Job
    *
    * @return void
    */
    public function add()
    {
        if (Access::get_permission() != ('admin' || 'limited')) {
            header('location: ' . URL . 'job/index');
        }
        // if we have POST data to create a new user entry
        if (isset($_POST["submit_add_job"])) {
            $_POST["create_at"] = date("Y-m-d H:i:s");
            if ($this->model->save($_POST)) {
                $this->importCodeBaseTickets($_POST);
                header('location: ' . URL . 'job/index');
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
    * Delete existing Job
    *
    * @return void
    */
    public function delete($job_id)
    {
        if (Access::get_permission() != 'admin') {
            header('location: ' . URL . 'job/index');
        }
        // if we have an id of a that should be deleted
        if (isset($job_id)) {
            // do deleteSong() in model/model.php
            $this->model->delete($job_id);
        }
        // where to go after has been deleted
        header('location: ' . URL . 'job/index');
    }

    /**
    * Import Code bast ticket to the system and create job mapping
    *
    * @param array $job that used to find the code base ticket
    * @return void
    */
    private function importCodeBaseTickets($job)
    {
        if (!isset($job['id'])) {
            $job['id'] = $this->db->lastInsertId();
        }
        // Import code base ticket
        $Code_Base_Model = new Code_Base_Model($this->db);
        $tickets = $Code_Base_Model->importTicketsByTag(Session::get('current_project_name'), $job['code_base_tag'], $job['id']);
    }

    /**
    * Update Project Roadmap ordering
    *
    * @param int $milestone_id that used to ordering the Job position on home/roadmap
    * @return void
    */
    public function update_order($milestone_id = 0)
    {
        if (isset($_POST['data'])) {
           $job_ids = explode(',',$_POST['data']);

            //call save to update each job with the $milestone_id and job_order then arrive in
            //check if array
            if (is_array($job_ids) && count($job_ids) > 0) {
                foreach  ($job_ids as $order => $job_id) {
                    $job_order = ($order + 1);
                    $_POST['job_order'] = $job_order;
                    $_POST['id'] = $job_id;
                    $_POST['milestone_id'] = ($milestone_id == 999 ? 0 : $milestone_id);
                    unset($_POST['data']);
                    $this->model->save($_POST);
                }
            }
        }
    }
    
    /**
    * Update tikcet status by posting the job id to codebase and get the newest data
    *
    * @param int $id Job id
    * @return void
    */
    public function update_ticket_status($id)
    {
        if (!isset($id)) {
            return false;
        }
        $job = $this->model->raw_query("SELECT `id`,`code_base_tag` FROM `job` WHERE `id` = " . $id);
        if (isset($job[0]['id'])) {
            $this->importCodeBaseTickets($job[0]);
        }
    }
    
    /**
    * Create a html a tag that link to the acutal ticket
    *
    * @return array
    */
    public function get_code_base_job($jobs, $project_name)
    {
        if ($jobs && $project_name) {
            $all_job = '';
            //if project name with space replace
            $project_name = str_replace(' ', '-', $project_name);
            
            $code_base_jobs = explode(",", $jobs);

            foreach ($code_base_jobs as $code_base_job) {
                $code_base_job = trim($code_base_job);
                //skip the empty string and wasn't a blank string 
                if (isset($code_base_job) && $code_base_job != '&nbsp;') {
                    $all_job .= '<a href="http://software.s2partnership.co.uk/projects/' . $project_name . '/jobs/' . $code_base_job .'" TARGET="_blank">' . 'job ' . $code_base_job . '</a><br>';
                }
            }
        }
        return $all_job;
    }
}
