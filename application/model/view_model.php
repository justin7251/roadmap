<?php
/**
* Class View
*
* Please note:
* Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
* This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
*
*/
class View_Model extends Model
{
    protected $sql = '';
    protected $_table = '';
    
    protected $_fields = array();
    
    /** geting db function **/
    function __construct($db)
    {
        parent::__construct($db);
        $this->create_sql_view($this->_table, $this->sql);
    }
    
    protected function create_sql_view($_table, $sql)
    {
        if (!$this->schemaExists($_table) && preg_match("/SELECT/", $this->sql)) {
           $sql = 'CREATE VIEW '  .$_table. ' AS ' . $sql;
           $query = $this->db->prepare($sql);
           return $query->execute();
        }
    }
}
