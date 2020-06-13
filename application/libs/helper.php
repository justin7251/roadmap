<?php
/**
* /application/libs/helper.php
*
* PHP Version 5
*/

/**
* Helper
*
* @category Helper
* @package Roadmap
* @subpackage library
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Helper
{
    /**
    * debugPDO
    *
    * Shows the emulated SQL query in a PDO statement. What it does is just extremely simple, but powerful:
    * It combines the raw query and the placeholders. For sure not really perfect (as PDO is more complex than just
    * combining raw query and arguments), but it does the job.
    * 
    * @author Panique
    * @param string $raw_sql
    * @param array $parameters
    * @return string
    */
    static public function debugPDO($raw_sql, $parameters) {

        $keys = array();
        $values = $parameters;

        foreach ($parameters as $key => $value) {
            // check if named parameters (':param') or anonymous parameters ('?') are used
            if (is_string($key)) {
                $keys[] = '/' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            // bring parameter into human-readable format
            if (is_string($value)) {
                $values[$key] = "'" . $value . "'";
            } elseif (is_array($value)) {
                $values[$key] = implode(',', $value);
            } elseif (is_null($value)) {
                $values[$key] = 'NULL';
            }
        }

        /*
        echo "<br> [DEBUG] Keys:<pre>";
        print_r($keys);
        
        echo "\n[DEBUG] Values: ";
        print_r($values);
        echo "</pre>";
        */
        
        $raw_sql = preg_replace($keys, $values, $raw_sql, 1, $count);

        return $raw_sql;
    }

    /**
    * Save date as SQL format
    *
    * @param string $date_time date time
    * @return string
    */
    static public function save_date_time($date_time)
    {
        $format = 'Y-m-d H:i';
        $new_format = preg_replace('/\//', '-', $date_time);
        $date_time = date($format, strtotime($new_format));
        return $date_time;
    }

}