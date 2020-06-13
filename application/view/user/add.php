<?php
echo '
<div class="container padding-10">
    <div class="navbar">
        <h3 class="home_title margin-bottom-10"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong></h3>';
        echo $form->right_menu($this->projects, $this->current_project_id);
    echo '
    </div>
    <div class="log-container">';

        if (isset($this->error)) {
            echo $form->message($this->error, array('class' => 'alert-danger'));
        }
    ?>
    <h1>Add User</h1><br>
  <form id="form-group" action="<?php echo URL; ?>user/add" method="POST">
    <div class="form-group">
        <label for="user_level_id">User Level</label>
        <select class="form-control" name="user_level_id">
            <?php
            if (isset($user_level[0]['id'])) {
                foreach($user_level as $level) {
                    echo '<option value="' . $level['id'] . '">' . $level['name'] . '</option>'; 
                }
            }
            ?>
        </select>
    </div>
    <input id="email" type="text" name="email" placeholder="Email" value="<?php (isset($_POST['email']) ?  $_POST['email'] : ''); ?>" required />
    <input type="text" name="first_name" placeholder="First Name" required />
    <input type="text" name="last_name" placeholder="Last Name" required />
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="submit_add_user" class="login login-submit" value="Submit">
  </form>
</div>
</div>
