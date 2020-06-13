<?php
echo '
<div class="container padding-10">
    <div class="navbar">
        <h3 class="home_title margin-bottom-10"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong></h3>';
        echo $form->right_menu($this->projects, $this->current_project_id);
    echo '
    </div>
    <div class="form-container">
        <h2>Edit User</h2>
        <form id="form-login" class="form-group " action="/user/edit" method="POST">';

if ($error) {
    echo $form->message($error, array('class' => 'alert-danger'));
}
echo $form->input('', 'hidden', array('name' => 'id','value' => $user['id']));
echo $form->input('First Name', 'text', array('name' => 'first_name', 'value' => $user['first_name']));
echo $form->input('Last Name', 'text', array('name' => 'last_name', 'value' => $user['last_name']));
echo $form->input('Email Address', 'text', array('name' => 'email', 'value' => $user['email']));

echo '
            <div class="form-group">
                <label for="user_level">User Level</label>
                <select class="form-control" name="user_level_id">';

foreach ($user_levels as $key => $value) {
    echo '
                    <option value="' . $key . '"' . ($key == $user['user_level_id'] ? ' selected': '') . '>' . $value . '</option>';
}
echo '
                </select>
            </div>';

echo $form->input('New Password', 'password', array('name' => 'password_match'));
echo $form->input('Confirm Password', 'password', array('name' => 'password_match2'));
echo $form->submit_button('Submit', 'submit', array('class' => 'btn-primary pull-right'));

echo '
        </form>
    </div>
</div>';
