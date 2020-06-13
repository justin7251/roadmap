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
    echo $form->bootstrap_form('End Date', $form->date_time_format($related_milestone['end_date']));
    echo $form->bootstrap_form('Milestone Goal', $related_milestone['goal']);
}

echo '
            <div class="heading">
                <h2>Features: ' . $job['name'] . '</h2>
            </div>';


echo $form->bootstrap_form('Confidence Level', $job['confidence_level']);
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

    echo '
            </tbody>
        </table>
    </div>';
}
echo '
</div>';
