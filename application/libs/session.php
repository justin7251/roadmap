<?php
/**
* /application/libs/session.php
*
* PHP Version 5
*/

/**
* Session
*
* @category Session
* @package Roadmap
* @subpackage library
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Session
{
    /**
    * Get Session by name
    *
    * @param string $variable_name name that saved in session
    * @return string
    */
    static public function get($variable_name)
    {
        $current = $_SESSION;
        foreach (explode(".", $variable_name) as $variable)
                $current =& $current[$variable];
        return $current;
    }
    
    /**
    * Set Session
    *
    * @param string $variable_name name that saved in session
    * @param string $data data value
    * @return string
    */
    static public function set($variable_name, $data)
    {
        $current =& $_SESSION;
        foreach (explode(".", $variable_name) as $variable) {
            $current =& $current[$variable];
        }
        $current = $data;
    }
    
    /**
    * Delete Session
    *
    * @param string $variable_name name that saved in session
    * @return void
    */
    static public function remove($variable_name)
    {
        $current =& $_SESSION;
        $steps = explode(".", $variable_name);
        $count = 0;
        foreach ($steps as $variable) {
            $count++;
            if (count($steps) == $count) {
                unset($current[$variable]);
            } else {
                $current =& $current[$variable];
            }
        }
    }
}