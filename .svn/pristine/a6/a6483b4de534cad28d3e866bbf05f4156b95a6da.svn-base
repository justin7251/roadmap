<?php

echo '
<div class="container padding-10">
    <div class="navbar">
        <h3 class="home_title"><strong>Automation Add</strong></h3>';
    echo $form->right_menu($this->projects, $this->current_project_id);
echo '
    </div>
    <div class="form-container">
        <h2>Add Automation</h2>
        <form action="/automation/add/" method="POST">';
        
echo $form->input('Class Name', 'text', array('name' => 'class_name', 'value' => ''));
echo $form->input('Status', 'text', array('name' => 'status', 'value' => ''));
echo $form->input('Assign', 'text', array('name' => 'assign_to', 'value' => ''));

echo $form->submit_button('Submit', 'submit', array('class' => 'btn-primary pull-right'));

echo '
        </form>
    </div>
</div>';
