<?php

class Progress_Status_Model extends Model
{
    public $_fields = array(
        'id',
        'class_name',
        'function',
        'status',
        'import_date'
    );
    protected $_table = 'progress_status';
    
}
?>