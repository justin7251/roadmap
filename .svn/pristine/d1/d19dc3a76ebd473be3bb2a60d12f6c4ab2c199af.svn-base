<?php
/**
* Class Automation
*
* Please note:
* Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
* This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
*
*/
class Automation_View_Model extends View_Model
{
    protected $_table = 'automation_view';
    protected $sql = "
    SELECT
        `automation`.*,
        `automation`.`class_name` AS `class`,
    (SELECT
        COUNT(`qtest`.`test_function_name`) AS `test_count`
    FROM
        `automation`
    LEFT JOIN `qtest` ON (
        `automation`.`class_name` = `qtest`.`class_name`
    )
    WHERE
        `automation`.`class_name` = `class`
    ) AS `qtest_count`,
    (SELECT
        COUNT(`tap`.`function`) AS `test_count`  
    FROM
        `automation`
    LEFT JOIN `tap` ON (
        `automation`.`class_name` = `tap`.`class_name`
    )
    WHERE
        `automation`.`class_name` = `class`
    ) AS `tap_count`,
    (SELECT
        count(`tap`.`status`)
    FROM
        `automation`
    LEFT JOIN `tap` ON (
        `automation`.`class_name` = `tap`.`class_name`
    AND
        `tap`.`status` = 'Skip'
    )
    WHERE
        `automation`.`class_name` = `class`
    ) AS `tap_skip`,
    (SELECT
        count(`tap`.`status`)
    FROM
        `automation`
    LEFT JOIN `tap` ON (
        `automation`.`class_name` = `tap`.`class_name`
    AND
        `tap`.`status` = 'Todo'
    )
    WHERE
        `automation`.`class_name` = `class`
    ) AS `tap_todo`,
    (SELECT
        count(`tap`.`status`)
    FROM
        `automation`
    LEFT JOIN `tap` ON (
        `automation`.`class_name` = `tap`.`class_name`
    AND
        `tap`.`status` = 'Completed'
    )
    WHERE
        `automation`.`class_name` = `class`
    ) AS `tap_completed`
 FROM
     `automation`";
     
    public function getStatistics()
    {
        $sql = '
            SELECT
                sum(`tap_completed`) AS `Completed`,
                sum(`tap_todo`) AS `Incomplete`,
                ((SELECT count(`id`) FROM `qtest`) - sum(`tap_todo`) - sum(`tap_completed`)
                 )AS `Skipped`
            FROM
                `automation_view`';

        // $sql = "
            // SELECT
                // sum(`tap_skip`) AS `Skipped`,
                // sum(`tap_todo`) AS `Incomplete`,
                // sum(`tap_completed`) AS `Completed`
            // FROM
                // `automation_view`
        // ";
        $data = $this->raw_query($sql);
        return $data;
    }

}
