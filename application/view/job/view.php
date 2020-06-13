<?php
$title = '';
if (isset($projects)) {
    foreach ($projects as $project) {
        if ($job['project_id'] == $project['id']) {
            $title = ucwords(str_replace('-', ' ', $project['description']));
        }
    }
}

echo '
<div class="container padding-10">
    <div class="navbar">
        <h3 class="home_title"><strong>' . $title . '</strong> ' . $job['name'] . '</h3>';
    echo $form->right_menu($this->projects, $this->current_project_id, 'job');
echo '
    </div>
    <div class="button_container pull-right">';
if (Access::get_permission() == 'admin') {
    echo $form->button(
        array(
            'btn-class' => 'btn-success',
            'title' => 'Edit the details for this Epic',
            'url' => 'job/edit/' . $job['id'] . '/1'
        ),
        'glyphicon-edit',
        'Edit Epic'
    );
    echo $form->button(
        array(
            'btn-class' => 'btn-danger',
            'title' => 'Remove this Epic permanently',
            'url' => 'job/delete/' . $job['id']
        ),
        'glyphicon-remove',
        'Remove Epic'
    );
}
echo '
    </div>
    <div class="form-container">
        <div class="row">';
        
if (isset($related_milestone)) {
    echo '
            <div class="heading">
                <h2>Milestone: ' . $related_milestone['name'] . '</h2>
            </div>';
            
    echo $form->bootstrap_form('Start Date', $form->date_time_format($related_milestone['start_date']));
    echo $form->bootstrap_form('End Date', $form->date_time_format($related_milestone['actual_date']));
    echo $form->bootstrap_form('Description', $related_milestone['description']);
}

echo '
            <div class="heading">
                <h2>Features: ' . $job['name'] . '</h2>
            </div>';
            
echo $form->bootstrap_form('CodebaseHQ Tag', $job['code_base_tag']);
echo $form->bootstrap_form('Number of Linked CodebaseHQ Tickets', $job['tickets']);
echo $form->bootstrap_form('Estimated Time', $job['story_points'] . ' hours');
echo $form->bootstrap_form('Time Spent', $job['time_spent'] . ' hours');
echo $form->bootstrap_form('Confidence Level', $job['confidence_level']);
echo $form->bootstrap_form('Status', $job['status']);
echo $form->bootstrap_form('Date Added', $form->date_time_format($job['create_at']));
echo $form->bootstrap_form('Last Modified Date', $form->date_time_format($job['update_at']));

$milestone_name = '';
if (isset($related_milestone)) {
    $milestone_name = $related_milestone['name'];
} else {
    $milestone_name = '-';
}
echo $form->bootstrap_form('Milestone Name', $milestone_name);

if ($job['file_path']) {
    echo $form->bootstrap_form('Uploaded File', '<a href="' . URL . 'uploads/' . $job['file_path'] . '" download>' . substr($job['file_path'], strpos($job['file_path'], "_") + 1) .'</a>');
}
if (isset($job['code_base_job']) && isset($related_milestone['project_id'])) {
    $all_job = '';
    foreach(Session::get('all_projects') as $key => $value) {
        if ($value['id'] == $related_milestone['project_id']) {
            $project_name = Session::get('all_projects');
            $all_job = $this->get_code_base_job($job['code_base_job'], $project_name[0]['name']);
        }
    }
    echo $form->bootstrap_form('Code Base job', $all_job);
}
echo $form->bootstrap_form('Description', $job['long_description']);
echo '
        </div>
    </div>';
    
if (isset($code_base_data[0]['ticket-id'])) {
    echo '
    <div>
        <div class="button_container pull-right">';
        
    echo $form->button(
        array(
            'btn-class' => 'btn-primary',
            'title' => 'Import CodebaseHQ Tickets Data',
            'class' => 'update_ticket_status margin-left-5'
        ),
        'glyphicon-import',
        'Import CodebaseHQ Tickets Data'
    );
    
    echo '
        </div>
        <h2>Linked Code Base Ticket</h2>
        <table class="table sortable">
            <thead>
                <tr>
                    <th>Ticket Id</th>
                    <th>Summary</th>
                    <th>Estimated Time</th>
                    <th>Time Spend So Far</th>
                    <th>Status</th>
                    <th>Ticket Type</th>
                </tr>
            </thead>
            <tbody>';

    $code_base_url = 'http://software.s2partnership.co.uk/projects/'. Session::get('current_project_name') .'/tickets/';

    foreach ($code_base_data as $value) {
        echo '
                <tr>
                    <td><a href="'. $code_base_url . $value['ticket-id'] .'" target="_blank">' . $value['ticket-id'] . '</a></td>
                    <td>' . $value['summary'] . '</td>
                    <td>' . round($value['estimated-time'] / 60, 2). ' hours</td>
                    <td>' . round($value['total-time-spent'] / 60, 2). ' hours</td>
                    <td>' . $value['status'] . '</td>
                    <td>' . $value['ticket-type'] . '</td>
                </tr>';
    }
    echo '
            </tbody>
        </table>
    </div>';
}
echo '
</div>';
?>
<script>
$(function() {
    $('.update_ticket_status').click(function() {
        $.notify({
            message: 'This Epic is now being updated with data from CodebaseHQ. Once complete, this page will refresh.'
        },{
            type: 'info',
            delay: 10000,
        });
        $.ajax({
            type: 'POST',
            data: '<?php echo $job['id'] ?>',
            url: '/job/update_ticket_status/' + <?php echo $job['id'] ?>,
            success: function(data) {
                   if (data) {
                        $.notify({
                            message: 'CodebaseHQ data Imported Successfully.'
                        },{
                            type: 'success'
                        });
                        setTimeout(function() {location.reload();}, 2000);
                   }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $.notify({
                        // options
                        message: 'There has been a problem retrieving data from CodebaseHQ.'
                    },{
                        // settings
                        type: 'info',
                        delay: 500,
                    });
            },
            cache:false
        });
    });
});
</script>