<?php
echo '
<div class="container margin-top-10">
    <div class="navbar">
        <h3 class="home_title"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong></h3>';
    echo $form->right_menu($this->projects, $this->current_project_id, 'job');
echo '
    </div>
    <div class="form-container">
        <h2>Add Epic</h2>
        <form action="/job/add" method="POST">';
        
echo $form->input('Epic Name', 'text', array('field_class' => 'required', 'name' => 'name', 'required' => true));
echo $form->input('Project Name', 'text', array('name' => 'project_name', 'value' => Session::get('current_project_description'), 'readonly' => true));
echo $form->input('', 'hidden', array('name' => 'project_id', 'value' => Session::get('current_project_id'), 'readonly' => true));

echo '
            <div class="form-group">
                <label for="milestone_id">Milestone Name </label>
                <select class="form-control" name="milestone_id">
                    <option value="">backlog</option>';
                    
foreach ($milestones as $milestone) {
    echo '
                    <option value="' . $milestone['id'] .'">' . $milestone['name'] . '</option>';
}
echo '
                </select>
            </div>
            <div class="form-group">
                <label for="priority">Epic Priority</label>
                <select class="form-control" name="priority" ' . ($read_only ? 'disabled' : '') . '>';

foreach ($priorities as $priority) {
    echo '
                    <option value="' . $priority . '" '. ($priority == '4. Would Have' ? ' selected': '') .'>' . $priority . '</option>';
}

echo '
                </select>
            </div>
            <div class="form-group">
                <label for="confidence_level">Confidence Level</label>
                <select class="form-control" name="confidence_level" ' . ($read_only ? 'disabled' : '') . '>';
            
foreach ($confidence_level as $confidence) {
    echo '
                    <option value="' . $confidence . '" ' . ($confidence == '3. Low' ? ' selected' : '') . '>' . $confidence . '</option>';
}
echo '
                </select>
            </div>
            <div class="form-group">
                <label for="file_upload">Upload File: </label>
                <input type="file" name="file_upload" id="file_upload">
                <p class="upload_message"></p>
            </div>';
            
echo $form->input('CodebaseHQ Tag', 'text', array('name' => 'code_base_tag'));

echo '
            <div class="form-group">
                <label for="long_description">Epic Description </label>
                <textarea class="form-control wysiwyg" rows="3" name="long_description"></textarea>
            </div>
            <button type="submit" style="margin-bottom:50px;" name="submit_add_job" class="btn btn-primary pull-right">Submit</button>
        </form>
    </div>
</div>';
