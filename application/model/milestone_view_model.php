<?php
/**
 * Class Milestone
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class Milestone_View_Model extends View_Model
{
    protected $_table = 'milestone_view';
    public $sql = "
    SELECT
        `milestone`.`id`,
        `milestone`.`name`,
        `milestone`.`goal`,
        `project`.`name` AS `project_name`,
        `project`.`description` AS `project_description`,
        `milestone`.`project_id` AS `project_id`,
        `milestone`.`start_date`,
        `milestone`.`end_date`,
        `milestone`.`completed`,
        `milestone`.`update_at`,
        `milestone`.`story_points`,
        DATEDIFF(`milestone`.`end_date`, CURDATE()) AS 'progress',
        COUNT(DISTINCT(`job`.`id`)) AS `jobs`
    FROM
        `milestone`
    INNER JOIN `project` ON
    (
        `milestone`.`project_id` = `project`.`id`
    AND
        `project`.`deleted` = 0
    )
    LEFT JOIN `job` ON
    (
        `job`.`milestone_id` = `milestone`.`id`
    )
    WHERE
        `milestone`.`completed` != 1
    AND
        `end_date` >= CURDATE()
      GROUP BY
        `milestone`.`id`";
}
