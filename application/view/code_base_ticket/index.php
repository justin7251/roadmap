<?php
echo '
<div class="container margin-top-10">
    <div class="navbar">
        <h3 class="home_title"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong> CodebaseHQ Tickets</h3>';
        echo $form->right_menu($this->projects, $this->current_project_id, 'code_base');
echo '
    </div>';
    
if ($tickets) {
    $columns = array(
        'ticket-id' =>'Code Base Ticket Id',
        'ticket-type' => 'Type',
        'summary' => 'Description',
        'priority' => 'Priority',
        'estimated-time' => 'Est Time',
        'status' => 'Status',
        'assignee' => 'Assign To'
    );
    echo '
    <table class="table sortable">
        <thead>
            <tr>';
            
    foreach ($columns as $value) {
        echo '
                <th>'. $value .'</th>';
    }
   echo '
            </tr>
        </thead>
        <tbody>';

    if (is_array($tickets) && count($tickets) > 0) {
        foreach($tickets as $ticket) {
            echo '
            <tr id="drag_drop_item_' . $ticket['id'] . '">';
                
            foreach (array_keys($columns) as $column) {
                echo '
                <td>' . $ticket[$column] . '</td>';
            }
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
