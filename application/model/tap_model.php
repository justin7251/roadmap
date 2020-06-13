<?php

class Tap_Model extends Model
{
    public $_fields = array(
        'id',
        'class_name',
        'function',
        'status',
        'import_date'
    );
    protected $_table = 'tap';
    
}
?>