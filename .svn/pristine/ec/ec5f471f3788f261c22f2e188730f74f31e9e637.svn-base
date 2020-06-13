<?php
echo '
<div class="container padding-10">
    <div class="navbar">
        <h3 class="home_title"><strong>Automation Class Detail' . (count($automation) > 0 ? ' - ' . $automation[0]['class_name'] : '') . '</strong></h3>';
    echo $form->right_menu($this->projects, $this->current_project_id);
    
echo '
    </div>
    <div class="row">';
    
    if (isset($automation)) {
    echo '
        <table class="table sortable">
            <thead>
                <tr>
                    <th>Class Name</th>
                    <th>Function Name</th>
                    <th>Status</th>';
        echo '
                </tr>
            </thead>
            <tbody>';
    if (count($automation) > 0) {
        foreach($automation as $test) {
            if ($test['status'] == 'Skip') {
                $class = 'text-danger';
                $status_text = 'Outstanding';
            } elseif ($test['status'] == 'Todo') {
                $class = 'text-warning';
                $status_text = 'Omitted';
            } else {
                $class = 'text-success';
                $status_text = 'Completed';
            }
            echo '
            <tr>
                <td>' . $test['class_name'] . '</a></td>
                <td>' . $test['function'] . '</td>
                <td class="' . $class . '">' . $status_text . '</td>
            </tr>';
        }
    }
    echo '
        </tbody>
    </table>';
    
    }
echo '

</div>';
