<?php
/**
* /controller/api.php
*
* PHP Version 5
*/

/**
* The controller class for API
*
* @category Automation
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Api extends Controller
{
    /**
    * Pre-load method
    * Permission Control
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Get ticket by sprint
    *
    * @return void
    */
    public function get_codebase_sprint($project_name, $sprint)
    {
        $this->render = false;
        if (strlen($sprint) < 1 || strlen($project_name) < 1) {
            return false;
        }
        $Code_Base_Model = new Code_Base_Model($this->db);
        prr($Code_Base_Model->getTicketsBySprint($project_name, $sprint));
    }
    
    /**
    * Get Current Automation Statistics
    *
    * @return void
    */
    public function getCurrentAutomationStatus($api_call = true)
    {
        $Automation_View_Model = new Automation_View_Model($this->db);
        $jenkins_data = $Automation_View_Model->getStatistics();
        $this->render = false;
        if ($api_call) {
            echo json_encode(current($jenkins_data));
        } else {
            return $jenkins_data;
        }
    }
    
    /**
    * Api call that save current bugability tickets
    *
    * @return void
    */
    public function getBugability($project_name = 'riskwise-2')
    {
        $this->Code_Base_Model = new Code_Base_Model($this->db);
        $this->Bugability_Model = new Bugability_Model($this->db);
        $MilestoneViewModel = new Milestone_View_Model($this->db);
        //curl request to codebase, get all open bug ticket
        $tickets_1 = $this->Code_Base_Model->get_code_base_bugability($project_name);
        $milestone = current(
            $MilestoneViewModel->get(
                array(),
                array('project_id' => Session::get('current_project_id')),
                "AND `project_name` = 'riskwise-2' AND `completed` = 0 AND `name` NOT IN ('On Hold', 'Unallocated') ORDER BY `start_date` ASC LIMIT 1"
            )
        );
        $milestone['name'] = urlencode($milestone['name']);
        $milestone['name'] = str_replace(',', '%2C', $milestone['name']);
        $milestone['name'] = str_replace('.', '%2E', $milestone['name']);
        $milestone['name'] = str_replace('-', '%2D', $milestone['name']);
        $tickets_2 = $this->Code_Base_Model->get_code_base_bugability($project_name, 'milestone:"' . $milestone['name'] . '"');
        //combine two array
        $tickets = $tickets_1 + $tickets_2;
        $this->Bugability_Model->save_open_bugability_ticket($tickets);
        prr('Bugability ticket has been imported');
        $this->render = false;
    }
    
    /**
    * Retrieve current bugability scores
    *
    * @return void
    */
    public function getCurrentBugabilityScores($api_call = true)
    {
        $bugability_score = array();
        // get bugability scores
        $Bugability_Model = new Bugability_Model($this->db);
        $current_live_bugability = $Bugability_Model->raw_query("
            SELECT
                sum(`bugability_score`) AS `bugability_score`
            FROM
                `bugability`
            WHERE
                `tags` NOT LIKE '%emergency_patch%' AND `tags` NOT LIKE '%dev_only%';");
        $current_dev_bugability = $Bugability_Model->raw_query("SELECT sum(`bugability_score`)  AS `bugability_score` FROM `bugability` WHERE `status` != 'Completed';");
        $projected_live_bugability = $Bugability_Model->raw_query("
            SELECT
                sum(bugability_score) AS `bugability_score`
            FROM
                `bugability`
            WHERE
                `bugability`.`code_base_milestone_id` NOT IN (
                        SELECT
                            (`milestone`.`id`)
                        FROM
                            `milestone`
                        WHERE
                            `milestone`.`project_id` = 8
                        AND
                            `milestone`.`actual_date` >= NOW()
                        AND
                            `milestone`.`name` NOT IN ('On Hold', 'Unallocated')
                )
            AND
                `tags` NOT LIKE '%emergency_patch%' AND `tags` NOT LIKE '%dev_only%'");
                
        $bugability_score =  array(
            'current_live_bugability' => current($current_live_bugability)['bugability_score'],
            'current_dev_bugability' => current($current_dev_bugability)['bugability_score'],
            'projected_live_bugability' => current($projected_live_bugability)['bugability_score'],
        );
        if ($api_call) {
            echo json_encode($bugability_score);
        } else {
            return $bugability_score;
        }
        $this->render = false;
    }
}
