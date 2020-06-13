<?php

class Model extends Sql
{

    public function save($data)
    {
        if (is_array($data) && count($data) > 0) {
            $save = array();
            $fields = $this->_fields;
            foreach ($fields as $field) {
                if (isset($data[$field])) {
                    $save[$field] = $data[$field];
                }
            }
            return $this->pdo_save($save);
        }
    }
    
    public function multiple_save($data)
    {
        if (is_array($data) && count($data) > 0) {
            return $this->pdo_multiple_save($data);
        }
    }
    
    /**
    * Retrieves whether or not the Model's respective database table or view exists
    *
    * @return boolean Whether or not the schema exists
    */
    protected function schemaExists($view_name)
    {
        $this->_result = $this->db->prepare("SHOW TABLES LIKE '" . $view_name . "'");
        $this->_result->execute();
        return ($this->_result->rowCount() == 1);
    }
        
    //get data from database and check 
    public function get($fields = array(),$where = array(), $limit = '')
    {
        if (count($fields) == 0 ) {
            $fields = $this->_fields;
        }
        $row = $this->pdo_get($fields, $where, $limit);
        return (isset($row[0]) ? json_decode(json_encode($row), true) : false);
    }
    
    /**
    *
    */
    public function pagination($sql_query, $limit = null)
    {
        $row = $this->pagination($sql_query, ($limit ? $limit : ''));
        return (isset($row[0]) ? $row : false);
    }

    public function delete($id)
    {
        return $this->pdo_delete($id);
    }
    
    /**
    *
    *
    * @return bool true or array
    */
    public function raw_query($sql)
    {
        $query = $this->db->prepare($sql);
        $query->execute();
        // if the call returns any columns then store it in rows public variable or store an empty array
        $result = ($query->columnCount() > 0) ? $query->fetchAll() : array();
        return (isset($result[0]) ? json_decode(json_encode($result), true) : true);
    }
}
