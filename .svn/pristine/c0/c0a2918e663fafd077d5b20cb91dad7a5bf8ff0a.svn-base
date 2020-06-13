<?php
echo '
<div class="container padding-10">
    <div class="navbar">
        <h3 class="home_title margin-bottom-10"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong></h3>';
        echo $form->right_menu($this->projects, $this->current_project_id);
    echo '
    </div>
    <div class="form-container">
        <h2>User Settings</h2>
        <form action="/user/index" method="POST">';
        
echo $form->input('', 'hidden', array('name' => 'id','value' => Session::get('user.id')));
echo $form->input('First Name', 'text', array('name' => 'first_name', 'value' => Session::get('user.first_name')));
echo $form->input('Last Name', 'text', array('name' => 'last_name', 'value' => Session::get('user.last_name')));
echo $form->input('Email', 'text', array('name' => 'email', 'value' => Session::get('user.email')));
$level = '';
foreach ($user_levels as $value) {

    if ($value['id'] == Session::get('user.user_level_id')) {
        $level = $value['name'];
    }
}
echo $form->input(
    'User Level',
    'text',
    array('name' => 'user_level', 'value' => $level, 'readonly' => true)
);

echo $form->input('New Password', 'password', array('name' => 'password_match'));
echo $form->input('Confirm Password', 'password', array('name' => 'password_match2'));
echo $form->submit_button('Submit', 'submit', array('class' => 'btn-primary pull-right', 'name' => 'submit_user_update'));

echo '
        </form>
    </div>
</div>';
