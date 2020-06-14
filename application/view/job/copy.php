<?php 
$read_only = '';
if (Access::get_permission() == 'read_only') {
    $read_only = 'readonly';
}

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
        <h3 class="home_title"><strong>' . $title . '</strong></h3>';
    echo $form->right_menu($this->projects, $this->current_project_id, 'job');
echo '
    </div>
    <div class="form-container">
        <h2>Copy Epic</h2>
        <form action="/job/copy/" method="POST">';
        
echo $form->input('Epic Name', 'text', array('field_class' => 'required', 'name' => 'name', 'value' => $job['name'], 'required' => true));
echo $form->input('Project Name', 'text', array('name' => 'project_name', 'value' => Session::get('current_project_description'), 'readonly' => true));
echo $form->input('Estimate', 'text', array('field_class' => 'required', 'name' => 'estimate', 'required' => true));
echo $form->input('', 'hidden', array('name' => 'project_id', 'value' => Session::get('current_project_id'), 'readonly' => true));

echo '
            <div class="form-group">
                <label for="milestone_id">Milestone Name</label>
                <select class="form-control" name="milestone_id" ' . ($read_only ? 'disabled' : '') . '>
                    <option value="">backlog</option>';
                
foreach ($milestone_names as $milestone) {
    echo '
                    <option value="' . $milestone['id'] . '"' . ($milestone['id'] == $job['milestone_id'] ? ' selected': '') . '>' . $milestone['name'] . '</option>'; 
}
echo '
                </select>
            </div>
            <div class="form-group">
                <label for="priority">Epic Priority</label>
                <select class="form-control" name="priority" ' . ($read_only ? 'disabled' : '') . '>';

foreach ($priorities as $priority) {
    echo '
                    <option value="' . $priority . '"' . ($priority == $job['priority'] ? ' selected': '') . '>' . $priority . '</option>';
}
echo '
                </select>
            </div>
            <div class="form-group">
                <label for="confidence_level">Confidence Level</label>
                <select class="form-control" name="confidence_level" ' . ($read_only ? 'disabled' : '') . '>';
                
foreach ($confidence_level as $confidence) {
    echo '
                    <option value="' . $confidence . '"' . ($confidence == $job['confidence_level'] ? ' selected': '') . '>' . $confidence . '</option>';
}
echo '
                </select>
            </div>';
            
/* no permission */
if (!$read_only) {
    echo '
            <div class="form-group">
                <label for="file_upload">Upload File: </label>';
                
    if (isset($job['file_path'])) {
        echo '
                <a href="' . URL . 'uploads/' . $job['file_path'] . '" download>' . substr($job['file_path'], strpos($job['file_path'], "_") + 1) . '</a>';
    }
    echo '
                <input type="file" name="file_upload" id="file_upload">
                <p class="upload_message"></p>
            </div>';
}
echo '
            <div class="form-group">
                <label for="long_description">Epic Description </label>
                <textarea class="form-control wysiwyg" rows="3" name="long_description">' . $job['long_description'] . '</textarea>
            </div>';
            
echo $form->submit_button('Submit', 'submit', array('class' => 'btn-primary pull-right'));

echo '
        </form>
    </div>
</div>';
