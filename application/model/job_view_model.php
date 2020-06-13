<?php

/**
 * Class Milestone
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class Job_View_Model extends View_Model
{
    protected $_table = 'job_view';
    protected $sql = "
        SELECT
            `job`.*,
            `milestone`.`name` AS `milestone_name`,
            IF((`milestone`.`active`) = 'Y', 1, 0) AS `active_milestone`
        FROM
            `job`
        LEFT JOIN `milestone` ON
        (
            `milestone`.`id` = `milestone_id`
        )
        RIGHT JOIN `project` ON
        (
            `project`.`id` = `job`.`project_id`
        AND
            `project`.`deleted` = 0
          )
        WHERE
            `job`.`deleted` = 0
        GROUP BY
            `job`.`id`";
}


?>