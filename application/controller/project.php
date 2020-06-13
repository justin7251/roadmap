<?php
/**
* /controller/project.php
*
* PHP Version 5
*/

/**
* The controller class for Project
*
* @category Project
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Project extends Controller
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
    }

    /**
    * Retrive related milestones and job
    *
    * @param int $project_id get related project data
    * @return void
    */
    public function related_project($project_id = 0)
    {
        if ($project_id) {
            Session::set('current_project_id', $project_id);
            $project_name = $this->model->get(array('name', 'description'), array('id' => $project_id));
            Session::set('current_project_name', $project_name[0]['name']);
            Session::set('current_project_description', $project_name[0]['description']);
            header('location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
    
    /**
    * Import Codebase ticket per project
    *
    * @param string $project_name 
    * @return void
    */
    public function get_all_code_base_data($project_name)
    {
        // get all milestones
        $this->Code_Base_Model = new Code_Base_Model($this->db);
        $this->Code_Base_Model->get_milestone($project_name);
        
        //get project id from project id 
        $this->Job_Model = new Job_Model($this->db);
        $sql = "SELECT `id` FROM `project` WHERE `name` = '" . $project_name . "'";
        $project_id = $this->Job_Model->raw_query($sql);
        // get all jobs for this project
        $sql = "SELECT `id`,`code_base_tag` FROM `job` WHERE `project_id` = '" . $project_id[0]['id'] . "'";
        $all_job = $this->Job_Model->raw_query($sql);
        // loop through jobs to call a $Code_base_Model->getJobTickets($job) method
        foreach ($all_job as $job) {
            $this->Code_Base_Model->getJobTickets($project_name, $job);
        }
    }
    
    /**
    * Update Codebase ticket per project
    *
    * @param string $project_name 
    * @return void
    */
    public function set_all_code_base_data($project_name)
    {
        if (Access::get_permission() != 'admin') {
            return false;
        }
        // get all milestones
        $this->Code_Base_Model = new Code_Base_Model($this->db);
        
        // get all jobs for this project
        $this->Job_Model = new Job_Model($this->db);
        $sql = "
        SELECT
            `Job`.*
        FROM
            `job` AS `Job`
        INNER JOIN `project` AS `Project` ON
        (
            `Project`.`id` = `Job`.`project_id`
            AND
            `Project`.`name` = '" . $project_name . "'
        )";
        $all_jobs = $this->Job_Model->raw_query($sql);
        // loop through jobs to call a $Code_base_Model->getJobTickets($job) method
        foreach ($all_jobs as $job) {
            $this->Code_Base_Model->updateJobTickets($project_name, $job);
        }
    }
}
