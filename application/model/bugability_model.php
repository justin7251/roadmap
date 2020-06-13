<?php

/**
 * Class Bugability
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class Bugability_Model extends Model
{
    public $_fields = array('id', 'ticket-id', 'summary', 'code_base_milestone_id', 'tags', 'milestone-id', 'category_name', 'bugability_score', 'status', 'ticket-type', 'create_at', 'update_at');
    protected $_table = 'bugability';
    
    /**
    * 
    * 
    * @param int $id
    * @param string $project_name
    * @return void
    */
    public function save_open_bugability_ticket($tickets)
    {
        //delete all data
        $sql = "DELETE FROM `bugability`";
        $this->raw_query($sql);

        foreach ($tickets as $ticket) {
            if (isset($ticket['ticket-id'])) {
               //code base ticket check before save
                $this->save($ticket);
            }
        }
        return true;
    }
}
