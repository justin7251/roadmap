<?php
/**
* /controller/home.php
*
* PHP Version 5
*/

/**
* The controller class for Home
*
* @category Home
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @version Release: 1.0
*/
class Home extends Controller
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
    * Kanban board of list of roadmap Milestones and Tickets
    *
    * @return void
    */
    public function roadmap()
    {
        $this->setViewVar('page_title', 'Product Road Map');
        
        $priorities = array(
            '1. Must Have' => 'glyphicon-ban-circle btn-danger',
            '2. Should Have' => 'glyphicon-warning-sign btn-warning',
            '3. Could Have' => 'glyphicon-ok-circle btn-info',
            '4. Would Have' => 'glyphicon-ok-circle btn-info'
        );
        
        $this->setViewVar('priorities', $priorities);
        $this->setViewVar('controller_js', array('sortable.js'));
        $MilestoneViewModel = new Milestone_View_Model($this->db);
        $configure_milestone = $MilestoneViewModel->get(array('id', 'name'), array('project_id' => Session::get('current_project_id')));
        $this->setViewVar('configure_milestone', $configure_milestone);
        
        $Settings = new Settings_Model($this->db);
        $setting_value = $Settings->get_setting('User', $_SESSION['user']['id'], 'roadmap.' . Session::get('current_project_id'));

        if (isset($setting_value[0]['setting_value'])) {
            $mile_stone = array();
            if ($configure_milestone) {
                foreach ($configure_milestone as $val) {
                    array_push($mile_stone, $val['id']);
                }
            }

            if (unserialize($setting_value[0]['setting_value'])) {
                $unchecked_milestone = array_diff($mile_stone, array_keys(unserialize($setting_value[0]['setting_value'])));
            } else {
                $unchecked_milestone = $mile_stone;
            }

            // first 2 milestone
            $milestones = $MilestoneViewModel->get(
                array(),
                array('project_id' => Session::get('current_project_id')),
                (isset($unchecked_milestone) && count($unchecked_milestone) > 0 ? 'AND `id` NOT IN (' . implode(',', array_values($unchecked_milestone)) . ') ' : '') .
                'AND actual_date >= NOW() ORDER BY actual_date ASC LIMIT 2');
                
            // future milestone
            $future_milestones = $MilestoneViewModel->get(
                array(),
                array('project_id' => Session::get('current_project_id')),
                (isset($unchecked_milestone) && count($unchecked_milestone) > 0 ? 'AND `id` NOT IN (' . implode(',', array_values($unchecked_milestone)) . ') ' : '') .
                'AND actual_date >= NOW() ORDER BY actual_date ASC LIMIT 2, 999');
        } else {
            // first 2 milestone
            $milestones = $MilestoneViewModel->get(array(), array('project_id' => Session::get('current_project_id')), 'AND actual_date >= NOW() ORDER BY actual_date ASC LIMIT 2');
            // future milestone
            $future_milestones = $MilestoneViewModel->get(array(), array('project_id' => Session::get('current_project_id')), 'AND actual_date >= NOW() ORDER BY actual_date ASC LIMIT 2, 999');
        }
        //check array
        $JobViewModel = new Job_View_Model($this->db);

        $used_ids = array();
        if (!empty($milestones)) {
            $total = count($milestones);
        } else {
            $total = 0;
        }
        if (is_array($milestones) && count($milestones) > 0) {
            foreach ($milestones as $key => $data) {
                $used_ids[] = $data['id'];
                $jobs = $JobViewModel->get(array(), array('milestone_id' => $data['id']), 'ORDER BY job_order ASC');
                
                //check job exists
                $milestones[$key]['jobs'] = $jobs;
            }
        }
        // future milestone job links
        if (is_array($future_milestones) && count($future_milestones) > 0) {
            foreach ($future_milestones as $key => $data) {
                $used_ids[] = $data['id'];
                $jobs = $JobViewModel->get(array(), array('milestone_id' => $data['id']), 'ORDER BY job_order ASC');
                
                //check job exists
                $future_milestones[$key]['jobs'] = $jobs;
            }
        }
        //get ticket without milestone related
        $milestones[$total]['jobs'] = $JobViewModel->get(array(), array('milestone_id' => 0, 'project_id' => Session::get('current_project_id')), 'ORDER BY (milestone_name IS NULL), job_order');
        $milestones[$total]['id'] = 999;
        $milestones[$total]['name'] = 'Plan of Intent';
        $milestones[$total]['version'] = 'Plan of Intent';
        $milestones[$total]['story_points'] = 0;
        $milestones[$total]['project_name'] = '';
        $this->setViewVar('milestones', $milestones);
        $this->setViewVar('future_milestones', $future_milestones);
        if (isset($unchecked_milestone)) {
            $this->setViewVar('unchecked_milestone', array_values($unchecked_milestone));
        } else {
            $this->setViewVar('unchecked_milestone', array());
        }
        $this->render_action = 'roadmap';
    }
    
    /**
    * Summary page, containing a visual representation of all Projects & Milestones
    *
    * @return void
    */
    public function index($year = null, $story_points = false, $link = false, $projects_selected = null)
    {
        $this->setViewVar('page_title', 'Product Delivery Timeline');
    
        $Settings = new Settings_Model($this->db);
        $setting_value = $Settings->get_setting('User', $_SESSION['user']['id'], 'delivery_timeline');
        
        if (isset($setting_value[0]['setting_value'])) {
             extract(unserialize($setting_value[0]['setting_value']));
        }

        if (!isset($year)) {
            $year = date('Y');
        }
        $sql = "
        SELECT
            *
        FROM
            `milestone_view`
        WHERE";
        
        if ($story_points) {
            $sql .= "
            `story_points` > 0
        AND";
        }

        if (isset($projects_selected) && count($projects_selected) > 0) {
                    $sql .= "
                `project_name` IN ('" . implode("','", array_keys($projects_selected)) . "')
            AND";
        }
        $sql .= "
        (
            YEAR(`start_date`) = '" . $year . "' OR YEAR(`actual_date`) = '" . $year . "'
        )
        ORDER BY
            `project_description`, `start_date`, `actual_date`";

        $project_details = array();
        $rows = $this->model->raw_query($sql);

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $project_details[$row['project_name']]['name'] = $row['project_description'];
                $project_details[$row['project_name']]['milestones'][$row['id']] = $row;
            }
        }
        $this->setViewVar('project_details', $project_details);
        $this->setViewVar('year', $year);
        $this->setViewVar('projects_selected', $projects_selected);
        $this->setViewVar('story_points', $story_points);
        $this->setViewVar('link', $link);
        
        $three_year = array(
            date("Y", strtotime("-1 year")) => date("Y", strtotime("-1 year")),
            date('Y') => date('Y'),
            date("Y", strtotime("+1 year")) => date("Y", strtotime("+1 year"))
        );

        $this->setViewVar('three_year', $three_year);
        $this->render_action = 'index';
    }
}
