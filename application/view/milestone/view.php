<?php
echo '
<div class="container padding-10">
<div class="navbar">
        <h3 class="home_title margin-bottom-10"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong></h3>';
        echo $form->right_menu($this->projects, $this->current_project_id, 'milestone');
echo '
    </div>
    <div class="button_container pull-right">';

echo $form->button(
    array(
        'btn-class' => 'btn-success',
        'title' => 'Edit the details for this Milestone',
        'url' => 'milestone/edit/' . $milestone['id'] . '/1'
    ),
    'glyphicon-edit',
    'Edit Milestone'
);
echo '
    </div>
    <div class="form-container">
        <div class="row">
            <div class="heading">
                <h2>Details</h2>
            </div>';

echo $form->bootstrap_form('Milestone', $milestone['name']);
echo $form->bootstrap_form('Start Date', $form->date_time_format($milestone['start_date']));
echo $form->bootstrap_form('End Date', $form->date_time_format($milestone['end_date']));

echo '
            <div class="heading">
                <h2>Project Roadmap Details</h2>
            </div>';
       
echo $form->bootstrap_form('MileStone Goal', $milestone['goal']);

echo '
        </div>
    </div>
</div>';
