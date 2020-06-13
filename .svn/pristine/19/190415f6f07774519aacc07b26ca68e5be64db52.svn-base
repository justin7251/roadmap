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
            IF(SUM(`code_base_ticket`.`estimated-time`) IS NULL, 0, ROUND(SUM(`code_base_ticket`.`estimated-time`)/60)) AS `story_points`,
            IF(SUM(`code_base_ticket`.`total-time-spent`) IS NULL, 0, ROUND(SUM(`code_base_ticket`.`total-time-spent`)/60)) AS `time_spent`,
            IF((`milestone`.`active`) = 'Y', 1, 0) AS `active_milestone`,
            COUNT(`code_base_ticket`.`id`) AS `tickets`,
            IF(
                COUNT(`code_base_ticket`.`id`) < 1,
                'No Tickets',
                IF(
                    GROUP_CONCAT(DISTINCT(`code_base_ticket`.`status`)) = 'Completed',
                    'Completed',
                    IF (
                        SUM(`code_base_ticket`.`total-time-spent`) = 0,
                        'Not Started',
                        'In Progress'
                    )
                )
            ) AS `status`
        FROM
            `job`
        LEFT JOIN `job_ticket_mappings` ON
        (
            `job_ticket_mappings`.`job_id` = `job`.`id`
        )
        LEFT JOIN `code_base_ticket` ON
        (
            `code_base_ticket`.`ticket-id` = `job_ticket_mappings`.`code_base_ticket_id`
        AND
            `code_base_ticket`.`status` != 'Invalid / Duplicate'
        )
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