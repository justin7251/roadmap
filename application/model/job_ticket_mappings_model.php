<?php

class Job_Ticket_Mappings_Model extends Model
{
    public $_fields = array(
        'id',
        'job_id',
        'code_base_ticket_id'
    );
    protected $_table = 'job_ticket_mappings';
    
    
    public function set_mapping($job_id, $code_base_ticket_id)
    {
        $data = array(
            'id' => null,
            'job_id' => $job_id,
            'code_base_ticket_id' => $code_base_ticket_id
        );
        return $this->save($data);
    }
    
    // delete all mapping per job id
    public function deleteAll($job_id)
    {
       $sql = '
        DELETE FROM
            `job_ticket_mappings`
        WHERE
            `job_id` = ' . $job_id;
       $query = $this->db->prepare($sql);
       return $query->execute();
    }
}
?>