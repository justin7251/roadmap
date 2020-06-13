<?php

class Automation_Model extends Model
{
    public $_fields = array(
        'id',
        'class_name',
        'assign_to',
        'comment_by_dev',
        'comment_by_qa',
        'archievable',
        'status',
        'time_measurement',
        'dev_estimate_complexity',
        'qa_test_priority',
        'high_intensity',
        'medium_intensity',
        'low_intensity',
        'sign_off_by',
        'completed_by',
        'completed_date',
        'sign_off_date',
        'deleted'
    );
    protected $_table = 'automation';
    
    /**
    *
    * @parma int $id
    * @return array
    */
    public function get_class_info($id)
    {
        $data = $this->raw_query(
            "
                SELECT
                    `tap`.*
                FROM
                    `automation`
                LEFT JOIN `tap` ON (
                    `automation`.`class_name` = `tap`.`class_name`
                )
                WHERE
                    `automation`.`id` = " . $id . "
        ");
        if ($data[0]['id']) {
            return $data;
        }
    }
}
?>