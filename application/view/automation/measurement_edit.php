<?php

echo '
<div class="container padding-10">
    <div class="navbar">
        <h3 class="home_title"><strong>Automation Measurement Edit</strong></h3>';
    echo $form->right_menu($this->projects, $this->current_project_id);
echo '
    </div>
    <div class="form-container">
        <h2>Edit Automation</h2>
        <form action="/automation/measurement_edit/' . $automation['id'] . '" method="POST">';
        
echo $form->input('Class Name', 'text', array('name' => '', 'value' => $automation['class_name'], 'readonly' => true));
echo $form->input('Dev Estimate Complexity', 'text', array('name' => 'dev_estimate_complexity', 'value' => $automation['dev_estimate_complexity']));
echo $form->input('Qa Test Priority', 'text', array('name' => 'qa_test_priority', 'value' => $automation['qa_test_priority']));
echo $form->input('High Intensity', 'text', array('name' => 'high_intensity', 'value' => $automation['high_intensity']));
echo $form->input('Medium Intensity', 'text', array('name' => 'medium_intensity', 'value' => $automation['medium_intensity']));
echo $form->input('Low Intensity', 'text', array('name' => 'low_intensity', 'value' => $automation['low_intensity']));
echo $form->input('', 'hidden', array('name' => 'id', 'value' => $automation['id'], 'readonly' => true));
echo $form->submit_button('Submit', 'submit', array('class' => 'btn-primary pull-right'));

echo '
        </form>
    </div>
</div>';
