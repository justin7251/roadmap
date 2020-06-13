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
        `milestone`.`description`,
        `project`.`name` AS `project_name`,
        `project`.`description` AS `project_description`,
        `milestone`.`project_id` AS `project_id`,
        `milestone`.`start_date`,
        `milestone`.`actual_date`,
        `milestone`.`story_points`,
        `milestone`.`completed`,
        `milestone`.`update_at`,
        COUNT(DISTINCT(`job`.`id`)) AS `jobs`,
        COUNT(`code_base_ticket`.`id`) AS `tickets`,
        ROUND(SUM(IF(`code_base_ticket`.`status` = 'Completed', `code_base_ticket`.`estimated-time` / 60, 0))) AS `banked_points`,
        IF (`milestone`.`completed`, 100, ROUND(SUM(IF(`code_base_ticket`.`status` = 'Completed', `code_base_ticket`.`estimated-time` / 60, 0)) / `milestone`.`story_points` * 100, 2)) AS `progress`
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
    LEFT JOIN `job_ticket_mappings` ON
    (
        `job_ticket_mappings`.`job_id` = `job`.`id`
    )
    LEFT JOIN `code_base_ticket` ON
    (
        `code_base_ticket`.`ticket-id` = `job_ticket_mappings`.`code_base_ticket_id`
    )
    WHERE
        `milestone`.`completed` != 1
    AND
        `actual_date` >= NOW()
      GROUP BY
        `milestone`.`id`";
}
