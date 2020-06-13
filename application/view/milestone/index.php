<?php
echo '
<div class="container margin-top-10">
    <div class="nav">
        <h3 class="home_title"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong> Milestones</h3>';
    echo $form->right_menu($this->projects, $this->current_project_id, 'milestone');
        
echo '
    </div>';
    echo $form->button(
        array(
            'btn-class' => 'btn-primary pull-right btn-adjust-margin',
            'title' => 'Create a new Epic',
            'url' => 'milestone/add'
        ),
        'glyphicon-plus',
        'Add Milestone'
    );
    
if ($milestones) {
    echo '
    <table class="table sortable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Available Story Points</th>
                <th class="sorter-shortDate dateFormat-ddmmyyyy">Start Date</th>
                <th class="sorter-shortDate dateFormat-ddmmyyyy">Delivery Date</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>';
        
    foreach ($milestones as $milestone) {
        echo '
            <tr>
                <td>' . $milestone['name'] . '</td>
                <td>' . $milestone['description'] . '</td>
                <td>' . $milestone['story_points'] . '</td>
                <td>' . ($milestone['start_date'] == null ? 'Not Set' : $form->date_time_format($milestone['start_date'])) . '</td>
                <td>' . ($milestone['actual_date'] == null ? 'Not Set' : $form->date_time_format($milestone['actual_date'])) . '</td>
                <td>' . $form->button(array('btn-class' => 'btn-info', 'title' => 'View details for this Milestone', 'url' => 'milestone/view/' . $milestone['id']), 'glyphicon-zoom-in') . '</td>';
                        
        if (Access::get_permission() == ('admin' || 'limited')) {
            echo '
                <td>' . $form->button(array('btn-class' => 'btn-success', 'title' => 'Modify this Milestone', 'url' => 'milestone/edit/' . $milestone['id']), 'glyphicon-edit') . '</td>';
        }
        echo '</tr>';
    }
    echo '
          </tbody>
        </table>';
}
echo '
</div>';
