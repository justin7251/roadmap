<?php
echo '
<div class="container margin-top-10">
    <div class="navbar">
        <h3 class="home_title margin-bottom-10"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong> User Management</h3>';
        echo $form->right_menu($this->projects, $this->current_project_id);
    echo '
    </div>';

if (Access::get_permission() == 'admin') {
    echo '
        <div class="button_container pull-right">';
        
    echo $form->button(
        array(
            'btn-class' => 'btn-primary',
            'url' => 'user/add'
        ),
        'glyphicon-plus',
        'Add New User'
    );
    echo '
        </div>';
}

    
if ($users) {
    echo '
    <table class="table sortable">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>User level</th>
                <th>Last Login</th>
                <th></th>
            </tr>
        </thead>
        <tbody>';

    foreach($users as $user) {
        echo '
            <tr>
                <td>' . $user['first_name'] . '</td>
                <td>' . $user['last_name'] . '</td>
                <td>' . $user['email'] . '</td>
                <td>' . $user_levels[$user['user_level_id']] . '</td>
                <td>' . $form->date_time_format($user['last_logged_in']) . '</td>';
                
        if (Access::get_permission() == 'admin') {
            echo '
                <td>' . $form->button(array('btn-class' => 'btn-success', 'title' => 'Modify this User', 'url' => 'user/edit/' . $user['id'] ), 'glyphicon-edit') . '</td>';
        }
        echo '
            </tr>';
    }
    echo '
        </tbody>
    </table>';
}
echo '
</div>';
