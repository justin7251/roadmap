<?php

echo '
<div class="container padding-10">
    <div class="navbar">
        <h3 class="home_title"><strong>Automation Edit</strong></h3>';
    echo $form->right_menu($this->projects, $this->current_project_id);
echo '
    </div>
    <div class="form-container">
        <h2>Edit Automation</h2>
        <form action="/automation/edit/' . $automation['id'] . '" method="POST">';
        
echo $form->input('Class Name', 'text', array('name' => '', 'value' => $automation['class_name'], 'readonly' => true));
echo '
            <div class="form-group">
                <label for="priority">Status</label>
                <select class="form-control" name="status">';

                foreach($status_list as $status) {
                    echo '
                        <option value="' . $status . '"' . ($status == $automation['status'] ? ' selected': '') . '>' . ucwords(str_replace('_', ' ',$status)) . '</option>';
                }
echo '
                </select>
            </div>';
            
echo '
            <div class="form-group">
                <label for="priority">Assign</label>
                <select class="form-control" name="assign_to">';

                foreach($assign_list as $assign) {
                    echo '
                        <option value="' . $assign . '"' . ($assign == $automation['assign_to'] ? ' selected': '') . '>' . ucwords(str_replace('_', ' ',$assign)) . '</option>';
                }
echo '
                </select>
            </div>';

echo $form->input('', 'hidden', array('name' => 'id', 'value' => $automation['id'], 'readonly' => true));
echo $form->submit_button('Submit', 'submit', array('class' => 'btn-primary pull-right'));

echo '
        </form>
    </div>
</div>';
