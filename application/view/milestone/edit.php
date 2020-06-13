<?php
foreach($projects as $project) {
    if ($project['id'] == $milestone['project_id']) {
        $milestone['project_name'] = $project['name'];
    }
}
echo '
<div class="container margin-top-10">
    <div class="navbar">
        <h3 class="home_title margin-bottom-10"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong></h3>';
        echo $form->right_menu($this->projects, $this->current_project_id, 'milestone');
    echo '
    </div>
    <div class="form-container">
        <h2>Edit Milestone</h2>
        <form action="/milestone/edit/' . $milestone['id'] . '/' . $from_view . '" method="POST">';
        
echo $form->input('', 'hidden', array('name' => 'id', 'value' => $milestone['id']));
echo $form->input('Milestone Name', 'text', array('name' => 'name', 'value' => $milestone['name'], 'readonly' => true));
echo $form->input('Project Name', 'text', array('name' => 'project_name', 'value' => $milestone['project_name'], 'readonly' => true));

echo '
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <div class="input-group datepicker date" id="datetimepicker1">
                <input name="start_date" value="' . $form->date_time_format($milestone['start_date']) . '" type="text" class="form-control"  readonly/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="end_date">End Date</label>
            <div class="input-group datepicker date" id="datetimepicker2">
                <input name="end_date" value="' . $form->date_time_format($milestone['end_date']) . '" type="text" class="form-control" readonly/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>';

echo '
        <div class="form-group">
            <label for="goal">MileStone Goal</label>
            <textarea class="form-control wysiwyg" rows="3" name="goal">' . $milestone['goal'] . '</textarea>
        </div>';
                
echo $form->submit_button('Submit', 'submit', array('class' => 'btn-primary pull-right'));

echo '
        </form>
    </div>
</div>';
