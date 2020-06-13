<?php
/**
* /controller/quality_assurance.php
*
* PHP Version 5
*/

/**
* Quality Assurance Controller
*
* @category QualityAssurance
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Quality_Assurance extends Controller
{
    public function index()
    {
        
    }
    
    /**
    * QA statistics data
    *
    * @return void
    */
    public function statistics()
    {
        $Api = new Api();
        $data = $Api->getCurrentAutomationStatus(0);
        if (isset($data) && $data[0]['Skipped']) {
            $this->setViewVar('statistics', $data[0]);
        }
        // get bugability scores
        $Bugability_Model = new Bugability_Model($this->db);
        // not include emergency_patch and dev only tags ticket
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
        $last_updated = $Bugability_Model->raw_query("SELECT `update_at` FROM `bugability` LIMIT 1");
        $this->setViewVar('current_live_bugability', current($current_live_bugability)['bugability_score']);
        $this->setViewVar('current_dev_bugability', current($current_dev_bugability)['bugability_score']);
        $this->setViewVar('projected_live_bugability', current($projected_live_bugability)['bugability_score']);
        $this->setViewVar('last_updated', current($last_updated)['update_at']);
    }
}
