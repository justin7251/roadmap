<?php
class Code_base_ticket_Model extends Model
{
    public $_fields = array('id', 'ticket-id', 'ticket-type', 'summary', 'priority', 'code_base_milestone_id', 'estimated-time', 'total-time-spent', 'status', 'assignee', 'create_at', 'update_at');
    protected $_table = 'code_base_ticket';
}
