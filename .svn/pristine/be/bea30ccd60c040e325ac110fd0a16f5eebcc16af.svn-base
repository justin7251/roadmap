<?php
echo '
<div class="container margin-top-10">
    <div class="navbar">
        <h3 class="home_title margin-bottom-10"><strong>Automation</strong></h3>';
            echo $form->right_menu($this->projects, $this->current_project_id);
    echo '
        </div>';
        
echo '
<div class="row">';
    echo $form->breadcrumb('Measure');
    echo '
        <div class="button_container pull-right">';
        //button
echo '</div>';

    
if (isset($automations)) {
    echo '
    <table class="table sortable">
        <thead>
            <tr>
                <th>Class Name</th>
                <th>Dev Estimate Complexity</th>
                <th>Qa Test Priority</th>
                <th title="Regression Time Taken">High Intensity</th>
                <th title="Regression Time Taken">Medium Intensity</th>
                <th title="Regression Time Taken">Low Intensity</th>
                <th></th>
            </tr>
        </thead>
        <tbody>';

    if (is_array($automations) && count($automations) > 0) {
        foreach($automations as $automation) {
            echo '
            <tr>
                <td>' . $automation['class_name'] . '</td>
                <td>' . $automation['dev_estimate_complexity'] . '</td>
                <td>' . $automation['qa_test_priority'] . '</td>
                <td>' . $automation['high_intensity'] . '</td>
                <td>' . $automation['medium_intensity'] . '</td>
                <td>' . $automation['low_intensity'] . '</td>';
                
                echo '
                <td>' . $form->button(array('btn-class' => 'btn-success', 'title' => 'Edit Automation', 'url' => 'automation/measurement_edit/' . $automation['id'] ), 'glyphicon-edit') . '</td>';
            echo '
            </tr>';
        }
    }
    echo '
        </tbody>
    </table>';
}
echo '
</div>';

?>
<script type="text/javascript">

</script>