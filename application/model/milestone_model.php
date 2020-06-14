<?php
/**
* /model/milestone_model.php
*
* PHP Version 5
*/

/**
* The controller class for Milestone
*
* @category Milestone
* @package Roadmap
* @subpackage Model
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Milestone_Model extends Model
{
    public $_fields = array(
        'id',
        'name',
        'goal',
        'project_id',
        'active',
        'story_points',
        'start_date',
        'end_date',
        'completed',
        'create_at',
        'deleted',
        'date_deleted'
    );
    protected $_table = 'milestone';
    
}