<?php

class Qtest_Model extends Model
{
    public $_fields = array(
        'id',
        'class_name',
        'test_function_name',
        'date_import'
    );
    protected $_table = 'qtest';
    
    
    public function import($qtest_data)
    {
        $this->raw_query("DELETE FROM `qtest`");
        $data = array();
        foreach ($qtest_data as $key => $val) {
            $data[$key]['class_name'] = $val[0];
            $data[$key]['test_function_name'] = $val[1];
            $data[$key]['date_import'] = date('Y-m-d H:i:s');
        }

        if ($this->multiple_save($data)) {
            echo '<h1>Update Successfully</h1>';
        }
    }
}
?>